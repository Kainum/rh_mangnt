<x-layout-app page-title="User profile">
    <h3>User Profile</h3>

    <hr>

    <x-profile-user-data />

    <hr>

    <div class="container-fluid m-0 p-0 mt-5">
        <div class="row">
            <x-profile-user-change-password />
            
            {{-- componente name - email --}}
            <x-profile-user-change-data :$colaborator />

            {{-- componente address --}}
            <x-profile-user-change-address :$colaborator />
        </div>
    </div>
</x-layout-app>
