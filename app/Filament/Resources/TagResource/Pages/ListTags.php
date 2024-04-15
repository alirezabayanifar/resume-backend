<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data) {
                    $data['slug'] = slugify($data['name']);
                    return $data;
                }),
        ];
    }
}
