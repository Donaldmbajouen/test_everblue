<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\Lang;

enum PlanFrequency: int implements HasColor, HasLabel
{

    case WEEKLY = 1;
    case MONTHLY = 2;
    case YEARLY = 3;

    public function getLabel(): string
    {
        return Lang::get('messages.plan.' . $this->value);
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::WEEKLY => 'info',
            self::MONTHLY => 'danger',
            self::YEARLY => 'primary',
        };
    }
}
