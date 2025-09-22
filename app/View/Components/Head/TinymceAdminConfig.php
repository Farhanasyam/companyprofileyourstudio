<?php

namespace App\View\Components\Head;

use Illuminate\View\Component;

class TinymceAdminConfig extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.head.tinymce-admin-config');
    }
}
