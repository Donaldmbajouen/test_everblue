<?php

namespace App\Filament\User\Resources\QuizzesResource\Widgets;

use App\Models\UserQuiz;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class ParticipanrUserTable extends BaseWidget
{

    protected int|string|array $columnSpan = 'full';

    protected function getTableHeading(): string  | null
    {
        return '';
    }



    public ?Model $record = null;

    public function table(Table $table): Table
    {
        $quizId = $this->record?->id;

        $answersQuery = UserQuiz::whereHas('quiz', function ($query) use ($quizId) {
            $query->where('quiz_id', $quizId);
        });

        return $table
            ->query($answersQuery)
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('quiz_id')
                    ->label('Answered')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn($record) => getQuestionCount($record)),
                TextColumn::make('started_at')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('j M Y h:i A') : '-')
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('j M Y h:i A') : '-')
                    ->sortable()
                    ->placeholder(__('messages.common.n/a')),
            ])->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.user.resources.user-quizzes.view', $record->id))
                    ->openUrlInNewTab(),
            ])->filters([
                Filter::make('started_at')
                    ->form([
                        DatePicker::make('started_at')
                            ->label('Started At')
                            ->displayFormat('d M Y')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['started_at'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('started_at', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['started_at'] ?? null) {
                            $indicators['started_at'] = 'Started on ' . Carbon::parse($data['started_at'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ]);
    }
}
