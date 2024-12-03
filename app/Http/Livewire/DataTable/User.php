<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Modules\WebsiteBase\app\Http\Livewire\DataTable\User as WebsiteBaseDataTableUser;

class User extends WebsiteBaseDataTableUser
{
    use BaseMarketDataTable;

    /**
     * @return void
     */
    protected function initFilters(): void
    {
        parent::initFilters();

        $this->addFilterElement('user_filter1', [
            'label'      => 'Filter',
            'default'    => 10,
            'position'   => 1700, // between elements rows and search
            'soft_reset' => true,
            'css_group'  => 'col-12 col-md-3 text-start',
            'css_item'   => '',
            'options'    => [
                ''          => '[No Filter]',
                ... $this->getFilterOptionsForImages()
            ],
            'view'       => 'data-table::livewire.js-dt.filters.default-elements.select',
        ]);
    }



    /**
     * @return array|array[]
     */
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
