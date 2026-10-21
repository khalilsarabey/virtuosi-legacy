<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Agency;
use App\Models\Menu;
use Livewire\Component;

class TopMenuLinks extends Component
{
    use WithSorting;
    use WithConfirmation;

    public $menus;
    public $create = false;
    public $menu = [];
    public $updatedMenu = [];
    public $editing = null;

    public function render()
    {
        $this->menus = Menu::where('agency_id', auth()->user()->agency_id)->orderBy('position')->get();
        return view('livewire.admin.settings.top-menu-links')->layout('layouts.admin');
    }

    public function add()
    {
        $this->create = true;
    }

    public function save()
    {
        $menu = new Menu;
        $menu->name = $this->menu['name'];
        $menu->link = $this->menu['link'];
        $menu->agency_id = auth()->user()->agency_id;
        $menu->save();
        $this->mount();
        $this->create = false;
    }

    public function updateOrder($list)
    {
        foreach($list as $item) {
            Menu::find($item['value'])->update(['position' => $item['order']]);
        }
    }

    public function edit($id)
    {
        $this->editing = $id;
        foreach($this->menus as $m):
            if($m['id'] == $id):
                $this->updatedMenu['link'] = $m['link'];
                $this->updatedMenu['name'] = $m['name'];
            endif;
        endforeach;
    }

    public function update($id)
    {
        Menu::where('id', $id)->update([
            'name' => $this->updatedMenu['name'],
            'link' => $this->updatedMenu['link']
        ]);
        $this->editing = null;
    }

    public function delete(Menu $menu)
    {
        $menu->delete();
    }
}
