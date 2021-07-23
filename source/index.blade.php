@extends('_layouts.master')

@section('body')
    @foreach ($posts->where('featured', true) as $featuredPost)
        <div class="w-full mb-6">
            @if ($featuredPost->cover_image)
                <img src="{{ $featuredPost->cover_image }}" alt="{{ $featuredPost->title }} cover image" class="mb-6">
            @endif

            <p class="my-2 font-medium text-gray-700">
                {{ $featuredPost->getDate()->format('F j, Y') }}
            </p>

            <h2 class="mt-0 text-3xl">
                <a href="{{ $featuredPost->getUrl() }}" title="Read {{ $featuredPost->title }}" class="font-extrabold text-gray-900">
                    {{ $featuredPost->title }}
                </a>
            </h2>

            <p class="mt-0 mb-4">{!! $featuredPost->getExcerpt() !!}</p>

            <a href="{{ $featuredPost->getUrl() }}" title="Read - {{ $featuredPost->title }}" class="mb-4 tracking-wide uppercase">
                Read
            </a>
        </div>

        @if (! $loop->last)
            <hr class="my-6 border-b">
        @endif
    @endforeach

    {{-- @include('_components.newsletter-signup') --}}

    @foreach ($posts->where('featured', false)->take(6)->chunk(2) as $row)
        <div class="flex flex-col md:flex-row md:-mx-6">
            @foreach ($row as $post)
                <div class="w-full md:w-1/2 md:mx-6">
                    @include('_components.post-preview-inline')
                </div>

                @if (! $loop->last)
                    <hr class="block w-full mt-2 mb-6 border-b md:hidden">
                @endif
            @endforeach
        </div>

        @if (! $loop->last)
            <hr class="w-full mt-2 mb-6 border-b">
        @endif
    @endforeach
@stop
