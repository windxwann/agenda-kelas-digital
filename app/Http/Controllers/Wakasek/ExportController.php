<?php

namespace App\Http\Controllers\Wakasek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExportController extends Controller
{
    use AuthorizesRequests;
    public function exportTeaching()
    {
        $this->authorize('view', User::class);

        $months = [];
        for ($i = 0; $i < 6; $i++) {
            $date = date('Y-m', strtotime("-$i months"));
            $months[$date] = date('F Y', strtotime($date));
        }

        return view('wakasek.export.teaching', compact('months'));
    }
}
