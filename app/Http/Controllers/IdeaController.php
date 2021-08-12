<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdeaRequest;
use App\Models\Idea;
use App\Models\Status;
use Illuminate\Support\Str;
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

    public function store(IdeaRequest $request)
    {
        return Idea::create([
            'title'        => $request->title,
            'category_id'  => $request->category,
            'status_id'    => 1,
            'user_id'      => auth()->id(),
            'description'  => $request->description,
            'spam_reports' => 0,
            'slug'         => Str::slug($request->title),
        ]);
    }

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

    public function update(IdeaRequest $request, Idea $idea)
    {
        $this->authorize('update', $idea);
        $idea->update($request->only('category_id', 'description', 'title'));
        return $idea;
    }

    public function destroy(Idea $idea)
    {
        $this->authorize('delete', $idea);
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
        $this->authorize('notSpam', Idea::class);

        $idea = Idea::findOrFail(request()->id);
        $idea->update(['spam_reports' => 0]);
        return ['spam_reports' => $idea->spam_reports];
    }

    public function updateStatus(Idea $idea)
    {
        $this->authorize('updateStatus', Idea::class);

        $status = Status::getId(request()->status);
        $idea->update(['status_id' => $status]);

        $idea->load('status:id,name');
        return response()->json([
            'message' => 'Status updated',
            'status'  => $idea->status,
        ]);
    }
}
