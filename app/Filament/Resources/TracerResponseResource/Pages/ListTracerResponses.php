<?php

namespace App\Filament\Resources\TracerResponseResource\Pages;

use App\Filament\Resources\TracerResponseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTracerResponses extends ListRecords
{
    protected static string $resource = TracerResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
