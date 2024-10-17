<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    protected $fillable = [
        'test_name',
        'test_description',
        'test_type',
        'price',
        'duration',
        'available_slots',
        'test_code',
        'instructions',
        'location_id',
        'preparation_required',
        'status',
        'max_bookings_per_slot',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_tests');
    }
}
