<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanbakuResource\Pages;
use App\Models\Bahanbaku;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class BahanbakuResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_bahan_baku')
                ->default(fn () => BahanBaku::getKodeBahanBaku()) // Ambil default dari method getKodeBahanBaku
                ->label('Kode Bahan Baku')
                ->required()
                ->readonly() 
                ,
                TextInput::make('nama_bahan_baku')
                    ->required()
                    ->placeholder('Masukkan nama bahan baku')
                ,
                Select::make('satuan')
                    ->required()
                    ->options([
                        'Gram' => 'gr',
                        'Kilogram' => 'kg',
                        'Mililiter' => 'ml',
                        'Liter' => 'l',
                        'Pack' => 'pcs',
                    ])
                    ->placeholder('Pilih satuan bahan baku')
                ,
                TextInput::make('harga_satuan')
                ->required()
                ->minValue(0) // Nilai minimal 0 (opsional jika tidak ingin ada harga negatif)
                ->reactive() // Menjadikan input reaktif terhadap perubahan
                ->extraAttributes(['id' => 'harga_satuan']) // Tambahkan ID untuk pengikatan JavaScript
                ->placeholder('Masukkan harga satuan') // Placeholder untuk membantu pengguna
                ->live()
                ->afterStateUpdated(fn ($state, callable $set) => 
                    $set('harga_satuan', number_format((int) str_replace('.', '', $state), 0, ',', '.'))
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_bahan_baku')->searchable(),
                TextColumn::make('nama_bahan_baku')->searchable(),
                TextColumn::make('satuan')->searchable(),
                TextColumn::make('harga_satuan')->searchable()
                ->label('Harga Satuan')
                ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                ->extraAttributes(['class' => 'text-right']) // Tambahkan kelas CSS untuk rata kanan
                ->sortable()
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
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
            'index' => Pages\ListBahanbakus::route('/'),
            'create' => Pages\CreateBahanbaku::route('/create'),
            'edit' => Pages\EditBahanbaku::route('/{record}/edit'),
        ];
    }
}