<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Models\Sale;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $pluralLabel = 'Vendas';

    protected static ?string $label = 'Venda';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {

        $recalc = function (Set $set, Get $get) {
            $items = $get('items') ?? [];
            $subtotal = 0;
            foreach ($items as $it) {
                $qty = (int) ($it['quantity'] ?? 0);
                $price = (float) ($it['unit_price'] ?? 0);
                $disc = (float) ($it['discount'] ?? 0);
                $subtotal += max(0, ($qty * $price) - $disc);
            }

            $discountTotal = (float) ($get('discount_total') ?? 0);
            $freight = (float) ($get('freight') ?? 0);
            $extraFee = (float) ($get('extra_fee') ?? 0);
            $surchargeTotal = $freight + $extraFee;

            $grand = max(0, $subtotal - $discountTotal + $surchargeTotal);

            $set('subtotal', round($subtotal, 2));
            $set('surcharge_total', round($surchargeTotal, 2));
            $set('grand_total', round($grand, 2));
        };

        return $form
            ->schema([
                Section::make(__('Sale Data'))
                    ->schema([
                        DatePicker::make('sale_date')
                            ->label(__('Sale Date'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->label(__('Customer'))
                            ->required()
                            ->searchable(),
                        Select::make('payment_method')
                            ->label(__('Payment Method'))
                            ->options(['pix' => 'Pix', 'debit' => (__('Debit')), 'credit' => (__('Credit'))])
                            ->native(false)->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                        TextInput::make('custom_terms')
                            ->numeric()
                            ->label(__('Custom Terms'))
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->helperText(__('If filled, overrides payment_method (days).'))
                            ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                        TextInput::make('installments')
                            ->label(__('Installments'))
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                        Select::make('status')
                            ->options([
                                'draft' => (__('Draft')),
                                'confirmed' => (__('Confirmed')),
                                'cancelled' => (__('Cancelled')),
                            ])
                            ->default('confirmed')
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                    ])->columns(3),

                Section::make(__('Items'))
                    ->schema([
                        Repeater::make('items')
                            ->label(__('Items'))
                            ->relationship()
                            ->minItems(1)
                            ->defaultItems(1)
                            ->columns(4)
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->label(__('Product'))
                                    ->required()
                                    ->searchable()
                                    ->columnSpan(2)
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                                TextInput::make('quantity')
                                    ->label(__('Quantity'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                                TextInput::make('unit_price')
                                    ->label(__('Unit Price'))
                                    ->numeric()
                                    ->prefix('R$')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                                TextInput::make('discount')
                                    ->label(__('Discount'))
                                    ->numeric()
                                    ->prefix('R$')
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->helperText(__('Per-item discount'))
                                    ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                            ])
                            ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                    ]),

                Section::make('Totals')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('R$')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('discount_total')
                            ->label(__('Discount Total'))
                            ->numeric()
                            ->prefix('R$')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),

                        TextInput::make('freight')
                            ->label(__('Freight'))
                            ->numeric()
                            ->prefix('R$')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),
                        TextInput::make('extra_fee')
                            ->label(__('Extra Fee / Card Fee / Interest'))
                            ->numeric()
                            ->prefix('R$')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => $recalc($set, $get)),

                        TextInput::make('surcharge_total')
                            ->label(__('Surcharge Total'))
                            ->prefix('R$')
                            ->disabled(),
                        TextInput::make('grand_total')
                            ->label(__('Grand Total'))
                            ->prefix('R$')
                            ->disabled(),
                    ])->columns(2),
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
