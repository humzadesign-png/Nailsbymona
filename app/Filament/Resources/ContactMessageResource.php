<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas\Components\Section as InfoSection;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    protected static ?string                     $navigationLabel = 'Messages';
    protected static string | \UnitEnum | null   $navigationGroup = 'Content';
    protected static ?int                        $navigationSort  = 3;

    public static function getNavigationBadge(): ?string
    {
        return (string) ContactMessage::where('is_read', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\IconColumn::make('is_read')
                    ->label('')->boolean()
                    ->trueIcon('heroicon-s-check-circle')->falseIcon('heroicon-s-envelope')
                    ->trueColor('success')->falseColor('warning')->width('40px'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight(fn (ContactMessage $r) => $r->is_read ? 'normal' : 'semibold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()->copyable()
                    ->description(fn (ContactMessage $r) => $r->phone),
                Tables\Columns\TextColumn::make('subject')->limit(40),
                Tables\Columns\TextColumn::make('message')->limit(60)->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')->dateTime('d M Y, g:ia')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')->label('Read'),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\Action::make('mark_read')
                    ->label('Mark read')->icon('heroicon-o-check')->color('success')
                    ->visible(fn (ContactMessage $r) => ! $r->is_read)
                    ->action(function (ContactMessage $r) {
                        $r->update(['is_read' => true]);
                        Notification::make()->title('Marked as read.')->success()->send();
                    }),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            InfoSection::make('From')->columns(2)->schema([
                Infolists\Components\TextEntry::make('name')->weight('bold'),
                Infolists\Components\TextEntry::make('email')->copyable(),
                Infolists\Components\TextEntry::make('phone')->copyable()->placeholder('—'),
                Infolists\Components\TextEntry::make('created_at')->dateTime('d M Y, g:ia')->label('Received'),
            ]),
            InfoSection::make('Message')->schema([
                Infolists\Components\TextEntry::make('subject')->weight('semibold'),
                Infolists\Components\TextEntry::make('message')->columnSpanFull(),
            ]),
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Toggle::make('is_read')->label('Mark as read'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view'  => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
