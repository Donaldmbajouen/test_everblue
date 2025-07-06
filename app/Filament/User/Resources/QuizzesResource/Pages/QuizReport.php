<?php

namespace App\Filament\User\Resources\QuizzesResource\Pages;

use App\Filament\User\Resources\QuizzesResource;
use App\Filament\User\Resources\QuizzesResource\Widgets\DifficultQuestion;
use App\Filament\User\Resources\QuizzesResource\Widgets\QuizReportOverview;
use App\Filament\User\Resources\QuizzesResource\Widgets\QuizReportQuestionsTable;
use App\Filament\User\Resources\QuizzesResource\Widgets\TopScoringParticipant;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class QuizReport extends ViewRecord
{
    protected static string $resource = QuizzesResource::class;


    protected function getActions(): array
    {
        return [
            Action::make('leaderboard')
                ->label(__('messages.quiz_report.leaderboard'))
                ->color('gray')
                ->icon('heroicon-o-trophy')
                ->url(QuizzesResource::getUrl('leaderboard', [$this->record->id])),
            Action::make('overview')
                ->label(__('messages.common.overview'))
                ->color('gray')
                ->icon('heroicon-o-eye')
                ->url(QuizzesResource::getUrl('view', [$this->record->id])),
            Action::make('participant')
                ->label(__('messages.participant.participants'))
                ->color('gray')
                ->icon('heroicon-o-users')
                ->url(QuizzesResource::getUrl('participant', [$this->record->id])),
            Actions\EditAction::make()
                ->label(__('messages.common.edit')),
        ];
    }

    public function getTitle(): string
    {
        return __('messages.common.reports');
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Livewire::make(QuizReportOverview::class)
                    ->columnSpanFull(),
                Livewire::make(DifficultQuestion::class),
                Livewire::make(TopScoringParticipant::class),
                Livewire::make(QuizReportQuestionsTable::class)
                    ->columnSpanFull(),
            ])->columns(2);
    }
}
