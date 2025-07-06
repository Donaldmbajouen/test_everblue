<div>
    @php
        $unAns = 0;
        $perchantage = 0;
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

        if ($unComplatePer < 100) {
            $correctAnswers = $qesAns->where('is_correct', 1)->whereNotNull('completed_at')->count();
            $perchantage = round(($correctAnswers / $totalQuestions) * 100, 2);
            $unComplateStart = 100 - $unComplatePer;
        }

        if ($unComplateStart >= $perchantage) {
            $gradient =
                'conic-gradient(#07b007a1 0%, #07b007a1 ' .
                $perchantage .
                '%, red ' .
                $perchantage .
                '%, red ' .
                $unComplateStart .
                '%, yellow ' .
                $unComplateStart .
                '%, yellow 100%)';
        } else {
            $gradient =
                'conic-gradient(#07b007a1 0%, #07b007a1 ' . $perchantage . '%, red ' . $perchantage . '%, red 100%)';
        }
    @endphp
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 flex items-center justify-center border border-gray-200 dark:border-gray-700 rounded-full"
            style="background-image: {{ $gradient }};">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-full"
                style="width: 70%; height: 70%;"></div>
        </div>
        <span class="text-lg">{{ $perchantage }}%</span>
    </div>
</div>
