<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Modules\WebsiteBase\app\Http\Livewire\DataTable\User as WebsiteBaseDataTableUser;

class User extends WebsiteBaseDataTableUser
{
    public function getColumns(): array
    {
        $parentResult = parent::getColumns();

        // find name field and change the view to market
        foreach ($parentResult as &$v) {
            if ($v['name'] === 'name') {
                $v['view'] = 'market::livewire.js-dt.tables.columns.user-name-detailed';
            }
        }

        return $parentResult;
    }
}
