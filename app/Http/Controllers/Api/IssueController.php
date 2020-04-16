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
        $issues = IssueCategory::with("issues")->get();
        $resp = array(
            "issues" => $issues
        );
        return ResponseHelper::success($resp);
    }
}
