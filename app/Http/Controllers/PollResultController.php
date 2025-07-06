<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollResult;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Cookie;

class PollResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create($code, Request $request)
    {

        $poll = Poll::where('unique_code', $code)->first();

        if (!$poll) {
            return redirect()->route('home');
        }
        $ip = $request->ip();

        $pollResults = PollResult::where('poll_id', $poll->id)->get();
        // $pollResult = $pollResults->where('ip_address', $ip)->first();

        $existingAnswers = json_decode(Cookie::get('poll_answers', '[]'), true);
        $pollResult = collect($existingAnswers)->firstWhere('id', $poll->id);

        $pollResultPercentage = [];

        if (!$pollResults->isEmpty()) {
            $resultsAns = $pollResults->pluck('answer')->toArray();
            $totalVotes = count($resultsAns);
            $totalPerAns = array_count_values($resultsAns);
            $options = ['option1', 'option2', 'option3', 'option4'];

            foreach ($options as $option) {
                if (!empty($poll->$option)) {
                    $label = $poll->$option;
                    $optionCount = $totalPerAns[$label] ?? 0;
                    $percentage = $totalVotes > 0 ? round(($optionCount / $totalVotes) * 100, 2) : 0;
                    $pollResultPercentage[$label] = $percentage;
                }
            }
        }

        $hasEnded = now()->greaterThan($poll->end_at);

        return view('poll_result.create', compact('poll', 'pollResult', 'pollResultPercentage', 'hasEnded'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'option' => 'required',
            'poll_id' => 'required|exists:polls,id'
        ]);

        $poll = Poll::findOrFail($request->poll_id);
        $optionField = 'option' . $request->option;


        // $existingPollResult = PollResult::where('poll_id', $poll->id)
        //     ->where('ip_address', $request->ip())
        //     ->first();

        // if ($existingPollResult) {
        //     return redirect()->back();
        // }

        $answer = $poll->$optionField;
        $location = Location::get($request->ip());

        $pollResultData = [
            'poll_id' => $poll->id,
            'answer' => $answer,
            'ip_address' => $request->ip(),
            'country' => $location ? $location->countryName : null,
        ];
        $pollResultData =  PollResult::create($pollResultData);

        $newAnswer = ['id' => $poll->id, 'ans' => $answer];
        $existingAnswers = json_decode(Cookie::get('poll_answers', '[]'), true);
        $existingAnswers[] = $newAnswer;
        Cookie::queue('poll_answers', json_encode($existingAnswers), 60 * 24 * 365);

        return redirect()->back();
    }
}
