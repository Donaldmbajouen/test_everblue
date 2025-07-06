<?php

namespace App\Filament\User\Resources\QuizzesResource\Widgets;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\Quiz;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserResponsesReportTable extends BaseWidget
{

    protected static ?string $heading = 'Responses';

    protected int|string|array $columnSpan = 'full';

    // protected static ?int $sort = 4;

    public ?Model $record = null;

    public function table(Table $table): Table
    {
        $quizId = $this->record->id;

        $answersQuery = Answer::with('quizAnswers')->whereHas('question', function ($query) use ($quizId) {
            $query->where('quiz_id', $quizId);
        });

        return $table
            ->query($answersQuery)
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Answer'),
                Tables\Columns\TextColumn::make('percentage')
                    ->label('No of people')
                    ->default(fn($record) => $this->getAnswerPercentage($record)),
            ])->filters([
                Tables\Filters\SelectFilter::make('question_id')
                    ->label('Question')
                    ->options(function () use ($quizId) {
                        return Question::where('quiz_id', $quizId)->pluck('title', 'id');
                    })->default(Question::where('quiz_id', $quizId)->first()->id),

            ]);
    }

    private function getAnswerPercentage(Answer $answer): string
    {
        $totalVotes = QuestionAnswer::where('question_id', $answer->question_id)->count();

        $percentage = $totalVotes > 0 ? ($answer->quizAnswers->count() / $totalVotes) * 100 : 0;

        return $answer->quizAnswers->count() . ' (' . number_format($percentage, 2) . '%)';
    }
}
