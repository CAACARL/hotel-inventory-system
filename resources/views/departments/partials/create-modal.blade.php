<!-- Create Department Modal -->
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
             class="modal-container bg-white rounded-lg shadow-2xl max-w-sm w-full mx-auto relative z-10 border border-amber-200">
            
            <!-- Modern Modal Header with Gradient -->
            <div class="modal-header-gradient flex items-center justify-between p-2.5 border-b border-gray-200 rounded-t-lg" style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-white">Create New Department</h3>
                </div>
                <button @click="createModal = false" class="text-white hover:text-amber-200 transition-colors duration-200 p-1 hover:bg-white hover:bg-opacity-10 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form action="{{ route('departments.store') }}" method="POST" class="p-2.5">
                @csrf
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                <div class="space-y-1.5">
                    <div>
                        <label for="modal_name" class="block text-xs font-semibold text-gray-700 mb-0.5">Department Name</label>
                        <input type="text" 
                               id="modal_name" 
                               name="name" 
                               required
                               class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs" 
                               placeholder="e.g., Housekeeping, Front Desk">
                    </div>
                    
                    <div>
                        <label for="modal_description" class="block text-xs font-semibold text-gray-700 mb-0.5">Description</label>
                        <textarea id="modal_description" 
                                  name="description" 
                                  rows="2"
                                  class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs" 
                                  placeholder="Brief description"></textarea>
                    </div>
                    
                    <div>
                        <label for="modal_location" class="block text-xs font-semibold text-gray-700 mb-0.5">Location</label>
                        <input type="text" 
                               id="modal_location" 
                               name="location" 
                               class="modern-input w-full px-2 py-1 border border-gray-300 rounded-lg transition-all duration-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400 text-xs" 
                               placeholder="e.g., 2nd Floor, Ground Floor">
                    </div>
                </div>
                
                <!-- Modern Modal Footer -->
                <div class="flex justify-end space-x-2 mt-2 pt-2 border-t border-gray-200">
                    <button type="button" 
                            @click="createModal = false"
                            class="px-3 py-1 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 font-medium text-xs">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="animated-button px-4 py-1 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg text-xs" 
                            style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
