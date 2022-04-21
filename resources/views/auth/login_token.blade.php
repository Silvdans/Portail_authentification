<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('login_token')}}" >
            @csrf
            
            <x-jet-input id="id" class="block mt-1 w-full" type="hidden" name="id" value={{$id}}/>
            <div>
                <x-jet-label for="token" value="{{ __('Token') }}" />
                <x-jet-input id="token" class="block mt-1 w-full" type="text" name="token" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-jet-button class="ml-4">
                    {{ __('Confirm') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
