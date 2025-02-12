<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ColaboratorController extends Controller
{
    public function index(): View
    {
        Auth::user()->canAny(['admin', 'rh']) ?: abort(403, 'You are not allowed to access this page.');

        $colaborators = User::withTrashed()
                        ->with('detail', 'department')
                        ->where('role', 'colaborator')
                        // ->whereNot('role', 'admin')
                        ->get();

        $isRhInfo = false;
        return view('colaborators.index', compact('colaborators', 'isRhInfo'));
    }

    public function show($id): View
    {
        Auth::user()->canAny(['admin', 'rh']) ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::with('detail', 'department')
                        ->findOrFail($id);
        
        // inibe pessoal do rh de ver info sobre rh e admin
        if (Auth::user()->role == 'rh' && in_array($colaborator->role, ['admin', 'rh'])) {
            abort(403, 'You are not allowed to access this page.');
        }
        
        return view('colaborators.show', compact('colaborator'));
    }

    public function home () {
        echo 'salve';
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

        $isRhInfo = false;
        return view('colaborators.create', compact('departments', 'isRhInfo'));
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

        return redirect()->route('colaborators.index');
    }

    public function edit($id): View
    {
        Auth::user()->can('rh') ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::with('detail')
                            ->where('role', 'colaborator')
                            ->findOrFail($id);

        $departments = Department::where('id', '>', 2)->get();

        $isRhInfo = false;
        return view('colaborators.edit', compact('colaborator', 'departments', 'isRhInfo'));
    }

    public function update(Request $request): RedirectResponse
    {
        Auth::user()->can('rh') ?: abort(403, 'You are not allowed to access this page.');

        // form validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary' => 'required|decimal:2',
            'admission_date' => 'required|date_format:Y-m-d',
            'department' => 'required|exists:departments,id',
        ]);

        // check if department is valid
        if ($request->department <= 2) {
            return redirect()->route('home');
        }

        $colaborator = User::where('role', 'colaborator')->findOrFail($request->user_id);

        $colaborator->update([
            'department_id' => $request->department,
        ]);
        $colaborator->detail->update([
            'salary' => $request->salary,
            'admission_date' => $request->admission_date,
        ]);

        return redirect()->route('colaborators.index')->with('success', 'Colaborator updated successfully.');
    }

    public function delete($id): View
    {
        Auth::user()->canAny(['admin', 'rh']) ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::where('role', 'colaborator')->findOrFail($id);

        // display page for confirmation
        $isRhInfo = false;
        return view('colaborators.delete-confirm', compact('colaborator', 'isRhInfo'));
    }

    public function destroy($id): RedirectResponse
    {
        Auth::user()->canAny(['admin', 'rh']) ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::where('role', 'colaborator')->findOrFail($id);
        $colaborator->delete();

        return redirect()->route('colaborators.index')->with('success', 'Colaborator deleted successfully.');
    }

    public function restore($id): RedirectResponse
    {
        Auth::user()->canAny(['admin', 'rh']) ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::withTrashed()->findOrFail($id);
        $colaborator->restore();

        return redirect()->route('colaborators.index')->with('success', 'Colaborator restored successfully.');
    }

}
