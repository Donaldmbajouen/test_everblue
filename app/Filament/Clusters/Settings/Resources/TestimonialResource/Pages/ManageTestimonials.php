<?php

namespace App\Filament\Clusters\Settings\Resources\TestimonialResource\Pages;

use App\Filament\Clusters\Settings\Resources\TestimonialResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTestimonials extends ManageRecords
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.home.new_testimonial'))
                ->createAnother(false)
                ->modalWidth('md')
                ->modalHeading(__('messages.home.create_testimonial'))
                ->successNotificationTitle(__('messages.home.testimonial_created_success')),
        ];
    }

    public function getTitle(): string
    {
        return __('messages.home.testimonials');
    }
}
