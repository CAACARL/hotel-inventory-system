<!-- Edit Item Modal -->
<div x-show="editModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[60] overflow-y-auto" 
     @keydown.escape="editModal = false"
     style="display: none;"
     x-init="$watch('editModal', value => { document.body.classList.toggle('modal-open', value) })">
    
    <!-- Enhanced Backdrop with Blur -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="editModal = false"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="editModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="modal-container bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-auto relative z-10 border border-indigo-200">
            
            <!-- Modern Modal Header with Gradient -->
            <div class="modal-header-gradient flex items-center justify-between p-4 border-b border-gray-200 rounded-t-2xl" style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);">
                <div class="flex items-center">
                    <div class="w-9 h-9 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Edit Item</h3>
                        <p class="text-indigo-100 text-xs">Update item information</p>
                    </div>
                </div>
                <button @click="editModal = false" class="text-white hover:text-indigo-200 transition-colors duration-200 p-1.5 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form :action="'/items/' + selectedItem?.id" method="POST" class="p-4" x-show="selectedItem" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                
                <div class="space-y-3">
                    <div>
                        <label for="edit_item_name" class="block text-xs font-semibold text-gray-700 mb-1.5">Item Name</label>
                        <input type="text" 
                               id="edit_item_name" 
                               name="name" 
                               required
                               :value="selectedItem?.name"
                               class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400 text-sm" 
                               placeholder="Enter item name">
                    </div>
                    
                    <div>
                        <label for="edit_category_id" class="block text-xs font-semibold text-gray-700 mb-1.5">Category</label>
                        <select name="category_id" 
                                id="edit_category_id" 
                                required
                                class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400 text-sm">
                            <option value="">Select Category</option>
                            @foreach(\App\Models\Category::where('is_active', true)->orderBy('path')->get() as $category)
                                <option :selected="selectedItem?.category_id == {{ $category->id }}" value="{{ $category->id }}">
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
                        <label for="edit_department_id" class="block text-xs font-semibold text-gray-700 mb-1.5">Department</label>
                        <select name="department_id" 
                                id="edit_department_id" 
                                class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400 text-sm">
                            <option value="">Select Department (Optional)</option>
                            @foreach(\App\Models\Department::where('is_active', true)->get() as $department)
                                <option :selected="selectedItem?.department_id == {{ $department->id }}" value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="edit_status" class="block text-xs font-semibold text-gray-700 mb-1.5">Status</label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 capitalize"
                                 x-text="selectedItem?.status?.replace('_', ' ')"></div>
                            <input type="hidden" name="status" :value="selectedItem?.status">
                        </div>

                        <div>
                            <label for="edit_item_type" class="block text-xs font-semibold text-gray-700 mb-1.5">Item Type</label>
                            <select name="item_type"
                                    id="edit_item_type"
                                    required
                                    class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400 text-sm">
                                <option :selected="selectedItem?.item_type == 'non-consumable'" value="non-consumable">Non-Consumable</option>
                                <option :selected="selectedItem?.item_type == 'consumable'" value="consumable">Consumable</option>
                            </select>
                        </div>
                        <div x-data="{ custom: false, unit: 'pcs' }" x-init="$watch('selectedItem', v => { const std = ['pcs','sets','bottles','bags','rolls','boxes','pairs','kits','reams','cans','packs']; custom = !!v?.unit && !std.includes(v.unit); unit = v?.unit || 'pcs'; })">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Unit</label>
                            <select x-show="!custom"
                                    x-model="unit"
                                    @change="if(unit === 'other') { custom = true; unit = ''; $nextTick(() => $refs.editUnitCustom.focus()); }"
                                    class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400 text-sm">
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
                                <input type="text" x-ref="editUnitCustom" x-model="unit" required
                                       class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="e.g. liters">
                                <button type="button" @click="custom = false; unit = 'pcs'"
                                        class="px-2 py-1 text-gray-400 hover:text-gray-600 text-xs rounded-lg border border-gray-200">↩</button>
                            </div>
                            <input type="hidden" name="unit" :value="unit">
                        </div>
                    </div>

                    <div>
                        <label for="edit_location" class="block text-xs font-semibold text-gray-700 mb-1.5">Location</label>
                        <input type="text" 
                               id="edit_location" 
                               name="location" 
                               :value="selectedItem?.location"
                               class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400 text-sm" 
                               placeholder="e.g., Storage Room A">
                    </div>
                    
                    <div>
                        <label for="edit_item_description" class="block text-xs font-semibold text-gray-700 mb-1.5">Description</label>
                        <textarea id="edit_item_description" 
                                  name="description" 
                                  rows="2"
                                  :value="selectedItem?.description"
                                  class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400 text-sm" 
                                  placeholder="Enter item description"></textarea>
                    </div>

                    <div>
                        <label for="edit_image" class="block text-xs font-semibold text-gray-700 mb-1.5">Item Image (Optional)</label>
                        <div x-show="selectedItem?.image" class="mb-2">
                            <img :src="'/storage/' + selectedItem?.image" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-400 mt-1">Current image — upload a new one to replace it.</p>
                        </div>
                        <input type="file"
                               id="edit_image"
                               name="image"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm text-gray-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all duration-200">
                        <p class="mt-1 text-xs text-gray-400">JPEG, PNG, GIF or WebP. Max 2MB.</p>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 mt-4 pt-3 border-t border-gray-200">
                    <button type="button"
                            @click="editModal = false"
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200 font-medium text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                            class="animated-button px-6 py-2 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 text-sm"
                            style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);">
                        <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dispose Modal -->
