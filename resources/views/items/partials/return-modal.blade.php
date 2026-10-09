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
             class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-auto relative z-10 border border-green-200">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-2.5 border-b border-gray-200 rounded-t-lg" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-white">Return Item</h3>
                </div>
                <button @click="returnModal = false" class="text-white hover:text-green-200 p-1 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Form -->
            <form :action="selectedItem ? '/items/' + selectedItem.id + '/return' : '#'" method="POST" class="p-2.5">
                @csrf
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                
                <div class="space-y-1.5">
                    <!-- Item Info -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-2 border border-green-200">
                        <h4 class="font-bold text-gray-900 text-xs mb-1" x-text="selectedItem?.name || 'Loading...'"></h4>
                        <div class="grid grid-cols-2 gap-1.5 text-[10px]">
                            <div class="bg-white bg-opacity-50 rounded px-1.5 py-1">
                                <span class="font-semibold text-gray-700">Available to Return:</span>
                                <div class="text-green-700 font-bold" x-text="selectedItem ? selectedItem.borrowed_quantity + ' ' + selectedItem.unit : 'Loading...'"></div>
                            </div>
                            <div class="bg-white bg-opacity-50 rounded px-1.5 py-1">
                                <span class="font-semibold text-gray-700">Current Stock:</span>
                                <div class="text-gray-900 font-bold" x-text="selectedItem ? selectedItem.quantity + ' ' + selectedItem.unit : 'Loading...'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Input -->
                    <div>
                        <label for="return_quantity" class="block text-xs font-semibold text-gray-700 mb-0.5">Return Quantity</label>
                        <input type="number" 
                               id="return_quantity" 
                               name="quantity" 
                               min="1" 
                               x-model="returnQuantity"
                               @input.debounce.500ms="loadReturnBatches()"
                               :max="selectedItem?.borrowed_quantity"
                               required
                               class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-xs"
                               placeholder="Enter quantity to return">
                        <p class="mt-0.5 text-[10px] text-gray-500">Maximum: <span class="font-semibold" x-text="selectedItem ? selectedItem.borrowed_quantity + ' ' + selectedItem.unit : ''"></span></p>
                    </div>

                    <!-- Batch Info -->
                    <div x-show="returnBatches.length > 0">
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Will be returned to:</label>
                        <div class="space-y-1">
                            <template x-for="(batch, index) in returnBatches" :key="index">
                                <div class="bg-green-50 border border-green-200 rounded px-2 py-1.5 text-[10px]">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-semibold text-green-900" x-text="batch.batch_number"></div>
                                            <div class="text-green-700" x-text="batch.location"></div>
                                            <div class="text-green-600">Borrowed: <span x-text="batch.borrowed_at"></span></div>
                                        </div>
                                        <div class="font-bold text-green-900" x-text="batch.quantity + ' ' + selectedItem?.unit"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label for="return_notes" class="block text-xs font-semibold text-gray-700 mb-0.5">Return Notes (Optional)</label>
                        <textarea id="return_notes" 
                                  name="notes" 
                                  rows="2"
                                  class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-xs"
                                  placeholder="Optional notes about the return"></textarea>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex justify-end space-x-2 mt-2 pt-2 border-t border-gray-200">
                    <button type="button" 
                            @click="returnModal = false"
                            class="px-3 py-1 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-xs">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-1 text-white font-semibold rounded-lg shadow-lg text-xs" 
                            style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                        Return Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
