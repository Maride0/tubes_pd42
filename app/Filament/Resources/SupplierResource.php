<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_supplier')
                    ->default(fn () => Supplier::getKodeSupplier()) // Ambil kode otomatis
                    ->label('Kode Supplier')
                    ->required()
                    ->readonly()
            ,
                TextInput::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->required()
                    ->placeholder('Masukkan nama supplier')
            ,
                TextInput::make('no_telp')
                    ->label('Nomor Telepon')
                    ->required()
                    ->placeholder('Masukkan nomor telpon')
            ,
                TextInput::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->placeholder('Masukkan Alamat Supplier')
            ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_supplier')->searchable(),
                TextColumn::make('nama_supplier')->searchable()->sortable(),
                TextColumn::make('no_telp'),
                TextColumn::make('alamat'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}