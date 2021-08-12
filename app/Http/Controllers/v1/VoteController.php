<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\Vote;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = QueryBuilder::for(Vote::class);

        return $query
            ->allowedFields([
                'id', 'idea_id', 'user_id', 'created_at',
            ])
            ->allowedIncludes('idea', 'user')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('idea_id'),
                AllowedFilter::exact('user_id'),
            ])
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idea = Idea::withCount('votes')->findOrFail($request->idea_id);

        $voteId = $idea->vote(auth()->user());
        return response()->json([
            'votes'   => $voteId ? $idea->votes_count + 1 : $idea->votes_count - 1,
            'vote_id' => $voteId,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
