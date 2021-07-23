<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">

        <meta property="og:title" content="{{ $page->title ? $page->title . ' | ' : '' }}{{ $page->siteName }}"/>
        <meta property="og:type" content="{{ $page->type ?? 'website' }}" />
        <meta property="og:url" content="{{ $page->getUrl() }}"/>
        <meta property="og:description" content="{{ $page->description ?? $page->siteDescription }}" />

        <title>{{ $page->title ?  $page->title . ' | ' : '' }}{{ $page->siteName }}</title>

        <link rel="home" href="{{ $page->baseUrl }}">
        <link rel="icon" href="/favicon.ico">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/fassets/avicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link href="/blog/feed.atom" type="application/atom+xml" rel="alternate" title="{{ $page->siteName }} Atom Feed">

        @if ($page->production)
            <!-- Insert analytics code here -->
        @endif

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,700,700i,800,800i" rel="stylesheet">
        <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
    </head>

    <body class="flex flex-col justify-between min-h-screen font-sans leading-normal text-gray-800 bg-gray-100">
        <header class="flex items-center h-24 py-4 bg-white border-b shadow" role="banner">
            <div class="container flex items-center px-4 mx-auto max-w-8xl lg:px-8">
                <div class="flex items-center">
                    <a href="/" title="{{ $page->siteName }} home" class="inline-flex items-center">
                        <img class="h-8 mr-3 md:h-10" src="/android-chrome-512x512.png" alt="{{ $page->siteName }} logo" />

                        <h1 class="my-0 text-lg font-semibold text-gray-500 md:text-2xl hover:text-blue-600">{{ $page->siteName }}</h1>
                    </a>
                </div>

                <div id="vue-search" class="flex items-center justify-end flex-1">
                    <search></search>

                    @include('_nav.menu')

                    @include('_nav.menu-toggle')
                </div>
            </div>
        </header>

        @include('_nav.menu-responsive')

        <main role="main" class="container flex-auto w-full max-w-4xl px-6 py-16 mx-auto">
            @yield('body')
        </main>

        <footer class="py-4 mt-12 text-sm text-center bg-white" role="contentinfo">
            <ul class="flex flex-col justify-center list-none md:flex-row">
                <li class="md:mr-2">
                    &copy; <a href="https://georgeboot.nl" title="George Boot" class="text-blue-500">George Boot</a> {{ date('Y') }}.
                </li>

                <li>
                    Built with <a href="http://jigsaw.tighten.co" title="Jigsaw by Tighten" class="text-blue-500">Jigsaw</a>
                    and <a href="https://tailwindcss.com" title="Tailwind CSS, a utility-first CSS framework" class="text-blue-500">Tailwind CSS</a>.
                </li>
            </ul>
        </footer>

        <script src="{{ mix('js/main.js', 'assets/build') }}"></script>

        @stack('scripts')
    </body>
</html>
