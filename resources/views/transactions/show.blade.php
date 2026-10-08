<x-app-layout>
    <div class="py-3 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
            
            <!-- Modern Page Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 sm:mb-6">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center shadow-md flex-shrink-0" 
                         style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold mb-0.5 text-amber-700">
                            Transaction #{{ $transaction->id }}
                        </h1>
                        <p class="text-gray-600 text-xs sm:text-sm font-medium hidden sm:block">Detailed information about this transaction</p>
                        <div class="flex items-center mt-0.5 sm:mt-1 text-xs text-gray-500">
                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></div>
                            <span class="font-medium">{{ $transaction->transaction_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm
                        @switch($transaction->transaction_type)
                            @case('borrow') bg-blue-100 text-blue-800 border border-blue-200 @break
                            @case('return') bg-purple-100 text-purple-800 border border-purple-200 @break
                            @case('replenish') bg-indigo-100 text-indigo-800 border border-indigo-200 @break
                            @case('disposal') bg-red-100 text-red-800 border border-red-200 @break
                            @case('spoiled') bg-orange-100 text-orange-800 border border-orange-200 @break
                            @default bg-gray-100 text-gray-800 border border-gray-200 @break
                        @endswitch">
                        @if($transaction->type === 'in')
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8l-8-8-8 8"></path>
                            </svg>
                        @else
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20V4m-8 8l8 8 8-8"></path>
                            </svg>
                        @endif
                        {{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}
                    </span>
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-xs font-semibold" style="border: 1px solid #D4AF37; color: #3D2914;">
                        <svg class="w-3.5 h-3.5 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="hidden sm:inline">Back to Transactions</span>
                    </a>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                <!-- Transaction Information Card -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Transaction Information
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Transaction Type</span>
                            <span class="text-sm font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $transaction->transaction_type) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Quantity</span>
                            <span class="text-base font-bold text-gray-900">
                                {{ $transaction->type === 'out' ? '-' : '+' }}{{ number_format($transaction->quantity) }} {{ $transaction->item->unit }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Processed By</span>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900">{{ $transaction->user->name }}</div>
                                <div class="text-xs text-gray-500 font-medium capitalize">{{ $transaction->user->role }}</div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Reference Number</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $transaction->reference_number ?? '—' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-semibold text-gray-700">Transaction Date</span>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900">{{ $transaction->transaction_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500 font-medium">{{ $transaction->transaction_date->format('h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Information Card -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Item Information
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Item Name</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $transaction->item->name }}</span>
                        </div>
                        
                        @if($transaction->batch)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Batch Number</span>
                            <span class="text-xs text-gray-600 font-mono">{{ $transaction->batch->batch_number }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Category</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $transaction->item->category->name }}</span>
                        </div>
                        
                        @if($transaction->item->department)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Department</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $transaction->item->department->name }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Current Stock</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($transaction->item->quantity) }} {{ $transaction->item->unit }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-semibold text-gray-700">Item Status</span>
                            <span class="text-sm font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $transaction->item->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description/Notes Card -->
            @if($transaction->notes)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 sm:p-6 mb-4 sm:mb-6">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Transaction Notes
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-900 text-sm leading-relaxed">{{ $transaction->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Action Buttons Card -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <a href="{{ route('items.show', ['item' => $transaction->item, 'from' => 'transaction', 'transaction_id' => $transaction->id]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-xs font-semibold" style="border: 1px solid #3B82F6; color: #1D4ED8;">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        View Item Details
                    </a>
                    
                    <div class="text-center sm:text-right">
                        <div class="text-xs text-gray-500 font-medium">Transaction ID</div>
                        <div class="text-sm font-bold text-gray-900">#{{ $transaction->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>