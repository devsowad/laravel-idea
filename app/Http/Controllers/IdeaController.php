<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Status;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IdeaController extends Controller
{
    public function index()
    {
        $query = QueryBuilder::for(Idea::class);

        return $query
            ->allowedFields(collect([
                'title', 'spam_reports', 'created_at', 'slug', 'description', 'id',
                'category_id', 'category.id', 'category.name',
                'status_id', 'status.id', 'status.name',
                'user_id', 'user.id', 'user.name', 'user.email',
            ])->all())
            ->allowedIncludes(['category', 'status', 'user'])
            ->defaultSorts('-created_at')
            ->allowedSorts([
                'title', 'spam_reports', 'created_at',
            ])
            ->allowedFilters([
                'title', AllowedFilter::exact('spam_reports'),
                'category.name',
                AllowedFilter::exact('status.name'),
            ])
            ->withCount('comments', 'votes')
            ->paginate(request()->limit);
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
     * @param  \App\Models\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $query = QueryBuilder::for(Idea::class);

        return $query->where('slug', $slug)
            ->allowedFields([
                'title', 'spam_reports', 'created_at', 'slug', 'description', 'id',
                'category_id', 'category.id', 'category.name',
                'status_id', 'status.id', 'status.name',
                'user_id', 'user.id', 'user.name', 'user.email',
                'comments.body', 'comments.created_at', 'comments.id', 'comments.idea_id', 'comments.spam_reports',
                'comments.user_id', 'comments.user.name', 'comments.user.id',
            ])
            ->withCount('comments', 'votes')
            ->allowedIncludes(['category', 'status', 'user', 'comments', 'comments.user'])
            ->firstOrFail();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Idea $idea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Idea $idea)
    {
        return $idea->delete();
    }

    public function markAsSpam()
    {
        $idea = Idea::findOrFail(request()->id);
        $idea->increment('spam_reports');
        return ['spam_reports' => $idea->spam_reports];
    }

    public function notSpam()
    {
        $idea = Idea::findOrFail(request()->id);
        $idea->update(['spam_reports' => 0]);
        return ['spam_reports' => $idea->spam_reports];
    }

    public function updateStatus(Idea $idea)
    {
        $status = Status::getId(request()->status);
        $idea->update(['status_id' => $status]);

        $idea->load('status:id,name');
        return response()->json([
            'message' => 'Status updated',
            'status'  => $idea->status,
        ]);
    }
}
