<x-layout-app>
    @php
        $route = $isRhInfo ? 'colaborators.rh' : 'colaborators';
    @endphp

    <x-slot name="pageTitle">
        @if ($isRhInfo)
            RH Colaborators
        @else
            Colaborators
        @endif
    </x-slot>

    @if ($isRhInfo)
        <h3>RH Colaborators</h3>
    @else
        <h3>All colaborators</h3>
    @endif

    <hr>

    @if (empty($colaborators->count()))
        <div class="text-center my-5">
            <p>No colaborators found.</p>
            @if ($isRhInfo)
                <a href="{{ route("$route.create") }}" class="btn btn-primary">
                    Create a new RH user
                </a>
            @else
                @can('rh')
                    <a href="{{ route("$route.create") }}" class="btn btn-primary">
                        Create a new colaborator
                    </a>
                @endcan
            @endif
        </div>
    @else
        <div class="mb-3">
            @if ($isRhInfo)
                <a href="{{ route("$route.create") }}" class="btn btn-primary">
                    Create a new RH user
                </a>
            @else
                @can('rh')
                    <a href="{{ route("$route.create") }}" class="btn btn-primary">
                        Create a new colaborator
                    </a>
                @endcan
            @endif
        </div>

        <table class="table" id="table">
            <thead class="table-dark">
                <th>Name</th>
                <th>Email</th>
                <th>Active</th>
                <th>Department</th>
                <th>Role</th>
                <th>Admission Date</th>
                <th class="text-end">Salary</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($colaborators as $colaborator)
                    <tr>
                        <td>{{ $colaborator->name }}</td>
                        <td>{{ $colaborator->email }}</td>
                        <td>
                            @empty($colaborator->email_verified_at)
                                <span class="badge bg-danger">No</span>
                            @else
                                <span class="badge bg-success">Yes</span>
                            @endempty
                        </td>
                        <td>{{ $colaborator->department->name ?? '-' }}</td>
                        <td>{{ $colaborator->role }}</td>
                        <td>{{ Carbon\Carbon::parse($colaborator->detail->admission_date)->format('d/m/Y') }}</td>
                        <td class="text-end">R$ {{ $colaborator->detail->salary }}</td>

                        <td>
                            <div class="d-flex gap-3 justify-content-end">
                                @empty($colaborator->deleted_at)
                                    <a href="{{ route('colaborators.show', ['id' => Crypt::encrypt($colaborator->id)]) }}"
                                        class="btn btn-sm btn-outline-dark ms-2">
                                        <i class="fas fa-eye me-2"></i>Details
                                    </a>
                                    @if ($isRhInfo || Auth::user()->can('rh'))
                                        <a href="{{ route("$route.edit", ['id' => Crypt::encrypt($colaborator->id)]) }}"
                                            class="btn btn-sm btn-outline-dark ms-2">
                                            <i class="fa-regular fa-pen-to-square me-2"></i>Edit
                                        </a>
                                    @endif
                                    <a href="{{ route("$route.delete", ['id' => Crypt::encrypt($colaborator->id)]) }}"
                                        class="btn btn-sm btn-outline-dark ms-2">
                                        <i class="fa-regular fa-trash-can me-2"></i>Delete
                                    </a>
                                @else
                                    <a href="{{ route('colaborators.restore', ['id' => Crypt::encrypt($colaborator->id)]) }}"
                                        class="btn btn-sm btn-outline-dark ms-2">
                                        <i class="fas fa-trash-arrow-up me-2"></i>Restore
                                    </a>
                                @endempty
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-layout-app>
