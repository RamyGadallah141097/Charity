<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory, HasActiveScope;

    protected $guarded = [];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
