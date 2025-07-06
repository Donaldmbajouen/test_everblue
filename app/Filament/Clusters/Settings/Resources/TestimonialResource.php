<?php

namespace App\Filament\Clusters\Settings\Resources;

use App\Enums\AdminSettingSidebar;
use App\Filament\Clusters\Settings;
use App\Filament\Clusters\Settings\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = AdminSettingSidebar::TESTIMONIALS->value;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Testimonial::getForm())
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('messages.home.no_testimonials'))
            ->recordAction(null)
            ->searchPlaceholder(__('messages.common.search'))
            ->paginated([5, 10, 20, 30])
            ->defaultSort('id', 'desc')
            ->actionsColumnLabel(__('messages.common.action'))
            ->actionsAlignment(getActiveLanguage()['code'] == 'ar' ? 'start' : 'end')
            ->columns([
                SpatieMediaLibraryImageColumn::make('icon')
                    ->collection(Testimonial::ICON)
                    ->label(__('messages.user.profile'))
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->description(function (Testimonial $record) {
                        return $record->role;
                    })
                    ->wrap()
                    ->searchable(['name', 'role'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('messages.quiz.description'))
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('messages.common.edit'))
                    ->tooltip(__('messages.common.edit'))
                    ->modalWidth('md')
                    ->modalHeading(__('messages.home.edit_testimonial'))
                    ->successNotificationTitle(__('messages.home.testimonial_updated_success')),
                \App\Filament\Actions\CustomDeleteAction::make()
                    ->setCommonProperties()
                    ->modalHeading(__('messages.home.delete_testimonial'))
                    ->successNotificationTitle(__('messages.home.testimonial_deleted_success')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    \App\Filament\Actions\CustomDeleteBulkAction::make()
                        ->setCommonProperties()
                        ->modalHeading(__('messages.home.delete_testimonials'))
                        ->successNotificationTitle(__('messages.home.testimonials_deleted_success')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTestimonials::route('/'),
        ];
    }
}
