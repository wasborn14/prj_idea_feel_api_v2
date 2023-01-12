<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $table = 'memos';
  
    protected $fillable = ['title', 'json'];

    protected $casts = [
        'json'  => 'json',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function child_memos()
    {
        return $this->hasMany('App\Models\Memo', 'parent_id');
    }
}
