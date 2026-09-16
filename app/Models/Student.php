<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'licence_no',
        'dob',
        'phone',
        'email',
        'vehicle_reg',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assessmentForms(): HasMany
    {
        return $this->hasMany(AssessmentForm::class)->latest('test_date');
    }

    public function reflectionForms(): HasMany
    {
        return $this->hasMany(ReflectionForm::class)->latest('lesson_date');
    }

    public function progressRecord(): HasOne
    {
        return $this->hasOne(ProgressRecord::class);
    }
}
