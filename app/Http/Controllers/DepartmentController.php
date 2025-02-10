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
            'name' => 'required|string|max:50|unique:departments',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()->route('departments.index'); 
    }
}
