<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'user_id',
        'transfer_proof',
    ];

    // Define the relationship with the Request model
    public function request()
    {
        return $this->belongsTo(ItemRequest::class);
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
