<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
  
    protected $fillable = ['context'];

    protected $casts = [
        'context'  => 'json',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
