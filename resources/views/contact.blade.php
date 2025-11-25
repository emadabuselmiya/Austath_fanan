@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <section class="mt-12">
        <h2 class="text-3xl font-bold text-center">Contact Us</h2>
        <div class="mt-8 max-w-md mx-auto">
            <form action="#" method="POST" class="bg-white shadow-lg rounded-lg p-6">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" id="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none" placeholder="Your Name">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none" placeholder="Your Email">
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-gray-700">Message</label>
                    <textarea id="message" class="w-full px-4 py-2 border rounded-lg focus:outline-none" rows="5" placeholder="Your Message"></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Send Message</button>
                </div>
            </form>
        </div>
    </section>
@endsection

