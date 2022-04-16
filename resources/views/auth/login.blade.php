<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">

            @csrf
            @method('post')
        
            <label for="username">Username</label>
            <input type="text" class="block mt-1 w-full" name="username" value="{{ old('username') }}" id="username"  required autofocus />
        
            <label for="password">Password</label>
            <input type="password" class="block mt-1 w-full" name="password" id="password" required autocomplete="current-password" />
        
            <x-jet-button class="ml-4">
                {{ __('Log in') }}
            </x-jet-button>
        
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
