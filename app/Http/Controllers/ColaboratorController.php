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
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        $colaborators = User::withTrashed()
                        ->with('detail', 'department')
                        ->whereNot('role', 'admin')
                        ->get();

        return view('colaborators.admin.index', compact('colaborators'));
    }

    public function show($id): View|RedirectResponse
    {
        Auth::user()->can('admin', 'rh') ?: abort(403, 'You are not allowed to access this page.');

        // check if the id is the same as the auth user
        if (Auth::user()->id == $id) {
            return redirect()->route('home');
        }

        $colaborator = User::with('detail', 'department')
                        ->findOrFail($id);
        
        return view('colaborators.admin.show', compact('colaborator'));
    }

    public function delete($id): View|RedirectResponse
    {
        Auth::user()->can('admin', 'rh') ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::findOrFail($id);

        // display page for confirmation
        return view('colaborators.admin.delete-confirm', compact('colaborator'));
    }

    public function destroy($id): RedirectResponse
    {
        Auth::user()->can('admin', 'rh') ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::findOrFail($id);
        $colaborator->delete();

        return redirect()->route('colaborators.admin.index')->with('success', 'Colaborator deleted successfully.');
    }

    public function restore($id): RedirectResponse
    {
        Auth::user()->can('admin', 'rh') ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::withTrashed()->findOrFail($id);
        $colaborator->restore();

        return redirect()->route('colaborators.rh.index')->with('success', 'Colaborator restored successfully.');
    }
}
