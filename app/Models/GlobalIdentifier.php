<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalIdentifier extends Model
{
    use HasFactory;
    protected $table = 'global_identifiers'; // تحديد اسم الجدول صراحة
    protected $fillable = ['national_id', 'owner_type', 'owner_id'];

    public function owner()
    {
        return $this->morphTo(); // إذا أردت علاقة للوصول للمالك
    }
}
