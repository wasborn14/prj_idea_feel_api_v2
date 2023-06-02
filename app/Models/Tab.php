<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Tab extends Model
{
    use UUID, HasFactory;

    protected $table = 'tabs';
  
    protected $fillable = ['tab_list'];

    protected $casts = [
        'tab_list'  => 'json',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
