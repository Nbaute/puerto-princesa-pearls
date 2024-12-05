<div class="bg-accent-500 font-[sans-serif]">
    <div class="flex flex-col items-center justify-center min-h-screen px-4 py-6">
        <div class="w-full max-w-md">
            <a href="javascript:void(0)"><img src="/logo-small-spacing.png" alt="logo"
                    class='block w-10 mx-auto mb-8' />
            </a>

            <div class="p-8 bg-white shadow rounded-2xl">
                <h2 class="text-2xl font-semibold text-center text-gray-800">Sign in</h2>
                <form method="POST" wire:submit.prevent='attemptLogin' class="mt-8 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm text-gray-800">Email</label>
                        <div class="relative flex items-center">
                            <input wire:model="email" name="email" type="text" required
                                class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                placeholder="Enter email address" />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                                class="absolute w-4 h-4 right-4" viewBox="0 0 24 24">
                                <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                                <path
                                    d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                                    data-original="#000000"></path>
                            </svg>
                        </div>
                    </div>
                    @error('email')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror

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
                    </div>
                    @error('password')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror

                    {{-- @if (session()->has('error_message'))
                        <div role="alert" class="text-white bg-red-500 alert">
                            <span>{{ session('error_message') }}</span>
                        </div>
                    @endif --}}

                    {{-- <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center">
                            <input wire:model='rememberMe' id="remember-me" name="remember-me" type="checkbox"
                                class="w-4 h-4 border-gray-300 rounded text-primary-600 shrink-0 focus:ring-primary-500" />
                            <label for="remember-me" class="block ml-3 text-sm text-gray-800">
                                Remember me
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="{{ route('guest.forgot-password') }}" wire:navigate
                                class="font-semibold text-primary-600 hover:underline">
                                Forgot your password?
                            </a>
                        </div>
                    </div> --}}

                    <div class="!mt-8">
                        <button type="submit"
                            class="w-full px-4 py-3 text-sm font-semibold tracking-wide rounded-lg text-primary-900 bg-primary-500 focus:outline-none">
                            Sign in
                        </button>
                    </div>
                    <p class="text-gray-800 text-sm !mt-8 text-center">Don't have an account? <a
                            href="{{ route('guest.register') }}" wire:navigate
                            class="ml-1 font-semibold text-primary-600 hover:underline whitespace-nowrap">Register
                            here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
