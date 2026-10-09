<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-3">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-3 pt-2">
            <x-primary-button id="password-save-btn" disabled>{{ __('Save') }}</x-primary-button>
            <button type="button" id="password-cancel-btn" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold text-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ __('Cancel') }}
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentPasswordInput = document.getElementById('update_password_current_password');
            const newPasswordInput = document.getElementById('update_password_password');
            const confirmPasswordInput = document.getElementById('update_password_password_confirmation');
            const saveButton = document.getElementById('password-save-btn');
            const cancelButton = document.getElementById('password-cancel-btn');
            
            function checkPasswordFormChanges() {
                const hasInput = 
                    currentPasswordInput.value.trim() !== '' || 
                    newPasswordInput.value.trim() !== '' || 
                    confirmPasswordInput.value.trim() !== '';
                
                saveButton.disabled = !hasInput;
                saveButton.classList.toggle('opacity-50', !hasInput);
                saveButton.classList.toggle('cursor-not-allowed', !hasInput);
                
                cancelButton.disabled = !hasInput;
                cancelButton.classList.toggle('opacity-50', !hasInput);
                cancelButton.classList.toggle('cursor-not-allowed', !hasInput);
            }
            
            function resetPasswordForm() {
                currentPasswordInput.value = '';
                newPasswordInput.value = '';
                confirmPasswordInput.value = '';
                checkPasswordFormChanges();
            }
            
            currentPasswordInput.addEventListener('input', checkPasswordFormChanges);
            newPasswordInput.addEventListener('input', checkPasswordFormChanges);
            confirmPasswordInput.addEventListener('input', checkPasswordFormChanges);
            cancelButton.addEventListener('click', resetPasswordForm);
            
            // Check on page load
            checkPasswordFormChanges();
        });
    </script>
</section>
