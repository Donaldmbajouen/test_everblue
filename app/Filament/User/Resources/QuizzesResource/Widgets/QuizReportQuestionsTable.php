<?php

namespace App\Filament\User\Resources\QuizzesResource\Widgets;

use App\Models\Question;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Model;

class QuizReportQuestionsTable extends TableWidget
{
    public ?Model $record = null;

    protected static ?string $heading = '';

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.quiz_report.no_question'))
            ->query(Question::where('quiz_id', $this->record->id))
            ->paginated([10, 25, 50, 100])
            ->columns([
                TextColumn::make('title')
                    ->label(__('messages.common.question'))
                    ->searchable(),
                TextColumn::make('correct_answers')
                    ->label(__('messages.participant.correct_answers'))
                    ->view('filament.user.resources.quizzes-resource.widgets.questions-correct-column'),
            ]);
    }
}
