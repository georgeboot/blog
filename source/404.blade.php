---
permalink: 404.html
---
@extends('_layouts.master')

@section('body')
    <div class="flex flex-col items-center mt-32 text-gray-700">
        <h1 class="mb-2 text-6xl font-light leading-none">404</h1>

        <h2 class="text-3xl">Page not found.</h2>

        <hr class="block w-full max-w-sm mx-auto my-8 border">

        <p class="text-xl">
            Need to update this page? See the <a title="404 Page Documentation" href="https://jigsaw.tighten.co/docs/custom-404-page/">Jigsaw documentation</a>.
        </p>
    </div>
@endsection
