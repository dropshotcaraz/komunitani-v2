<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar</title>
    <link rel="icon" href="https://i.ibb.co.com/89qxHLW/logokomunitani-chara.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/additional-methods.min.js" integrity="sha512-owaCKNpctt4R4oShUTTraMPFKQWG9UdWTtG6GRzBjFV4VypcFi6+M3yc4Jk85s3ioQmkYWJbUl1b2b2r41RTjA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <style>
        .error {
            margin-top: 0.5rem;
            display: block;
            text-align: left;
        }
    </style>
</head>
<body class="bg-white-100 flex justify-center items-center min-h-screen">
    <section class="flex my-12 flex-col px-11 pt-8 pb-8 bg-[#F7F0CF] rounded-3xl shadow-lg max-md:px-5 max-md:pb-24">
        <!-- Header Section -->
        <div class="flex justify-center items-center mb-2">
            <img
                loading="lazy"
                src="https://i.ibb.co.com/Ns8HNB2/mix-logo.png" alt="logokomunitani-chara" 
                class="w-full max-w-[300px]"
            />
        </div>
        <!-- Main Section -->
        <main class="flex flex-col justify-center items-center px-8 py-10 text-center bg-white rounded-3xl shadow-lg w-[575px] max-md:px-5 max-md:mt-10">
            <div class="w-full max-w-[474px] flex flex-col">
                <!-- Welcome Text -->
                <h1 class="text-4xl font-bold text-stone-700 mb-4 self-start text-left max-md:text-4xl">Daftar</h1>

                <!-- Signup Form -->
                <form id="signup" action="{{ route('register') }}" method="POST" class="flex flex-col space-y-4">
                    @csrf <!-- Blade directive for CSRF token -->

                    <!-- Name Input -->
                    <div class="flex flex-col">
                        <label for="name" class="text-[#434028] text-base font-normal mb-2 text-left">Name</label>
                        <input type="text" id="name" name="name" placeholder="Name"
                               class="bg-white border border-gray-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#618805]" required autofocus autocomplete="name" value="{{ old('name') }}">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Input -->
                    <div class="flex flex-col">
                        <label for="email" class="text-[#434028] text-base font-normal mb-2 text-left">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email"
                               class="bg-white border border-gray-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#618805]" required autocomplete="username" value="{{ old('email') }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password Input -->
                    <div class=" flex flex-col">
                        <label for="password" class="text-[#434028] text-base font-normal mb-2 text-left">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password"
                               class="bg-white border border-gray-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#618805]" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="flex flex-col">
                        <label for="password_confirmation" class="text-[#434028] text-base font-normal mb-2 text-left">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"
                               class="bg-white border border-gray-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#618805]" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Signup Button -->
                    <button type="submit" class="bg-[#618805] text-white text-base font-bold py-3 rounded-lg shadow hover:bg-[#4c6a04] transition duration-300">
                        Daftar
                    </button>

                    <!-- Login Link -->
                    <div class="text-center text-sm mt-4">
                        <span class="text-[#434028]">Sudah punya akun?</span>
                        <a href="/login" class="text-[#618805] ml-1 underline">Masuk</a>
                    </div>
                </form>
            </div>
        </main>
    </section>

    <script>
        // Setup CSRF token untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false
        });

        $(document).ready(function() {
            // Validasi kustom untuk kata-kata terlarang
            $.validator.addMethod("forbiddenWords", function(value, element) {
                const forbidden = ['admin', 'administrator', 'superadmin', 'root'];
                return !forbidden.some(word => value.toLowerCase().includes(word));
            }, "Nama ini tidak diperbolehkan untuk digunakan");

            // Inisialisasi validasi form
            $("#signup").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        forbiddenWords: true
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    name: {
                        required: "Nama tidak boleh kosong",
                        minlength: "Nama minimal 2 karakter",
                        forbiddenWords: "Nama ini tidak diperbolehkan untuk digunakan"
                    },
                    email: {
                        required: "Email tidak boleh kosong",
                        email: "Format email tidak valid",
                        remote: "Email sudah terdaftar"
                    },
                    password: {
                        required: "Password tidak boleh kosong",
                        minlength: "Password minimal 8 karakter",
                    },
                    password_confirmation: {
                        required: "Konfirmasi password tidak boleh kosong",
                        equalTo: "Konfirmasi password harus sama dengan password"
                    }
                },
                errorClass: 'text-red-500 font-semibold text-left',
                errorElement: 'div',
                highlight: function(element) {
                    $(element).addClass('border-red-500').removeClass('border-gray-300');
                },
                unhighlight: function(element) {
                    $(element).removeClass('border-red-500').addClass('border-gray-300');
                },

                // Submit handler
                submitHandler: function(form) {
                    const submitBtn = $(form).find('button[type="submit"]');
                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

                    $.ajax({
                        url: "{{ route('register') }}",
                        method: 'POST',
                        data: $(form).serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pendaftaran Berhasil',
                                text: 'Akun anda telah berhasil dibuat!',
                                confirmButtonText: 'Lanjutkan'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/login';
                                }
                            });
                        },
                        error: function(xhr) {
                            submitBtn.prop('disabled', false).html('Daftar');
                            const errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan. Silahkan coba lagi.';
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Pendaftaran Gagal',
                                text: errorMessage,
                                confirmButtonText: 'Coba Lagi'
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>