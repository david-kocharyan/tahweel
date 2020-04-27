<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InspectionForm extends Model
{

    const NO_WARRANTY = 0;
    const NORMAL_WARRANTY = 1;
    const FULL_WARRANTY = 2;

    const DECLINED = 0;
    const APPROVED = 1;
}
