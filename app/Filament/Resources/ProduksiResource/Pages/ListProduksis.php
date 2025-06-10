<?php

namespace App\Filament\Resources\ProduksiResource\Pages;

use App\Filament\Resources\ProduksiResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListProduksis extends ListRecords
{
    protected static string $resource = ProduksiResource::class;

 protected function getHeaderActions(): array
{
    return [
        Actions\CreateAction::make(),
        Actions\Action::make('unduhPdf')
            ->label('Unduh PDF')
            ->icon('heroicon-o-arrow-down-tray') // ikon yang tersedia dan valid
            ->openUrlInNewTab()
            ->color('primary'),
    ];
}

}
