@extends('layouts.app')

@section('title', 'Our Teachers')

@section('content')
    <section class="mt-12">
        <h2 class="text-3xl font-bold text-center">Our Teachers</h2>
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <!-- Add teacher cards here -->
            <div class="text-center">
                <img src="{{ asset('images/teacher1.jpg') }}" class="rounded-full h-32 w-32 mx-auto" alt="Teacher 1">
                <h3 class="mt-4 text-lg font-semibold">Teacher 1</h3>
                <p class="text-gray-600">Expert in Mathematics</p>
            </div>
            <div class="text-center">
                <img src="{{ asset('images/teacher2.jpg') }}" class="rounded-full h-32 w-32 mx-auto" alt="Teacher 2">
                <h3 class="mt-4 text-lg font-semibold">Teacher 2</h3>
                <p class="text-gray-600">Expert in Science</p>
            </div>
        </div>
    </section>
@endsection

