<x-layout-app page-title="RH Users">

    <h3>RH Users</h3>

    <hr>

    @if (empty($colaborators->count()))
        <div class="text-center my-5">
            <p>No colaborators found.</p>
            <a href="{{ route('colaborators.rh.create') }}" class="btn btn-primary">Create a new RH user</a>
        </div>
    @else
        <div class="mb-3">
            <a href="{{ route('colaborators.rh.create') }}" class="btn btn-primary">Create a new RH user</a>
        </div>

        <table class="table" id="table">
            <thead class="table-dark">
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Permissions</th>
                <th>Admission Date</th>
                <th>City</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($colaborators as $colaborator)
                    <tr>
                        <td>{{ $colaborator->name }}</td>
                        <td>{{ $colaborator->email }}</td>
                        <td>{{ $colaborator->role }}</td>

                        @php
                            $permissions = json_decode($colaborator->permissions);
                        @endphp

                        <td>{{ implode(', ', $permissions) }}</td>
                        <td>{{ Carbon\Carbon::parse($colaborator->detail->admission_date)->format('d/m/Y') }}</td>
                        <td>{{ $colaborator->detail->city }}</td>

                        <td>
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('colaborators.rh.edit', ['id' => $colaborator->id]) }}"
                                    class="btn btn-sm btn-outline-dark ms-2">
                                    <i class="fa-regular fa-pen-to-square me-2"></i>Edit
                                </a>
                                <a href="{{ route('colaborators.rh.delete', ['id' => $colaborator->id]) }}"
                                    class="btn btn-sm btn-outline-dark ms-2">
                                    <i class="fa-regular fa-trash-can me-2"></i>Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-layout-app>
