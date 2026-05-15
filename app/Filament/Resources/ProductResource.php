<?php

namespace App\Filament\Resources;

use App\Enums\ProductTier;
use App\Enums\StockStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static string | \UnitEnum | null   $navigationGroup = 'Catalogue';
    protected static ?int    $navigationSort  = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('')->disk('public')->square()->size(56),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()->weight('semibold')
                    ->description(fn (Product $r) => $r->slug),

                Tables\Columns\TextColumn::make('tier')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        ProductTier::Everyday    => 'gray',
                        ProductTier::Signature   => 'primary',
                        ProductTier::Glam        => 'warning',
                        ProductTier::BridalSingle => 'success',
                        ProductTier::BridalTrio  => 'danger',
                        default                  => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state instanceof ProductTier ? $state->label() : $state),

                Tables\Columns\TextColumn::make('price_pkr')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_status')
                    ->label('Stock')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        StockStatus::InStock     => 'success',
                        StockStatus::MadeToOrder => 'warning',
                        StockStatus::SoldOut     => 'danger',
                        default                  => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state instanceof StockStatus ? $state->label() : $state),

                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lead_time_days')->label('Lead (days)')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tier')
                    ->options(collect(ProductTier::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()])),
                Tables\Filters\SelectFilter::make('stock_status')
                    ->options(collect(StockStatus::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()])),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
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
            FormSection::make('Product details')->columns(2)->schema([
                Forms\Components\TextInput::make('name')
                    ->required()->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state)))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->required()->unique(ignoreRecord: true)
                    ->helperText('Auto-filled from name. Must be unique.'),
                Forms\Components\Select::make('tier')
                    ->options(collect(ProductTier::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()]))
                    ->required(),
                Forms\Components\TextInput::make('price_pkr')
                    ->label('Price (PKR)')->numeric()->required()->prefix('Rs.'),
                Forms\Components\Select::make('stock_status')
                    ->options(collect(StockStatus::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()]))
                    ->required()->default(StockStatus::MadeToOrder->value),
                Forms\Components\TextInput::make('lead_time_days')
                    ->label('Lead time (days)')->numeric()->default(7),
                Forms\Components\Textarea::make('description')->rows(4)->columnSpanFull(),
                Forms\Components\FileUpload::make('cover_image')
                    ->label('Cover image')->image()->disk('public')->directory('products')->columnSpanFull(),
            ]),
            FormSection::make('Visibility')->columns(2)->schema([
                Forms\Components\Toggle::make('is_active')
                    ->label('Active (visible on shop)')->default(true),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Featured (shown on homepage)'),
            ]),
            FormSection::make('SEO')->collapsed()->schema([
                Forms\Components\TextInput::make('meta_title')
                    ->label('Meta title')->helperText('Leave blank to use product name.'),
                Forms\Components\Textarea::make('meta_description')
                    ->label('Meta description')->rows(2)->maxLength(160),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
