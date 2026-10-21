<?php

namespace App\Livewire\Admin\Blocks;

use App\Models\Block;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
{
    public Block $block;

    public array $mediaToRemove = [];

    public array $listsForFields = [];

    public array $mediaCollections = [];

    public function addMedia($media): void
    {
        $this->mediaCollections[$media['collection_name']][] = $media;
    }

    public function removeMedia($media): void
    {
        $collection = collect($this->mediaCollections[$media['collection_name']]);

        $this->mediaCollections[$media['collection_name']] = $collection->reject(fn ($item) => $item['uuid'] === $media['uuid'])->toArray();

        $this->mediaToRemove[] = $media['uuid'];
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->block->id]));
        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    public function mount($id)
    {
        $this->block             = Block::where('id', $id)->where('agency_id', auth()->user()->agency_id)->first();
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.admin.blocks.edit')->layout('layouts.admin');
    }

    public function submit()
    {
        $this->validate();
        $this->block->save();
        $this->syncMedia();

        return redirect()->route('admin.blocks.index');
    }

    protected function rules(): array
    {
        return [
            'block.name' => [
                'string',
                'required',
            ],
            'block.active' => [
                'boolean',
            ],
            'block.layout' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['layout'])),
            ],
            'block.hide_title' => [
                'boolean',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['layout'] = $this->block::LAYOUT_SELECT;
        $this->listsForFields['parent'] = Block::pluck('name', 'id')->toArray();
    }
}
