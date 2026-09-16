<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'created_by',
        'updated_by',
        'test_date',
        'result',
        'status',
        'signature_path',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'test_date' => 'date',
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
