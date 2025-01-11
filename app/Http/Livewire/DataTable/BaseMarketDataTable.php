<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Modules\WebsiteBase\app\Http\Livewire\DataTable\BaseWebsiteBaseDataTable;

trait BaseMarketDataTable
{
    use BaseWebsiteBaseDataTable;

    /**
     * Add messagebox buttons and call it in initBooted()
     *
     * @return void
     */
    protected function addBaseMarketMessageBoxes(): void
    {
        $this->addMessageBoxButton('accept-offer', 'market');
        $this->addMessageBoxButton('create-offer-binding', 'market');
        $this->addMessageBoxButton('offer-suspend', 'market');
        $this->addMessageBoxButton('re-offer', 'market');
        $this->addMessageBoxButton('reject-offer', 'market');
    }


}
