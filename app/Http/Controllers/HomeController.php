<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function home(): View|RedirectResponse
    {
        if (Auth::user()->can('admin')) {
            return $this->admin_home();
        } else if (Auth::user()->can('rh')) {
            return redirect()->route('colaborators.index');
        } else if (Auth::user()->can('colaborator')) {
            return $this->colaborator_home();
        }
    }

    private function admin_home(): View
    {
        Auth::user()->can('admin') ?: abort(403, 'You are not allowed to access this page.');

        // collect all the info about the organization
        $data = [];

        // get total number of colaborators
        $data['total_colaborators'] = User::withoutTrashed()->count();

        // total colaborators deleted
        $data['total_colaborators_deleted'] = User::onlyTrashed()->count();


        // total salary for all colaborators
        $data['total_salary'] = User::withoutTrashed()
            ->with('detail')
            ->get()
            ->sum(function ($colaborator) {
                return $colaborator->detail->getAttributes()['salary'];
            });
        $data['total_salary'] = 'R$ ' . number_format($data['total_salary'], 2, ',', '.');

        // total colaborators by department
        $data['total_colaborators_by_department'] = User::withoutTrashed()
            ->with('department')
            ->get()
            ->groupBy('department_id')
            ->map(function ($row) {
                return [
                    'department' => $row->first()->department->name ?? '-',
                    'total' => $row->count(),
                ];
            });

        // total salary by department
        $data['total_salary_by_department'] = User::withoutTrashed()
            ->with('detail', 'department')
            ->get()
            ->groupBy('department_id')
            ->map(function ($row) {
                return [
                    'department' => $row->first()->department->name ?? '-',
                    'total' => $row->sum(function ($colaborator) {
                        return $colaborator->detail->getAttributes()['salary'];
                    }),
                ];
            });

        // format salary
        $data['total_salary_by_department'] = $data['total_salary_by_department']->map(function ($row) {
            return [
                'department' => $row['department'],
                'total' => 'R$ ' . number_format($row['total'], 2, ',', '.'),
            ];
        });

        return view('admin.dashboard', compact('data'));
    }

    private function colaborator_home(): View
    {
        Auth::user()->canAny(['colaborator']) ?: abort(403, 'You are not allowed to access this page.');

        $colaborator = User::with('detail', 'department')->findOrFail(Auth::user()->id);

        return view('colaborators.show', compact('colaborator'));
    }
}
