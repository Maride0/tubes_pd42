<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

// tambahan
use Illuminate\Validation\ValidationException;

class CreateJurnal extends CreateRecord
{
    protected static string $resource = JurnalResource::class;
}