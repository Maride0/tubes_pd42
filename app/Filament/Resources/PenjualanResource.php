<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\PenjualanBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Radio;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Penjualan';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Penjualan')
                        ->schema([
                            Forms\Components\Section::make('Data Penjualan')
                                ->icon('heroicon-m-document-duplicate')
                                ->schema([
                                    TextInput::make('no_penjualan')
                                        ->default(fn () => Penjualan::getKodePenjualan())
                                        ->label('Nomor Penjualan')
                                        ->required()
                                        ->readonly(),
                                    
                                    DateTimePicker::make('tanggal')
                                        ->default(now())
                                        ->required()
                                        ->timezone('Asia/Jakarta'),

                                    Radio::make('is_member')
                                        ->label('Apakah Pembeli Member?')
                                        ->options([
                                            true => 'Ya',
                                            false => 'Tidak',
                                        ])
                                        ->default(true)
                                        ->reactive(),                                    

                                    Select::make('no_telp')
                                        ->label('Pilih Nomor Telepon')
                                        ->options(Member::pluck('no_telp', 'no_telp')->toArray()) // Ambil no_telp dari tabel Member
                                        ->visible(fn (Get $get) => $get('is_member')) // Hanya tampil jika member dipilih
                                        ->required(fn (Get $get) => $get('is_member')) // Wajib diisi jika member dipilih
                                        ->searchable()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                                // Cari member berdasarkan nomor telepon yang dipilih
                                                $member = Member::where('no_telp', $state)->first();
                                    
                                                if ($member) {
                                                    // Set nama dan id_member berdasarkan data member
                                                    $set('nama', $member->nama); // Nama otomatis terisi
                                                    $set('id_member', $member->id); // ID member otomatis terisi
                                                } else {
                                                    // Jika nomor telepon tidak ditemukan, kosongkan nama dan id_member
                                                    $set('nama', null);
                                                    $set('id_member', null);
                                                }
                                            }
                                        }),
                                    
                                    TextInput::make('nama')
                                        ->label('Nama Pembeli')
                                        ->visible(fn (Get $get) => !$get('is_member')) // Nama hanya terlihat kalau bukan member
                                        ->required(fn (Get $get) => !$get('is_member')) // Nama wajib jika bukan member
                                        ->nullable(), // Agar bisa nullable untuk member, karena member sudah ada nama                                    

                                    TextInput::make('tagihan')
                                        ->default(0)
                                        ->hidden(),

                                    TextInput::make('status')
                                        ->default('pesan')
                                        ->hidden(),
                                ])
                                ->collapsible() // Membuat section dapat di-collapse
                                ->columns(3)
                        ]),
                    Wizard\Step::make('Pilih Menu')
                        ->schema([
                            Repeater::make('items')
                                ->relationship('penjualanBarang')
                                ->schema([
                                    Select::make('kode_menu')
                                        ->label('Menu')
                                        ->options(Menu::pluck('nama_menu', 'id')->toArray())
                                        ->required()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, $set) {
                                            $menu = Menu::find($state);
                                            $set('harga_beli', $menu?->harga_menu ?? 0);
                                            $set('harga_jual', $menu ? round($menu->harga_menu * 1.1) : 0);
                                        })
                                        ->searchable(),

                                    TextInput::make('harga_beli')
                                        ->label('Harga Beli')
                                        ->numeric()
                                        ->default(fn ($get) => $get('kode_menu') ? Menu::find($get('kode_menu'))?->harga_menu ?? 0 : 0)
                                        ->readonly() // Agar pengguna tidak bisa mengedit
                                        ->hidden()
                                        ->dehydrated(),

                                    TextInput::make('harga_jual')
                                        ->label('Harga Barang')
                                        ->numeric()
                                        ->readonly()
                                        ->dehydrated(),

                                    TextInput::make('jumlah')
                                        ->label('Jumlah')
                                        ->default(1)
                                        ->reactive()
                                        ->live()
                                        ->required()
                                        ->afterStateUpdated(function ($state, $set, $get) {
                                            // $harga = $get('harga_jual'); // Ambil harga barang
                                            // $total = $harga * $state; // Hitung total
                                            // $set('total', $total); // Set total secara otomatis
                                            $totalTagihan = collect($get('penjualan_barang'))
                                            ->sum(fn ($item) => ($item['harga_jual'] ?? 0) * ($item['jumlah'] ?? 0));
                                            $set('tagihan', $totalTagihan);
                                        }),

                                    DateTimePicker::make('tanggal')
                                        ->default(now())
                                        ->required(),
                                ])
                                ->columns([
                                    'md' => 4, //mengatur kolom menjadi 4
                                ])
                                ->addable()
                                ->deletable()
                                ->reorderable()
                                ->createItemButtonLabel('Tambah Item') // Tombol untuk menambah item baru
                                ->minItems(1) // Minimum item yang harus diisi
                                ->required() // Field repeater wajib diisi
                                ,
                            //tambahan form simpan sementara
                            // **Tombol Simpan Sementara**
                            Forms\Components\Actions::make([
                                Forms\Components\Actions\Action::make('Simpan Sementara')
                                ->action(function ($get) {
                                    $isMember = $get('is_member');
                                    $idMember = $get('id_member');
                                    $nama = $isMember
                                        ? Member::find($idMember)?->nama
                                        : $get('nama');
                                
                                        $penjualan = Penjualan::updateOrCreate(
                                            ['no_penjualan' => $get('no_penjualan')],
                                            [
                                                'tanggal' => $get('tanggal'),
                                                'id_member' => $get('is_member') ? $get('id_member') : null,
                                                'nama' => $get('nama'),
                                                'status' => 'pesan',
                                                'tagihan' => 0,
                                            ]
                                        );  
                                
                                    // Simpan data barang
                                    foreach ($get('items') as $item) {
                                        PenjualanBarang::updateOrCreate(
                                            [
                                                'penjualan_id' => $penjualan->id,
                                                'kode_menu' => $item['kode_menu'],
                                            ],
                                            [
                                                'harga_beli' => $item['harga_beli'],
                                                'harga_jual' => $item['harga_jual'],
                                                'jumlah' => $item['jumlah'],
                                                'tanggal' => $item['tanggal'],
                                            ]
                                        );
                                
                                        $menu = Menu::find($item['kode_menu']);
                                        if ($menu) {
                                            $menu->decrement('stok', $item['jumlah']);
                                        }
                                    }
                                
                                    // Hitung total tagihan
                                    $totalTagihan = PenjualanBarang::where('penjualan_id', $penjualan->id)
                                        ->sum(DB::raw('harga_jual * jumlah'));
                                        //apakah di tabel penjualan ada nilai id_member
                                        if ($penjualan->id_member) {
                                            $totalTagihan = $totalTagihan * 0.98;
                                        }
                                    $penjualan->update(['tagihan' => $totalTagihan]);
                                    })
                                
                                        ->label('Proses')
                                        ->color('primary'),
                                                            
                                    ])
                                
                        ]),
                        Wizard\Step::make('Pembayaran')
                        ->schema([
                            Placeholder::make('Tabel Pembayaran')
                                ->content(fn (Get $get) => view('filament.components.penjualan-table', [
                                    'pembayarans' => Penjualan::where('no_penjualan', $get('no_penjualan'))->get(),
                                ])),
                    
                            Actions::make([
                                Actions\Action::make('bayar')
                                    ->label('Bayar')
                                    ->color('success')
                                    ->requiresConfirmation()
                                    ->modalHeading('Konfirmasi Pembayaran')
                                    ->modalDescription('Yakin ingin menyelesaikan pembayaran?')
                                    ->modalButton('Ya, Bayar')
                                    ->action(fn ($livewire) => $livewire->simpanPembayaran()),
                            ])
                        ]),
        ])->columnSpan(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('no_penjualan')
                ->label('No. Penjualan')
                ->searchable()
                ->sortable(),
        
            TextColumn::make('member')
                ->label('Member')
                ->getStateUsing(fn ($record) => $record->id_member ? 'Ya' : 'Tidak')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Ya' => 'warning',
                    'Tidak' => 'danger',
                    default => 'secondary',
                }),
            
            TextColumn::make('nama')
                ->label('Nama')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state, $record) => $record->member?->nama ?? $record->nama),
        
            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'bayar' => 'success',
                    'pesan' => 'warning',
                    default => 'secondary',
                }),
        
            TextColumn::make('tagihan')
                ->label('Total Tagihan')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->alignment('end')
                ->sortable(),
        
            TextColumn::make('created_at')
                ->label('Tanggal Transaksi')
                ->date('d M Y')
                ->sortable(),
        ])
        
        
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pesan' => 'Pemesanan',
                        'bayar' => 'Pembayaran',
                    ])
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $penjualan = Penjualan::all();

                        $pdf = Pdf::loadView('pdf.penjualan', ['penjualan' => $penjualan]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'penjualan-list.pdf'
                        );
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
