<?php

namespace App\Filament\User\Resources\QuizzesResource\Pages;

use App\Filament\User\Resources\QuizzesResource;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;

class ViewQuizzes extends ViewRecord
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
            Action::make('report')
                ->label(__('messages.common.reports'))
                ->color('gray')
                ->icon('heroicon-o-document-chart-bar')
                ->url(QuizzesResource::getUrl('report', [$this->record->id])),
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
        return __('messages.common.overview');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        RepeatableEntry::make('Questions')
                            ->schema([
                                TextEntry::make('title')
                                    ->hiddenLabel()
                                    ->formatStateUsing(function ($state, $component) {
                                        $data = explode('.', $component->getStatePath())[1];
                                        $index = $data + 1;
                                        return $index . '. ' . $state;
                                    })->weight(FontWeight::Bold),
                                RepeatableEntry::make('Answers')
                                    ->schema([
                                        TextEntry::make('title')
                                            ->label('Option')
                                            ->hiddenLabel()
                                            ->iconPosition(IconPosition::After)
                                            ->icon(fn($record) => $record['is_correct'] == 1 ? 'heroicon-m-check' : '')
                                            ->iconColor(fn($record) => $record['is_correct'] == 1 ? 'success' : '')
                                    ])->grid(2)
                                    ->contained(false)
                                    ->hiddenLabel()
                                    ->visible(fn($record) => !empty($record['answers'])),
                            ])->hiddenLabel()->contained(false)
                    ])
            ])->columns(1);
    }
}
