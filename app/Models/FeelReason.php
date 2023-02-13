<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeelReason extends Model
{
    use HasFactory;

    protected $table = 'feel_reasons';

    protected $fillable = ['title'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
