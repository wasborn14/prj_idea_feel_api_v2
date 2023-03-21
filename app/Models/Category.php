<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Category extends Model
{
    use UUID, HasFactory;

    protected $table = 'categories';
  
    protected $fillable = ['category_list'];

    protected $casts = [
        'category_list'  => 'json',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
