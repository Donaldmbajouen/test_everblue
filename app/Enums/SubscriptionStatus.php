<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\Lang;

enum SubscriptionStatus: int implements HasLabel
{

    case INACTIVE = 0;
    case ACTIVE = 1;
    case PENDING = 2;
    case REJECTED = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INACTIVE => Lang::get('messages.common.inactive'),
            self::ACTIVE => Lang::get('messages.common.active'),
            self::PENDING => Lang::get('messages.common.pending'),
            self::REJECTED => Lang::get('messages.common.rejected'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::INACTIVE => 'danger',
            self::ACTIVE => 'success',
            self::PENDING => 'warning',
            self::REJECTED => 'danger',
        };
    }
}
