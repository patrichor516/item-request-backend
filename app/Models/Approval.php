<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_request_id',
        'user_id',
        'status',
        'reason',
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
