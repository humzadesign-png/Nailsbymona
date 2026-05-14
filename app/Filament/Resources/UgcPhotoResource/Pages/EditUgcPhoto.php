<?php

namespace App\Filament\Resources\UgcPhotoResource\Pages;

use App\Filament\Resources\UgcPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUgcPhoto extends EditRecord
{
    protected static string $resource = UgcPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
