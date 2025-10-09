<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;


class EmployeeController extends Controller
{
    public function statusChart()
    {
        $activeCount = Employee::where('work_status', 'Active')->count();
        $deactivatedCount = Employee::where('work_status', 'Deactivated')->count();

        // Morris.js expects an array of objects
        $chartData = [
            ['status' => 'Active', 'count' => $activeCount],
            ['status' => 'Deactivated', 'count' => $deactivatedCount],
        ];

        return response()->json($chartData);
    }
}
