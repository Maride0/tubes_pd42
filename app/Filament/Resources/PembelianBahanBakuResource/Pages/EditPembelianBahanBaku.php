<?php

namespace App\Filament\Resources\PembelianBahanBakuResource\Pages;

use App\Filament\Resources\PembelianBahanBakuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelianBahanBaku extends EditRecord
{
    protected static string $resource = PembelianBahanBakuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
