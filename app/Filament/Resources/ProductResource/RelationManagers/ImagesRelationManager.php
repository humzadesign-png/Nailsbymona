<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Gallery Images';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\FileUpload::make('path')
                ->label('Image')
                ->image()
                ->disk('public')
                ->directory('products')
                ->imageResizeMode('cover')
                ->required()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('alt')
                ->label('Alt text')
                ->helperText('Describe the image for accessibility.')
                ->default(fn () => $this->getOwnerRecord()->name)
                ->maxLength(255)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('sort_order')
                ->label('Order')
                ->numeric()
                ->default(fn () => $this->getOwnerRecord()->images()->count())
                ->helperText('Lower numbers appear first. 0 = first.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->size(80),
                Tables\Columns\TextColumn::make('alt')
                    ->label('Alt text')
                    ->limit(40),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Add image')
                    ->after(function ($record) {
                        // Auto-set cover_image to first image if none set
                        $product = $this->getOwnerRecord();
                        if (! $product->cover_image) {
                            $product->update(['cover_image' => $record->path]);
                        }
                    }),
            ])
            ->actions([
                Actions\Action::make('set_cover')
                    ->label('Set as cover')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(function ($record) {
                        $this->getOwnerRecord()->update(['cover_image' => $record->path]);
                    })
                    ->tooltip('Use this image as the main product cover'),
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->after(function ($record) {
                        // If the deleted image was the cover, set the next image as cover
                        $product = $this->getOwnerRecord();
                        if ($product->cover_image === $record->path) {
                            $next = $product->images()->first();
                            $product->update(['cover_image' => $next?->path]);
                        }
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
