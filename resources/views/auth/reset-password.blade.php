<x-layout-guests page-title="Reset de senha">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-5">
    
                <!-- logo -->
                <x-site-logo />
    
                <!-- redefine password -->
                <div class="card p-5">
    
                    <form action="{{ route('password.update') }}" method="post">

                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
    
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="password">Senha</label>
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation">Confirmar Senha</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('login') }}">Já sei a minha senha?</a>
                            <button type="submit" class="btn btn-primary px-4">Definir Senha</button>
                        </div>
    
                    </form>
    
                </div>
    
            </div>
        </div>
    </div>
</x-layout-guests>