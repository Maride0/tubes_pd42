<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaResource\Pages;
use App\Filament\Resources\CoaResource\RelationManagers;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput; //kita menggunakan textinput
use Filament\Forms\Components\Select; //buat dropdown

class CoaResource extends Resource
{
    protected static ?string $model = Coa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('header_akun')
                        ->required()
                        ->placeholder('Masukkan header akun')
                    ,
                    TextInput::make('kode_akun')
                        ->required()
                        ->placeholder('Masukkan kode akun')
                    ,
                    TextInput::make('nama_akun')
                        ->autocapitalize('words')
                        ->label('Nama akun')
                        ->required()
                        ->placeholder('Masukkan nama akun')
                    ,
                    Select::make('posisi_dr_cr')
                    ->label('Posisi Debit Kredit')
                    ->required()
                    ->options([
                        'debit' => 'Debit',
                        'kredit' => 'Kredit',
                    ])
                    ->placeholder('Pilih Posisi Debit Kredit')
                    ,
                    TextInput::make('saldo_awal')
                        ->required()
                        ->minValue(0) // Nilai minimal 0 (opsional jika tidak ingin ada harga negatif)
                        ->reactive() // Menjadikan input reaktif terhadap perubahan
                        ->extraAttributes(['id' => 'saldo_awal']) // Tambahkan ID untuk pengikatan JavaScript
                        ->placeholder('Masukkan saldo Awal') // Placeholder untuk membantu pengguna
                        ->live()
                        ->afterStateUpdated(fn ($state, callable $set) => 
                            $set('saldo_awal', number_format((int) str_replace('.', '', $state), 0, ',', '.'))
                          )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('header_akun'),
                TextColumn::make('kode_akun'),
                TextColumn::make('nama_akun'),
                TextColumn::make('posisi_dr_cr'),
                TextColumn::make('saldo_awal')
                ->label('Saldo Awal')
                ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                ->extraAttributes(['class' => 'text-right']) // Tambahkan kelas CSS untuk rata kanan
                ->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('header_akun')
                ->options([
                    1 => 'Aset/Aktiva',
                    2 => 'Utang',
                    3 => 'Modal',
                    4 => 'Pendapatan',
                    5 => 'Beban',
                ])
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
            'index' => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit' => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}