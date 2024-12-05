<?php

namespace App\Livewire\Components;

use App\Livewire\AppComponent;
use Livewire\Component;

class SearchLivewire extends AppComponent
{
    public $isSearchVisible = false;
    public $isSearching = false;
    public $q = '';
    public function __construct()
    {
        $this->q = $_GET['q'] ?? '';
        if (trim($this->q) != '') {
            $this->isSearchVisible = true;
        }
    }
    public function showSearch()
    {
        if (!$this->isSearchVisible) {

            $this->isSearchVisible = true;
        } else {
            $this->isSearching = true;
            $this->fetchResults();
        }
    }
    public function fetchResults()
    {
        if ($this->q == '') {
            return $this->redirect('/search', navigate: true);
        }
        return $this->redirect('/search?q=' . $this->q, navigate: true);
    }
    public function render()
    {
        return view('livewire.components.search-livewire');
    }
}
