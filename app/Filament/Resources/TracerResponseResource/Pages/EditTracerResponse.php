<?php

namespace App\Filament\Resources\TracerResponseResource\Pages;

use App\Filament\Resources\TracerResponseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTracerResponse extends EditRecord
{
    protected static string $resource = TracerResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
