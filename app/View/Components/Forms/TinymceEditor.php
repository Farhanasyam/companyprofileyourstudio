<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TinymceEditor extends Component
{
    public $name;
    public $id;
    public $value;
    public $placeholder;
    public $required;

    /**
     * Create a new component instance.
     */
    public function __construct($name = 'content', $id = null, $value = '', $placeholder = null, $required = false)
    {
        $this->name = $name;
        $this->id = $id ?? 'tinymce-editor-' . uniqid();
        $this->value = $value;
        $this->placeholder = $placeholder ?? 'Enter your content here...';
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.tinymce-editor');
    }
}
