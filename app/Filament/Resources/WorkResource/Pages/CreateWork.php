<?php

namespace App\Filament\Resources\WorkResource\Pages;

use App\Filament\Resources\WorkResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWork extends CreateRecord
{
    protected static string $resource = WorkResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = slugify($data['title']);
        $data['user_id'] = auth()->id();

        return $data;
    }
}
