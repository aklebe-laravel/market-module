<?php

namespace Modules\Market\app\Listeners;

class ImportContentAddress extends ImportContentMarket
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->requiredTypesSingular[] = 'address';
    }
}
