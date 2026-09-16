<?php

namespace App\Filament\Resources\CustomOrderRequestResource\Pages;

use App\Filament\Resources\CustomOrderRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomOrderRequest extends CreateRecord
{
    protected static string $resource = CustomOrderRequestResource::class;

    protected static ?string $title = 'New custom order link';

    /** Land on the edit page, where the link + "Send on WhatsApp" button live. */
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Link ready — send it to the customer on WhatsApp.';
    }
}
