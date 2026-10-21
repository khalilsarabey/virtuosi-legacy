<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use ZxcvbnPhp\Zxcvbn;

class ChangePassword extends Component
{
    public $password;
    public int $strengthScore = 0;
    public array $strengthLevels = [
        0 => '',
        1 => 'Your password is very weak',
        2 => 'Almost there',
        3 => 'Not bad',
        4 => 'Good',
        5 => 'Strong',
    ];
    /**
     * @var true
     */
    public bool $success;

    public function render()
    {
        return view('livewire.change-password')->layout('layouts.livewire-auth');
    }

    public function save()
    {
        if($this->password && strlen($this->password) >= 6) {
            if($this->strengthScore > 2) {
                auth()->user()->password = Hash::make($this->password);
                auth()->user()->force_reset_password = 0;
                auth()->user()->save();
                $this->success = true;
            }
        }
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
        $this->updatedPassword($value);
    }
    public function updatedPassword($value)
    {
        $this->strengthScore = (new Zxcvbn())->passwordStrength($value)['score'];
    }
}
