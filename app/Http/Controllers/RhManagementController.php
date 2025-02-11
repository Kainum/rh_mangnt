<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RhManagementController extends Controller
{

    public function index(): View
    {
        Auth::user()->can('rh') ?: abort(403, 'You are not allowed to access this page.');

        // get all colaborators that are not role admin nor rh
        $colaborators = User::withTrashed()
            ->with('detail', 'department')
            ->where('role', 'colaborator')
            ->get();

        return view('colaborators.admin.index', compact('colaborators'));
    }

    public function create(): View
    {
        Auth::user()->can('rh') ?: abort(403, 'You are not allowed to access this page.');

        // get all departments
        $departments = Department::where('id', '>', 2)->get();

        // if there are no departments, abort the request
        if ($departments->count() === 0) {
            abort(403, 'There are no departments to add a new colaborator. Please contact the system administrator.');
        }

        return view('colaborators.admin.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        Auth::user()->can('rh') ?: abort(403, 'You are not allowed to access this page.');

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

        // create user confirmation token
        $token = Str::random(60);

        // create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'confirmation_token' => $token,
            'role' => 'colaborator',
            'permissions' => '["colaborator"]',
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

        // send email to user
        Mail::to($user->email)->send(new ConfirmAccountEmail(
            route('confirm_account', $token)
        ));

        return redirect()->route('rh.management.index');
    }

    public function edit($id): View|RedirectResponse
    {
        Auth::user()->can('rh') ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::with('detail')->findOrFail($id);

        return view('colaborators.admin.edit', compact('colaborator'));
    }

    public function update(Request $request): RedirectResponse
    {
        Auth::user()->can('rh') ?: abort(403, 'You are not allowed to access this page.');

        // form validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary' => 'required|decimal:2',
            'admission_date' => 'required|date_format:Y-m-d',
        ]);

        $colaborator = User::findOrFail($request->user_id);

        $colaborator->detail->update([
            'salary' => $request->salary,
            'admission_date' => $request->admission_date,
        ]);

        return redirect()->route('rh.management.index')->with('success', 'Colaborator updated successfully.');
    }
}
