<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProduksiResource\Pages;
use App\Models\Produksi;
use App\Models\BahanBaku;
use App\Models\Menu;
use App\Models\Karyawan;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Navigation\NavigationItem;
use Filament\Tables;

//nsmbshin di pdf pdf itu
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class ProduksiResource extends Resource
{
    protected static ?string $model = Produksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Produksi';
    protected static ?string $pluralModelLabel = 'Produksi';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make()->schema([
                Wizard::make([
                    Step::make('Data Produksi')
                        ->schema([
                            Select::make('kode_karyawan')
                                ->label('Karyawan')
                                ->options(Karyawan::pluck('nama', 'id')->toArray())
                                ->searchable()
                                ->required()
                                ->placeholder('Pilih Karyawan'),

                            TextInput::make('kode_produksi')
                                ->label('No. Produksi')
                                ->required()
                                ->disabled()
                                ->default(fn () => Produksi::generateKodeProduksi()),

                            DatePicker::make('tgl_produksi')
                                ->label('Tanggal Produksi')
                                ->required(),
                        ])
                        ->columns(3),

                    Step::make('Menu & Jumlah')
                        ->schema([
                            Select::make('kode_menu')
                                ->label('Menu')
                                ->options(Menu::pluck('nama_menu', 'kode_menu')->toArray())
                                ->searchable()
                                ->required()
                                ->placeholder('Pilih Menu'),

                            TextInput::make('jumlah')
                                ->label('Jumlah Produksi')
                                ->numeric()
                                ->required(),

                            TextInput::make('porsi')
                                ->label('Porsi')
                                ->numeric()
                                ->required(),
                        ])
                        ->columns(3),

                    Step::make('Detail Bahan Baku')
                        ->schema([
                            Repeater::make('bahan_baku')
                                ->label('Bahan Baku Digunakan')
                                ->schema([
                                    Select::make('id')
                                        ->label('Bahan Baku')
                                        ->options(BahanBaku::pluck('nama_bahan_baku', 'id')->toArray())
                                        ->searchable()
                                        ->required(),

                                    TextInput::make('jumlah')
                                        ->label('Jumlah')
                                        ->numeric()
                                        ->required(),
                                ])
                                ->columns(2)
                                ->minItems(1)
                                ->createItemButtonLabel('Tambah Bahan Baku'),
                        ]),
                ])->columnSpan('full'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_produksi')->label('Kode Produksi'),
                TextColumn::make('menu.nama_menu')->label('Nama Menu'),
                TextColumn::make('jumlah')->label('Jumlah'),
                TextColumn::make('tgl_produksi')->label('Tanggal Produksi')->date(),
                TextColumn::make('karyawan.nama')->label('Nama Karyawan'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('unduhPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action(function () {
                    $produksi = \App\Models\Produksi::with(['menu', 'karyawan'])->get();
                    $pdf = Pdf::loadView('pdf.produksi', compact('produksi'));
                    
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'laporan-produksi.pdf'
                    );
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProduksis::route('/'),
            'create' => Pages\CreateProduksi::route('/create'),
            'edit'   => Pages\EditProduksi::route('/{record}/edit'),
            // 'laporan' => Pages\LaporanProduksi::route('/laporan'), // Hapus jika tidak digunakan
        ];
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Produksi')
                ->icon('heroicon-o-rectangle-stack')
                ->group('Transaksi')
                ->url(static::getUrl('index')),
        ];
    }
}
