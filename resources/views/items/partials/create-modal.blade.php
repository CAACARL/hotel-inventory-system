<!-- Search Modal -->
<div x-show="searchModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[60] overflow-y-auto" 
     style="display: none;"
     @keydown.escape="searchModal = false"
     x-init="$watch('searchModal', value => { document.body.classList.toggle('modal-open', value) })">
    
    <!-- Enhanced Backdrop with Blur -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="searchModal = false"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="searchModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="modal-container bg-white rounded-lg shadow-2xl max-w-sm w-full mx-auto relative z-10 border border-amber-200">
            
            <!-- Modern Modal Header with Gradient -->
            <div class="modal-header-gradient flex items-center justify-between p-3 border-b border-gray-200 rounded-t-lg" style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                <div class="flex items-center">
                    <div class="w-7 h-7 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-white">Search Items</h3>
                </div>
                <button @click="searchModal = false" class="text-white hover:text-amber-200 transition-colors duration-200 p-1 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body with Modern Form -->
            <form method="GET" action="{{ route('items.index') }}" class="p-3">
                <div class="space-y-2">
                    <div>
                        <label for="search" class="block text-xs font-semibold text-gray-700 mb-1">Search Term</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Enter item name..."
                               class="modern-input w-full px-2.5 py-1.5 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs"
                               autofocus>
                    </div>
                    
                    <div>
                        <label for="category" class="block text-xs font-semibold text-gray-700 mb-1">Category (Optional)</label>
                        <select name="category" id="category" class="modern-input w-full px-2.5 py-1.5 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 mb-1">Status (Optional)</label>
                        <select name="status" id="status" class="modern-input w-full px-2.5 py-1.5 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs">
                            <option value="">All Statuses</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                            <option value="spoiled" {{ request('status') == 'spoiled' ? 'selected' : '' }}>Spoiled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="department" class="block text-xs font-semibold text-gray-700 mb-1">Department (Optional)</label>
                        <select name="department" id="department" class="modern-input w-full px-2.5 py-1.5 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs">
                            <option value="">All Departments</option>
                            @foreach(\App\Models\Department::where('is_active', true)->get() as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Modern Modal Footer -->
                <div class="flex justify-end space-x-2 mt-3 pt-3 border-t border-gray-200">
                    <button type="button" 
                            @click="searchModal = false"
                            class="px-3 py-1.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 font-medium text-xs">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="animated-button px-4 py-1.5 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl text-xs" 
                            style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Archive Confirmation Modal -->
<div x-show="deleteModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[70] overflow-y-auto" 
     style="display: none;"
     @keydown.escape="deleteModal = false"
     x-init="$watch('deleteModal', value => { document.body.classList.toggle('modal-open', value) })">
    
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="deleteModal = false"></div>
    
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="deleteModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="modal-container bg-white rounded-lg shadow-2xl max-w-sm w-full mx-auto border border-gray-300 relative z-10">
            
            <div class="modal-header-gradient flex items-center justify-between p-2.5 border-b border-gray-200 rounded-t-lg" style="background: linear-gradient(135deg, #374151 0%, #6B7280 100%);">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M10 12v4m4-4v4"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-white">Archive Item</h3>
                </div>
                <button @click="deleteModal = false" class="text-white hover:text-gray-200 transition-colors duration-200 p-1 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-2.5">
                <div class="bg-gray-50 rounded-lg p-2 mb-2 border border-gray-200">
                    <div class="flex items-start space-y-1">
                        <svg class="w-4 h-4 text-gray-500 mr-1.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M10 12v4m4-4v4"></path>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold text-gray-800">Archive this item?</p>
                            <p class="text-[10px] text-gray-600 mt-0.5">
                                <span class="font-medium" x-text="deleteItemName"></span> will be moved to the archive. No operations can be performed on it. You can view it under Archived Items.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" 
                            @click="deleteModal = false"
                            class="px-3 py-1 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 font-medium text-xs">
                        Cancel
                    </button>
                    <button type="button"
                            @click="
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '/items/' + deleteItemId;
                                const csrfToken = document.createElement('input');
                                csrfToken.type = 'hidden';
                                csrfToken.name = '_token';
                                csrfToken.value = '{{ csrf_token() }}';
                                const methodField = document.createElement('input');
                                methodField.type = 'hidden';
                                methodField.name = '_method';
                                methodField.value = 'DELETE';
                                const pageField = document.createElement('input');
                                pageField.type = 'hidden';
                                pageField.name = 'page';
                                pageField.value = '{{ request("page", 1) }}';
                                form.appendChild(csrfToken);
                                form.appendChild(methodField);
                                form.appendChild(pageField);
                                document.body.appendChild(form);
                                form.submit();
                            "
                            class="animated-button px-4 py-1 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg text-xs" 
                            style="background: linear-gradient(135deg, #374151 0%, #6B7280 100%);">
                        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M10 12v4m4-4v4"></path>
                        </svg>
                        Archive
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Item Modal -->
<div x-show="createModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[60] overflow-y-auto" 
     @keydown.escape="createModal = false"
     style="display: none;"
     x-init="$watch('createModal', value => { document.body.classList.toggle('modal-open', value) })">
    
    <!-- Enhanced Backdrop with Blur -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="createModal = false"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="createModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="modal-container bg-white rounded-lg shadow-2xl max-w-md w-full mx-auto relative z-10 border border-amber-200">
            
            <!-- Modern Modal Header with Gradient -->
            <div class="modal-header-gradient flex items-center justify-between p-2.5 border-b border-gray-200 rounded-t-lg" style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-white">Create New Item</h3>
                </div>
                <button @click="createModal = false" class="text-white hover:text-amber-200 transition-colors duration-200 p-1 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="p-2.5">
                @csrf
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                
                <div class="space-y-1.5">
                    <div>
                        <label for="modal_item_name" class="block text-xs font-semibold text-gray-700 mb-0.5">Item Name</label>
                        <input type="text" 
                               id="modal_item_name" 
                               name="name" 
                               required
                               class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs" 
                               placeholder="Enter item name">
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-0.5">
                            <label for="modal_category_id" class="block text-xs font-semibold text-gray-700">Category</label>
                            <button type="button"
                                    @click="quickCreateCategoryModal = true"
                                    class="inline-flex items-center px-1.5 py-0.5 text-green-600 hover:bg-green-50 rounded text-[10px] font-medium"
                                    title="Create new category">
                                <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New
                            </button>
                        </div>
                        <select name="category_id" 
                                id="modal_category_id" 
                                required
                                class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs">
                            <option value="">Select Category</option>
                            @foreach(\App\Models\Category::where('is_active', true)->orderBy('path')->get() as $category)
                                <option value="{{ $category->id }}">
                                    @if($category->level == 0)
                                        {{ $category->name }}
                                    @else
                                        {{ str_repeat('│  ', $category->level - 1) }}├─ {{ $category->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="modal_department_id" class="block text-xs font-semibold text-gray-700 mb-0.5">Department</label>
                        <select name="department_id" 
                                id="modal_department_id" 
                                class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs">
                            <option value="">Select Department (Optional)</option>
                            @foreach(\App\Models\Department::where('is_active', true)->get() as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-1.5">
                        <div>
                            <input type="hidden" name="status" value="available">
                            <label for="modal_item_type" class="block text-xs font-semibold text-gray-700 mb-0.5">Item Type</label>
                            <select name="item_type" 
                                    id="modal_item_type" 
                                    required
                                    class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs">
                                <option value="non-consumable">Non-Consumable</option>
                                <option value="consumable">Consumable</option>
                            </select>
                        </div>
                        <div x-data="{ custom: false, unit: 'pcs' }">
                            <label class="block text-xs font-semibold text-gray-700 mb-0.5">Unit</label>
                            <select x-show="!custom"
                                    x-model="unit"
                                    @change="if(unit === 'other') { custom = true; unit = ''; $nextTick(() => $refs.unitCustom.focus()); }"
                                    class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs">
                                <option value="pcs">pcs</option>
                                <option value="sets">sets</option>
                                <option value="bottles">bottles</option>
                                <option value="bags">bags</option>
                                <option value="rolls">rolls</option>
                                <option value="boxes">boxes</option>
                                <option value="pairs">pairs</option>
                                <option value="kits">kits</option>
                                <option value="reams">reams</option>
                                <option value="cans">cans</option>
                                <option value="packs">packs</option>
                                <option value="other">Other...</option>
                            </select>
                            <div x-show="custom" class="flex gap-1">
                                <input type="text" x-ref="unitCustom" x-model="unit" required
                                       class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                       placeholder="e.g. liters">
                                <button type="button" @click="custom = false; unit = 'pcs'"
                                        class="px-1.5 py-0.5 text-gray-400 hover:text-gray-600 text-xs rounded border border-gray-200">↩</button>
                            </div>
                            <input type="hidden" name="unit" :value="unit">
                        </div>
                    </div>
                    
                    <div>
                        <label for="modal_description" class="block text-xs font-semibold text-gray-700 mb-0.5">Description</label>
                        <textarea id="modal_description" 
                                  name="description" 
                                  rows="2"
                                  class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs" 
                                  placeholder="Enter item description"></textarea>
                    </div>

                    <div>
                        <label for="modal_image" class="block text-xs font-semibold text-gray-700 mb-0.5">Item Image (Optional)</label>
                        <input type="file"
                               id="modal_image"
                               name="image"
                               accept="image/*"
                               class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs text-gray-700 file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all duration-200">
                        <p class="mt-0.5 text-[10px] text-gray-400">JPEG, PNG, GIF or WebP. Max 2MB.</p>
                    </div>
                </div>
                
                <!-- Modern Modal Footer -->
                <div class="flex justify-end space-x-2 mt-2 pt-2 border-t border-gray-200">
                    <button type="button" 
                            @click="createModal = false"
                            class="px-3 py-1 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 font-medium text-xs">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="animated-button px-4 py-1 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl text-xs" 
                            style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Create Category Modal -->
<div x-show="quickCreateCategoryModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[70] overflow-y-auto" 
     @keydown.escape="quickCreateCategoryModal = false"
     style="display: none;"
     x-init="$watch('quickCreateCategoryModal', value => { document.body.classList.toggle('modal-open', value) })">
    
    <!-- Enhanced Backdrop with Blur -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="quickCreateCategoryModal = false"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="quickCreateCategoryModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="modal-container bg-white rounded-lg shadow-2xl max-w-sm w-full mx-auto relative z-10 border border-amber-200">
            
            <!-- Modern Modal Header with Gradient -->
            <div class="modal-header-gradient flex items-center justify-between p-3 border-b border-gray-200 rounded-t-lg" style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                <div class="flex items-center">
                    <div class="w-7 h-7 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-white">Create New Category</h3>
                </div>
                <button @click="quickCreateCategoryModal = false" class="text-white hover:text-amber-200 transition-colors duration-200 p-1 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body with Modern Form -->
            <form id="quickCreateCategoryForm" action="{{ route('categories.store') }}" method="POST" class="p-3">
                @csrf
                <div class="space-y-2.5">
                    <div>
                        <label for="quick_category_name" class="block text-xs font-semibold text-gray-700 mb-1">Category Name</label>
                        <input type="text" 
                               id="quick_category_name" 
                               name="name" 
                               required
                               class="modern-input w-full px-2.5 py-1.5 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs" 
                               placeholder="Enter category name">
                    </div>

                    <div>
                        <label for="quick_parent_id" class="block text-xs font-semibold text-gray-700 mb-1">Parent Category (Optional)</label>
                        <select name="parent_id" 
                                id="quick_parent_id" 
                                class="modern-input w-full px-2.5 py-1.5 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs">
                            <option value="">-- Root Category --</option>
                            @foreach(\App\Models\Category::where('is_active', true)->orderBy('path')->get() as $category)
                                <option value="{{ $category->id }}">
                                    @if($category->level == 0)
                                        {{ $category->name }}
                                    @else
                                        {{ str_repeat('│  ', $category->level - 1) }}├─ {{ $category->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="quick_category_description" class="block text-xs font-semibold text-gray-700 mb-1">Description (Optional)</label>
                        <textarea id="quick_category_description" 
                                  name="description" 
                                  rows="2"
                                  class="modern-input w-full px-2.5 py-1.5 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs" 
                                  placeholder="Enter category description"></textarea>
                    </div>
                </div>
                
                <!-- Modern Modal Footer -->
                <div class="flex justify-end space-x-2 mt-3 pt-3 border-t border-gray-200">
                    <button type="button" 
                            @click="quickCreateCategoryModal = false"
                            class="px-3 py-1.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 font-medium text-xs">
                        Cancel
                    </button>
                    <button type="submit" 
                            onclick="event.preventDefault(); submitQuickCategory();"
                            class="animated-button px-4 py-1.5 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl text-xs" 
                            style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let isSubmittingCategory = false;

function submitQuickCategory() {
    // Prevent multiple submissions
    if (isSubmittingCategory) {
        return false;
    }
    
    isSubmittingCategory = true;
    
    const form = document.getElementById('quickCreateCategoryForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);
    
    // Disable button and show loading state
    submitBtn.disabled = true;
    const originalBtnContent = submitBtn.innerHTML;
    submitBtn.innerHTML = `
        <svg class="animate-spin w-4 h-4 mr-1.5 inline" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Creating...
    `;
    
    fetch('{{ route('categories.store') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('modal_category_id');
            const option = document.createElement('option');
            option.value = data.category.id;
            
            let displayText = data.category.name;
            if (data.category.level > 0) {
                displayText = '\u2502  '.repeat(data.category.level - 1) + '\u251C\u2500 ' + data.category.name;
            }
            option.text = displayText;
            option.selected = true;
            
            if (data.category.parent_id) {
                let inserted = false;
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value == data.category.parent_id) {
                        select.add(option, select.options[i + 1]);
                        inserted = true;
                        break;
                    }
                }
                if (!inserted) {
                    select.appendChild(option);
                }
            } else {
                select.appendChild(option);
            }
            
            form.reset();
            
            // Show success notification matching site style
            const notification = document.createElement('div');
            notification.setAttribute('x-data', '{ show: true }');
            notification.setAttribute('x-show', 'show');
            notification.setAttribute('x-init', 'setTimeout(() => show = false, 4000)');
            notification.className = 'fixed top-4 right-4 z-[100] max-w-sm w-full pointer-events-auto';
            notification.style.cssText = 'transition: opacity 0.3s, transform 0.3s;';
            
            notification.innerHTML = `
                <div class="bg-white rounded-2xl shadow-2xl border border-green-200 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900">Success!</h4>
                                <p class="text-sm text-gray-600">Category created successfully</p>
                            </div>
                            <button onclick="this.closest('[x-data]').remove()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200 ml-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="bg-gray-200 h-1">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-1" style="width: 100%; animation: shrink 4s linear;"></div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 4100);
            
            setTimeout(() => {
                const closeBtn = document.querySelector('[x-show="quickCreateCategoryModal"] button[type="button"]');
                if (closeBtn) closeBtn.click();
                
                // Reset flag after modal closes
                isSubmittingCategory = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnContent;
            }, 100);
        } else {
            // Re-enable button on error
            isSubmittingCategory = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        // Re-enable button on error
        isSubmittingCategory = false;
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnContent;
        console.error('Error:', error);
        alert('Error creating category. Please try again.');
    });
    
    return false;
}
</script>
