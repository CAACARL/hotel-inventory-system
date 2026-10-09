<!-- Mobile Card Layout -->
<div class="sm:hidden space-y-3">
    @forelse($items as $item)
    @php $userBorrowedQuantity = $item->getBorrowedQuantityByUser(auth()->id()); @endphp
    <div class="border rounded-lg p-4 transition-all duration-200 hover:shadow-md {{ $item->isLowStock() ? 'border-yellow-300 bg-yellow-50/50' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                {{-- Name + badges --}}
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div class="flex items-start gap-3 min-w-0">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-11 h-11 object-cover rounded-lg border border-gray-200 flex-shrink-0 cursor-pointer hover:opacity-80 transition-opacity" onclick="openLightbox('{{ Storage::url($item->image) }}', '{{ addslashes($item->name) }}')">
                        @else
                            <div class="w-11 h-11 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm" style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <div class="font-bold text-gray-900 text-sm">{{ $item->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $item->category->getFullPath() }}</div>
                            @if($item->department)
                                <div class="text-xs text-gray-400 mt-0.5">{{ $item->department->name }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-md
                            @switch($item->status)
                                @case('available') bg-green-100 text-green-700 border border-green-200 @break
                                @case('in_use') bg-blue-100 text-blue-700 border border-blue-200 @break
                                @case('disposed') bg-gray-100 text-gray-700 border border-gray-200 @break
                                @case('spoiled') bg-yellow-100 text-yellow-700 border border-yellow-200 @break
                            @endswitch">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                        <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-md {{ $item->item_type === 'consumable' ? 'bg-orange-100 text-orange-700 border border-orange-200' : 'bg-purple-100 text-purple-700 border border-purple-200' }}">
                            {{ $item->item_type === 'consumable' ? 'Consumable' : 'Non-Consumable' }}
                        </span>
                    </div>
                </div>

                {{-- Stock row --}}
                <div class="flex items-center justify-between text-xs text-gray-600 mb-3 bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">
                    <span>Stock: <span class="font-semibold text-gray-900">{{ $item->quantity }} {{ $item->unit }}</span>
                        @if($item->isLowStock()) <span class="text-red-600 font-medium">(Low)</span> @endif
                    </span>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-1.5">
                    <a href="{{ route('items.show', $item) }}"
                       class="inline-flex items-center px-2.5 py-1.5 text-blue-600 hover:bg-blue-50 text-xs font-medium rounded-lg border border-blue-200 transition-colors">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        View
                    </a>

                    @if(auth()->user()->isAdmin())
                        <button @click="selectedItem = {{ $item->toJson() }}; editModal = true; setTimeout(() => loadSubcategoriesForEdit({{ $item->toJson() }}), 100)"
                                class="inline-flex items-center px-2.5 py-1.5 text-indigo-600 hover:bg-indigo-50 text-xs font-medium rounded-lg border border-indigo-200 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </button>
                    @endif

                    @if($item->quantity > 0 && $item->status === 'available')
                        <button @click="$dispatch('open-borrow', { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', quantity: {{ $item->quantity }}, unit: '{{ $item->unit }}' })"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg border transition-colors hover:bg-amber-50"
                                style="color: #D4AF37; border-color: #D4AF37;">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Borrow
                        </button>
                    @endif

                    @if($userBorrowedQuantity > 0)
                        <button @click="$dispatch('open-return', { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', unit: '{{ $item->unit }}', quantity: {{ $item->quantity }}, borrowed_quantity: {{ $userBorrowedQuantity }} })"
                                class="inline-flex items-center px-2.5 py-1.5 text-green-600 hover:bg-green-50 text-xs font-medium rounded-lg border border-green-200 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            Return
                        </button>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <button @click="deleteItemId = {{ $item->id }}; deleteItemName = '{{ $item->name }}'; deleteModal = true"
                                class="inline-flex items-center px-2.5 py-1.5 text-gray-600 hover:bg-gray-100 text-xs font-medium rounded-lg border border-gray-300 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M10 12v4m4-4v4"></path></svg>
                            Archive
                        </button>

                        @if($item->quantity > 0 && $item->status !== 'disposed' && $item->status !== 'spoiled')
                            <button @click="$dispatch('open-dispose', { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', qty: {{ $item->quantity }} })"
                                    class="inline-flex items-center px-2.5 py-1.5 text-red-600 hover:bg-red-50 text-xs font-medium rounded-lg border border-red-200 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                                Dispose
                            </button>
                        @else
                            <button disabled class="inline-flex items-center px-2.5 py-1.5 text-gray-400 cursor-not-allowed text-xs font-medium rounded-lg border border-gray-300 opacity-50">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                                Dispose
                            </button>
                        @endif
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12 text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No items found</h3>
                <p class="text-sm text-gray-500">Try adjusting your search or filter criteria</p>
            </div>
            @endforelse
        </div>

<!-- Desktop Table View -->
<div class="hidden sm:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        {{-- DESKTOP TABLE LAYOUT --}}
        <table class="w-full table-fixed divide-y divide-gray-200">
            <thead style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                <tr>
                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider w-[250px]">Item</th>
                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider w-[120px]">Category</th>
                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider w-[100px]">Department</th>
                    <th class="px-3 py-3 text-center text-[10px] font-bold text-white uppercase tracking-wider w-[80px]">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($items as $index => $item)
                @php 
                    $userBorrowedQuantity = $item->getBorrowedQuantityByUser(auth()->id());
                @endphp
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-2">
                            @if($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-8 h-8 object-cover rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity flex-shrink-0" onclick="openLightbox('{{ Storage::url($item->image) }}', '{{ addslashes($item->name) }}')">
                            @else
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0" style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-semibold text-gray-900 truncate">{{ $item->name }}</div>
                                <div class="text-[10px] text-gray-500 truncate">{{ Str::limit($item->description, 40) }}</div>
                                <div class="text-[10px] font-medium {{ $item->isLowStock() ? 'text-red-600' : 'text-gray-600' }}">{{ $item->quantity }} {{ $item->unit }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        <div class="text-xs text-gray-900 font-medium truncate">{{ $item->category->getFullPath() }}</div>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-medium rounded-md border {{ $item->item_type === 'consumable' ? 'bg-orange-50 text-orange-700 border-orange-200' : 'bg-purple-50 text-purple-700 border-purple-200' }}">
                                {{ $item->item_type === 'consumable' ? 'Consumable' : 'Non-Cons' }}
                            </span>
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-medium rounded-md border
                                @switch($item->status)
                                    @case('available') bg-green-50 text-green-700 border-green-200 @break
                                    @case('in_use') bg-blue-50 text-blue-700 border-blue-200 @break
                                    @case('disposed') bg-gray-50 text-gray-700 border-gray-200 @break
                                    @case('spoiled') bg-yellow-50 text-yellow-700 border-yellow-200 @break
                                @endswitch">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        <div class="text-xs text-gray-900 truncate">{{ $item->department->name ?? 'Not assigned' }}</div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-center">
                            <div class="relative inline-block text-left" 
                                 x-data="{ 
                                    open: false, 
                                    buttonId: 'btn-{{ $item->id }}',
                                    toggleDropdown(event) {
                                        this.open = !this.open;
                                        if(this.open) {
                                            this.$nextTick(() => {
                                                const rect = event.target.closest('button').getBoundingClientRect();
                                                const dropdown = document.getElementById('dropdown-{{ $item->id }}');
                                                dropdown.style.top = rect.bottom + window.scrollY + 8 + 'px';
                                                dropdown.style.left = rect.right - 192 + 'px';
                                            });
                                        }
                                    }
                                 }" 
                                 @click.away="open = false">
                                <button @click="toggleDropdown($event)" 
                                        class="inline-flex items-center px-3 py-1.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 text-xs font-medium border border-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown rendered at body level -->
                                <template x-teleport="body">
                                    <div x-show="open" 
                                         id="dropdown-{{ $item->id }}"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[9999]"
                                         style="display: none; position: absolute;">
                                        <div class="py-1">
                                        <a href="{{ route('items.show', $item) }}"
                                           class="flex items-center px-4 py-2 text-xs text-blue-600 hover:bg-blue-50 transition-colors duration-150">
                                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Details
                                        </a>

                                        @if(auth()->user()->isAdmin())
                                            <button @click="selectedItem = {{ $item->toJson() }}; editModal = true; setTimeout(() => loadSubcategoriesForEdit({{ $item->toJson() }}), 100); open = false"
                                                    class="w-full flex items-center px-4 py-2 text-xs text-indigo-600 hover:bg-indigo-50 transition-colors duration-150">
                                                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit Item
                                            </button>
                                        @endif

                                        @if($item->quantity > 0 && $item->status === 'available')
                                            <button @click="$dispatch('open-borrow', { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', quantity: {{ $item->quantity }}, unit: '{{ $item->unit }}' }); open = false"
                                                    class="w-full flex items-center px-4 py-2 text-xs text-amber-600 hover:bg-amber-50 transition-colors duration-150">
                                                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                                </svg>
                                                Borrow Item
                                            </button>
                                        @endif

                                        @if($userBorrowedQuantity > 0)
                                            <button @click="$dispatch('open-return', { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', unit: '{{ $item->unit }}', quantity: {{ $item->quantity }}, borrowed_quantity: {{ $userBorrowedQuantity }} }); open = false"
                                                    class="w-full flex items-center px-4 py-2 text-xs text-green-600 hover:bg-green-50 transition-colors duration-150">
                                                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                </svg>
                                                Return Item
                                            </button>
                                        @endif

                                        @if(auth()->user()->isAdmin())
                                            @if($item->quantity > 0 && $item->status !== 'disposed' && $item->status !== 'spoiled')
                                                <button @click="$dispatch('open-dispose', { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', qty: {{ $item->quantity }} }); open = false"
                                                        class="w-full flex items-center px-4 py-2 text-xs text-orange-600 hover:bg-orange-50 transition-colors duration-150">
                                                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                                    </svg>
                                                    Dispose Item
                                                </button>
                                            @endif

                                            <div class="border-t border-gray-100"></div>
                                            <button @click="deleteItemId = {{ $item->id }}; deleteItemName = '{{ $item->name }}'; deleteModal = true; open = false"
                                                    class="w-full flex items-center px-4 py-2 text-xs text-gray-600 hover:bg-gray-100 transition-colors duration-150">
                                                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M10 12v4m4-4v4"></path>
                                                </svg>
                                                Archive Item
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </template>
                        </div>
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-8 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No items found</h3>
                                <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<!-- Pagination -->
<div class="mt-4 sm:mt-6">
    {{ $items->links('vendor.pagination.custom') }}
</div>
