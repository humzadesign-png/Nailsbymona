<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriberResource\Pages;
use App\Models\Subscriber;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Read-only admin view of the blog subscribe list. Mona can see who's
 * signed up, when, and from which page. CSV export is a row action so
 * she can paste the list into a future newsletter tool (Mailgun / Resend)
 * when content marketing moves out of MVP.
 *
 * Deliberately read-only — no create/edit pages because subscribers
 * sign themselves up via /subscribe. A delete row action is included
 * for GDPR-style "remove me" requests.
 */
class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;
    protected static ?string                     $navigationLabel = 'Subscribers';
    protected static string | \BackedEnum | null $navigationIcon  = 'heroicon-o-at-symbol';
    protected static string | \UnitEnum   | null $navigationGroup = 'Content';
    protected static ?int                        $navigationSort  = 4;

    public static function getNavigationBadge(): ?string
    {
        return (string) Subscriber::whereNull('unsubscribed_at')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'gray';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('subscribed_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('source')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('subscribed_at')
                    ->label('Subscribed')
                    ->dateTime('d M Y, g:ia')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unsubscribed_at')
                    ->label('Unsubscribed')
                    ->dateTime('d M Y, g:ia')
                    ->placeholder('Active')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->label('Active subscribers')
                    ->query(fn ($query) => $query->whereNull('unsubscribed_at'))
                    ->default(),
            ])
            ->headerActions([
                Actions\Action::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function (): StreamedResponse {
                        $filename = 'subscribers-' . now()->format('Y-m-d') . '.csv';
                        return response()->streamDownload(function () {
                            $out = fopen('php://output', 'w');
                            fputcsv($out, ['email', 'source', 'subscribed_at', 'unsubscribed_at']);
                            Subscriber::orderByDesc('subscribed_at')->chunk(500, function ($chunk) use ($out) {
                                foreach ($chunk as $s) {
                                    fputcsv($out, [
                                        $s->email,
                                        $s->source,
                                        optional($s->subscribed_at)->toIso8601String(),
                                        optional($s->unsubscribed_at)->toIso8601String(),
                                    ]);
                                }
                            });
                            fclose($out);
                        }, $filename, ['Content-Type' => 'text/csv']);
                    }),
            ])
            ->actions([
                Actions\DeleteAction::make()
                    ->label('Remove')
                    ->modalHeading('Remove subscriber')
                    ->modalDescription('Permanently removes this email from your list. Used for GDPR-style removal requests. There is no undo.'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscribers::route('/'),
        ];
    }
}
