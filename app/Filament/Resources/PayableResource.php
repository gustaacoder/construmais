<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayableResource\Pages;
use App\Filament\Resources\PayableResource\RelationManagers;
use App\Models\Payable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayableResource extends Resource
{
    protected static ?string $model = Payable::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-down';

    protected static ?string $pluralLabel = 'Pagamentos';
    protected static ?string $label = 'Pagamento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('stock_entry_id')
                    ->label(__('Stock Entry'))
                    ->relationship('stockEntry', 'id'),
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
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stockEntry.id')
                    ->numeric()
                    ->label(__('Stock Entry'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_no')
                    ->searchable()
                    ->label(__('Document No')),
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
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListPayables::route('/'),
            'create' => Pages\CreatePayable::route('/create'),
            'edit' => Pages\EditPayable::route('/{record}/edit'),
        ];
    }
}
