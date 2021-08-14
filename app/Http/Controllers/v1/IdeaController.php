<?php

namespace App\Http\Controllers\v1;

use App\Events\IdeaDeletedEvent;
use App\Http\Controllers\Controller;
use App\Http\Queries\v1\IdeaQuery;
use App\Http\Requests\v1\IdeaRequest;
use App\Models\Idea;
use App\Models\Status;
use Illuminate\Support\Str;

class IdeaController extends Controller
{
    public function index(IdeaQuery $query)
    {
        return $query->paginate(request()->limit);
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

    public function show(IdeaQuery $query, $slug)
    {
        return $query->where('slug', $slug)->firstOrFail();
    }

    public function update(IdeaRequest $request, Idea $idea)
    {
        $this->authorize('update', $idea);
        $idea->update($request->only('category_id', 'description', 'title'));
        return $idea->only('slug');
    }

    public function destroy(Idea $idea)
    {
        $this->authorize('delete', $idea);

        IdeaDeletedEvent::dispatch($idea);
        $idea->delete();
        return response()->json('Idea deleted');
    }

    public function markAsSpam(Idea $idea)
    {
        $idea->increment('spam_reports');
        return ['spam_reports' => $idea->spam_reports];
    }

    public function notSpam(Idea $idea)
    {
        $this->authorize('notSpam', Idea::class);

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
