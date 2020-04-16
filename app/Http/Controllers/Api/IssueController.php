<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Issue;
use App\Model\IssueCategory;
use Illuminate\Http\Request;
use App\helpers\ResponseHelper;

class IssueController extends Controller
{
    public function index()
    {
        $issues = IssueCategory::selectRaw("id, name")->with(["issues" => function($query) {
            $query->selectRaw("id, category_id, name");
        }])->get();
        $resp = array(
            "issues" => $issues
        );
        return ResponseHelper::success($resp);
    }
}
