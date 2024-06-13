<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'quantity',  
        'status',
        'role',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Approval model
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    // Define the relationship with the Transfer model
    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }
}
