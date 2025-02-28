<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\Sale;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSales extends ManageRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->after(function () {
                $lastSale = Sale::query()->latest()->first();
                $supply = $lastSale?->supply;
                $supply->update(['stock' => $supply->stock - $lastSale->quantity]);
            }),
        ];
    }
}
