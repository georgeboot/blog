@extends('_layouts.master')

@section('body')
    <article class="pb-4 mx-auto mb-10 prose border-b border-blue-200 max-w-none lg:prose-lg">
        <h1>{{ $page->title }}</h1>
        @yield('content')
    </article>

    @foreach ($page->posts($posts) as $post)
        @include('_components.post-preview-inline')

        @if (! $loop->last)
            <hr class="w-full mt-2 mb-6 border-b">
        @endif
    @endforeach

    {{-- @include('_components.newsletter-signup') --}}
@stop
