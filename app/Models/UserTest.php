<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'booked_time'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_tests');
    }

    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
}
