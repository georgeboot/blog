---
title: About Me
description: Who is George Boot?
---
@extends('_layouts.master')

@section('body')
    <h1>About me</h1>

    <img src="/assets/img/george.jpg"
        alt="George Boot"
        class="flex w-64 h-64 mx-auto my-6 bg-contain rounded-full md:float-right md:ml-10">

    <p class="mb-6">I've been developing software since I was 12. After I had bought my first PHP book, I quickly started to dig deeper into this programming thing.<br>I started using Laravel a few days after version 4 got released.</p>
    <p class="mb-6">Nowdays I spend my days working on a wide range of projects. My day job is developer at <a target="_blank" href="https://www.entryninja.com">Entry Ninnja</a>. After hours, I run a gym subscription management platform called <a target="_blank" href="https://gymme.nl">Gymme</a>.</p>
    <p class="mb-6">Oh and I live in Veenendaal, The Netherlands.</p>

    <h3>My setup</h3>
    <p class="mb-6">I use VSCode. I've tried PHPStorm and it is super nice, but it does too many things autmatically, for my taste. I am rocking a (now pretty old) iMac 5k from 2015 but it still puts a smile on my face every day.</p>
    <p class="mb-6">One day I'm planning to get one of these hot Apple Silicone MacBook Pro's, but you know the struggle... Every time you finally convinced yourself to spend the bucks, a newer, faster but also more expensive model comes out.</p>
    <p class="mb-6">Oh and I try to get all my colleagues on Mac's as well. They are always super grateful for that. (not really)</p>
@endsection
