<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Two-Factor Authentication') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- {{ __("You're logged in!") }} --}}
                    <h1>Enable Two-Factor Authentication</h1>
                    <p>Scan the QR Code below using your authenticator app:</p>
                    {{-- <img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl={{ $qrCodeUrl }}"
                        alt="QR Code"> --}}
                    {!! $qrCodeSvg !!}
                    {{-- <img src="data:image/png;base64,{{ $qrCodeImage }}" alt="QR Code"> --}}
                    <form action="{{ route('two-factor.verify') }}" method="POST">
                        @csrf
                        <label for="otp">Enter OTP:</label>
                        <input type="text" name="otp" id="otp"
                            class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600"
                            required>
                        <button type="submit" class="rounded-full">Verify</button>

                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
