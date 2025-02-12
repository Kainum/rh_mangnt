<x-layout-app page-title="Departments">

    <h3>Departments</h3>

    <hr>

    @if (empty($departments->count()))
        <div class="text-center my-5">
            <p>No departments found.</p>
            <a href="{{ route('departments.create') }}" class="btn btn-primary">Create a new department</a>
        </div>
    @else
        <div class="mb-3">
            <a href="{{ route('departments.create') }}" class="btn btn-primary">Create a new department</a>
        </div>

        <table class="table" id="table">
            <thead class="table-dark">
                <th>Department</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $department->name }}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-end">
                                @if (in_array($department->id, [1, 2]))
                                    <i class="fa-solid fa-lock me-2"></i>
                                @else
                                    <a href="{{ route('departments.edit', ['id' => $department->id]) }}" class="btn btn-sm btn-outline-dark ms-2">
                                        <i class="fa-regular fa-pen-to-square me-2"></i>Edit
                                    </a>
                                    <a href="{{ route('departments.delete', ['id' => $department->id]) }}" class="btn btn-sm btn-outline-dark ms-2">
                                        <i class="fa-regular fa-trash-can me-2"></i>Delete
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-layout-app>
