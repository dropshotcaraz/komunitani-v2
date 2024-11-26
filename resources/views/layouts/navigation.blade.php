<nav x-data="{ open: false }" class="bg-[#F6FEDB] sticky top-0 z-50 border-b border-[#E6D3A3] shadow-[4px_4px_10px_0px_rgba(0,0,0,0.1),-4px_-4px_10px_0px_rgba(255,255,255,0.5)]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto p-2 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 mr-6">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-[#91972A]" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" 
                           class="text-[#91972A] hover:text-[#B6C454] transition duration-200 
                                  px-3 py-2 rounded-lg 
                                  bg-[#F6FEDB] 
                                  shadow-[3px_3px_6px_0px_rgba(0,0,0,0.1),-3px_-3px_6px_0px_rgba(255,255,255,0.5)] 
                                  hover:shadow-[inset_3px_3px_6px_0px_rgba(0,0,0,0.1),inset_-3px_-3px_6px_0px_rgba(255,255,255,0.5)]">
                            {{ __('For You') }}
                        </a>
                        
                        <a href="{{ route('followingpage') }}" 
                           class="text-[#91972A] hover:text-[#B6C454] transition duration-200 
                                  px-3 py-2 rounded-lg 
                                  bg-[#F6FEDB] 
                                  shadow-[3px_3px_6px_0px_rgba(0,0,0,0.1),-3px_-3px_6px_0px_rgba(255,255,255,0.5)] 
                                  hover:shadow-[inset_3px_3px_6px_0px_rgba(0,0,0,0.1),inset_-3px_-3px_6px_0px_rgba(255,255,255,0.5)]">
                            {{ __('Following') }}
                        </a>

                        <!-- Add similar styled links for other navigation items -->
                        <a href="{{ route('messages') }}" 
                           class="text-[#91972A] hover:text-[#B6C454] transition duration-200 
                                  px-3 py-2 rounded-lg 
                                  bg-[#F6FEDB] 
                                  shadow-[3px_3px_6px_0px_rgba(0,0,0,0.1),-3px_-3px_6px_0px_rgba(255,255,255,0.5)] 
                                  hover:shadow-[inset_3px_3px_6px_0px_rgba(0,0,0,0.1),inset_-3px_-3px_6px_0px_rgba(255,255,255,0.5)]">
                            {{ __('Messages') }}
                        </a>

                        <!-- Profile and other links -->
                        <a href="{{ route('profile.show') }}" 
                           class="text-[#91972A] hover:text-[#B6C454] transition duration-200 
                                  px-3 py-2 rounded-lg 
                                  bg-[#F6FEDB] 
                                  shadow-[3px_3px_6px_0px_rgba(0,0,0,0.1),-3px_-3px_6px_0px_rgba(255,255,255,0.5)] 
                                  hover:shadow-[inset_3px_3px_6px_0px_rgba(0,0,0,0.1),inset_-3px_-3px_6px_0px_rgba(255,255,255,0.5)]">
                            {{ __('Profile') }}
                        </a>

                        <!-- Logout Button with Neomorphic Style -->
                        <form method="POST" action="{{ route('logout') }}" class="ml-4">
                            @csrf
                            <button onclick="confirmLogout(event)"
                                    class="group relative inline-flex items-center 
                                           px-4 py-2 rounded-lg 
                                           text-red-600 
                                           bg-[#F6FEDB] 
                                           shadow-[3px_3px_6px_0px_rgba(0,0,0,0.1),-3px_-3px_6px_0px_rgba(255,255,255,0.5)] 
                                           hover:shadow-[inset_3px_3px_6px_0px_rgba(0,0,0,0.1),inset_-3px_-3px_6px_0px_rgba(255,255,255,0.5)] 
                                           transition-all duration-200 hover:text-red-700 active:scale-95">
                                {{ __('Log Out') }}
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="ml-2 h-4 w-4 transition-transform duration-200 group-hover:translate-x-1"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger for Mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md 
                               text-[#91972A] hover:bg-[#E6D3A3] 
                               focus:outline-none transition duration-150 ease-in-out 
                               shadow-[3px_3px_6px_0px_rgba(0,0,0,0.1),-3px_-3px_6px_0px_rgba(255,255,255,0.5)]">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12  12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="block text-[#91972A] hover:bg-[#D8D174] transition duration-200 
                      px-4 py-2 rounded-lg">
                {{ __('For You') }}
            </a>
            <a href="{{ route('followingpage') }}" 
               class="block text-[#91972A] hover:bg-[#D8D174] transition duration-200 
                      px-4 py-2 rounded-lg">
                {{ __('Following') }}
            </a>
            <a href="{{ route('messages') }}" 
               class="block text-[#91972A] hover:bg-[#D8D174] transition duration-200 
                      px-4 py-2 rounded-lg">
                {{ __('Messages') }}
            </a>
            <a href="{{ route('profile.show') }}" 
               class="block text-[#91972A] hover:bg-[#D8D174] transition duration-200 
                      px-4 py-2 rounded-lg">
                {{ __('Profile') }}
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button onclick="confirmLogout(event)"
                        class="block w-full text-left text-red-600 hover:bg-red-500 hover:text-white 
                               transition duration-200 px-4 py-2 rounded-lg">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownTrigger = document.querySelector('[x-data] [x-slot="trigger"] button');
            const dropdownContent = document.querySelector('[x-data] [x-slot="content"]');

            dropdownTrigger.addEventListener('click', function() {
                dropdownContent.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!dropdownTrigger.contains(event.target) && !dropdownContent.contains(event.target)) {
                    dropdownContent.classList.add('hidden');
                }
            });
        });
        function confirmLogout(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan logout dari website ini.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#91972A',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Logout',
                cancelButtonText: 'Batal',
                background: '#F6FEDB',
                borderRadius: '1rem',
                customClass: {
                    popup: 'rounded-xl shadow-xl border border-[#E6D3A3]'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (form) {
                        form.submit();
                    } else {
                        // For dropdown and responsive menu items that might not be in a form
                        const logoutForm = document.createElement('form');
                        logoutForm.method = 'POST';
                        logoutForm.action = '{{ route('logout') }}';

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';

                        logoutForm.appendChild(csrfToken);
                        document.body.appendChild(logoutForm);
                        logoutForm.submit();
                    }
                }
            });
        }
    </script>
</nav>
