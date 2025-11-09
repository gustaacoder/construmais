<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;
    protected static ?string $pluralLabel = 'Vendas';
    protected static ?string $label = 'Venda';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('sale_date')
                    ->label(__('Sale Date'))
                    ->required(),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label(__('Customer'))
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->label(__('Payment Method')),
                Forms\Components\TextInput::make('custom_terms')
                    ->label(__('Custom Terms'))
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('subtotal')
                    ->label(__('Subtotal'))
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('discount_total')
                    ->label(__('Discount Total'))
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('surcharge_total')
                    ->label(__('Surcharge Total'))
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('grand_total')
                    ->label(__('Grand Total'))
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('installments')
                    ->label(__('Installments'))
                    ->numeric(),
                Forms\Components\DatePicker::make('due_date')
                    ->label(__('Due Date')),
                Forms\Components\Textarea::make('notes')
                    ->label(__('Notes'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sale_date')
                    ->label(__('Sale Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('Customer'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label(__('Payment Method')),
                Tables\Columns\TextColumn::make('custom_terms')
                    ->label(__('Custom Terms'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label(__('Subtotal'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_total')
                    ->label(__('Discount Total'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('surcharge_total')
                    ->label(__('Surcharge Total'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label(__('Grand Total'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('installments')
                    ->label(__('Installments'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
