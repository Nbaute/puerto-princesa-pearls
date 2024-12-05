<div class="bg-accent-500 font-[sans-serif]">
    <div class="flex flex-col items-center justify-center min-h-screen px-4 py-6">
        <div class="w-full max-w-md">
            <a><img src="/logo-small-spacing.png" alt="logo" class='block w-10 mx-auto mb-8' />
            </a>

            <div class="p-8 bg-white shadow rounded-2xl">
                <h2 class="text-2xl font-semibold text-center text-gray-800">Register</h2>
                <form method="POST" wire:submit.prevent='createAccount' class="mt-8 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm text-gray-800">Full Name</label>
                        <div class="relative flex items-center">
                            <input wire:model="name" name="name" type="text" required
                                class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                placeholder="Enter full name" />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                                class="absolute w-4 h-4 right-4" viewBox="0 0 24 24">
                                <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                                <path
                                    d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                                    data-original="#000000"></path>
                            </svg>
                        </div>
                        @error('name')
                            <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm text-gray-800">Email</label>
                        <div class="relative flex items-center">
                            <input wire:model="email" name="email" type="text" required
                                class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                placeholder="Enter email address" />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" viewBox="0 0 20 20"
                                class="absolute size-4 right-4">
                                <path
                                    d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                                <path
                                    d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                            </svg>

                        </div>
                        @error('email')
                            <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm text-gray-800">Password</label>
                        <div class="relative flex items-center">
                            <input wire:model='password' name="password"
                                type="{{ $showPassword ? 'text' : 'password' }}" required
                                class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                placeholder="Enter password" />
                            <svg wire:click='togglePassword' xmlns="http://www.w3.org/2000/svg" fill="#bbb"
                                stroke="#bbb" class="absolute w-4 h-4 cursor-pointer right-4" viewBox="0 0 128 128">
                                <path
                                    d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"
                                    data-original="#000000"></path>
                            </svg>
                        </div>
                        @error('password')
                            <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                        @enderror
                    </div>

                    @if (session()->has('error_message'))
                        <div role="alert" class="text-white alert alert-error">
                            <span>{{ session('error_message') }}</span>
                        </div>
                    @endif

                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="flex flex-row items-stretch justify-stretch">
                        {!! captcha_img() !!}
                    </div>
                    <p>
                        <input type="text" wire:model='captcha'
                            class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                            placeholder="Enter CAPTCHA" name="captcha" required>
                    </p>
                    @error('captcha')
                        {{-- <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span> --}}
                        <span class="text-xs text-red-500">The CAPTCHA code you entered is incorrect</span>
                    @enderror

                    <div class="!mt-8">
                        <button type="submit"
                            class="w-full px-4 py-3 text-sm font-semibold tracking-wide rounded-lg text-primary-900 bg-primary-500 focus:outline-none">
                            Create an account
                        </button>
                    </div>
                    <p class="text-gray-800 text-sm !mt-8 text-center">Already have an account? <a
                            href="{{ route('login') }}" wire:navigate
                            class="ml-1 font-semibold text-primary-600 hover:underline whitespace-nowrap">Sign in
                            here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
