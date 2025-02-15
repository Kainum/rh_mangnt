<x-layout-guests page-title="Account confirmation">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-4">

                <!-- logo -->
                <x-site-logo />

                <!-- login form -->
                <div class="card p-5">

                    <form action="{{ route('confirm_account_submit') }}" method="post">

                        @csrf

                        <input type="hidden" name="token" value="{{ $user->confirmation_token }}">

                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation">Repeat Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                            @error('password_confirmation')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-primary px-4">Complete</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
</x-layout-guests>
