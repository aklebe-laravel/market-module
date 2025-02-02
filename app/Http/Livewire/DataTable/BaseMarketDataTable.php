<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Modules\WebsiteBase\app\Http\Livewire\DataTable\BaseWebsiteBaseDataTable;
use Modules\WebsiteBase\app\Services\WebsiteService;

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
        // @todo: 'data-table' is messed ... more performant is to let similar dts decide
        app(WebsiteService::class)->provideMessageBoxButtons(category: 'data-table');
    }


}
