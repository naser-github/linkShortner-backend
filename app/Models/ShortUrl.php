<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    public function urlTag()
    {
        return $this->belongsTo('App\Models\UrlTag', 'fk_tag_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'fk_user_id');
    }
}
