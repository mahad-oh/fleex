<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use App\Services\VoucherService;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('serial_num')
                    ->label('Numéro de série')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code_encrypted')
                    ->label('Code')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'physical' => 'Physique',
                        'electronic' => 'E-Voucher'
                    ])
                    ->required()
                    ->default('physical'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serial_num')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'redemeed' => 'gray',
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expiration')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Création')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('activer')
                    ->hidden(fn($record) => $record->status !== 'inactive')
                    ->color('primary')
                    ->label('Activer')
                    ->icon('heroicon-o-power')
                    ->form([

                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->label('Montant de recharge')
                            ->suffix('FDJ')
                            ->required(),
                        // Forms\Components\TextInput::make('company_id')
                        //     ->label('Entreprise')
                        //     ->required(),
                    ])
                    ->action(function (array $data, Voucher $record): void {
                        $formData = $data;
                        try{
                            VoucherService::activateCard(
                                $record->serial_num,
                                $record->type,
                                $formData['amount'],
                                1
                            );
                        } catch(\Exception $e){

                        }
                    })
                    ->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null);
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
