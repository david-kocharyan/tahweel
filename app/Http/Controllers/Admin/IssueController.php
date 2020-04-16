<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Model\Issue;
use App\Model\IssueCategory;
use Illuminate\Http\Request;

class IssueController extends Controller
{

    const FOLDER = "admin.issue";
    const TITLE = "Issue";
    const ROUTE = "/admin/issues";

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Issue::with('category')->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = IssueCategory::all();
        $title = "Create " . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.create', compact('title', 'route', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'name' => 'required',
        ]);

        $issue = new Issue;
        $issue->category_id = $request->category;
        $issue->name = $request->name;
        $issue->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     * @param \App\Model\Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param \App\Model\Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function edit(Issue $issue)
    {
        $category = IssueCategory::all();
        $title = "Edit " . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.edit', compact('title', 'route', 'category', 'issue'));
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Issue         $issue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Issue $issue)
    {
        $request->validate([
            'category' => 'required',
            'name' => 'required',
        ]);

        $issue->category_id = $request->category;
        $issue->name = $request->name;
        $issue->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Model\Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        Issue::destroy($issue->id);
        return redirect(self::ROUTE);
    }
}
