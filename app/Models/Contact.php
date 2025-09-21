<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $fillable = ['name', 'email', 'phone', 'message'];
}


=======
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'message',
        'reply',
        'replied_at',
    ];

    // Quan hệ: mỗi liên hệ thuộc về một user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8
