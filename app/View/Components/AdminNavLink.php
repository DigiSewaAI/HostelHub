<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AdminNavLink extends Component
{
    public $href;
    public $active;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($href)
    {
        $this->href = $href;

        // सही तरिकाले active status जाँच गर्ने
        $currentRouteName = Route::currentRouteName();

        // route name वा URL दुवैलाई समर्थन गर्ने
        if (Str::startsWith($href, 'http')) {
            // URL हो भने
            $this->active = request()->is(trim($href, '/').'*');
        } else {
            // route name हो भने
            $this->active = $currentRouteName === $href ||
                           Str::startsWith($currentRouteName, $href.'.');
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin-nav-link');
    }
}
