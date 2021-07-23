---
title: Blog
description: The list of blog posts for the site
pagination:
    collection: posts
    perPage: 4
---
@extends('_layouts.master')

@section('body')
    <article class="pb-4 mx-auto mb-10 prose border-b border-blue-200 max-w-none lg:prose-lg">
        <h1>{{ $page->title }}</h1>
        @yield('content')
    </article>

    @foreach ($pagination->items as $post)
        @include('_components.post-preview-inline')

        @if ($post != $pagination->items->last())
            <hr class="my-6 border-b">
        @endif
    @endforeach

    @if ($pagination->pages->count() > 1)
        <nav class="flex my-8 text-base">
            @if ($previous = $pagination->previous)
                <a
                    href="{{ $previous }}"
                    title="Previous Page"
                    class="px-5 py-3 mr-3 bg-gray-200 rounded hover:bg-gray-400"
                >&LeftArrow;</a>
            @endif

            @foreach ($pagination->pages as $pageNumber => $path)
                <a
                    href="{{ $path }}"
                    title="Go to Page {{ $pageNumber }}"
                    class="bg-gray-200 hover:bg-gray-400 rounded mr-3 px-5 py-3 {{ $pagination->currentPage == $pageNumber ? 'text-blue-600' : 'text-blue-700' }}"
                >{{ $pageNumber }}</a>
            @endforeach

            @if ($next = $pagination->next)
                <a
                    href="{{ $next }}"
                    title="Next Page"
                    class="px-5 py-3 mr-3 bg-gray-200 rounded hover:bg-gray-400"
                >&RightArrow;</a>
            @endif
        </nav>
    @endif
@stop
