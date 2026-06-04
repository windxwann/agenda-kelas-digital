<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        return view('admin.academic_years.index', compact('academicYears'));
    }

    public function store(Request $request)
    {
        if ($request->has('name')) {
            $request->merge(['name' => ucwords(strtolower($request->name))]);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:academic_years',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'name.unique' => 'Tahun Ajaran ini sudah ada, silakan gunakan nama lain.',
            'end_date.after_or_equal' => 'Tanggal Selesai harus sama atau setelah Tanggal Mulai.',
        ]);

        AcademicYear::create($request->all());

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        if ($request->has('name')) {
            $request->merge(['name' => ucwords(strtolower($request->name))]);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:academic_years,name,' . $academicYear->id,
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'name.unique' => 'Tahun Ajaran ini sudah ada, silakan gunakan nama lain.',
            'end_date.after_or_equal' => 'Tanggal Selesai harus sama atau setelah Tanggal Mulai.',
        ]);

        $academicYear->update($request->all());

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->is_active) {
            return redirect()->route('admin.academic-years.index')->with('error', 'Tidak dapat menghapus Tahun Ajaran yang sedang aktif.');
        }

        $academicYear->delete();
        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
    }

    public function setActive(AcademicYear $academicYear)
    {
        DB::transaction(function () use ($academicYear) {
            AcademicYear::query()->update(['is_active' => false]);
            $academicYear->update(['is_active' => true]);
        });

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun Ajaran ' . $academicYear->name . ' berhasil diaktifkan.');
    }
}
