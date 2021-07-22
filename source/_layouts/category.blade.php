@extends('_layouts.master')

@section('body')
    <h1>{{ $page->title }}</h1>

    <div class="pb-10 mb-6 text-2xl border-b border-blue-200">
        @yield('content')
    </div>

    @foreach ($page->posts($posts) as $post)
        @include('_components.post-preview-inline')

        @if (! $loop->last)
            <hr class="w-full mt-2 mb-6 border-b">
        @endif
    @endforeach

    {{-- @include('_components.newsletter-signup') --}}
@stop
