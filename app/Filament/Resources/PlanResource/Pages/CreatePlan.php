<?php

namespace App\Filament\Resources\PlanResource\Pages;

use App\Filament\Resources\PlanResource;
use App\Models\Plan;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePlan extends CreateRecord
{
    protected static string $resource = PlanResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        if ($data['assign_default']) {
            $existingDefaultPlan = Plan::where('assign_default', 1)->first();
            if ($existingDefaultPlan && $existingDefaultPlan->count() > 0) {
                $existingDefaultPlan->update(['assign_default' => 0]);
            }
        }

        return parent::handleRecordCreation($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('messages.plan.plan_created_success');
    }

    public function getTitle(): string
    {
        return __('messages.plan.create_plan');
    }
}
