<?php

namespace App\Filament\User\Resources\UserQuizResource\Pages;

use App\Filament\User\Resources\UserQuizResource;
use App\Filament\User\Resources\UserQuizResource\Widgets\ParticipantQuestionTable;
use Carbon\Carbon;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUserQuiz extends ViewRecord
{
    protected static string $resource = UserQuizResource::class;

    public function getTitle(): string
    {
        return __('messages.participant.participant_details');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        $this->record->load('questionAnswers.question', 'questionAnswers.answer', 'quiz');

        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('messages.participant.player_name') . ':'),
                        TextEntry::make('email')
                            ->label(__('messages.user.email') . ':'),
                        TextEntry::make('quiz.title')
                            ->label(__('messages.quiz.quiz') . ':')
                            ->limit(50),
                        TextEntry::make('question_count')
                            ->label(__('messages.quiz.number_of_questions') . ':')
                            ->default(fn($record) => getQuestionCount($record)),
                        TextEntry::make('started_at')
                            ->label(__('messages.participant.started_at') . ':')
                            ->date('d/m/Y h:i A')
                            ->placeholder(__('messages.common.n/a')),
                        TextEntry::make('completed_at')
                            ->label(__('messages.participant.completed_at') . ':')
                            ->date('d/m/Y h:i A')
                            ->placeholder(__('messages.common.n/a')),
                        TextEntry::make('total_time')
                            ->label(__('messages.participant.total_time') . ':')
                            ->default(fn($record) => TotalSpendTime($record)),
                    ])->columns(2),

                Livewire::make(ParticipantQuestionTable::class),


            ])->columns(1);
    }
}
