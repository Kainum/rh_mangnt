<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // form validation
        $request->validate([
            'name' => 'required|string|min:3|max:50|unique:departments',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()->route('departments.index');
    }

    public function edit($id): View|RedirectResponse
    {
        $id = Operations::decryptId($id);

        // Não deixa editar o departamento Administração
        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments.index');
        }

        $department = Department::findOrFail($id);

        return view('departments.edit', compact('department'));
    }

    public function update(Request $request): RedirectResponse
    {
        $id = $request->id;
        $id = Operations::decryptId($id);

        // form validation
        $request->validate([
            'id' => 'required',
            'name' => 'required|string|min:3|max:50|unique:departments,name,' . $id,
        ]);

        // Não deixa editar o departamento Administração
        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments.index');
        }

        $department = Department::findOrFail($id);
        $department->update([
            'name' => $request->name,
        ]);

        return redirect()->route('departments.index');
    }

    public function delete($id): View|RedirectResponse
    {
        $id = Operations::decryptId($id);

        // Não deixa editar o departamento Administração
        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments.index');
        }

        $department = Department::findOrFail($id);

        // display page for confirmation
        return view('departments.delete-confirm', compact('department'));
    }

    public function destroy($id): RedirectResponse
    {
        $id = Operations::decryptId($id);
        
        // Não deixa editar o departamento Administração
        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments.index');
        }

        $department = Department::findOrFail($id);
        $department->delete();

        // update all colaborators department to null
        User::where('department_id', $id)->update([
            'department_id' => null,
        ]);

        return redirect()->route('departments.index');
    }

    private function isDepartmentBlocked($id)
    {
        return in_array(intval($id), [1, 2]);
    }
}
