<?php

namespace App\Http\Controllers\Wakasek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        return view('wakasek.evaluation.index');
    }

    public function report()
    {
        return view('wakasek.evaluation.report');
    }
}
