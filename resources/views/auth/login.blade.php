<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
    <link rel="icon" href="https://i.ibb.co.com/89qxHLW/logokomunitani-chara.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/additional-methods.min.js" integrity="sha512-owaCKNpctt4R4oShUTTraMPFKQWG9UdWTtG6GRzBjFV4VypcFi6+M3yc4Jk85s3ioQmkYWJbUl1b2b2r41RTjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Vite and Tailwind CSS Integration -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css') <!-- Adjust if using Blade directive for Vite -->
</head>
<body class="bg-white-100 flex justify-center items-center min-h-screen">
    <section class="flex flex-col px-11 pt-8 pb-8 bg-[#F7F0CF] rounded-3xl shadow-lg max-md:px-5 max-md:pb-24"> 
        <!-- Header Section -->
        <div class="flex justify-center items-center mb-2">
            <a href="/ "><img
                loading="lazy"
                src="https://i.ibb.co.com/Ns8HNB2/mix-logo.png" alt="logokomunitani-chara" 
                class="w-full max-w-[300px]"
            /></a>
        </div>

        <!-- Main Section -->
        <main class="flex flex-col justify-center items-center px-8 py-10 text-center bg-white rounded-3xl shadow-lg w-[575px] max-md:px-5 max-md:mt-10">
            <div class="w-full max-w-[474px] flex flex-col">
                <!-- Welcome Text -->
                <h1 class="text-4xl font-bold text-stone-700 mb-4 self-start text-left max-md:text-4xl">Masuk</h1>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="flex flex-col mb-4">
                            <x-input-label for="email" :value="__('Email')" class="text-[#434028]  text-left text-base font-normal mb-2" />
                            <x-text-input id="email" class="block w-full px-4 py-3 bg-white border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#618805]" type="email" name="email" :value="old('email')" required autofocus placeholder="Email" />
                            <x-input-error :messages="$errors->get('email')" class="text-red-500 font-semibold underline mt-2" />
                        </div>

                        <!-- Password Input -->
                        <div class="flex flex-col mb-4">
                            <x-input-label for="password" :value="__('Password')" class="text-[#434028] text-left text-base font-normal mb-2" />
                            <x-text-input id="password" class="block w-full px-4 py-3 bg-white border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#618805]" type="password" name="password" required placeholder="Password" />
                            <x-input-error :messages="$errors->get('password')" class="text-red-500 font-semibold underline mt-2" />
                        </div>

                        <!-- Forgot Password Link -->
                        <!-- <div class="text-right mb-4">
                            <a href="{{ route('password.request') }}" class="text-[#618805] text-xs underline">Lupa password?</a>
                        </div> -->

                        <!-- Login Button -->
                        <x-primary-button class="w-full py-3 bg-[#618805] justify-center text-white font-bold rounded-lg shadow hover:bg-[#4c6a04] transition duration-300">
                            {{ __('Masuk') }}
                        </x-primary-button>

                        <!-- Register Link -->
                        <div class="text-center text-sm mt-4">
                            <span class="text-[#434028]">Belum punya akun?</span>
                            <a href="{{ route('register') }}" class="text-[#618805] ml-1 underline">Daftar</a>
                        </div>
                    </form>
        </main>
    </section>
    <script>
            $(document).ready(function() {
                $("#login-form").validate({
                    rules: {
                        email: { required: true, email: true },
                        password: { required: true, minlength: 8 }
                    },
                    messages: {
                        email: { required: "Email tidak boleh kosong", email: "Email tidak valid" },
                        password: { required: "Password tidak boleh kosong", minlength: "Password minimal 8 karakter" }
                    },
                    errorClass: 'text-red-500 font-semibold underline text-left',
                    submitHandler: function(form) {
                        $.ajax({
                            url: '{{ route("login") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                email: $('#email').val(),
                                password: $('#password').val()
                            },
                            success: function(response) {
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 1500, showConfirmButton: false });
                                setTimeout(() => { window.location.href = '/dashboard'; }, 1500);
                            },
                            error: function() {
                                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Invalid email or password', timer: 1500, showConfirmButton: false });
                            }
                        });
                    }
                });
            });
        </script>
</body>
</html>

