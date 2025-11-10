<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $pluralLabel = 'Produtos';

    protected static ?string $label = 'Produto';

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('barcode')
                    ->label(__('Barcode'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit')
                    ->label(__('Unit'))
                    ->required(),
                Forms\Components\TextInput::make('category')
                    ->label(__('Category'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('brand')
                    ->label(__('Brand'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('avg_cost')
                    ->label(__('Avg Cost'))
                    ->prefix('R$')
                    ->numeric(),
                Forms\Components\TextInput::make('sale_price')
                    ->label(__('Sale Price'))
                    ->prefix('R$')
                    ->numeric(),
                Forms\Components\TextInput::make('min_stock')
                    ->label(__('Min Stock'))
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('Is Active'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->label(__('Barcode'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label(__('Unit')),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('Category'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label(__('Brand'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('avg_cost')
                    ->label(__('Avg Cost'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->label(__('Sale Price'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_stock')
                    ->label(__('Min Stock'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Is Active'))
                    ->boolean(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
