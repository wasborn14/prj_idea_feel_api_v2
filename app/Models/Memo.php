<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $table = 'memos';
  
    protected $fillable = ['title'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
