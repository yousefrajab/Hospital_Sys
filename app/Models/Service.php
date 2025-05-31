<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Translatable;
    use HasFactory;
    public $translatedAttributes = ['name'];
    public $fillable = [ 'price', 'description', 'status', 'doctor_id'];





    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    // Relationship for Grouped Services
    public function groups()
    {
        // Assuming 'Service_Group' is your pivot table name from migration
        return $this->belongsToMany(Group::class, 'Service_Group', 'Service_id', 'Group_id')->withPivot('quantity');
    }

}
