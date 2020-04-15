<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\IssueCategory;
use Illuminate\Http\Request;

class IssueCategoryController extends Controller
{
    const FOLDER = "admin.issue.category";
    const TITLE = "Issue Category";
    const ROUTE = "/admin/issue-category";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\IssueCategory  $issueCategory
     * @return \Illuminate\Http\Response
     */
    public function show(IssueCategory $issueCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\IssueCategory  $issueCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(IssueCategory $issueCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\IssueCategory  $issueCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IssueCategory $issueCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\IssueCategory  $issueCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssueCategory $issueCategory)
    {
        //
    }
}
