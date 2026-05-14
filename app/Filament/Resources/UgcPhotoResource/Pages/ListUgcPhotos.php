<?php

namespace App\Filament\Resources\UgcPhotoResource\Pages;

use App\Filament\Resources\UgcPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUgcPhotos extends ListRecords
{
    protected static string $resource = UgcPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
