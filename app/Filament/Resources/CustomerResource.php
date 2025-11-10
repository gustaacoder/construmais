<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $pluralLabel = 'Clientes';

    protected static ?string $label = 'Cliente';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tax_id')
                    ->label(__('Tax ID'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label(__('Address'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->label(__('City'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->label(__('State'))
                    ->maxLength(2),
                Forms\Components\TextInput::make('zip')
                    ->label(__('Zip'))
                    ->maxLength(12),
                Forms\Components\TextInput::make('country')
                    ->label(__('Country'))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax_id')
                    ->label(__('Tax ID'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('Address'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('City'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->label(__('State'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip')
                    ->label(__('Zip'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->label(__('Country'))
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
