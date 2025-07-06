<?php

namespace App\Filament\Resources;

use App\Enums\AdminSidebar;
use App\Enums\PlanFrequency;
use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;
use App\Models\Subscription;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-m-view-columns';

    protected static ?int $navigationSort = AdminSidebar::PLANS->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.plan.plans');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Plan::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.plan.no_plans'))
            ->recordUrl(false)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([10, 25, 50, 100])
            ->defaultSort('id', 'desc')
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->limit(100)
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('frequency')
                    ->formatStateUsing(function ($state) {
                        return PlanFrequency::from($state);
                    })
                    ->badge()
                    ->alignStart()
                    ->sortable()
                    ->label(__('messages.plan.frequency'))
                    ->color(function ($state) {
                        return PlanFrequency::from($state)->getColor();
                    }),
                TextColumn::make('price')
                    ->alignEnd()
                    ->label(__('messages.common.price'))
                    ->formatStateUsing(function ($record) {
                        $currencySymbol = $record->currency->symbol;
                        return getCurrencyPosition() ? $currencySymbol . ' ' . number_format($record->price ?? 0, 2) : number_format($record->price ?? 0, 2) . ' ' . $currencySymbol;
                    })
                    ->default('-')
                    ->sortable(),
                IconColumn::make('assign_default')
                    ->label(__('messages.plan.assign_default'))
                    ->boolean()
                    ->alignCenter(),
                ToggleColumn::make('status')
                    ->alignCenter()
                    ->label(__('messages.common.status'))
                    ->afterStateUpdated(fn() => Notification::make()->title(__('messages.plan.status_updated_success'))->success()->send()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('messages.common.edit'))
                    ->tooltip(__('messages.common.edit')),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading(__('messages.plan.delete_plan'))
                    ->before(function (Tables\Actions\DeleteAction $action) {
                        $subscriptionExist = Subscription::where('plan_id', $action->getRecord()->id)->exists();
                        if ($subscriptionExist) {
                            Notification::make()
                                ->title(__('messages.plan.cannot_delete_plan'))
                                ->danger()
                                ->send();
                            $action->cancel();
                        }
                    })
                    ->successNotificationTitle(__('messages.plan.plan_deleted_success')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Tables\Actions\DeleteBulkAction $action) {
                            $planIds = $action->getRecords()->pluck('id');
                            $subscriptionExist = Subscription::whereIn('plan_id', $planIds)->exists();
                            if ($subscriptionExist) {
                                Notification::make()
                                    ->title(__('messages.plan.cannot_delete_plan'))
                                    ->danger()
                                    ->send();
                                $action->cancel();
                            }
                        })
                        ->modalHeading(__('messages.plan.delete_selected_plans'))
                        ->successNotificationTitle(__('messages.plan.plans_deleted_success')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
