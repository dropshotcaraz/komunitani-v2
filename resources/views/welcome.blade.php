<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunitani</title>
    <link rel="icon" href="https://i.ibb.co.com/89qxHLW/logokomunitani-chara.png" type="image/x-icon">
    <!-- Vite and Tailwind CSS Integration -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css') <!-- Adjust if using Blade directive for Vite -->
</head>
<body class="bg-white-100 flex justify-center items-center min-h-screen">
    <section class="flex flex-col px-11 pt-8 pb-8 bg-[#F7F0CF] rounded-3xl shadow-lg max-md:px-5 max-md:pb-24"> 
        <!-- Header Section -->
        <div class="flex justify-center items-center mb-2">
            <img
                loading="lazy"
                src="https://i.ibb.co.com/Ns8HNB2/mix-logo.png" alt="logokomunitani-chara" 
                class="w-full max-w-[300px]"
            />
        </div>
        
        <!-- Main Section -->
        <main class="flex flex-col justify-center items-center px-8 py-20 text-center bg-white rounded-3xl shadow-lg w-[575px] max-md:px-5 max-md:mt-10">
            <div class="w-full max-w-[474px] flex flex-col">
                <!-- Welcome Text -->
                <h1 class="text-5xl font-bold text-stone-700 mb-8 max-md:text-4xl">Selamat Datang</h1>

                @if (Route::has('login'))
                    <nav class="flex flex-col gap-4 w-full items-center mt-4">
                        @auth
                            <!-- Dashboard Button -->
                            <a href="{{ url('/dashboard') }}" class="px-28 py-3 bg-lime-700 rounded-lg shadow text-white font-bold min-h-[54px] hover:bg-lime-600">
                                Dashboard
                            </a>
                        @else
                            <!-- Log in Button -->
                            <a href="{{ route('login') }}" class="w-full py-3 bg-lime-700 rounded-lg shadow text-white font-bold min-h-[54px] hover:bg-lime-600">
                                Log in
                            </a>

                            <!-- Register Button -->
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="w-full py-3 bg-lime-700 rounded-lg shadow text-white font-bold min-h-[54px] hover:bg-lime-600 mt-2">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </main>
    </section>
</body>
</html>