<div x-data="{ 
    open: false, 
    itemId: null, 
    itemName: '', 
    maxQty: 0,
    batches: [],
    selectedBatch: null,
    async loadBatches() {
        if (!this.itemId) return;
        const response = await fetch(`/items/${this.itemId}/batches`);
        this.batches = await response.json();
        this.selectedBatch = this.batches.length > 0 ? this.batches[0].id : null;
    }
}"
     @open-dispose.window="open = true; itemId = $event.detail.id; itemName = $event.detail.name; maxQty = $event.detail.qty; loadBatches()"
     x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[70] overflow-y-auto"
     @keydown.escape="open = false"
     style="display:none"
     x-init="$watch('open', v => document.body.classList.toggle('modal-open', v))">

    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="open = false"></div>

    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="modal-container bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-auto relative z-10 border border-red-200">

            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t-2xl" style="background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);">
                <div class="flex items-center">
                    <div class="w-9 h-9 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Dispose Item</h3>
                        <p class="text-red-100 text-xs" x-text="itemName"></p>
                    </div>
                </div>
                <button @click="open = false" class="text-white hover:text-red-200 p-1.5 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="'/items/' + itemId + '/disposal'" method="POST" class="p-4">
                @csrf
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Select Batch <span class="text-red-500">*</span></label>
                        <select name="batch_id" x-model="selectedBatch" required
                                class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-400 focus:border-red-400">
                            <option value="">Select a batch...</option>
                            <template x-for="batch in batches" :key="batch.id">
                                <option :value="batch.id" x-text="`${batch.batch_number} - ${batch.location || 'No location'} (${batch.quantity} available${batch.expiry_date ? ', expires ' + batch.expiry_date : ''})`"></option>
                            </template>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Select which batch to dispose from</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Quantity to Dispose <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" min="1" required
                               class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-400 focus:border-red-400"
                               placeholder="Enter quantity">
                        <p class="mt-1 text-xs text-gray-500">
                            <span x-show="selectedBatch">
                                Available in selected batch: <span class="font-semibold" x-text="batches.find(b => b.id == selectedBatch)?.quantity || 0"></span>
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Reason <span class="text-red-500">*</span></label>
                        <input type="text" name="notes" required
                               class="modern-input w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-red-400 focus:border-red-400"
                               placeholder="e.g. Water damage, defective, expired">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-4 pt-3 border-t border-gray-200">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium text-sm transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="animated-button px-5 py-2 text-white font-semibold rounded-xl shadow-lg text-sm transition-all"
                            style="background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);">
                        Confirm Dispose
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
