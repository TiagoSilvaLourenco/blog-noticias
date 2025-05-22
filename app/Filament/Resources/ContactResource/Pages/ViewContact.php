<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Resources\Pages\ViewRecord;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        // Se ainda estiver 'new', atualiza para 'read'
        if ($this->record->status === 'new') {
            $this->record->update(['status' => 'read']);
        }
    }
}
