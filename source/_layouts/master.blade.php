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
        @if ($page->cover_image)
        <meta property="og:image" content="{{ $page->baseUrl }}{{ $page->cover_image }}"/>
        @endif
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
                    <div x-data="search" class="flex items-center justify-end flex-1 px-4 text-right">
                        <div
                            class="absolute top-0 left-0 z-10 justify-end w-full px-4 bg-white md:relative mt-7 md:mt-0 md:px-0"
                            :class="{ 'hidden md:flex': ! searching }"
                        >
                            <label for="search" class="hidden">Search</label>

                            <input
                                id="search"
                                x-model="query"
                                x-ref="search"
                                class="relative block w-full h-10 px-4 pt-px pb-0 text-gray-700 transition-all duration-200 ease-out bg-gray-100 border border-gray-500 outline-none cursor-pointer lg:w-1/2 lg:focus:w-3/4 focus:border-blue-400"
                                :class="{ 'transition-border': query }"
                                autocomplete="off"
                                name="search"
                                placeholder="Search"
                                type="text"
                                @keyup.escape="reset"
                                @blur="reset"
                            >

                            <template x-if="query || searching">
                                <button
                                    class="absolute top-0 right-0 text-3xl leading-snug text-blue-500 font-400 hover:text-blue-600 focus:outline-none pr-7 md:pr-3"
                                    @click="reset"
                                >&times;</button>
                            </template>

                            <template x-if="query">
                                <div x-transition class="absolute left-0 right-0 w-full mb-4 text-left md:inset-auto lg:w-3/4 md:mt-10">
                                    <div class="flex flex-col mx-4 bg-white border border-t-0 border-b-0 border-blue-400 rounded-b-lg shadow-search md:mx-0">
                                        <template x-for="(result, index) in results">
                                            <a
                                                class="p-4 text-xl bg-white border-b border-blue-400 cursor-pointer hover:bg-blue-100"
                                                :class="{ 'rounded-b-lg': (index === results.length - 1) }"
                                                :href="result.item.link"
                                                :title="result.item.title"
                                                :key="result.link"
                                                @mousedown.prevent
                                            >
                                                <span x-text="result.item.title"></span>

                                                <span class="block my-1 text-sm font-normal text-gray-700" x-html="result.item.snippet"></span>
                                            </a>
                                        </template>

                                        <template x-if="! results.length">
                                            <div class="w-full p-4 bg-white border-b border-blue-400 rounded-b-lg shadow cursor-pointer hover:bg-blue-100">
                                                <p class="my-0">No results for <strong x-text="query"></strong></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button
                            title="Start searching"
                            type="button"
                            class="flex items-center justify-center h-10 px-3 bg-gray-100 border border-gray-500 rounded-full md:hidden hover:bg-blue-100 focus:outline-none"
                            @click.prevent="showInput"
                        >
                            <img src="/assets/img/magnifying-glass.svg" alt="search icon" class="w-4 h-4 max-w-none">
                        </button>
                    </div>

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

        <script defer src="{{ mix('js/main.js', 'assets/build') }}"></script>

        @stack('scripts')
    </body>
</html>
