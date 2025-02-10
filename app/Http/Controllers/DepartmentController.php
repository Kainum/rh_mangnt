<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    public function create(): View
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');
        return view('departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

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
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        // Não deixa editar o departamento Administração
        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments.index');
        }

        $department = Department::findOrFail($id);

        return view('departments.edit', compact('department'));
    }

    public function update(Request $request): RedirectResponse
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        $id = $request->id;

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
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

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
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        // Não deixa editar o departamento Administração
        if ($this->isDepartmentBlocked($id)) {
            return redirect()->route('departments.index');
        }

        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.index');
    }

    private function isDepartmentBlocked($id) {
        return in_array(intval($id), [1, 2]);
    }
}
