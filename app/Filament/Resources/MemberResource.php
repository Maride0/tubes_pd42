<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// untuk form dan table
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;

// untuk model ke user
use App\Models\User;

class MemberResource extends Resource
{
    protected static ?string $model = member::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';

    protected static ?string $navigationLabel = 'Member';

    protected static ?string $navigationGroup = 'Masterdata';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 //direlasikan ke tabel user
                 Select::make('id')
                 ->label('Id User')
                 ->relationship('user', 'email', fn (Builder $query) => $query->where('user_group', '!=', 'admin'))
                 ->searchable() // Menambahkan fitur pencarian
                 ->preload() // Memuat opsi lebih awal untuk pengalaman yang lebih cepat
                 ->required()
                 ->live()
                 ->afterStateUpdated(function ($state, callable $set) {
                     if ($state) {
                         $user = User::find($state);
                         $set('nama', $user->name);
                     }
                 })
                 ,
                    TextInput::make('id_member')
                        ->default(fn () =>  Member::getidmember())
                        ->required()
                        ->placeholder('Masukkan id member')
                    ,
                    TextInput::make('nama')
                    ->autocapitalize('words')
                    ->label('Nama member')
                    ->required()
                    ->placeholder('Masukkan nama member')
                    ,
                    TextInput::make('alamat')
                    ->required()
                    ->placeholder('Masukkan alamat pembeli') // Placeholder untuk membantu pengguna
                ,
                    TextInput::make('no_telp')
                    ->required()
                    ->placeholder('Masukkan nomor telepon') // Placeholder untuk membantu pengguna
                    ->numeric() // Validasi agar hanya angka yang diizinkan
                    ->prefix('+62') // Contoh: Menambahkan prefix jika diperlukan
                    ->extraAttributes(['pattern' => '^[0-9]+$', 'title' => 'Masukkan angka yang diawali dengan 0']) // Validasi dengan pattern regex
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_member')
                ->sortable(),
                TextColumn::make('nama'),
                TextColumn::make('alamat'),
                TextColumn::make('no_telp')
                ->label('No HP')
                ->formatStateUsing(fn ($state) => '+62' . substr($state, 1)), // jika no disimpan pakai 08xxxx, 
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}