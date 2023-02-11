<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feel extends Model
{
    use HasFactory;

    protected $table = 'feels';

    protected $fillable = ['date', 'feel', 'memo', 'is_predict'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
