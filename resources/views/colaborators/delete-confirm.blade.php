<x-layout-app page-title="Delete colaborator">
    @php
        $route = $isRhInfo ? 'colaborators.rh' : 'colaborators';
    @endphp
    <div class="w-25">

        <h3>Delete colaborator</h3>
    
        <hr>
    
        <p>Are you sure you want to delete this colaborator?</p>
        
        <div class="text-center">
            <h3 class="my-5">{{ $colaborator->name }}</h3>
            <p>{{ $colaborator->email }}</p>
            <a href="{{ route("$route.index") }}" class="btn btn-secondary px-5">No</a>
            <a href="{{ route("$route.destroy", ['id' => $colaborator->id]) }}" class="btn btn-danger px-5">Yes</a>
        </div>
        
    </div>
</x-layout-app>