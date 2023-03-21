<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Feel extends Model
{
    use UUID, HasFactory;

    protected $table = 'feels';

    protected $fillable = ['date', 'feel', 'memo', 'is_predict', 'reason_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function feel_reason()
    {
        return $this->belongsTo('App\Models\FeelReason');
    }
}
