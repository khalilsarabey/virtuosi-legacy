<?php

namespace App\Livewire;

trait WithConfirmation
{
    public function confirm($callback, ...$argv)
    {
        $this->dispatch('confirm', callback: $callback, argv: $argv);
    }
}
