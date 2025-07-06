<div>
    @php
        $currentPer = $getState();
        $results = json_decode($getRecord()->result, true);
        if (isset($results['current_score_percent'])) {
            $currentPer = number_format($results['current_score_percent'], 2);
            $unComplateStart = number_format($results['current_score_percent'] + $results['wrong_score_percent'], 2);
        } else {
            $unAns = 0;
            $unComplateStart = 0;
            $userId = $getRecord()->id;
            $qesAns = \App\Models\QuestionAnswer::whereIn('question_id', $quizQuestionIds)
                ->where('quiz_user_id', $userId)
                ->get();
            $qesAnsCount = $qesAns->count();

            if ($qesAnsCount < $totalQuestions) {
                $unAns = $totalQuestions - $qesAnsCount;
            }
            $unComplateQueAns = $qesAns->whereNull('completed_at')->count();
            if ($unComplateQueAns > 0) {
                $unAns += $unComplateQueAns;
            }
            $unComplatePer = ($unAns / $totalQuestions) * 100;
            $unComplateStart = 100 - $unComplatePer;
        }

        if ($unComplateStart >= $currentPer) {
            $gradient =
                'conic-gradient(#07b007a1 0%, #07b007a1 ' .
                $currentPer .
                '%, red ' .
                $currentPer .
                '%, red ' .
                $unComplateStart .
                '%, yellow ' .
                $unComplateStart .
                '%, yellow 100%)';
        } else {
            $gradient =
                'conic-gradient(#07b007a1 0%, #07b007a1 ' . $currentPer . '%, red ' . $currentPer . '%, red 100%)';
        }
    @endphp
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 flex items-center justify-center border border-gray-200 dark:border-gray-700 rounded-full"
            style="background-image: {{ $gradient }};">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-full"
                style="width: 70%; height: 70%;"></div>
        </div>
        <span class="text-lg">{{ $currentPer }}%</span>
    </div>
</div>
