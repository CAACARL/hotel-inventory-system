<x-app-layout>
    <div class="py-3 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">

            <!-- Modern Page Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 sm:mb-6">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center shadow-md flex-shrink-0"
                         style="background: linear-gradient(135deg, #374151 0%, #6B7280 100%);">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M10 12v4m4-4v4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold mb-0.5 text-gray-700">Archived Categories</h1>
                        <p class="text-gray-600 text-xs sm:text-sm font-medium hidden sm:block">Categories removed from active use</p>
                        <div class="flex items-center mt-0.5 sm:mt-1 text-xs text-gray-500">
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></div>
                            <span class="font-medium">{{ $categories->total() }} Archived</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-xs font-semibold" style="border: 1px solid #D4AF37; color: #3D2914;">
                    <svg class="w-3.5 h-3.5 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="hidden sm:inline">Back to Categories</span>
                </a>
            </div>

            @if($categories->count() > 0)

                <!-- Mobile Card View -->
                <div class="sm:hidden space-y-3 mb-4">
                    @foreach($categories as $category)
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 opacity-75 hover:opacity-90 transition-opacity">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-start gap-2 min-w-0 flex-1">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 bg-gray-300">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-gray-700 text-sm">{{ $category->name }}</div>
                                    @if($category->path)
                                        <div class="text-xs text-gray-400 truncate">{{ $category->path }}</div>
                                    @endif
                                    @if($category->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($category->description, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-600 flex-shrink-0">Archived</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Archived {{ $category->deleted_at->diffForHumans() }}</div>
                        <form method="POST" action="{{ route('categories.unarchive', $category->id) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-amber-300 text-amber-700 hover:bg-amber-50 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Unarchive
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden sm:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead style="background: linear-gradient(135deg, #374151 0%, #6B7280 100%);">
                                <tr>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Category</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Description</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">Archived</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-white uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($categories as $category)
                                <tr class="hover:bg-gray-50 opacity-75 hover:opacity-90 transition-all">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-gray-200">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold text-gray-700 truncate">{{ $category->name }}</div>
                                                @if($category->path)
                                                    <div class="text-[10px] text-gray-400 truncate">{{ $category->path }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-gray-500">{{ $category->description ?: '—' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-xs text-gray-600">{{ $category->deleted_at->format('M d, Y') }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $category->deleted_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <form method="POST" action="{{ route('categories.unarchive', $category->id) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-amber-300 text-amber-700 hover:bg-amber-50 transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                </svg>
                                                Unarchive
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 sm:mt-6">
                    {{ $categories->links('vendor.pagination.custom') }}
                </div>

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="text-center py-12 px-4">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-gray-100">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M10 12v4m4-4v4"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-2">No Archived Categories</h3>
                        <p class="text-sm text-gray-500">Categories you archive will appear here.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
