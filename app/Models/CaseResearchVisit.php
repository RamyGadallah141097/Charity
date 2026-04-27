<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseResearchVisit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'visited_at' => 'date',
    ];

    public function caseResearchFile()
    {
        return $this->belongsTo(CaseResearchFile::class);
    }
}
