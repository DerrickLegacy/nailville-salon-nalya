<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public $title; // new property for header title

    /**
     * Create a new component instance.
     */
    public function __construct($title = null)
    {
        $this->title = $title; // accept dynamic title
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
