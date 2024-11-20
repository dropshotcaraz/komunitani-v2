<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and details.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="profile-update" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="4">{{ old('bio', $user->bio ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            <input type="file" id="profile_picture" name="profile_picture" class="mt-1 block w-full" accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
            @if (isset($user->profile_picture) && $user->profile_picture)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="max-w-xs h-48 object-cover rounded-md" />
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="cover_photo" :value="__('Cover Photo')" />
            <input type="file" id="cover_photo" name="cover_photo" class="mt-1 block w-full" accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('cover_photo')" />
            @if (isset($user->cover_photo) && $user->cover_photo)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $user->cover_photo) }}" alt="Cover Photo" class="w-full h-64 object-cover rounded-md" />
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <!-- Add this script section at the bottom of your form -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Add custom method for admin check
            $.validator.addMethod("notAdmin", function(value, element) {
                return !/^admin$/i.test(value.trim());
            }, "Name 'admin' is not allowed");

            // Form validation
            $("#profile-update").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        notAdmin: true
                    }
                },
                messages: {
                    name: {
                        required: "Name field is required",
                        minlength: "Name must be at least 2 characters long",
                        notAdmin: "The name 'admin' is not allowed"
                    }
                },
                errorElement: 'span',
                errorClass: 'text-red-500 text-sm mt-1',

                // Handle form submission
                submitHandler: function(form) {
                    // Check one last time if the name is 'admin'
                    const nameValue = $('#name').val().trim().toLowerCase();
                    if (nameValue === 'admin') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Name',
                            text: 'The name "admin" is not allowed',
                            confirmButtonText: 'Ok'
                        });
                        return false;
                    }
                    
                    // If all is well, submit the form
                    form.submit();
                }
            });

            // Additional real-time validation for the name field
            $('#name').on('input', function() {
                const nameValue = $(this).val().trim().toLowerCase();
                if (nameValue === 'admin') {
                    $(this).addClass('border-red-500');
                } else {
                    $(this).removeClass('border-red-500');
                }
            });
        });
    </script>
</section>