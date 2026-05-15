<?php

namespace App\Filament\Resources;

use App\Enums\ExpenseCategory;
use App\Filament\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;
    protected static string | \BackedEnum | null $navigationIcon  = 'heroicon-o-banknotes';
    protected static string | \UnitEnum | null  $navigationGroup = 'Finance';
    protected static ?int                       $navigationSort  = 2;
    protected static ?string                    $navigationLabel = 'Expenses';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FormSection::make()->columns(2)->schema([
                Forms\Components\Select::make('category')
                    ->options(collect(ExpenseCategory::cases())->mapWithKeys(
                        fn ($e) => [$e->value => $e->label()]
                    ))
                    ->required(),

                Forms\Components\DatePicker::make('expense_date')
                    ->label('Date')
                    ->required()
                    ->default(now()),

                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('amount_pkr')
                    ->label('Amount (Rs.)')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->prefix('Rs.'),

                Forms\Components\Textarea::make('notes')
                    ->rows(2)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('receipt_path')
                    ->label('Receipt (optional)')
                    ->image()
                    ->disk('public')
                    ->directory('receipts')
                    ->maxSize(5120)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('expense_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('expense_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        ExpenseCategory::Materials  => 'primary',
                        ExpenseCategory::Packaging  => 'warning',
                        ExpenseCategory::Courier    => 'info',
                        ExpenseCategory::Marketing  => 'danger',
                        ExpenseCategory::Tools      => 'success',
                        default                     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('amount_pkr')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state))
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('notes')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(collect(ExpenseCategory::cases())->mapWithKeys(
                        fn ($e) => [$e->value => $e->label()]
                    )),

                Tables\Filters\Filter::make('this_month')
                    ->label('This month')
                    ->query(fn ($query) => $query->whereMonth('expense_date', now()->month)
                                                  ->whereYear('expense_date', now()->year))
                    ->toggle(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit'   => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
