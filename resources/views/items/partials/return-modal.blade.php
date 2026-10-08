<!-- Return Item Modal -->
<div x-data="{
    returnModal: false,
    selectedItem: null,
    returnQuantity: 1,
    returnBatches: [],
    
    openReturnModal(item) {
        this.selectedItem = item;
        this.returnQuantity = item.borrowed_quantity || 1;
        this.returnModal = true;
        this.loadReturnBatches();
    },
    
    loadReturnBatches() {
        if (!this.selectedItem || this.returnQuantity < 1) return;
        
        fetch(`/items/${this.selectedItem.id}/return-batches?quantity=${this.returnQuantity}`)
            .then(res => res.json())
            .then(data => {
                this.returnBatches = data.batches || [];
            })
            .catch(err => console.error('Error loading batches:', err));
    }
}"
@open-return.window="openReturnModal($event.detail)"
x-show="returnModal"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-[60] overflow-y-auto"
@keydown.escape="returnModal = false"
style="display: none;"
x-init="$watch('returnModal', value => document.body.classList.toggle('modal-open', value))">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="returnModal = false"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="returnModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto relative z-10 border border-green-200">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t-2xl" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                <div class="flex items-center">
                    <div class="w-9 h-9 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Return Item</h3>
                        <p class="text-green-100 text-xs">Return borrowed inventory item</p>
                    </div>
                </div>
                <button @click="returnModal = false" class="text-white hover:text-green-200 p-1.5 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Form -->
            <form :action="selectedItem ? '/items/' + selectedItem.id + '/return' : '#'" method="POST" class="p-4">
                @csrf
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                
                <div class="space-y-3">
                    <!-- Item Info -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-3 border border-green-200">
                        <h4 class="font-bold text-gray-900 text-sm mb-2" x-text="selectedItem?.name || 'Loading...'"></h4>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-white bg-opacity-50 rounded-lg p-2">
                                <span class="font-semibold text-gray-700">Available to Return:</span>
                                <div class="text-green-700 font-bold" x-text="selectedItem ? selectedItem.borrowed_quantity + ' ' + selectedItem.unit : 'Loading...'"></div>
                            </div>
                            <div class="bg-white bg-opacity-50 rounded-lg p-2">
                                <span class="font-semibold text-gray-700">Current Stock:</span>
                                <div class="text-gray-900 font-bold" x-text="selectedItem ? selectedItem.quantity + ' ' + selectedItem.unit : 'Loading...'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Input -->
                    <div>
                        <label for="return_quantity" class="block text-xs font-semibold text-gray-700 mb-1.5">Return Quantity</label>
                        <input type="number" 
                               id="return_quantity" 
                               name="quantity" 
                               min="1" 
                               x-model="returnQuantity"
                               @input.debounce.500ms="loadReturnBatches()"
                               :max="selectedItem?.borrowed_quantity"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                               placeholder="Enter quantity to return">
                        <p class="mt-1 text-xs text-gray-500">Maximum: <span class="font-semibold" x-text="selectedItem ? selectedItem.borrowed_quantity + ' ' + selectedItem.unit : ''"></span></p>
                    </div>

                    <!-- Batch Info -->
                    <div x-show="returnBatches.length > 0" class="mt-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Will be returned to:</label>
                        <div class="space-y-1.5">
                            <template x-for="(batch, index) in returnBatches" :key="index">
                                <div class="bg-green-50 border border-green-200 rounded-lg px-3 py-2 text-xs">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-semibold text-green-900" x-text="batch.batch_number"></div>
                                            <div class="text-green-700 text-xs" x-text="batch.location"></div>
                                            <div class="text-green-600 text-xs">Borrowed: <span x-text="batch.borrowed_at"></span></div>
                                        </div>
                                        <div class="font-bold text-green-900" x-text="batch.quantity + ' ' + selectedItem?.unit"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label for="return_notes" class="block text-xs font-semibold text-gray-700 mb-1.5">Return Notes (Optional)</label>
                        <textarea id="return_notes" 
                                  name="notes" 
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm"
                                  placeholder="Optional notes about the return"></textarea>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex justify-end space-x-3 mt-4 pt-3 border-t border-gray-200">
                    <button type="button" 
                            @click="returnModal = false"
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium text-sm">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 text-sm" 
                            style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                        Return Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
