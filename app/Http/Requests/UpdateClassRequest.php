<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $classId = $this->route('class');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('classes')->where(function ($query) {
                    return $query->where('grade_level', $this->grade_level)
                                 ->where('academic_year', $this->academic_year);
                })->ignore($classId)
            ],
            'major' => 'required|string|max:255',
            'grade_level' => 'required|in:X,XI,XII',
            'academic_year' => 'required|string',
            'homeroom_teacher_id' => [
                'nullable',
                'exists:users,id',
                \Illuminate\Validation\Rule::unique('classes')->where(function ($query) {
                    return $query->where('is_active', true);
                })->ignore($classId)
            ],
            'capacity' => 'required|integer|min:1|max:50',
            'description' => 'nullable|string|max:500'
        ];
    }
}
