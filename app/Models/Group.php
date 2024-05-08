<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "user_id"
    ];

    public function group_users()
    {
        return $this->hasMany(Group_user::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
