<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IssueCategory extends Model
{
    public function issues()
    {
        return $this->hasMany("App\Model\Issue", "category_id", "id");
    }
}
