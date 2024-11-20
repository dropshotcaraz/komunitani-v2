<nav x-data="{ open: false, dropdownOpen: false }" 
     class="bg-gradient-to-br from-[#F7F0CF] to-[#FFFFFF] sticky top-0 z-50 shadow-lg border-b border-[#91972A]/20 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo and Brand -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="h-10 w-auto fill-[#91972A] transform transition hover:scale-110" />

                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex items-center space-x-4">
                <!-- Main Navigation Links -->
                <div class="flex space-x-4">
                    @php
                        $navLinks = [
                            ['route' => 'dashboard', 'label' => 'For You'],
                            ['route' => 'followingpage', 'label' => 'Following'],
                            ['route' => 'messages', 'label' => 'Messages'],
                            ['route' => 'profile.show', 'label' => 'Profile'],
                            ['route' => 'chatbot.index', 'label' => 'Chatbot'],
                            ['route' => 'search', 'label' => 'Search']
                        ];
                    @endphp

                    @foreach($navLinks as $link)
                        <x-nav-link :href="route($link['route'])" :active="request()->routeIs($link['route'])"
                            class="text-[#91972A] hover:text-[#B6C454] transition-all duration-300 
                            hover:bg-[#E6D3A3] px-3 py-2 rounded-lg group relative overflow-hidden">
                            <span class="relative z-10">{{ $link['label'] }}</span>
                            <span class="absolute inset-0 bg-[#B6C454] opacity-0 group-hover:opacity-20 transition-opacity"></span>
                        </x-nav-link>
                    @endforeach

                    @auth
                        @if (auth()->user()->name === 'Admin')
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')"
                                class="text-[#91972A] hover:text-[#B6C454] transition-all duration-300 
                                hover:bg-[#E6D3A3] px-3 py-2 rounded-lg group relative overflow-hidden">
                                <span class="relative z-10">Users</span>
                                <span class="absolute inset-0 bg-[#B6C454] opacity-0 group-hover:opacity-20 transition-opacity"></span>
                            </x-nav-link>
                        @endif
                    @endauth
                </div>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center space-x-2 bg-[#6FA843] border-black text-white px-4 py-2 
                        rounded-full hover:bg-[#D8D174] hover:text-black transition-all duration-300 
                        focus:outline-none focus:ring-2 focus:ring-[#91972A]/50">
                        @if (isset(Auth::user()->profile_picture) && Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                alt="Profile" class="h-8 w-8 rounded-full object-cover" />
                        @endif
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" 
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="dropdownOpen" 
                         @click.away="dropdownOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg 
                         border border-black/10 z-50 overflow-hidden">
                        <a href="{{ route('profile.edit') }}" 
                           class="block px-4 py-2 text-[#91972A] hover:bg-[#F6FEDB] transition-colors">
                            Profile Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="button" 
                                    onclick="confirmLogout(event)"
                                    class="w-full text-left px-4 py-2 text-red-600 
                                    hover:bg-red-50 transition-colors">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open"
                        class="text-[#91972A] hover:bg-[#E6D3A3] p-2 rounded-md 
                        transition-all duration-300 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" 
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" 
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" 
         class="sm:hidden bg-[#F6FEDB] shadow-lg">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <!-- Duplicate mobile navigation links here -->
            @foreach($navLinks as $link)
                <x-responsive-nav-link :href="route($link['route'])" :active="request()->routeIs($link['route'])"
                    class="block px -4 py-2 text-[#91972A] hover:bg-[#D8D174] transition duration-200 rounded-lg">
                    {{ $link['label'] }}
                </x-responsive-nav-link>
            @endforeach

            @auth
                @if (auth()->user()->name === 'Admin')
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')"
                        class="block px-4 py-2 text-[#91972A] hover:bg-[#D8D174] transition duration-200 rounded-lg">
                        Users
                    </x-responsive-nav-link>
                @endif
            @endauth

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="confirmLogout(event)"
                    class="block px-4 py-2 text-red-600 hover:bg-red-500 hover:text-white transition duration-200 rounded-lg">
                    Log Out
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>

<script>
    function confirmLogout(event) {
        event.preventDefault();
        const form = event.target.closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of this website.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#91972A',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Logout',
            cancelButtonText: 'Cancel',
            background: '#F6FEDB',
            borderRadius: '1rem',
            customClass: {
                popup: 'rounded-xl shadow-xl border border-[#E6D3A3]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>