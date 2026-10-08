<x-app-layout>
    <div x-data="{}" class="py-3 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center flex-shrink-0" 
                         style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-amber-700">{{ $batch->batch_number }}</h1>
                        <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 mt-0.5">
                            <span>{{ $batch->item->name }}</span>
                            <span>•</span>
                            <span>{{ number_format($batch->quantity) }} {{ $batch->item->unit }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm
                        @switch($batch->status)
                            @case('active') bg-green-100 text-green-800 border border-green-200 @break
                            @case('expired') bg-red-100 text-red-800 border border-red-200 @break
                            @default bg-gray-100 text-gray-800 border border-gray-200 @break
                        @endswitch">
                        {{ ucfirst($batch->status) }}
                    </span>
                    <a href="{{ route('batches.index') }}" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-xs font-semibold" style="border: 1px solid #D4AF37; color: #3D2914;">
                        <svg class="w-3.5 h-3.5 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="hidden sm:inline">Back to Batches</span>
                    </a>
                </div>
            </div>

            <!-- Status Alerts -->
            @if($batch->isExpired())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-red-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="text-red-700 font-semibold text-sm">This batch has expired</span>
                    </div>
                </div>
            @elseif($batch->isExpiringSoon())
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-yellow-700 font-semibold text-sm">This batch is expiring soon</span>
                    </div>
                </div>
            @endif

            <!-- Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <!-- Basic Information Card -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        Basic Information
                    </h3>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Batch Number</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $batch->batch_number }}</span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Item</span>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900">{{ $batch->item->name }}</div>
                                <div class="text-xs text-gray-500">{{ $batch->item->category->name }}</div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Quantity</span>
                            <span class="text-base font-bold text-gray-900">{{ number_format($batch->quantity) }} {{ $batch->item->unit }}</span>
                        </div>

                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full 
                                @switch($batch->status)
                                    @case('active') bg-green-100 text-green-800 @break
                                    @case('expired') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800 @break
                                @endswitch">
                                {{ ucfirst($batch->status) }}
                            </span>
                        </div>

                        @if($batch->unit_cost)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Unit Cost</span>
                            <span class="text-sm font-semibold text-gray-900">₱{{ number_format($batch->unit_cost, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-semibold text-gray-700">Total Value</span>
                            <span class="text-base font-bold text-green-600">₱{{ number_format($batch->quantity * $batch->unit_cost, 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tracking Information Card -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Tracking Information
                    </h3>
                    
                    <div class="space-y-2">
                        @if($batch->supplier)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Supplier</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $batch->supplier }}</span>
                        </div>
                        @endif

                        @if($batch->location)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Location</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $batch->location }}</span>
                        </div>
                        @endif

                        @if($batch->lot_number)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Lot Number</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $batch->lot_number }}</span>
                        </div>
                        @endif

                        @if($batch->manufacture_date)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Manufacture Date</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $batch->manufacture_date->format('M d, Y') }}</span>
                        </div>
                        @endif

                        @if($batch->expiry_date)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Expiry Date</span>
                            <div class="text-right">
                                <div class="text-sm font-semibold {{ $batch->isExpired() ? 'text-red-600' : ($batch->isExpiringSoon() ? 'text-yellow-600' : 'text-gray-900') }}">
                                    {{ $batch->expiry_date->format('M d, Y') }}
                                </div>
                                @if($batch->isExpired())
                                    <div class="text-xs text-red-500">Expired {{ $batch->expiry_date->diffForHumans() }}</div>
                                @elseif($batch->isExpiringSoon())
                                    <div class="text-xs text-yellow-600">Expires {{ $batch->expiry_date->diffForHumans() }}</div>
                                @else
                                    <div class="text-xs text-gray-600">{{ $batch->expiry_date->diffForHumans() }}</div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-semibold text-gray-700">Created</span>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900">{{ $batch->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-600">{{ $batch->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Depreciation Card -->
            @if($batch->hasDepreciation())
            <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 mb-4">
                <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Depreciation
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="text-[10px] font-semibold text-gray-500 mb-0.5">Method</p>
                        <p class="text-xs font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $batch->depreciation_method)) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="text-[10px] font-semibold text-gray-500 mb-0.5">Purchase Price</p>
                        <p class="text-xs font-bold text-gray-900">₱{{ number_format($batch->purchase_price, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="text-[10px] font-semibold text-gray-500 mb-0.5">Purchase Date</p>
                        <p class="text-xs font-bold text-gray-900">{{ $batch->purchase_date->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="text-[10px] font-semibold text-gray-500 mb-0.5">Useful Life</p>
                        <p class="text-xs font-bold text-gray-900">{{ $batch->useful_life_years }} years</p>
                    </div>
                    @if($batch->salvage_value)
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="text-[10px] font-semibold text-gray-500 mb-0.5">Salvage Value</p>
                        <p class="text-xs font-bold text-gray-900">₱{{ number_format($batch->salvage_value, 2) }}</p>
                    </div>
                    @endif
                    <div class="bg-indigo-50 rounded-lg p-2">
                        <p class="text-[10px] font-semibold text-indigo-600 mb-0.5">Accumulated Depreciation</p>
                        <p class="text-xs font-bold text-indigo-900">₱{{ number_format($batch->calculateDepreciation(), 2) }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-2">
                        <p class="text-[10px] font-semibold text-green-600 mb-0.5">Current Book Value</p>
                        <p class="text-xs font-bold text-green-900">₱{{ number_format($batch->getCurrentBookValue(), 2) }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes Card -->
            @if($batch->notes)
            <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 mb-4">
                <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Notes
                </h3>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-900 text-sm">{{ $batch->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Actions and Batch ID -->
            <div class="flex justify-center">
                @if($batch->status === 'active' && $batch->isExpired())
                <div class="bg-white border border-gray-200 rounded-lg p-4 inline-block">
                    <form action="{{ route('batches.mark-expired', $batch) }}" method="POST" class="inline-flex items-center gap-4">
                        @csrf
                        <button type="submit" class="text-orange-600 hover:text-orange-700 text-sm font-medium inline-flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mark as Expired
                        </button>
                        <span class="text-xs text-gray-400">|</span>
                        <span class="text-xs text-gray-500">Batch ID: <span class="font-bold text-gray-900">#{{ $batch->id }}</span></span>
                    </form>
                </div>
                @else
                <div class="bg-white border border-gray-200 rounded-lg p-4 inline-block">
                    <span class="text-xs text-gray-500">Batch ID: <span class="font-bold text-gray-900">#{{ $batch->id }}</span></span>
                </div>
                @endif
            </div>
        </div>
    
</div>
</x-app-layout>