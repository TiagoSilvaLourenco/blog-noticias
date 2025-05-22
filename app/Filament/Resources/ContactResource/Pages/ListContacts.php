<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

     // título que aparece no topo da página
    protected static ?string $title = 'Listar Contatos';

    // rótulo da “breadcrumb”
    protected static ?string $breadcrumb = 'Contatos';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
