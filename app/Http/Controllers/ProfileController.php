<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function index(): View
    {
        $colaborator = User::with('detail', 'department')->findOrFail(Auth::user()->id);
        return view('users.profile', compact('colaborator'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        // form validation
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|max:16|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        // check if the current password is correct
        if (!password_verify($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // update password in database
        $user->password = bcrypt($request->new_password);
        $user->save();

        // redirect
        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateData(Request $request): RedirectResponse
    {
        // form validation
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        // update user data
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // redirect
        return redirect()->back()->with('success_change_data', 'Profile updated successfully.');
    }

    public function updateAddress(Request $request): RedirectResponse
    {
        // form validation
        $request->validate([
            'address' => 'required|min:3|max:100',
            'zip_code' => 'required|min:8|max:10',
            'city' => 'required|min:3|max:50',
            'phone' => 'required|min:6|max:20',
        ]);

        // update user data
        $detail = Auth::user()->detail;

        $detail->update([
            'address' => $request->address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'phone' => $request->phone,
        ]);

        // redirect
        return redirect()->back()->with('success_change_address', 'Profile updated successfully.');
    }
}
