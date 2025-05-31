<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptAccount extends Model
{
    use HasFactory;

    public function patients()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
