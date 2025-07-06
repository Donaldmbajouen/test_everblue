<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Actions\Subscription\CreateSubscription;
use App\Filament\Resources\UserResource;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        unset($data['password_confirmation']);
        if (isset($data['plan']) && $data['plan'] != null) {
            $plan = Plan::find($data['plan']);
        } else {
            $plan = Plan::where('assign_default', true)->first();
        }

        unset($data['plan']);
        $record = User::create($data);
        $record->assignRole(User::USER_ROLE);

        if ($plan) {
            $planData['plan'] = $plan->load('currency')->toArray();
            $planData['user_id'] = $record->id;
            $planData['payment_type'] = Subscription::TYPE_FREE;
            if ($plan->trial_days != null && $plan->trial_days > 0) {
                $planData['trial_days'] = $plan->trial_days;
            }
            CreateSubscription::run($planData);
        }
        if(isset(getSetting()->send_mail_verification) && getSetting()->send_mail_verification){
            $record->sendEmailVerificationNotification();
        }else{
            $record->email_verified_at = now();
            $record->save();
        }

        return $record;
    }

    public function getTitle(): string
    {
        return __('messages.user.create_user');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('messages.user.user_created_success');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
