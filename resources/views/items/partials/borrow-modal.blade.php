<!-- Borrow Item Modal -->
<div x-data="{
    borrowModal: false,
    selectedItem: null,
    borrowQuantity: 1,
    borrowBatches: [],
    
    openBorrowModal(item) {
        this.selectedItem = item;
        this.borrowQuantity = 1;
        this.borrowModal = true;
        this.loadBorrowBatches();
    },
    
    loadBorrowBatches() {
        if (!this.selectedItem || this.borrowQuantity < 1) return;
        
        fetch(`/items/${this.selectedItem.id}/borrow-batches?quantity=${this.borrowQuantity}`)
            .then(res => res.json())
            .then(data => {
                this.borrowBatches = data.batches || [];
            })
            .catch(err => console.error('Error loading batches:', err));
    }
}"
@open-borrow.window="openBorrowModal($event.detail)"
x-show="borrowModal"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-[60] overflow-y-auto"
@keydown.escape="borrowModal = false"
style="display: none;"
x-init="$watch('borrowModal', value => document.body.classList.toggle('modal-open', value))">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="borrowModal = false"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div x-show="borrowModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-auto relative z-10 border border-amber-200">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-2.5 border-b border-gray-200 rounded-t-lg" style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-white">Borrow Item</h3>
                </div>
                <button @click="borrowModal = false" class="text-white hover:text-amber-200 p-1 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Form -->
            <form :action="selectedItem ? '/items/' + selectedItem.id + '/borrow' : '#'" method="POST" class="p-2.5">
                @csrf
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                
                <div class="space-y-1.5">
                    <!-- Item Name -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Item</label>
                        <div class="w-full px-2 py-1 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-lg text-gray-900 font-medium text-xs" x-text="selectedItem?.name || 'Loading...'"></div>
                    </div>
                    
                    <!-- Available Quantity -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Available Quantity</label>
                        <div class="w-full px-2 py-1 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg text-gray-900 font-medium text-xs" x-text="selectedItem ? selectedItem.quantity + ' ' + selectedItem.unit : 'Loading...'"></div>
                    </div>
                    
                    <!-- Quantity Input -->
                    <div>
                        <label for="borrow_quantity" class="block text-xs font-semibold text-gray-700 mb-0.5">Quantity to Borrow</label>
                        <input type="number" 
                               id="borrow_quantity" 
                               name="quantity" 
                               min="1"
                               x-model="borrowQuantity"
                               @input.debounce.500ms="loadBorrowBatches()"
                               :max="selectedItem?.quantity"
                               required
                               class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-xs" 
                               placeholder="Enter quantity">
                    </div>

                    <!-- Batch Info -->
                    <div x-show="borrowBatches.length > 0" class="mt-1.5">
                        <label class="block text-xs font-semibold text-gray-700 mb-0.5">Will be borrowed from:</label>
                        <div class="space-y-1">
                            <template x-for="(batch, index) in borrowBatches" :key="index">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg px-2 py-1.5 text-xs">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-semibold text-blue-900" x-text="batch.batch_number"></div>
                                            <div class="text-blue-700 text-[10px]" x-text="batch.location"></div>
                                        </div>
                                        <div class="font-bold text-blue-900" x-text="batch.quantity + ' ' + selectedItem?.unit"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Borrower Name -->
                    <div>
                        <label for="borrower_name" class="block text-xs font-semibold text-gray-700 mb-0.5">Borrower Name</label>
                        <input type="text" 
                               id="borrower_name" 
                               name="borrower_name" 
                               value="{{ auth()->user()->name }}"
                               readonly
                               class="w-full px-2 py-1 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 text-xs">
                    </div>
                    
                    <!-- Department -->
                    <div>
                        <label for="borrower_department" class="block text-xs font-semibold text-gray-700 mb-0.5">Department</label>
                        <input type="text" 
                               id="borrower_department" 
                               name="borrower_department" 
                               value="{{ auth()->user()->department }}"
                               readonly
                               class="w-full px-2 py-1 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 text-xs">
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label for="borrow_notes" class="block text-xs font-semibold text-gray-700 mb-0.5">Notes (Optional)</label>
                        <textarea id="borrow_notes" 
                                  name="notes" 
                                  rows="2"
                                  class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-xs" 
                                  placeholder="Enter any additional notes"></textarea>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex justify-end space-x-2 mt-2 pt-2 border-t border-gray-200">
                    <button type="button" 
                            @click="borrowModal = false"
                            class="px-3 py-1 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-xs">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-1 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl text-xs" 
                            style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                        Borrow Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
