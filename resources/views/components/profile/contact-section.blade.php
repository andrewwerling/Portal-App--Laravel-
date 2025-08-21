@props(['email', 'phone', 'social_media'])

<div class="grid gap-6 w-full">
    {{-- Email --}}
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    {{ __('Your email address is unverified.') }}
                    <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif
    </div>
    {{-- Phone --}}
    <div>
        <x-input-label for="phone" :value="__('Phone')" />
        <x-text-input wire:model="phone" id="phone" name="phone" type="text" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
    </div>
    {{-- Instagram --}}
    <div>
        <x-input-label for="social_media_instagram" :value="__('Instagram')" />
        <x-text-input wire:model="social_media.instagram" id="social_media_instagram" name="social_media.instagram" type="url" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('social_media.instagram')" />
    </div>
    {{-- LinkedIn --}}
    <div>
        <x-input-label for="social_media_linkedin" :value="__('LinkedIn')" />
        <x-text-input wire:model="social_media.linkedin" id="social_media_linkedin" name="social_media.linkedin" type="url" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('social_media.linkedin')" />
    </div>
    {{-- Facebook --}}
    <div>
        <x-input-label for="social_media_facebook" :value="__('FaceBook')" />
        <x-text-input wire:model="social_media.facebook" id="social_media_facebook" name="social_media.facebook" type="url" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('social_media.facebook')" />
    </div>
    {{-- Tik Tok --}}
    <div>
        <x-input-label for="social_media_tiktok" :value="__('Tik Tok')" />
        <x-text-input wire:model="social_media.tiktok" id="social_media_tiktok" name="social_media.tiktok" type="url" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('social_media.tiktok')" />
    </div>
    {{-- X/Twitter --}}
    <div>
        <x-input-label for="social_media_twitter" :value="__('X/Twitter')" />
        <x-text-input wire:model="social_media.twitter" id="social_media_twitter" name="social_media.twitter" type="url" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('social_media.twitter')" />
    </div>
</div> 