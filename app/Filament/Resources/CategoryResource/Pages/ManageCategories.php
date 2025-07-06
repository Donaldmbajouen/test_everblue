<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.quiz.new_category'))
                ->createAnother(false)
                ->modalWidth('md')
                ->modalHeading(__('messages.quiz.create_category'))
                ->successNotificationTitle(__('messages.quiz.category_created_success')),
        ];
    }

    public function getTitle(): string
    {
        return __('messages.quiz.categories');
    }
}
