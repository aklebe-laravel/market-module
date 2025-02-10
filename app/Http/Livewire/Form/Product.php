<?php

namespace Modules\Market\app\Http\Livewire\Form;

use Modules\Form\app\Http\Livewire\Form\Base\ModelBase;

class Product extends ModelBase
{
    /**
     * @return void
     */
    protected function initLiveCommands(): void
    {
        // add reload button
        $this->addReloadCommand();

        // add select to change the view mode
        $this->addViewModeCommand(self::viewModeSimple);
    }
}
