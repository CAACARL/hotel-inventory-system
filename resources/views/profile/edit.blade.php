<x-app-layout>
    <!-- Profile Hero Section -->
    <div class="mb-6 bg-gradient-to-r from-amber-50 via-yellow-50 to-amber-50 rounded-xl p-4 sm:p-6 border border-amber-200">
        <div class="flex items-center gap-4">
            <!-- Profile Picture -->
            <div class="relative flex-shrink-0">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                         alt="Profile" 
                         class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover border-4 border-white shadow-lg">
                @else
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full flex items-center justify-center border-4 border-white shadow-lg"
                         style="background: linear-gradient(135deg, #3D2914 0%, #D4AF37 100%);">
                        <span class="text-white font-bold text-2xl sm:text-3xl">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                @endif
                <div class="absolute bottom-0 right-0 w-6 h-6 sm:w-7 sm:h-7 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            
            <!-- User Info -->
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ auth()->user()->name }}</h1>
                <p class="text-sm text-gray-600 truncate">{{ auth()->user()->email }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                    @if(auth()->user()->two_factor_enabled)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            2FA Enabled
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Left Column - Main Settings -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Profile Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900">Profile Information</h2>
                            <p class="text-xs text-gray-500">Update your account details and email</p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900">Password Security</h2>
                            <p class="text-xs text-gray-500">Keep your account secure with a strong password</p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Right Column - Security & Danger Zone -->
        <div class="space-y-4">
            <!-- Two-Factor Authentication Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900">Two-Factor Auth</h2>
                            <p class="text-xs text-gray-500">Extra security layer</p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    @include('profile.partials.two-factor-authentication-form')
                </div>
            </div>

            <!-- Account Stats Card -->
            <div class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-xl shadow-sm border border-amber-200 overflow-hidden">
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Account Stats
                    </h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-2 bg-white rounded-lg">
                            <span class="text-xs text-gray-600">Member Since</span>
                            <span class="text-xs font-semibold text-gray-900">{{ auth()->user()->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 bg-white rounded-lg">
                            <span class="text-xs text-gray-600">Last Login</span>
                            <span class="text-xs font-semibold text-gray-900">{{ auth()->user()->updated_at->diffForHumans() }}</span>
                        </div>
                        @if(auth()->user()->department)
                            @php
                                $department = \App\Models\Department::find(auth()->user()->department);
                            @endphp
                            @if($department)
                                <div class="flex items-center justify-between p-2 bg-white rounded-lg">
                                    <span class="text-xs text-gray-600">Department</span>
                                    <span class="text-xs font-semibold text-gray-900">{{ $department->name }}</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
