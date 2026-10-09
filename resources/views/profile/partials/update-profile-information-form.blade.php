<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-3" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Picture Section -->
        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            <div class="mt-1.5 flex items-center space-x-3">
                <!-- Current Profile Picture or Default Avatar -->
                <div id="profile-picture-preview" class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center flex-shrink-0">
                    @if($user->getProfilePictureUrl())
                        <img id="profile-picture-img" src="{{ $user->getProfilePictureUrl() }}" alt="Profile Picture" class="w-full h-full object-cover">
                    @else
                        @php $avatar = $user->getDefaultAvatar(); @endphp
                        <div id="profile-picture-initials" class="w-full h-full {{ $avatar['color'] }} flex items-center justify-center text-white font-semibold text-sm">
                            {{ $avatar['initials'] }}
                        </div>
                    @endif
                </div>
                
                <!-- File Input -->
                <div class="flex-1">
                    <input type="file" 
                           id="profile_picture" 
                           name="profile_picture" 
                           accept="image/*"
                           class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    <p class="mt-0.5 text-[10px] text-gray-600">JPG, PNG, GIF up to 2MB</p>
                </div>
            </div>
            <x-input-error class="mt-1" :messages="$errors->get('profile_picture')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs mt-1.5 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-xs text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 font-medium text-xs text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="department_display" :value="__('Department')" />
            <div class="mt-1 text-sm text-gray-700">
                @if($user->department)
                    {{ $user->department }}
                @else
                    <span class="text-gray-400">Not assigned</span>
                @endif
            </div>
            <p class="mt-0.5 text-[10px] text-gray-600">Contact your administrator to change departments</p>
        </div>

        <!-- Password Confirmation (shown when email is changed) -->
        <div id="password-confirmation" style="display: none;">
            <x-input-label for="current_password" :value="__('Current Password')" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error class="mt-1" :messages="$errors->get('current_password')" />
            <p class="mt-0.5 text-xs text-gray-600">{{ __('Please confirm your password to change your email address.') }}</p>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <x-primary-button id="profile-save-btn" disabled>{{ __('Save') }}</x-primary-button>
            <button type="button" id="profile-cancel-btn" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold text-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ __('Cancel') }}
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('profile.update') }}"]');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const profilePictureInput = document.getElementById('profile_picture');
            const passwordConfirmation = document.getElementById('password-confirmation');
            const saveButton = document.getElementById('profile-save-btn');
            const cancelButton = document.getElementById('profile-cancel-btn');
            
            const originalName = '{{ $user->name }}';
            const originalEmail = '{{ $user->email }}';
            const originalProfilePictureSrc = document.getElementById('profile-picture-img')?.src || '';
            
            let hasProfilePictureChanged = false;
            
            function checkFormChanges() {
                const hasChanges = 
                    nameInput.value !== originalName || 
                    emailInput.value !== originalEmail ||
                    hasProfilePictureChanged;
                
                saveButton.disabled = !hasChanges;
                saveButton.classList.toggle('opacity-50', !hasChanges);
                saveButton.classList.toggle('cursor-not-allowed', !hasChanges);
                
                cancelButton.disabled = !hasChanges;
                cancelButton.classList.toggle('opacity-50', !hasChanges);
                cancelButton.classList.toggle('cursor-not-allowed', !hasChanges);
            }
            
            function resetForm() {
                nameInput.value = originalName;
                emailInput.value = originalEmail;
                hasProfilePictureChanged = false;
                
                // Reset profile picture preview
                const previewContainer = document.getElementById('profile-picture-preview');
                const existingImg = document.getElementById('profile-picture-img');
                const existingInitials = document.getElementById('profile-picture-initials');
                
                if (originalProfilePictureSrc) {
                    if (!existingImg) {
                        if (existingInitials) existingInitials.remove();
                        const img = document.createElement('img');
                        img.id = 'profile-picture-img';
                        img.src = originalProfilePictureSrc;
                        img.alt = 'Profile Picture';
                        img.className = 'w-full h-full object-cover';
                        previewContainer.appendChild(img);
                    } else {
                        existingImg.src = originalProfilePictureSrc;
                    }
                } else {
                    // Restore initials if no picture
                    if (existingImg) existingImg.remove();
                    if (!existingInitials) {
                        @php $avatar = $user->getDefaultAvatar(); @endphp
                        const initialsDiv = document.createElement('div');
                        initialsDiv.id = 'profile-picture-initials';
                        initialsDiv.className = 'w-full h-full {{ $avatar['color'] }} flex items-center justify-center text-white font-semibold text-sm';
                        initialsDiv.textContent = '{{ $avatar['initials'] }}';
                        previewContainer.appendChild(initialsDiv);
                    }
                }
                
                // Reset file input
                profilePictureInput.value = '';
                
                checkFormChanges();
            }
            
            cancelButton.addEventListener('click', resetForm);
            
            function togglePasswordField() {
                if (emailInput.value !== originalEmail) {
                    passwordConfirmation.style.display = 'block';
                    document.getElementById('current_password').required = true;
                } else {
                    passwordConfirmation.style.display = 'none';
                    document.getElementById('current_password').required = false;
                    document.getElementById('current_password').value = '';
                }
            }
            
            // Profile picture preview
            profilePictureInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    hasProfilePictureChanged = true;
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        const previewContainer = document.getElementById('profile-picture-preview');
                        const existingImg = document.getElementById('profile-picture-img');
                        const existingInitials = document.getElementById('profile-picture-initials');
                        
                        // Remove existing content
                        if (existingImg) existingImg.remove();
                        if (existingInitials) existingInitials.remove();
                        
                        // Create new image element
                        const img = document.createElement('img');
                        img.id = 'profile-picture-img';
                        img.src = event.target.result;
                        img.alt = 'Profile Picture Preview';
                        img.className = 'w-full h-full object-cover';
                        previewContainer.appendChild(img);
                    };
                    
                    reader.readAsDataURL(file);
                    checkFormChanges();
                }
            });
            
            nameInput.addEventListener('input', checkFormChanges);
            emailInput.addEventListener('input', function() {
                togglePasswordField();
                checkFormChanges();
            });
            emailInput.addEventListener('change', togglePasswordField);
            
            // Check on page load
            togglePasswordField();
            checkFormChanges();
        });
    </script>
</section>
