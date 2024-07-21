<?php

namespace Modules\Market\app\Listeners;

use Modules\DeployEnv\app\Listeners\ImportContent;

class ImportContentMarket extends ImportContent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->columnTypeMap['price'] = 'double';
    }

}
