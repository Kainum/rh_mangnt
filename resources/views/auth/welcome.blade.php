<x-layout-guests page-title="Welcome">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col">

                <!-- logo -->
                <x-site-logo />

                {{-- welcome message --}}
                <div class="card p-5 text-center">
                    <p>Welcome, <strong>{{ $user->name }}</strong>!</p>
                    <p>Your account has been successfully created.</p>
                    <p>You can now <a href="{{ route('login') }}">login</a> to your account.</p>
                </div>

            </div>
        </div>
    </div>

</x-layout-guests>
