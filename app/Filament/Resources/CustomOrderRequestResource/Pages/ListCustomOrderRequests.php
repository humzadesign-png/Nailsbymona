<?php

namespace App\Filament\Resources\CustomOrderRequestResource\Pages;

use App\Filament\Resources\CustomOrderRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomOrderRequests extends ListRecords
{
    protected static string $resource = CustomOrderRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New custom order link'),
        ];
    }
}
