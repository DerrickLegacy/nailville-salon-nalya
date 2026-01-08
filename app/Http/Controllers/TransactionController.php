<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loggedInUser = Auth::user()->name;

        $employees = Employee::all()->map(fn($e) => [
            'id'   => $e->employee_id,
            'name' => $e->full_name,
        ]);

        $services = Service::where('status', 'Active')->orderBy('name')->get();

        $latestExpenseId = Transaction::where('transaction_type', 'Expense')
            ->selectRaw('CAST(SUBSTRING(receipt_id, 5) AS UNSIGNED) as numeric_id')
            ->orderByDesc('numeric_id')
            ->value('numeric_id');

        $nextNumeric = $latestExpenseId ? (int)$latestExpenseId + 1 : 1;
        $formattedExpenseId = "EXP-" . str_pad($nextNumeric, 4, "0", STR_PAD_LEFT);

        // dd($formattedExpenseId);


        $transactionType = $request->route('transaction_type')
            ?? $request->get('transaction_type', 'Income');

        $view = $transactionType === 'Income'
            ? 'pages.transactions.transactions-income'
            : 'pages.transactions.transactions-expense';

        return response()->view($view, [
            'transactionType'       => $transactionType,
            'name'                  => $loggedInUser,
            'employees'             => $employees,
            'services'              => $services,
            'exp_transaction_id'    => $formattedExpenseId,
            'exp_transaction_raw'   => $nextNumeric,
        ]);
    }


    public function getRecords(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        // join transaction with services
        $query = Transaction::with([
            'employee:employee_id,first_name,last_name',
            'recordedBy:id,name',
            'service:id,name as service_name,price,category_id'

        ])->orderBy('date', 'desc');

        // 🔹 Ordering
        $columns = [
            0 => 'date',
            1 => 'service_name', //issue:use sevice name from service table
            2 => 'receipt_id',
            3 => 'employee_id',
            4 => 'customer_name',
            5 => 'payment_method',
            6 => 'amount',
        ];

        $orderColIndex = $request->input('order.0.column');
        $orderDir      = $request->input('order.0.dir', 'asc');

        if (isset($columns[$orderColIndex])) {
            $query->orderBy($columns[$orderColIndex], $orderDir);
        } else {
            $query->latest();
        }

        // 🔹 Search filter
        if ($request->filled('searchTerm')) {
            $searchTerm = $request->input('searchTerm');
            $query->where(function ($q) use ($searchTerm) {
                $q->orWhere('transaction_type', 'like', "%{$searchTerm}%")
                    ->orWhere('payment_method', 'like', "%{$searchTerm}%")
                    ->orWhere('service_description', 'like', "%{$searchTerm}%")
                    ->orWhere('receipt_id', 'like', "%{$searchTerm}%")
                    ->orWhereRaw("CAST(amount AS CHAR) LIKE ?", ["%{$searchTerm}%"]);

                try {
                    $date = \Carbon\Carbon::parse($searchTerm);
                    $q->orWhereDate('date', $date->toDateString());
                } catch (\Exception $e) {
                    // ignore invalid date
                }

                $q->orWhereHas('employee', function ($empQuery) use ($searchTerm) {
                    $empQuery->where('first_name', 'like', "%{$searchTerm}%")
                        ->orWhere('last_name', 'like', "%{$searchTerm}%");
                });

                $q->orWhereHas('recordedBy', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%");
                });

                // Search in related service table
                $q->orWhereHas('service', function ($serviceQuery) use ($searchTerm) {
                    $serviceQuery->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('service_code', 'like', "%{$searchTerm}%")
                        ->orWhere('category', 'like', "%{$searchTerm}%");
                });
            });
        }

        // 🔹 Filters
        if ($request->filled('cashTypeFilter')) {
            $query->where('payment_method', $request->cashTypeFilter);
        }

        if ($request->filled('fromDate') || $request->filled('toDate')) {
            $from = $request->filled('fromDate') ? \Carbon\Carbon::parse($request->fromDate)->startOfDay() : null;
            $to   = $request->filled('toDate') ? \Carbon\Carbon::parse($request->toDate)->endOfDay() : null;

            if (!$from && $to) $from = $to->copy()->startOfDay();
            if (!$to && $from) $to = $from->copy()->endOfDay();

            $query->whereBetween('date', [$from, $to]);
        }

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        // 🔹 Total records
        $recordsTotal = Transaction::count();
        $recordsFiltered = $query->count();

        // 🔹 Sum of amount for filtered records (all pages)
        $totalAmountAllPages = $query->sum('amount');

        // 🔹 Pagination
        $start  = $request->get('start', 0);
        $length = $request->get('length', 10);

        $transactions = $query->skip($start)->take($length)->get();

        // 🔹 Format dates
        $transactionsFormatted = $transactions->map(function ($txn) {
            $txn->date = $txn->date ? $txn->date->format('Y-m-d\TH:i:s') : null;
            $txn->created_at = $txn->created_at->format('Y-m-d\TH:i:s');
            return $txn;
        });

        // Totals
        $totalIncome = Transaction::where('transaction_type', 'Income')->sum('amount');
        $totalExpense = Transaction::where('transaction_type', 'Expense')->sum('amount');

        return response()->json([
            'draw'               => intval($request->get('draw')),
            'recordsTotal'       => $recordsTotal,
            'recordsFiltered'    => $recordsFiltered,
            'data'               => $transactionsFormatted,
            'totalAmountAllPages' => $totalAmountAllPages,
            'totalIncome'        => $totalIncome,
            'totalExpense'       => $totalExpense,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_type'  => 'required|in:Income,Expense',
            'employee_id'       => 'required_if:transaction_type,Income|nullable|exists:employees,employee_id',
            'service_id'        => 'required_if:transaction_type,Income|nullable|exists:services,id',
            'receipt_id'        => 'nullable|string|max:100',
            'customer_name'     => 'nullable|string|max:150',
            'amount'            => 'required|numeric|min:0',
            'payment_method'    => 'required|in:Cash,MobileMoney,Card,Bank,Other',
            'service_offered'   => 'nullable|string|max:255',
            'expense_type'      => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
            'date'              => 'nullable|date',
        ]);

        // Generate a unique transaction_id
        $transactionId = strtoupper(Str::random(10));

        $transaction = Transaction::create([
            'employee_id'        => $validated['employee_id'] ?? null,
            'recorded_by'        => Auth::id(),
            'transaction_id'     => $transactionId,
            'receipt_id'         => $validated['receipt_id'] ?? null,
            'customer_name'      => $validated['customer_name'] ?? null,
            'amount'             => $validated['amount'],
            'transaction_type'   => $validated['transaction_type'],
            'payment_method'     => $validated['payment_method'],
            'service_description' => $validated['transaction_type'] === 'Income'
                ? ($validated['service_id'] ?? null)
                : ($validated['expense_type'] ?? null),
            'notes'              => $validated['notes'] ?? null,
            'date'               => isset($validated['date']) ? date('Y-m-d', strtotime($validated['date'])) : now(),
        ]);

        return redirect()->back()->with('success', 'Transaction saved successfully!');
    }

    public function show($id)
    {
        $transaction = Transaction::with(['employee', 'recordedBy'])->find($id);
        return view('pages.transactions.details', compact('transaction'));
    }

    public function delete($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->delete();
            return redirect()->back()->with('success', 'Transaction deleted successfully!');
        }
        return redirect()->back()->with('error', 'Transaction not found.');
    }

    public function edit($id)
    {
        // dd($id);
        $employees = Employee::all();
        $services = Service::where('status', 'Active')->orderBy('name')->get();

        // dd($services);

        $transaction = Transaction::with(['employee', 'recordedBy'])->find($id);
        return  response()->view(
            'pages.transactions.edit',
            [
                'transaction'            => $transaction,
                'employees'       => $employees,
                'services'        => $services,
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'receipt_id'        => 'nullable|string|max:100',
            'employee_id'       => 'required|exists:employees,employee_id',
            'customer_name'     => 'nullable|string|max:150',
            'amount'            => 'required|numeric|min:0',
            'transaction_type'  => 'required|in:Income,Expense',
            'payment_method'    => 'required|in:Cash,MobileMoney,Card,Bank,Other',
            'service_offered'   => 'nullable|string|max:255',
            'expense_type'      => 'nullable|string|max:255',
            'notes'             => 'nullable|string',
            'date'              => 'nullable|date',
        ]);
        // dd($validated);

        $transaction = Transaction::findOrFail($id); // safer than find
        $transaction->update([
            'employee_id'        =>  $validated['employee_id'],
            'recorded_by'        => Auth::id(),
            'receipt_id'         => $validated['receipt_id'] ?? null,
            'customer_name'      => $validated['customer_name'] ?? null,
            'amount'             => $validated['amount'],
            'transaction_type'   => $validated['transaction_type'],
            'payment_method'     => $validated['payment_method'],
            'service_description' => $validated['transaction_type'] === 'Income'
                ? ($validated['service_offered'] ?? null)
                : ($validated['expense_type'] ?? null),
            'notes'              => $validated['notes'] ?? null,
            'date'               => DATE('Y-m-d', strtotime($validated['date'])) ?? now(),
        ]);


        // Redirect based on transaction type
        if ($validated['transaction_type'] === 'Income') {
            return redirect()->route('transactions.income')
                ->with('success', 'Transaction saved successfully!');
        } else {
            return redirect()->route('transactions.expense')
                ->with('success', 'Transaction saved successfully!');
        }
    }
}
