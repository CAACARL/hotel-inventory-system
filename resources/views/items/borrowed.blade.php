<x-app-layout>
    <div class="py-3 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">

            <!-- Modern Page Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 sm:mb-6">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center shadow-md flex-shrink-0"
                         style="background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold mb-0.5" style="color: #EA580C;">Borrowed Items</h1>
                        <p class="text-gray-600 text-xs sm:text-sm font-medium hidden sm:block">
                            @if(auth()->user()->isAdmin())
                                Track all items currently borrowed by staff members
                            @else
                                Items you currently have borrowed
                            @endif
                        </p>
                        <div class="flex items-center mt-0.5 sm:mt-1 text-xs text-gray-500">
                            <div class="w-1.5 h-1.5 bg-orange-500 rounded-full mr-1.5"></div>
                            <span class="font-medium">{{ $borrowedItems->count() }} Active Borrowings</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('items.index') }}" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-xs font-semibold" style="border: 1px solid #D4AF37; color: #3D2914;">
                    <svg class="w-3.5 h-3.5 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="hidden sm:inline">Back to Items</span>
                </a>
            </div>

            @if($borrowedItems->count() > 0)

                <!-- Mobile Card View -->
                <div class="sm:hidden space-y-3 mb-4">
                    @foreach($borrowedItems as $borrowing)
                    <div class="border border-orange-200 rounded-xl p-4 bg-orange-50/30 hover:bg-orange-50/50 transition-colors">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="flex items-start gap-2 min-w-0 flex-1">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-gray-900 text-sm">{{ $borrowing->item->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $borrowing->item->category->name }}</div>
                                </div>
                            </div>
                            <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded-full bg-orange-100 text-orange-800 flex-shrink-0">
                                {{ $borrowing->quantity_borrowed }} {{ $borrowing->item->unit }}
                            </span>
                        </div>
                        <div class="space-y-1 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 w-20">Borrower:</span>
                                <span class="font-medium text-gray-900">{{ $borrowing->borrower_name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 w-20">Department:</span>
                                <span class="text-gray-700">{{ $borrowing->borrower_department }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 w-20">Date:</span>
                                <span class="text-gray-700">{{ $borrowing->borrowed_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 w-20">Reference:</span>
                                <span class="font-mono text-gray-700 text-[10px]">{{ $borrowing->reference_number }}</span>
                            </div>
                            @if($borrowing->notes)
                            <div class="flex items-start gap-2 pt-1 border-t border-orange-100">
                                <span class="text-gray-500 w-20">Notes:</span>
                                <span class="text-gray-700 flex-1">{{ $borrowing->notes }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden sm:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead style="background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);">
                                <tr>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Item</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Borrower</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Department</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Quantity</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Reference</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($borrowedItems as $borrowing)
                                <tr class="hover:bg-orange-50/30 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold text-gray-900 truncate">{{ $borrowing->item->name }}</div>
                                                <div class="text-[10px] text-gray-500 truncate">{{ $borrowing->item->category->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg overflow-hidden flex-shrink-0 shadow-sm">
                                                @if($borrowing->user->getProfilePictureUrl())
                                                    <img src="{{ $borrowing->user->getProfilePictureUrl() }}" alt="{{ $borrowing->user->name }}" class="w-full h-full object-cover">
                                                @else
                                                    @php $avatar = $borrowing->user->getDefaultAvatar(); @endphp
                                                    <div class="w-full h-full {{ $avatar['color'] }} flex items-center justify-center text-white font-bold text-[10px]">
                                                        {{ $avatar['initials'] }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs font-medium text-gray-900 truncate">{{ $borrowing->borrower_name }}</div>
                                                <div class="text-[10px] text-gray-500 truncate">{{ $borrowing->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-gray-900">{{ $borrowing->borrower_department }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                                            {{ $borrowing->quantity_borrowed }} {{ $borrowing->item->unit }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $borrowing->borrowed_date->format('M d, Y') }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $borrowing->borrowed_date->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono bg-gray-100 text-gray-800">
                                            {{ $borrowing->reference_number }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-gray-700 max-w-xs truncate">{{ $borrowing->notes ?: '—' }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="text-center py-12 px-4">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-orange-50">
                            <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-2">No Items Currently Borrowed</h3>
                        <p class="text-sm text-gray-500">All items have been returned or no borrowing transactions have been made.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
