<?php

namespace App\Filament\Resources;

use App\Enums\FaqCategory;
use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;
    protected static ?string                     $navigationLabel = 'FAQs';
    protected static string | \UnitEnum | null   $navigationGroup = 'Content';
    protected static ?int                        $navigationSort  = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof FaqCategory ? $state->label() : $state)
                    ->colors([
                        'primary' => FaqCategory::Sizing->value,
                        'success' => FaqCategory::Payment->value,
                        'info'    => FaqCategory::Shipping->value,
                        'warning' => FaqCategory::Returns->value,
                        'gray'    => FaqCategory::General->value,
                    ]),
                Tables\Columns\TextColumn::make('question')
                    ->searchable()->wrap()->weight('semibold'),
                Tables\Columns\TextColumn::make('answer')->limit(80)->wrap(),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(collect(FaqCategory::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()])),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Forms\Components\Select::make('category')
                ->options(collect(FaqCategory::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()]))
                ->required(),
            Forms\Components\Toggle::make('is_active')->label('Active (shown on site)')->default(true),
            Forms\Components\TextInput::make('question')->required()->columnSpanFull(),
            Forms\Components\Textarea::make('answer')->required()->rows(4)->columnSpanFull(),
            Forms\Components\TextInput::make('sort_order')
                ->numeric()->default(0)
                ->helperText('Lower numbers appear first within the same category.'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit'   => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
