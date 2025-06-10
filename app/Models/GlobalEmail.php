<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalEmail extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'owner_type', 'owner_id'];

}
