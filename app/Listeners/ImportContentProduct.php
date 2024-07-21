<?php

namespace Modules\Market\app\Listeners;

class ImportContentProduct extends ImportContentMarket
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->requiredTypesSingular[] = 'product';
    }
}
