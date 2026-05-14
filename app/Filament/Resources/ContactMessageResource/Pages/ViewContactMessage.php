<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mark_read')
                ->label('Mark as read')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn () => ! $this->record->is_read)
                ->action(function () {
                    $this->record->update(['is_read' => true]);
                    $this->refreshFormData(['is_read']);
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterMount(): void
    {
        // Auto-mark as read when opened
        if (! $this->record->is_read) {
            $this->record->update(['is_read' => true]);
        }
    }
}
