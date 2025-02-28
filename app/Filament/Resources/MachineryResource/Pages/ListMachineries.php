<?php

namespace App\Filament\Resources\MachineryResource\Pages;

use App\Filament\Resources\MachineryResource;
use App\Filament\Resources\MachineryResource\Widgets\MachineryMaintenanceAmount;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMachineries extends ListRecords
{
    protected static string $resource = MachineryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
//            MachineryMaintenanceAmount::class,
            MachineryResource\Widgets\MachineryMaintenanceCost::class,
        ];
    }
}
