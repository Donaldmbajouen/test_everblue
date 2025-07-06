<?php

namespace App\Filament\Clusters\Settings\Resources;

use App\Enums\AdminSettingSidebar;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = AdminSettingSidebar::FAQS->value;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Faq::getForm())
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.setting.no_faqs'))
            ->recordAction(null)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([5, 10, 20, 30])
            ->defaultSort('id', 'desc')
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label(__('messages.setting.faqs'))
                    ->description(function ($record) {
                        return $record->answer;
                    })
                    ->wrap()
                    ->searchable(['question', 'answer'])
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label(__('messages.common.status'))
                    ->sortable()
                    ->updateStateUsing(function ($record, $state) {
                        $record->status = $state;
                        $record->save();

                        Notification::make()
                            ->success()
                            ->title(__('messages.setting.faq_status_updated_success'))
                            ->send();

                        return $state;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('messages.common.edit'))
                    ->tooltip(__('messages.common.edit'))
                    ->modalWidth('md')
                    ->modalHeading(__('messages.setting.edit_faq'))
                    ->successNotificationTitle(__('messages.setting.faq_updated_success')),
                \App\Filament\Actions\CustomDeleteAction::make()
                    ->setCommonProperties()
                    ->modalHeading(__('messages.setting.delete_faq'))
                    ->successNotificationTitle(__('messages.setting.faq_deleted_success')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    \App\Filament\Actions\CustomDeleteBulkAction::make()
                        ->setCommonProperties()
                        ->modalHeading(__('messages.setting.delete_faqs'))
                        ->successNotificationTitle(__('messages.setting.faqs_deleted_success')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFaqs::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.setting.faqs');
    }
}
