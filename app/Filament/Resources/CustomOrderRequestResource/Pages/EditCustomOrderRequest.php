<?php

namespace App\Filament\Resources\CustomOrderRequestResource\Pages;

use App\Filament\Resources\CustomOrderRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditCustomOrderRequest extends EditRecord
{
    protected static string $resource = CustomOrderRequestResource::class;

    protected function getHeaderActions(): array
    {
        return CustomOrderRequestResource::linkActions();
    }
}
