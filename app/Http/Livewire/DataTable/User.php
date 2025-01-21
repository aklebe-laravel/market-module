<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Modules\Form\app\Forms\Base\NativeObjectBase;
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
     * Add stuff like messagebox buttons here
     *
     * @return void
     */
    protected function initBooted(): void
    {
        parent::initBooted();

        $this->addBaseMarketMessageBoxes();

        $this->addMessageBoxButton('accept-rating', 'website-base');
    }

    /**
     * @return void
     */
    protected function initFilters(): void
    {
        parent::initFilters();

        if ($this->canManage()) {
            $this->addFilterElement('user_filter_switch_human', [
                'label'      => 'Humans',
                'default'    => NativeObjectBase::switch3Unused,
                'position'   => 1650, // between elements rows and search
                'soft_reset' => true,
                'css_group'  => 'col text-center',
                'css_item'   => '',
                'view'       => 'data-table::livewire.js-dt.filters.default-elements.3-switch',
                'builder'    => function (Builder $builder, string $filterElementKey, string $filterValue) {
                    switch ($filterValue) {
                        case NativeObjectBase::switch3No:
                            $builder->mergeConstraintsFrom(UserModel::withAclResources(['puppet']));
                            break;
                        case NativeObjectBase::switch3Yes:
                            $builder->mergeConstraintsFrom(UserModel::withNoAclResources(['puppet']));
                            break;
                    }
                },
            ]);
            $this->addFilterElement('user_filter_switch_trader', [
                'label'      => 'Traders',
                'default'    => NativeObjectBase::switch3Unused,
                'position'   => 1651, // between elements rows and search
                'soft_reset' => true,
                'css_group'  => 'col text-center',
                'css_item'   => '',
                'view'       => 'data-table::livewire.js-dt.filters.default-elements.3-switch',
                'builder'    => function (Builder $builder, string $filterElementKey, string $filterValue) {
                    switch ($filterValue) {
                        case NativeObjectBase::switch3No:
                            $builder->mergeConstraintsFrom(UserModel::withNoAclResources(['trader']));
                            break;
                        case NativeObjectBase::switch3Yes:
                            $builder->mergeConstraintsFrom(UserModel::withAclResources(['trader']));
                            break;
                    }
                },
            ]);
        }

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
