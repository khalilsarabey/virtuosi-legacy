<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use ZxcvbnPhp\Zxcvbn;

class MyProfile extends Component
{
    public $profile;
    public $password;
    public int $strengthScore = 0;

    public array $strengthLevels = [
        0 => '',
        1 => 'Very Weak',
        2 => 'Okay but...',
        3 => 'Good',
        4 => 'Strong',
    ];
    public $password_confirmation;
    public $message;
    public function mount()
    {
        $this->profile = User::find(auth()->user()->id);
    }

    public function render()
    {
        return view('livewire.frontend.my-profile');
    }

    public function save()
    {
        $this->validate();
        if($this->password && $this->password_confirmation && $this->password == $this->password_confirmation && strlen($this->password) > 5) {
            if($this->strengthScore < 3) {
                session()->flash('message', 'Choose a stronger password');
                return false;
            }
            $this->profile->password = Hash::make($this->password);
            $this->profile->save();
            session()->flash('message', 'Password successfully updated.');
        } else {
            $this->addError('error', 'Choose a stronger password');
        }
    }
    public function rules() {
        return [
            'profile.name' => 'required',
        ];
    }

    public function generatePassword(): void
    {
        $lowercase = range('a', 'z');
        $uppercase = range('A', 'Z');
        $digits = range(0,9);
        $special = ['!', '@', '#', '$', '%', '^', '*'];
        $chars = array_merge($lowercase, $uppercase, $digits, $special);
        $length = 12;
        do {
            $password = array();
            for ($i = 0; $i <= $length; $i++) {
                $int = rand(0, count($chars) - 1);
                $password[] = $chars[$int];
            }
        } while (empty(array_intersect($special, $password)));
        $this->setPasswords(implode('', $password));
    }

    private function setPasswords($value): void
    {
        $this->password = $value;
        $this->password_confirmation = $value;
        $this->updatedPassword($value);
    }
    public function updatedPassword($value)
    {
        $this->strengthScore = (new Zxcvbn())->passwordStrength($value)['score'];
    }

}
