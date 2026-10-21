<?php

namespace App\Livewire\Admin\Blocks;

use App\Livewire\WithConfirmation;
use App\Models\Block;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use \App\Livewire\WithSorting;

class Index extends Component
{
    use WithConfirmation;

    public function updateBlockOrder($data = [])
    {
        foreach($data as $el) {
            Block::where('id', $el['value'])->update([
                'position' => $el['order']
            ]);
            if(isset($el['items'])) {
                foreach($el['items'] as $item):
                    Block::where('id', $item['value'])->update([
                        'position' => $item['order'],
                        'parent_id' => $el['value']
                    ]);
                endforeach;
            }
        }
    }

    public function updateGroupOrder($data = [])
    {
        foreach($data as $el) {
            Block::where('id', $el['value'])->update([
                'position' => $el['order']
            ]);
        }
    }

    public function activate($v, $k)
    {
        Block::where('id', $v)->update(['active' => $k]);
    }

    public function deactivate($v, $k)
    {
        Block::where('id', $v)->update(['active' => $k]);
    }

    public function render()
    {
        $blocks = Block::with(['parent', 'agency'])
            ->with('children', function($q) { $q->orderBy('position'); })
            ->where(function($q) {
                $q->where('parent_id', '=', '')->orWhereNull('parent_id');
            })
            ->where('agency_id', auth()->user()->agency_id)
            ->orderBy('position')
            ->get();
        return view('livewire.admin.blocks.index', compact('blocks'))->layout('layouts.admin');
    }

    public function delete(Block $block)
    {
        $block->delete();
    }
}
