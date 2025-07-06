<?php

namespace App\Filament\Resources;

use App\Enums\AdminSidebar;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?int $navigationSort = AdminSidebar::CATEGORIES->value;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Category::getForm())
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.quiz.no_categories'))
            ->recordAction(null)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([10, 25,50, 100])
            ->defaultSort('id', 'desc')
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->wrap()
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('messages.common.edit'))
                    ->tooltip(__('messages.common.edit'))
                    ->modalWidth('md')
                    ->modalHeading(__('messages.quiz.edit_category'))
                    ->successNotificationTitle(__('messages.quiz.category_updated_successfully')),
                \App\Filament\Actions\CustomDeleteAction::make()
                    ->setCommonProperties()
                    ->modalHeading(__('messages.quiz.delete_category'))
                    ->successNotificationTitle(__('messages.quiz.category_deleted_success')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    \App\Filament\Actions\CustomDeleteBulkAction::make()
                        ->setCommonProperties()
                        ->modalHeading(__('messages.quiz.delete_categories'))
                        ->successNotificationTitle(__('messages.quiz.categories_deleted_success')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
