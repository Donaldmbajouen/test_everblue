<div>
    @php
        $perchantage = 0;
        $quizType = $getRecord()->quiz->quiz_type ?? 0;
        $questionId = $getRecord()->id;
        $queAnsUser = \App\Models\QuestionAnswer::where('question_id', $questionId)
            ->whereNotNull('completed_at')
            ->get();
        $queAnsUserCount = $queAnsUser->count();
        $currentAns = $queAnsUser->where('is_correct', 1)->count();
        if ($quizType == \App\Models\Quiz::MULTIPLE_CHOICE) {
            $queAnsUserCount = 0;
            $currentAns = 0;
            foreach ($queAnsUser as $questionAnswer) {
                $multiAnswer = $questionAnswer->multi_answer;
                if ($multiAnswer) {
                    foreach ($multiAnswer as $key => $answerId) {
                        $answer = \App\Models\Answer::find($answerId)?->toArray();
                        $queAnsUserCount++;
                        if ($answer && ($answer['is_correct'] ?? false)) {
                            $currentAns++;
                        }
                    }
                }
            }
        }
        if ($queAnsUserCount > 0) {
            $perchantage = round(($currentAns / $queAnsUserCount) * 100, 2);
        }

        $gradient =
            'conic-gradient(#07b007a1 0%, #07b007a1 ' . $perchantage . '%, red ' . $perchantage . '%, red 100%)';
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
