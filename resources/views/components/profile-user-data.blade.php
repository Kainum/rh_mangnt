<div class="d-flex gap-5">

    <div>
        <i class="fa-solid fa-user me-3"></i>{{ Auth::user()->name }}
    </div>
    <div>
        <i class="fa-solid fa-user me-3"></i>{{ Auth::user()->role }}
    </div>
    <div>
        <i class="fa-solid fa-at me-3"></i>{{ Auth::user()->email }}
    </div>
    <div>
        <i class="fa-regular fa-calendar-days me-3"></i>{{ Auth::user()->created_at->format('d/m/Y') }}
    </div>

</div>