<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-full mx-auto">

        <!-- Breadcrumb -->
        <nav class="flex mb-2 text-sm text-gray-500 fade-in" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li><a href="#" class="hover:text-blue-600">Transactions</a></li>
                <li class="flex items-center"><span class="mx-2">›</span>
                    <a href="{{ route('transactions.' . strtolower($transaction->transaction_type)) }}"
                        class="hover:text-blue-600">
                        {{ ucfirst($transaction->transaction_type) }}
                    </a>
                </li>
                <li class="flex items-center"><span class="mx-2">›</span>
                    <span class="text-gray-400">Details</span>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-2 fade-in ">
            <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                {{ ucfirst($transaction->transaction_type) }} Transaction
            </h1>
            <a href="{{ url('/transactions/income') }}?type={{ $transaction->transaction_type }}"
                class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow hover:opacity-90 transition">
                ← Back to Transactions
            </a>
        </div>

        <!-- Main Card -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6  .fade-in ">

            <!-- Transaction Details -->
            <div class="lg:col-span-2 bg-white shadow-xl rounded-2xl p-8 space-y-6 card-hover">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transaction ID -->
                    <div>
                        <p class="text-xs uppercase text-gray-400">Transaction ID</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $transaction->transaction_id }}</p>
                    </div>
                    <!-- Receipt ID -->
                    <div>
                        <p class="text-xs uppercase text-gray-400">Receipt ID</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $transaction->receipt_id ?? '—' }}</p>
                    </div>
                    <!-- Customer -->
                    <div>
                        <p class="text-xs uppercase text-gray-400">Customer</p>
                        <p class="text-lg font-medium text-gray-800">{{ $transaction->customer_name ?? '—' }}</p>
                    </div>
                    <!-- Service -->
                    <div>
                        <p class="text-xs uppercase text-gray-400">Service</p>
                        <p class="text-lg font-medium text-gray-800">{{ $transaction->service_description ?? '—' }}</p>
                    </div>
                </div>



                <!-- Extra Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                    <div>
                        <p class="text-xs uppercase text-gray-400">Payment Method</p>
                        <p class="text-gray-800">{{ $transaction->payment_method }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-400">Serviced By</p>
                        <p class="text-gray-800">
                            {{ $transaction->employee ? $transaction->employee->first_name . ' ' . $transaction->employee->last_name : 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400">Date</p>
                        <p class="text-gray-800">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400">Recorded By</p>
                        <p class="text-gray-800">{{ $transaction->recordedBy->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Notes -->
                <div class="pt-4 border-t">
                    <p class="text-xs uppercase text-gray-400 mb-1">Notes</p>
                    <p class=" text-gray-700 border-black">
                        {{ $transaction->notes ?? 'No notes were taken' }}</p>
                </div>

                <!-- Amount + Type -->
                <div class="flex flex-wrap items-center gap-4 border-t pt-4 lg:mt-20">
                    <p class="text-2xl font-bold text-green-600">
                        Shs. {{ number_format($transaction->amount, 0) }}
                    </p>
                    <span
                        class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $transaction->transaction_type === 'Income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($transaction->transaction_type) }}
                    </span>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-gray-50 to-white shadow-xl rounded-2xl p-6 flex flex-col items-center justify-center card-hover">
                <p class="text-sm font-medium text-gray-600 mb-3">Digital Receipt</p>
                <div id="qrcode" class="bg-white p-4 rounded-lg shadow"></div>
                <p class="text-xs text-gray-400 mt-3 text-center">
                    Scan to view full transaction details
                </p>
                <div class="w-12 h-px bg-black my-4"></div>
                <a href="mailto:">
                    <button type="button"
                        class="w-full mt-3.5 sm:w-auto px-5 py-2.5 text-sm font-medium rounded-lg
               bg-blue-600 text-white shadow hover:bg-blue-700 focus:ring-2
               focus:ring-offset-2 focus:ring-blue-500 transition">
                        📧 Send Email Receipt
                    </button>
                </a>
            </div>

        </div>
    </div>

    <!-- QR Code Script -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create a shorter, more concise receipt text
            let receiptText =
                `Kenvies Beauty:{{ $transaction->transaction_id }}:{{ $transaction->receipt_id ?? 'N/A' }}:{{ $transaction->customer_name ?? 'Walk-in' }}:{{ number_format($transaction->amount, 0) }}:{{ $transaction->payment_method }}:{{ $transaction->created_at->format('dMy') }}`;

            // Remove any special characters that might cause issues
            receiptText = receiptText.replace(/[^\w\s:.-]/gi, '');

            try {
                new QRCode(document.getElementById("qrcode"), {
                    text: receiptText,
                    width: 180,
                    height: 180,
                    colorDark: "#1f2937",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel
                        .L // Use lowest error correction for more data capacity
                });
            } catch (error) {
                console.error("QR Code generation error:", error);
                document.getElementById("qrcode").innerHTML =
                    '<div class="text-center text-red-500 text-sm p-4">' +
                    'QR Code could not be generated<br>(Data too long)' +
                    '</div>';
            }
        });
    </script>

</x-app-layout>
