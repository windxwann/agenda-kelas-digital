<?php

namespace App\Traits;

use App\Models\AcademicYear;

trait HasAcademicYear
{
    public static function bootHasAcademicYear()
    {
        static::creating(function ($model) {
            if (empty($model->academic_year_id)) {
                $activeYear = AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    $model->academic_year_id = $activeYear->id;
                }
            }
        });

        static::addGlobalScope('academic_year', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (request()->has('academic_year_id') && request('academic_year_id') != '') {
                $builder->where('academic_year_id', request('academic_year_id'));
            } else {
                $activeYear = AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    $builder->where('academic_year_id', $activeYear->id);
                }
            }
        });
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
