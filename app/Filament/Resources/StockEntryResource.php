<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockEntryResource\Pages;
use App\Filament\Resources\StockEntryResource\RelationManagers;
use App\Models\StockEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockEntryResource extends Resource
{
    protected static ?string $model = StockEntry::class;
    protected static ?string $pluralLabel = 'Entradas de Estoque';
    protected static ?string $label = 'Entrada de Estoque';

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('entry_date')
                    ->label(__('Entry Date'))
                    ->required(),
                Forms\Components\Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->label(__('Supplier'))
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label(__('Product'))
                    ->required(),
                Forms\Components\TextInput::make('unit_cost')
                    ->label(__('Unit Cost'))
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('purchase_price')
                    ->label(__('Purchase Price'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('supplier_payment_terms')
                    ->label(__('Supplier Payment Terms'))
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->maxLength(32),
                Forms\Components\TextInput::make('invoice_series')
                    ->label(__('Invoice Series'))
                    ->maxLength(16),
                Forms\Components\TextInput::make('batch')
                    ->label(__('Batch'))
                    ->maxLength(255),
                Forms\Components\DatePicker::make('expiration_date')
                    ->label(__('Expiration Date')),
                Forms\Components\TextInput::make('warehouse')
                    ->label(__('Warehouse'))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entry_date')
                    ->label(__('Entry Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label(__('Supplier'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_cost')
                    ->label(__('Unit Cost'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->label(__('Purchase Price'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier_payment_terms')
                    ->label(__('Supplier Payment Terms'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_series')
                    ->label(__('Invoice Series'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch')
                    ->label(__('Batch'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('expiration_date')
                    ->label(__('Expiration Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warehouse')
                    ->label(__('Warehouse'))
                    ->searchable(),
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
            'index' => Pages\ListStockEntries::route('/'),
            'create' => Pages\CreateStockEntry::route('/create'),
            'edit' => Pages\EditStockEntry::route('/{record}/edit'),
        ];
    }
}
