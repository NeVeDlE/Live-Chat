<!doctype html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Codeitter</title>
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

<!-- Styles -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
<livewire:styles />
<body style="font-family: Open Sans, sans-serif">

<style>
    html {
        scroll-behavior: smooth;
    }
</style>
<section class="px-6 py-8">
    <nav class="md:flex md:justify-between md:items-center">
        <div>
            <a href="/">
                <h1 class="text-4xl">
                    <span class="text-blue-500">Codeitter</span>
                </h1>
            </a>
        </div>


        <div class="mt-8 md:mt-0 flex items-center">
            @auth
{{--                <livewire:messages-counter/>--}}
             
                                        <span class="text-xs font-bold uppercase">Welcome, {{auth()->user()->name }}!</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            @else
                <a href="/register" class="text-xs font-bold uppercase">Register</a>
                <a href="/login" class=" ml-6 text-xs font-bold uppercase">Log In</a>
            @endauth


        </div>
    </nav>

    {{$slot}}


    <footer id="newsletter"
            class="bg-gray-100 border border-black border-opacity-5 rounded-xl text-center py-16 px-10 mt-16">
        <h5 class="text-3xl">This is a Live Chat Simulator I <a
                href="https://www.linkedin.com/in/mostafa-shaher-4433a0223/"
                class="text-blue-500 no-underline hover:underline ... hover:text-blue-700" target="_blank">Mostafa
                Shaher</a> did to practice on.</h5>
        <p class="text-sm mt-3">the front belongs to Laracasts, Laravel from scratch series but i did add some front
            because i made new features xD</p>


    </footer>
</section>
<livewire:scripts />
</body>
