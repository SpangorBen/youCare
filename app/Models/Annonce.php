<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function application()
    {
        return $this->hasMany(Application::class);
    }
}
