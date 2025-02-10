<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RhUserController extends Controller
{
    public function index(): View
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        $colaborators = User::where('role', 'rh')->get();
        return view('colaborators.rh.index', compact('colaborators'));
    }

    public function create(): View
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        // get all departments
        $departments = Department::all();

        return view('colaborators.rh.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        // form validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'department' => 'required|exists:departments,id',
            'address' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'city' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'salary' => 'required|decimal:2',
            'admission_date' => 'required|date_format:Y-m-d',
        ]);

        // check if department id == 2
        if ($request->department != 2) {
            return redirect()->route('home');
        }

        // create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'rh',
            'permissions' => '["rh"]',
            'department_id' => $request->department,
        ]);

        $user->detail()->create([
            'address' => $request->address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'phone' => $request->phone,
            'salary' => $request->salary,
            'admission_date' => $request->admission_date,
        ]);

        return redirect()->route('colaborators.rh.index');
    }

    public function edit($id): View|RedirectResponse
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        // Não deixa editar o departamento Administração
        if (intval($id) === 1) {
            return redirect()->route('colaborators.rh.index');
        }

        $colaborator = User::findOrFail($id);

        return view('colaborators.rh.edit', compact('colaborator'));
    }

    public function update(Request $request): RedirectResponse
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        $id = $request->id;

        // form validation
        $request->validate([
            'id' => 'required',
            'name' => 'required|string|min:3|max:50|unique:colaborators,name,' . $id,
        ]);

        // Não deixa editar o departamento Administração
        if (intval($id) === 1) {
            return redirect()->route('colaborators.rh.index');
        }


        $colaborator = User::findOrFail($id);
        $colaborator->update([
            'name' => $request->name,
        ]);

        return redirect()->route('colaborators.rh.index');
    }

    public function delete($id): View|RedirectResponse
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::findOrFail($id);

        // display page for confirmation
        return view('colaborators.rh.delete-confirm', compact('colaborator'));
    }

    public function destroy($id): RedirectResponse
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::findOrFail($id);
        $colaborator->delete();

        return redirect()->route('colaborators.rh.index');
    }
}
