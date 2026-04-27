<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseResearchFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'date',
        'expected_end_at' => 'date',
        'completed_at' => 'date',
        'actual_end_at' => 'date',
        'provided_documents' => 'array',
        'missing_documents' => 'array',
        'attachments' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function researcher()
    {
        return $this->belongsTo(SocialResearcher::class, 'social_researcher_id');
    }

    public function visits()
    {
        return $this->hasMany(CaseResearchVisit::class)->orderBy('visited_at', 'desc');
    }

    public function getStatusAttribute($value)
    {
        if ($value !== 'completed' && $this->expected_end_at && now()->startOfDay()->gt($this->expected_end_at)) {
            return 'delayed';
        }
        return $value;
    }
}
