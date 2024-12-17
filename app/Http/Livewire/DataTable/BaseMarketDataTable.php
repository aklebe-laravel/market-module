<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Modules\WebsiteBase\app\Http\Livewire\DataTable\BaseWebsiteBaseDataTable;

trait BaseMarketDataTable
{
    use BaseWebsiteBaseDataTable;

    /**
     * Add stuff like messagebox buttons here
     *
     * @return void
     */
    protected function initBeforeRender(): void
    {
        $this->addMessageBoxButton('accept-offer', 'market');
        $this->addMessageBoxButton('create-offer-binding', 'market');
        $this->addMessageBoxButton('offer-suspend', 'market');
        $this->addMessageBoxButton('re-offer', 'market');
        $this->addMessageBoxButton('reject-offer', 'market');
    }


}
