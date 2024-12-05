<?php

namespace App\Livewire;

use App\Traits\AlertTrait;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class AppComponent extends Component
{
    use AlertTrait;
    #[On('logout')]
    public function logout()
    {

        Auth::logout();
        return $this->redirect('/', navigate: true);
    }
}
