<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class GeneratedResume extends Model
{
    use HasUuids;

    protected $fillable = [
        'resume_id',
        'job_description',
        'output',
        'ats_score',
    ];

    protected $casts = [
        'output' => 'array',
        'ats_score' => 'integer',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
