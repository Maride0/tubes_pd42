<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianBahanBakuResource\Pages;
use App\Models\PembelianBahanBaku;
use App\Models\Supplier;
use App\Models\Bahanbaku;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianBahanBakuResource extends Resource
{
    protected static ?string $model = PembelianBahanBaku::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pembelian Bahan Baku';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                // Step 1: Data Pembelian
                Wizard\Step::make('Pembelian Bahan Baku')->schema([
                    Section::make('Pembelian')
                        ->icon('heroicon-o-document-text')
                            ->schema([
                                Select::make('kode_karyawan')
                                    ->label('Karyawan')
                                    ->options(Karyawan::pluck('nama', 'id')->toArray())
                                    ->required()
                                    ->placeholder('Pilih Karyawan')
                                ,
                                TextInput::make('no_faktur')
                                    ->label('Nomor Faktur')
                                    ->required()
                                ,
                                DateTimePicker::make('tgl_beli')
                                    ->label('Tanggal Pembelian')
                                    ->default(now())
                                    ->timezone('Asia/Jakarta')
                                ,

                                Select::make('kode_supplier')
                                    ->label('Supplier')
                                    ->options(Supplier::pluck('nama_supplier', 'id')->toArray())
                                    ->required()
                                    ->placeholder('Pilih Supplier'),
                            ])
                            ->collapsible()
                            ->columns(4),
                ]),

                // Step 2: Pilih Bahan Baku
                Wizard\Step::make('Pilih Bahan Baku')->schema([
                    Repeater::make('items')
                        ->relationship('detailPembelian') // nama relasi yang benar di model
                        ->schema([
                            Select::make('kode_bahan_baku')
                                ->label('Bahan Baku')
                                ->options(Bahanbaku::pluck('nama_bahan_baku', 'id')->toArray())
                                ->required()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->reactive()
                                ->placeholder('Pilih Bahan Baku')
                                ->afterStateUpdated(function ($state, $set) {
                                    $bahanBaku = Bahanbaku::find($state);
                                    if ($bahanBaku) {
                                        // Isi nilai lain berdasarkan bahan baku yang dipilih
                                        // Misalnya, jika bahan baku memiliki harga_satuan:
                                        $set('harga_satuan', $bahanBaku->harga_satuan ?? 0);
                                        // Jika ada field lain yang ingin diisi otomatis:
                                        // $set('field_lain', $bahanBaku->nilai_lain ?? 0);
                                    }
                                })
                                ->searchable(),

                            TextInput::make('harga_satuan')
                                ->label('Harga Satuan')
                                ->numeric()
                                ->default(fn ($get) => $get('kode_bahan_baku') ? Bahanbaku::find($get('kode_bahan_baku'))?->harga_satuan ?? 0 : 0)
                                ->readonly() // Agar pengguna tidak bisa mengedit
                                ,
                                
                            TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->required(),
                        ])
                        ->columns(3)
                        ->addable()
                        ->deletable()
                        ->reorderable()
                        ->createItemButtonLabel('Tambah Item')
                        ->minItems(1)
                        ->required()
                        ->reactive() 
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $items = $get('items') ?? [];
                            $total = 0;
                            foreach ($items as $item) {
                                $qty = (int) ($item['quantity'] ?? 0);
                                $harga = (int) ($item['harga_satuan'] ?? 0);
                                $total += $qty * $harga;
                            }
                            $set('total_beli', $total);
                        }),
                    ]),
                // Step 3: Detail Pembayaran
                Wizard\Step::make('Detail Pembayaran')
                ->schema([
                    TextInput::make('total_beli')
                        ->label('Total Pembelian')
                        ->numeric()
                        ->default(0)
                        ->readonly()
                        ->formatStateUsing(fn ($state) => rupiah($state)) // format rupiah
                        ->dehydrated(),

                    Select::make('metode_pembayaran')
                        ->label('Metode Pembayaran')
                        ->options([
                            'tunai' => 'Tunai',
                            'kredit' => 'Kredit',
                        ])
                        ->required()
                        ->placeholder('Pilih Metode Pembayaran'),

                    DatePicker::make('jatuh_tempo')
                        ->label('Tanggal Jatuh Tempo')
                        ->required()
                        ->timezone('Asia/Jakarta'),
                ]),
            ])->columnSpan(3)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_faktur')
                ->label('No Faktur')
                ->searchable(),

                TextColumn::make('supplier.nama_supplier') 
                    ->label('Nama Supplier')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tgl_beli')
                    ->label('Tanggal Pembelian')
                    ->date()
                    ->sortable(),

            
                TextColumn::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tunai' => 'success',
                        'kredit' => 'warning',
                        default => 'secondary',
                    }),

                TextColumn::make('total_beli')
                    ->label('Tagihan')
                    ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    ->sortable()
                    ->alignment('end'),

                TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('metode_pembayaran')
                    ->label('Filter Metode Pembayaran')
                    ->options([
                        'tunai' => 'Tunai',
                        'kredit' => 'Kredit',
                    ])
                    ->searchable()
                    ->preload(),
            ])
            
            ->headerActions([
                Tables\Actions\Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $pembelian = PembelianBahanBaku::all();
            
                        $pdf = Pdf::loadView('pdf.pembelian', ['pembelian' => $pembelian]);
            
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'pembelian-list.pdf'
                        );
                    }),
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
            // Tambahkan relasi kalau ada
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelianBahanBakus::route('/'),
            'create' => Pages\CreatePembelianBahanBaku::route('/create'),
            'edit' => Pages\EditPembelianBahanBaku::route('/{record}/edit'),
        ];
    }
}
