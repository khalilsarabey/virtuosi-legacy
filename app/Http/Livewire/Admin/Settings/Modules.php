<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Models\Agency;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Modules extends Component
{
    public $tawk;
    public $tawkApi;
    public $tawkWidgetId;
    public $roundtrip;
    public $autopricing;
    public $oneway;
    public $fixed;
    public $lowcost;
    public $multiple;
    public $packages;
    public $hotels;

    protected $agency;

    public function mount()
    {
        $this->agency           = Agency::find(auth()->user()->agency_id);
        $this->roundtrip        = $this->agency->roundtrip;
        $this->fixed            = $this->agency->fixed;
        $this->multiple         = $this->agency->multiple;
        $this->hotels           = $this->agency->hotels;
        $this->packages         = $this->agency->packages;
        $this->oneway           = $this->agency->oneway;
        $this->lowcost          = $this->agency->lowcost;
        $this->tawk             = $this->agency->tawk;
        $this->autopricing      = $this->agency->autopricing;
        $this->tawkApi          = $this->agency->tawk_api;
        $this->tawkWidgetId     = $this->agency->tawk_widget_id;
    }

    public function updated()
    {
        Cache::forget('settings');
    }

    public function updatedFixed($v, $k)
    {
        Agency::where('id', auth()->user()->agency_id)->update([
            'fixed' => $v
        ]);
        session()->flash('message', 'B2B Fixed Departure successfully updated.');
    }

    public function updatedMultiple($v, $k)
    {
        Agency::where('id', auth()->user()->agency_id)->update([
            'multiple' => $v
        ]);
        session()->flash('message', 'B2B Multiple Destinations successfully updated.');
    }

    public function updatedOneway($v, $k)
    {
        Agency::where('id', auth()->user()->agency_id)->update([
            'oneway' => $v
        ]);
        session()->flash('message', 'B2B Oneway Flights successfully updated.');
    }

    public function updatedLowcost($v, $k)
    {
        Agency::where('id', auth()->user()->agency_id)->update([
            'lowcost' => $v
        ]);
        session()->flash('message', 'B2B Low cost Flights successfully updated.');
    }

    public function updatedRoundtrip($v, $k)
    {
        Agency::where('id', auth()->user()->agency_id)->update([
            'roundtrip' => $v
        ]);
        session()->flash('message', 'B2B Roundtrip Flights successfully updated.');
    }

    public function updatedAutopricing($v, $k)
    {
        Agency::where('id', auth()->user()->agency_id)->update([
            'autopricing' => $v
        ]);
        session()->flash('message', 'Auto Pricing successfully updated.');
    }

    public function updatedHotels($v, $k)
    {
        $data = [ 'hotels' => $v ];
        Agency::where('id', auth()->user()->agency_id)->update($data);
        session()->flash('message', 'Hotels successfully updated.');
    }

    public function updatedPackages($v, $k)
    {
        Agency::where('id', auth()->user()->agency_id)->update([
            'packages' => $v
        ]);
        session()->flash('message', 'Packages successfully updated.');
    }

    public function render()
    {
        return view('livewire.admin.settings.modules')->layout('layouts.admin');
    }

    public function updatedTawk()
    {
        if(!$this->tawk) {
            $agency = Agency::find(auth()->user()->agency_id);
            $agency->tawk = 0;
            $agency->save();
        }
        Cache::forget('settings');
    }

    public function save()
    {
        $agency = Agency::find(auth()->user()->agency_id);
        $agency->tawk           = 1;
        $agency->tawk_api       = $this->tawkApi;
        $agency->tawk_widget_id = $this->tawkWidgetId;
        try {
            $agency->save();
            session()->flash('message', 'Tawk API has been successfully updated.');
        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }
}
