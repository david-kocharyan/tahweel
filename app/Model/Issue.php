<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{

    public function category()
    {
        return $this->belongsTo('App\Model\IssueCategory', 'category_id', 'id');
    }
}
