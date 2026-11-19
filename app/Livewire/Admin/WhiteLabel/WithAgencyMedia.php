<?php

namespace App\Livewire\Admin\WhiteLabel;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Media handling for the <x-dropzone> uploads on the white-label forms
 * (same flow as the Branding settings page).
 */
trait WithAgencyMedia
{
    public array $mediaToRemove = [];

    public array $mediaCollections = [
        'agency_logo'       => [],
        'agency_print_logo' => [],
        'agency_login_bg'   => [],
        'agency_search_bg'  => [],
    ];

    public function addMedia($media): void
    {
        $this->mediaCollections[$media['collection_name']][] = $media;
    }

    public function removeMedia($media): void
    {
        $collection = collect($this->mediaCollections[$media['collection_name']] ?? []);

        $this->mediaCollections[$media['collection_name']] = $collection
            ->reject(fn ($item) => $item['uuid'] === $media['uuid'])
            ->values()
            ->toArray();

        $this->mediaToRemove[] = $media['uuid'];
    }

    public function getMediaCollection($name)
    {
        return $this->mediaCollections[$name] ?? [];
    }

    protected function syncMedia(int $agencyId): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $agencyId]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }
}
