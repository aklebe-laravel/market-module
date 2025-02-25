<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Modules\Acl\app\Models\AclResource;
use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase;
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

        $this->addBaseWebsiteMessageBoxes();
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
                            $builder->mergeConstraintsFrom(UserModel::withAclResources([AclResource::RES_PUPPET]));
                            break;
                        case NativeObjectBase::switch3Yes:
                            $builder->mergeConstraintsFrom(UserModel::withNoAclResources([AclResource::RES_PUPPET]));
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
                            $builder->mergeConstraintsFrom(UserModel::withNoAclResources([AclResource::RES_TRADER]));
                            break;
                        case NativeObjectBase::switch3Yes:
                            $builder->mergeConstraintsFrom(UserModel::withAclResources([AclResource::RES_TRADER]));
                            break;
                    }
                },
            ]);
        }

        $this->addFilterElement('user_filter1', [
            'label'      => 'Filter',
            'default'    => app('system_base')::selectValueNoChoice,
            'position'   => 1700, // between elements rows and search
            'soft_reset' => true,
            'css_group'  => 'col-12 col-md-3 text-start',
            'css_item'   => '',
            'options'    => $this->getFilterOptionsForImages(),
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
