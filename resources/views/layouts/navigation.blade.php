<nav x-data="{ open: false }" class="bg-[#F6FEDB] border-b border-[#E6D3A3] shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto p-2 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-[#91972A]" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-[#91972A] hover:text-[#B6C454] transition duration-200">
                        {{ __('Homepage') }}
                    </x-nav-link>
                    <x-nav-link :href="route('messages')" :active="request()->routeIs('messages')" class="text-[#91972A] hover:text-[#B6C454] transition duration-200">
                        {{ __('Messages') }}
                    </x-nav-link>
                    <x-nav-link :href="route('profile.show')" :active="request()->routeIs('profile.show')" class="text-[#91972A] hover:text-[#B6C454] transition duration-200">
                        {{ __('Profile') }}
                    </x-nav-link>
                    <x-nav-link :href="route('chatbot.index')" :active="request()->routeIs('chatbot.index')" class="text-[#91972A] hover:text-[#B6C454] transition duration-200">
                        {{ __('Chatbot') }}
                    </x-nav-link>
                    <x-nav-link :href="route('search')" :active="request()->routeIs('search')" class="text-[#91972A] hover:text-[#B6C454] transition duration-200">
                        {{ __('Search') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-black text-sm leading-4 font-medium rounded-2xl text-[#91972A] bg-[#E6D3A3] hover:bg-[#D8D174] transition ease-in-out duration-150">
                            @if (isset(Auth::user()->profile_picture) && Auth::user()->profile_picture)
                                <div class="p-1">
                                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="h-6 w-6 rounded-full object-cover" />
                                </div>
                            @endif
                            <div class="mx-1">{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-[#F6FEDB]">
                            {{ __('Profile Setting') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="hover:bg-[#F6FEDB]">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#91972A] hover:bg-[#E6D3A3] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hover:bg-[#D8D174]">
                {{ __('Homepage') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('messages')" :active="request()->routeIs('messages')" class="hover:bg-[#D8D174]">
                {{ __('Messages') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.show')" :active="request()->routeIs('profile.show')" class="hover:bg-[#D8D174]">
                {{ __('Profile') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('chatbot.index')" :active="request()->routeIs('chatbot.index')" class="hover:bg-[#D8D174]">
            {{ __('Chatbot') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('search')" :active="request()->routeIs('search')" class="hover:bg-[#D8D174]">
            {{ __('Search') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-[#E6D3A3]">
            <div class="px-4">
                <div class="font-medium text-base text-[#91972A]">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-[#B6C454]">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="hover:bg-[#F6FEDB]">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="hover:bg-[#F6FEDB]">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownTrigger = document.querySelector('[x-data] [x-slot="trigger"] button');
            const dropdownContent = document.querySelector('[x-data] [x-slot="content"]');

            dropdownTrigger.addEventListener('click', function () {
                dropdownContent.classList.toggle('hidden');
            });

            document.addEventListener('click', function (event) {
                if (!dropdownTrigger.contains(event.target) && !dropdownContent.contains(event.target)) {
                    dropdownContent.classList.add('hidden');
                }
            });
        });
    </script>
</nav>
