<?php

namespace App\Traits;

use Jantinnerezo\LivewireAlert\LivewireAlert;

trait AlertTrait
{
    use LivewireAlert;


    public function successAlert($message = "Success!")
    {
        $this->alert('success', $message, [
            'position' => 'bottom-end'
        ]);
    }
    public function errorAlert($message = "Error!")
    {
        $this->alert('error', $message, [
            'position' => 'bottom-end'
        ]);
    }
}
