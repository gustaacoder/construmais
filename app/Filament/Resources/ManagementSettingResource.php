<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManagementSettingResource\Pages;
use App\Filament\Resources\ManagementSettingResource\RelationManagers;
use App\Models\ManagementSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManagementSettingResource extends Resource
{
    protected static ?string $model = ManagementSetting::class;

    protected static ?string $pluralLabel = 'Configurações';
    protected static ?string $label = 'Configuração de Gerenciamento';

    protected static ?string $navigationIcon = 'heroicon-m-cog-6-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('expense_forecast')
                    ->label(__('Expense Forecast'))
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('reference_period')
                    ->label(__('Reference Period'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('credit_card_default_terms')
                    ->label(__('Credit Card Default Terms'))
                    ->required()
                    ->numeric()
                    ->default(30),
                Forms\Components\TextInput::make('pix_debit_default_terms')
                    ->label(__('PIX Debit Default Terms'))
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('safety_stock_days')
                    ->label(__('Safety Stock Days'))
                    ->required()
                    ->numeric()
                    ->default(7),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expense_forecast')
                    ->label(__('Expense Forecast'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference_period')
                    ->label(__('Reference Period'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('credit_card_default_terms')
                    ->label(__('Credit Card Default Terms'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pix_debit_default_terms')
                    ->label(__('PIX Debit Default Terms'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('safety_stock_days')
                    ->label(__('Safety Stock Days'))
                    ->numeric()
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
            'index' => Pages\ListManagementSettings::route('/'),
            'create' => Pages\CreateManagementSetting::route('/create'),
            'edit' => Pages\EditManagementSetting::route('/{record}/edit'),
        ];
    }
}
