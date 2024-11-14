<nav x-data="{ open: false }" class="bg-green-50 border-r border-white-300 shadow-md fixed h-full w-64 left-0 top-0">
    <!-- Primary Navigation Menu -->
    <div class="p-4">
        <!-- Logo -->
        <div class="shrink-0 mb-6">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-green-800" />
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex flex-col space-y-4">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block hover:text-green-600 transition duration-200">
                {{ __('Homepage') }}
            </x-nav-link>
            <x-nav-link :href="route('messages')" :active="request()->routeIs('messages')" class="block hover:text-green-600 transition duration-200">
                {{ __('Messages') }}
            </x-nav-link>
            <x-nav-link :href="route('profile.show')" :active="request()->routeIs('profile.show')" class="block hover:text-green-600 transition duration-200">
                {{ __('Profile') }}
            </x-nav-link>
            <x-nav-link :href="route('search.page')" :active="request()->routeIs('search.page')" class="block hover:text-green-600 transition duration-200">
                {{ __('Search') }}
            </x-nav-link>
        </div>

        <!-- Settings Dropdown -->
        <div class="mt-8">
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button class="w-full flex items-center px-3 py-2 border border-[#434028] text-sm leading-4 font-medium rounded-2xl text-gray-700 bg-[#ffffff] hover:bg-green-100 transition ease-in-out duration-150">
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
                    <x-dropdown-link :href="route('profile.edit')" class="hover:bg-green-100">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="hover:bg-green-100">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</nav>
