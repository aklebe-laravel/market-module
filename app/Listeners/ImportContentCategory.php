<?php

namespace Modules\Market\app\Listeners;

class ImportContentCategory extends ImportContentMarket
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->requiredTypesSingular[] = 'category';
    }
}
