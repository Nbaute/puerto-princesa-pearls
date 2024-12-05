<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $title ?? env('APP_NAME') }}</title>

    @livewireStyles
    @vite('resources/css/app.css')


    <link rel="icon" href="logo-small-spacing.png" type="image/png" />
    <link rel="shortcut icon" href="logo-small-spacing.png" type="image/png" />
    {{-- <link rel="shortcut icon" href="favicon.ico?v=333" type="image/x-icon" /> --}}
</head>

<body>
    <div class="w-full navbar bg-primary-500 text-primary-950">
        {{-- <div class="flex-none">
            <button class="btn btn-square btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block w-5 h-5 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div> --}}
        <div class="flex-1">
            <a href="{{ route('guest.home') }}" wire:navigate
                class="flex items-center gap-4 text-xl text-primary-900 btn btn-ghost">

                <img src="/logo-small-spacing.png" alt="logo">
                <span class="hidden font-normal lg:flex">@yield('app_name', env('APP_NAME'))</span>
            </a>
        </div>
        @livewire('components.search-livewire')
        <div class="flex-none hidden md:block">

            <ul class="items-center gap-4 px-1 my-auto menu menu-horizontal">
                <li>

                </li>
                <li><a class="active:!bg-[#e7c375] active:!text-primary-950" href="{{ route('guest.home') }}"
                        wire:navigate>Home</a></li>
                <li><a class="active:!bg-[#e7c375] active:!text-primary-950" href="{{ route('guest.shops') }}"
                        wire:navigate>Visit Shops</a>
                </li>
                <li><a class="active:!bg-[#e7c375] active:!text-primary-950" href="{{ route('guest.contact-us') }}"
                        wire:navigate>Contact Us </a></li>
                @guest
                    <li><a class="active:!bg-[#e7c375] active:!text-primary-950" href="{{ route('login') }}"
                            wire:navigate>Log In / Register</a></li>
                @endguest


            </ul>
        </div>

        @auth
            @php
                $user = auth()->user();
            @endphp
            <div class="flex-none">
                @livewire('components.nav-items.cart-livewire')
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img id='user_profile_picture' alt="{{ $user->name }}"
                                src="{{ $user->profile_picture_url }}" />
                        </div>
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                        <li class="md:hidden">
                            <a href="{{ route('guest.shops') }}" wire:navigate class="justify-between">
                                Visit Shops
                            </a>
                        </li>
                        <li class="md:hidden">
                            <a href="{{ route('guest.contact-us') }}" wire:navigate class="justify-between">
                                Contact Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile') }}" wire:navigate class="justify-between">
                                Profile
                                {{-- <span class="badge">New</span> --}}
                            </a>
                        </li>
                        <li><a href="{{ route('orders') }}" wire:navigate>Orders</a></li>
                        <li><a href="{{ route('my.shops') }}" wire:navigate>My Shops</a></li>
                        @if (count($user->shops ?? []) === 0)
                            <li><a href="/my/shops/create" wire:navigate>Become a Seller</a></li>
                        @endif
                        <li>@livewire('components.logout-button-livewire')</li>
                    </ul>
                </div>
            </div>

            {{-- <li>
                        <div class="bg-center bg-no-repeat bg-cover rounded-full size-10 bg-primary-600"
                            style='background-image: url({{ auth()->user()->profile_picture_url }})'></div>
                    </li> --}}
        @endauth
        <div class="flex-none hidden">
            <button class="btn btn-square btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block w-5 h-5 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    <div class="min-h-[100dvh] bg-accent-500">
        {{ $slot }}</div>


    <footer class="p-10 text-white footer footer-center bg-primary-950 ">
        <aside>

            <img src="/logo-small-spacing.png" alt="logo">
            <p class="font-bold">
                {{ env('APP_NAME') }}
                <br />
                High Quality Pearls
            </p>
            <p>Copyright © {{ now()->format('Y') }} - All right reserved</p>
        </aside>
        <nav>
            <div class="grid grid-flow-col gap-4">
                <a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        class="fill-current">
                        <path
                            d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z">
                        </path>
                    </svg>
                </a>
                <a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        class="fill-current">
                        <path
                            d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z">
                        </path>
                    </svg>
                </a>
                <a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        class="fill-current">
                        <path
                            d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z">
                        </path>
                    </svg>
                </a>
            </div>
        </nav>
    </footer>

    @livewireScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-livewire-alert::scripts />
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('openLink', e => {
                console.log(e[0])
                location.href = e[0]
            })
            Livewire.on('userUpdated', e => {
                console.log('user updated', e)
                let user = e[0]

                document.getElementById('user_profile_picture').src = user.profile_picture_url;
            })

            Livewire.on('profilePicturePreviewed', e => {
                console.log('profilePicturePreviewed', e)
            })

            Livewire.on('copyToClipboard', e => {
                console.log('copyToClipboard', e);
                const textToCopy = e; // The text to copy from the event
                let isCopied = false;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(textToCopy)
                        .then(() => {
                            console.log('Text copied to clipboard successfully!');
                            isCopied = true;
                        })
                        .catch(err => {
                            console.error('Failed to copy text to clipboard:', err);
                        });
                } else {
                    // Fallback for unsupported browsers
                    console.warn('navigator.clipboard is not available. Using fallback method.');
                    try {
                        const input = document.createElement('input');
                        input.value = textToCopy;
                        document.body.appendChild(input);

                        input.select();
                        input.setSelectionRange(0, 99999);
                        input.select();
                        const successful = document.execCommand('copy');
                        if (successful) {
                            console.log('Text copied to clipboard using fallback!');
                            isCopied = true;
                        } else {
                            console.error('Fallback method failed to copy text.');
                        }
                        document.body.removeChild(input);
                    } catch (err) {
                        console.error('Error using fallback method:', err);
                    }
                }
            });

        })
    </script>
</body>

</html>
