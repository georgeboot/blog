@extends('_layouts.master')

@php
    $page->type = 'article';
@endphp

@section('body')
    <article class="pb-4 mx-auto mb-10 prose border-b border-blue-200 lg:prose-lg">
        <header>
            @if ($page->cover_image)
                <img src="{{ $page->cover_image }}" alt="{{ $page->title }} cover image" class="mb-2">
            @endif

            <h1>{{ $page->title }}</h1>

            <p> 
                <address class="inline">{{ $page->author }}</address>  •  <time class="inline" datetime="{{ date('Y-m-d', $page->date) }}">{{ date('F j, Y', $page->date) }}</time>
            </p>

            @if ($page->categories)
                @foreach ($page->categories as $i => $category)
                    <a
                        href="{{ '/blog/categories/' . $category }}"
                        title="View posts in {{ $category }}"
                        class="inline-block px-3 pt-px mr-4 text-xs font-semibold leading-loose tracking-wide text-gray-800 uppercase bg-gray-300 rounded hover:bg-blue-200"
                    >{{ $category }}</a>
                @endforeach
            @endif
        </header>

        <div class="" v-pre>
            @yield('content')
        </div>
    </article>

    <div class="pb-4 mb-10 border-b border-blue-200">
        <script src="https://utteranc.es/client.js"
            repo="georgeboot/blog"
            issue-term="pathname"
            label="comment"
            theme="github-light"
            crossorigin="anonymous"
            async>
        </script>
    </div>

    <nav class="flex justify-between text-sm md:text-base">
        <div>
            @if ($next = $page->getNext())
                <a href="{{ $next->getUrl() }}" title="Older Post: {{ $next->title }}">
                    &LeftArrow; {{ $next->title }}
                </a>
            @endif
        </div>

        <div>
            @if ($previous = $page->getPrevious())
                <a href="{{ $previous->getUrl() }}" title="Newer Post: {{ $previous->title }}">
                    {{ $previous->title }} &RightArrow;
                </a>
            @endif
        </div>
    </nav>
@endsection
