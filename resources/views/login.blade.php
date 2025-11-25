@extends('layouts.app')

@section('content')

<style>
    /* Base styles for existing HTML structure */
    .h-screen {
        height: 100vh;
    }
    
    .flex {
        display: flex;
    }
    
    .items-center {
        align-items: center;
    }
    
    .justify-center {
        justify-content: center;
    }
    
    .bg-gray-50 {
        background-color: #f9fafb;
    }
    
    .w-full {
        width: 100%;
    }
    
    .max-w-md {
        max-width: 28rem;
    }
    
    .bg-white {
        background-color: white;
    }
    
    .rounded-lg {
        border-radius: 0.5rem;
    }
    
    .shadow-lg {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .p-8 {
        padding: 2rem;
    }
    
    .border {
        border-width: 1px;
    }
    
    .border-gray-200 {
        border-color: #e5e7eb;
    }
    
    .text-3xl {
        font-size: 1.875rem;
    }
    
    .font-bold {
        font-weight: 700;
    }
    
    .text-center {
        text-align: center;
    }
    
    .text-primary {
        color: #3b82f6;
    }
    
    .mb-6 {
        margin-bottom: 1.5rem;
    }
    
    .text-gray-600 {
        color: #6b7280;
    }
    
    .mb-8 {
        margin-bottom: 2rem;
    }
    
    .block {
        display: block;
    }
    
    .text-gray-700 {
        color: #374151;
    }
    
    .font-medium {
        font-weight: 500;
    }
    
    .mb-2 {
        margin-bottom: 0.5rem;
    }
    
    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-3 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    
    .border-gray-300 {
        border-color: #d1d5db;
    }
    
    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .border-red-500 {
        border-color: #ef4444 !important;
    }
    
    .focus\:ring:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }
    
    .focus\:border-primary:focus {
        border-color: #3b82f6;
    }
    
    .relative {
        position: relative;
    }
    
    .h-4 {
        height: 1rem;
    }
    
    .w-4 {
        width: 1rem;
    }
    
    .ml-2 {
        margin-left: 0.5rem;
    }
    
    .text-sm {
        font-size: 0.875rem;
    }
    
    .bg-primary {
        background-color: #3b82f6;
    }
    
    .text-white {
        color: white;
    }
    
    .hover\:bg-primary-dark:hover {
        background-color: #2563eb;
    }
    
    .transition {
        transition: all 0.2s ease-in-out;
    }
    
    .duration-200 {
        transition-duration: 200ms;
    }
    
    .focus\:outline-none:focus {
        outline: none;
    }
    
    .focus\:ring-2:focus {
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
    
    /* Input styles */
    input[type="email"], input[type="password"] {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease-in-out;
        font-size: 1rem;
    }
    
    input[type="email"]:focus, input[type="password"]:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Button styles */
    button[type="submit"] {
        width: 100%;
        background-color: #3b82f6;
        color: white;
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    button[type="submit"]:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    /* Checkbox styles */
    input[type="checkbox"] {
        height: 1rem;
        width: 1rem;
        color: #3b82f6;
        border: 1px solid #d1d5db;
        border-radius: 0.25rem;
    }
    
    /* Responsive */
    @media (max-width: 640px) {
        .max-w-md {
            margin: 1rem;
        }
        
        .p-8 {
            padding: 1.5rem;
        }
        
        .text-3xl {
            font-size: 1.5rem;
        }
    }
</style>
<div class="h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 border border-gray-200">
        <h2 class="text-3xl font-bold text-center text-primary mb-6">Welcome Back</h2>
        <p class="text-center text-gray-600 mb-8">Login to access your account</p>

     
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Input -->
            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-primary/50 focus:border-primary  border-red-500 "
                    placeholder="Enter your email"
                    required
                    autocomplete="email"
                >
               
            </div>

            <!-- Password Input -->
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-primary/50 focus:border-primary  border-red-500 "
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                </div>
            
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 text-primary focus:ring-primary/50 border-gray-300 rounded"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Remember me
                    </label>
                </div>
                
               
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-primary text-white font-medium py-3 rounded-lg hover:bg-primary-dark transition duration-200 focus:outline-none focus:ring-2 focus:ring-primary/50"
            >
                Login
            </button>
        </form>

        
    </div>
</div>
@endsection