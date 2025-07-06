<?php

namespace App\Filament\Clusters\Settings\Resources\FaqResource\Pages;

use App\Filament\Clusters\Settings\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFaqs extends ManageRecords
{
    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.setting.new_faq'))
                ->createAnother(false)
                ->modalWidth('md')
                ->modalHeading(__('messages.setting.create_faq'))
                ->successNotificationTitle(__('messages.setting.faq_created_success')),
        ];
    }

    public function getTitle(): string
    {
        return __('messages.setting.faqs');
    }
}
