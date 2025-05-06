<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    protected $fillable = ['name' , "borrower_id", 'nationalID', 'phone', 'address', 'job' , "guarantorAge"];
    use HasFactory;
}
