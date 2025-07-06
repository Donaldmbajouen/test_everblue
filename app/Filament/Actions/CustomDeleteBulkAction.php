<?php

namespace App\Filament\Actions;

use Closure;
use Filament\Tables\Actions\DeleteBulkAction;

class CustomDeleteBulkAction extends DeleteBulkAction
{
    public function setCommonProperties(string | Closure | null $url = null): static
    {
        return $this
            ->label(__('messages.common.delete_selected'))
            ->modalSubmitActionLabel(__('messages.common.confirm'))
            ->modalCancelActionLabel(__('messages.common.cancel'))
            ->modalDescription(__('messages.common.are_you_sure_you_would_like_to_do_this'))
            ->successRedirectUrl(function ($table) use ($url) {
                $getRecords = $table->getRecords();
                $currentPage = $getRecords->currentPage();
                $perPage = $getRecords->perPage();
                $totalRecords = $getRecords->total();
                $totalPages = ceil($totalRecords / $perPage);
                if ($currentPage > $totalPages) {
                    return $url . '?page=' . $totalPages;
                }
                return null;
            });
    }
}
