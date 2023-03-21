<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Idea extends Model
{
    use UUID, HasFactory;

    protected $table = 'ideas';
  
    protected $fillable = ['idea_list'];

    protected $casts = [
        'idea_list'  => 'json',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
