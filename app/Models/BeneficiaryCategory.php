<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryCategory extends Model
{
    use HasFactory, HasActiveScope;

    protected $guarded = [];
}
