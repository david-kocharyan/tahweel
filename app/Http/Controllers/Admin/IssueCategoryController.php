<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\IssueCategory;
use Illuminate\Http\Request;

class IssueCategoryController extends Controller
{
    const FOLDER = "admin.issue.category";
    const TITLE = "Issue Category";
    const ROUTE = "/admin/issue-categories";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = IssueCategory::all();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER.'.index', compact('title', 'route', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Create ".self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER.'.create', compact('title', 'route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required"
        ]);

        $category = new IssueCategory;
        $category->name = $request->name;
        $category->save();

        return redirect(self::ROUTE);
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
        $title = "Edit ".self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER.'.edit', compact('title', 'route', 'issueCategory'));
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
        $request->validate([
            "name" => "required"
        ]);

        $issueCategory->name = $request->name;
        $issueCategory->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\IssueCategory  $issueCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssueCategory $issueCategory)
    {
        IssueCategory::destroy($issueCategory->id);
        return redirect(self::ROUTE);
    }
}
