<?php

namespace App\Filament\Resources\PropertyOutflowResource\Pages;

use App\Filament\Resources\PropertyOutflowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyOutflows extends ListRecords
{
    protected static string $resource = PropertyOutflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
