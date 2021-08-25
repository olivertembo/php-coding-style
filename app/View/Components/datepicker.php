<?php

namespace App\View\Components;

use Illuminate\View\Component;

class datepicker extends Component
{


    /**
     * The alert type.
     *
     * @var string
     */
    public $date;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.datepicker');
    }
}
