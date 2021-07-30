---
title: Contact
description: Get in contact with George Boot
---
@extends('_layouts.master')

@section('body')

    <article class="pb-4 mx-auto mb-10 prose border-b border-blue-200 lg:prose-lg">
        <h1>{{ $page->title }}</h1>
        <p>
            If you want to get in touch, here are some options:
        </p>
        <ul class="list-inside">
            <li>Twitter: <a target="_blank" rel="noopener"href="https://twitter.com/georgeboot">@georgeboot</a> <span class="text-sm text-gray-500">(be careful, I enjoy politics too...)</span></li>
            <li>Telegram: <a target="_blank" rel="noopener" href="https://t.me/georgeboot">@georgeboot</a></li>
            <li>E-mail: <a target="_blank" rel="noopener" data-obfuscated-email="{{ base64_encode('georgeboot@icloud.com') }}"><i>email hidden from spam bots</i></a></li>
        </ul>
        <script>
            const el = document.querySelector('a[data-obfuscated-email]')
            const email = atob(el.getAttribute('data-obfuscated-email'))
            el.innerHTML = email
            el.setAttribute('href', `mailto:${email}`)
        </script>
    </article>

@stop
