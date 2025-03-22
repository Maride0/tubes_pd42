<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\TextInput; //kita menggunakan textinput
use Filament\Forms\Components\Grid;

use Filament\Tables\Columns\TextColumn;

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



class MemberResource extends Resource
{
    protected static ?string $model = member::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1) // Membuat hanya 1 kolom
                ->schema([
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
                    TextInput::make('no_telp')
                    ->required()
                    ->placeholder('Masukkan no telepon')
                    ,
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_member'),
                TextColumn::make('nama'),
                TextColumn::make('no_telp'), 
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