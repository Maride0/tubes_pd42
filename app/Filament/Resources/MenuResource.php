<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

//komponen input form
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;

//komponen table
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_menu')
                    ->default(fn () => menu::getKodeMenu()) // Ambil default dari method getKodeMenu
                    ->label('Kode Menu')
                    ->required()
                    ->readonly() // Membuat field menjadi read-only
                ,
                TextInput::make('nama_menu')
                    ->required()
                    ->placeholder('Masukkan nama menu') // Placeholder untuk membantu pengguna
                ,
                Radio::make('kategori')
                ->label('Kategori')
                ->options([
                    'Makanan' => 'Makanan',
                    'Minuman' => 'Minuman',
                ])
                ->required(),            
                TextInput::make('harga_menu')
                    ->required()
                    ->minValue(0) // Nilai minimal 0 (opsional jika tidak ingin ada harga negatif)
                    ->reactive() // Menjadikan input reaktif terhadap perubahan
                    ->extraAttributes(['id' => 'harga-menu']) // Tambahkan ID untuk pengikatan JavaScript
                    ->placeholder('Masukkan harga menu') // Placeholder untuk membantu pengguna
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('harga_menu', number_format((int) str_replace('.', '', $state), 0, ',', '.'))
                      )
                ,
                FileUpload::make('foto')
                    ->directory('foto')
                    ->required()
                ,
                TextInput::make('rating')
                    ->required()
                    ->placeholder('Masukkan rating menu') // Placeholder untuk membantu pengguna
                    ->minValue(0)
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_menu')
                    ->searchable(),
                // agar bisa di search
                TextColumn::make('nama_menu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harga_menu')
                    ->label('Harga Menu')
                    ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    ->extraAttributes(['class' => 'text-right']) // Tambahkan kelas CSS untuk rata kanan
                    ->sortable()
                ,
                ImageColumn::make('foto'),
                TextColumn::make('rating'),
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}