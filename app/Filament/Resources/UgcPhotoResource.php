<?php

namespace App\Filament\Resources;

use App\Enums\UgcPlacement;
use App\Filament\Resources\UgcPhotoResource\Pages;
use App\Models\Product;
use App\Models\UgcPhoto;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class UgcPhotoResource extends Resource
{
    protected static ?string $model = UgcPhoto::class;
    protected static ?string                     $navigationLabel = 'UGC Photos';
    protected static string | \UnitEnum | null   $navigationGroup = 'Catalogue';
    protected static ?int                        $navigationSort  = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->label('')->disk('public')->square()->size(64),
                Tables\Columns\TextColumn::make('alt')->label('Caption / alt')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('placement')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        UgcPlacement::HomeGrid       => 'primary',
                        UgcPlacement::ProductCarousel => 'info',
                        UgcPlacement::BridalGallery  => 'success',
                        UgcPlacement::AboutInline    => 'warning',
                        default                      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state instanceof UgcPlacement ? $state->label() : $state),
                Tables\Columns\TextColumn::make('product.name')->label('Product')->placeholder('—'),
                Tables\Columns\IconColumn::make('face_visible')->label('Face visible')
                    ->boolean()->trueColor('danger')->falseColor('success'),
                Tables\Columns\IconColumn::make('is_published')->label('Published')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('placement')
                    ->options(collect(UgcPlacement::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()])),
                Tables\Filters\TernaryFilter::make('face_visible')->label('Face visible'),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
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
        return $schema->components([
            Forms\Components\FileUpload::make('image_path')
                ->label('Photo (hand-only — no faces)')
                ->image()->disk('public')->directory('ugc')->required()->columnSpanFull(),
            Forms\Components\TextInput::make('alt')
                ->label('Alt text / caption')
                ->helperText('Describe the nails, not the person. E.g. "Dusty rose ombre set on fair hands — Karachi customer."')
                ->required()->columnSpanFull(),
            Forms\Components\Select::make('placement')
                ->options(collect(UgcPlacement::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()]))
                ->required(),
            Forms\Components\Select::make('product_id')
                ->label('Linked product (optional)')
                ->options(Product::orderBy('name')->pluck('name', 'id'))
                ->searchable()->nullable(),
            Forms\Components\Toggle::make('face_visible')
                ->label('Face visible in photo?')
                ->helperText('If checked, this photo will NEVER be shown publicly.')
                ->default(false),
            Forms\Components\Toggle::make('is_published')->label('Published')->default(true),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUgcPhotos::route('/'),
            'create' => Pages\CreateUgcPhoto::route('/create'),
            'edit'   => Pages\EditUgcPhoto::route('/{record}/edit'),
        ];
    }
}
