<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReceivableResource\Pages;
use App\Models\Receivable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReceivableResource extends Resource
{
    protected static ?string $model = Receivable::class;

    protected static ?string $pluralLabel = 'Recebimentos';

    protected static ?string $label = 'Recebimento';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sale_id')
                    ->label(__('Sale'))
                    ->relationship('sale', 'id'),
                Forms\Components\TextInput::make('document_no')
                    ->label(__('Document No'))
                    ->maxLength(40),
                Forms\Components\DatePicker::make('issue_date')
                    ->label(__('Issue Date')),
                Forms\Components\DatePicker::make('due_date')
                    ->label(__('Due Date'))
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label(__('Amount'))
                    ->prefix('R$')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->label(__('Status'))
                    ->options([
                        'open' => __('Open'),
                        'paid' => __('Paid'),
                        'overdue' => __('Overdue'),
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sale.id')
                    ->label(__('Sale'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_no')
                    ->label(__('Document No'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label(__('Issue Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'paid' => 'success',
                        'overdue' => 'danger',
                    }),
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
            'index' => Pages\ListReceivables::route('/'),
            'create' => Pages\CreateReceivable::route('/create'),
            'edit' => Pages\EditReceivable::route('/{record}/edit'),
        ];
    }
}
