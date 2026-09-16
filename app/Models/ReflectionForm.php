<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReflectionForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'created_by',
        'updated_by',
        'lesson_date',
        'scale',
        'status',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'lesson_date' => 'date',
            'scale' => 'integer',
            'data' => 'array',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
