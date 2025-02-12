<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RhColaboratorController extends Controller
{
    public function index(): View
    {
        $colaborators = User::withTrashed()
                            ->with('detail')
                            ->where('role', 'rh')
                            ->get();

        $isRhInfo = true;
        return view('colaborators.index', compact('colaborators', 'isRhInfo'));
    }

    public function create(): View
    {
        // get all departments
        $departments = Department::all();

        $isRhInfo = true;
        return view('colaborators.create', compact('departments', 'isRhInfo'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'department' => Operations::decryptId($request->department),
        ]);

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

        // create user confirmation token
        $token = Str::random(60);

        // create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'confirmation_token' => $token,
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

        // send email to user
        Mail::to($user->email)->send(new ConfirmAccountEmail(
            route('confirm_account', $token)
        ));

        return redirect()->route('colaborators.rh.index');
    }

    public function edit($id): View
    {
        $id = Operations::decryptId($id);
        
        $colaborator = User::with('detail')->where('role', 'rh')->findOrFail($id);

        $isRhInfo = true;
        return view('colaborators.edit', compact('colaborator', 'isRhInfo'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->merge([
            'user_id' => Operations::decryptId($request->user_id),
        ]);

        // form validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary' => 'required|decimal:2',
            'admission_date' => 'required|date_format:Y-m-d',
        ]);

        $colaborator = User::where('role', 'rh')->findOrFail($request->user_id);

        $colaborator->detail->update([
            'salary' => $request->salary,
            'admission_date' => $request->admission_date,
        ]);

        return redirect()->route('colaborators.rh.index')->with('success', 'Colaborator updated successfully.');
    }

    public function delete($id): View
    {
        $id = Operations::decryptId($id);

        $colaborator = User::where('role', 'rh')->findOrFail($id);

        // display page for confirmation
        $isRhInfo = true;
        return view('colaborators.delete-confirm', compact('colaborator', 'isRhInfo'));
    }

    public function destroy($id): RedirectResponse
    {
        $id = Operations::decryptId($id);

        $colaborator = User::where('role', 'rh')->findOrFail($id);
        $colaborator->delete();

        return redirect()->route('colaborators.rh.index')->with('success', 'Colaborator deleted successfully.');
    }
}
