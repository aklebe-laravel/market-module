<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Modules\WebsiteBase\app\Http\Livewire\DataTable\User as WebsiteBaseDataTableUser;
use Modules\Market\app\Models\User as UserModel;

class User extends WebsiteBaseDataTableUser
{
    use BaseMarketDataTable;

    /**
     * @var string
     */
    public string $eloquentModelName = UserModel::class;

    /**
     * @return void
     */
    protected function initFilters(): void
    {
        parent::initFilters();

        $this->addFilterElement('user_filter1', [
            'label'      => 'Filter',
            'default'    => '',
            'position'   => 1700, // between elements rows and search
            'soft_reset' => true,
            'css_group'  => 'col-12 col-md-3 text-start',
            'css_item'   => '',
            'options'    => [
                '' => '[No Filter]',
                ... $this->getFilterOptionsForImages(),
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
