<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Illuminate\Database\Eloquent\Builder;
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

        $this->addFilterElement('user_filter1', [
            'label'      => 'Filter',
            'default'    => '',
            'position'   => 1700, // between elements rows and search
            'soft_reset' => true,
            'css_group'  => 'col-12 col-md-3 text-start',
            'css_item'   => '',
            'options'    => [
                ''                       => '[No Filter]',
                ... $this->getFilterOptionsForImages(),
                'acl_resource_puppet'    => [
                    'label'   => __('Humans'),
                    'builder' => function (Builder $builder, string $filterElementKey, string $filterValue) {
                        $builder->mergeConstraintsFrom(UserModel::withNoAclResources(['puppet']));
                    },
                ],
                'acl_resource_no_puppet' => [
                    'label'   => __('No Humans'),
                    'builder' => function (Builder $builder, string $filterElementKey, string $filterValue) {
                        $builder->mergeConstraintsFrom(UserModel::withAclResources(['puppet']));
                    },
                ],
                'acl_resource_trader'    => [
                    'label'   => __('Traders'),
                    'builder' => function (Builder $builder, string $filterElementKey, string $filterValue) {
                        $builder->mergeConstraintsFrom(UserModel::withAclResources(['trader']));
                    },
                ],
                'acl_resource_no_trader' => [
                    'label'   => __('No Traders'),
                    'builder' => function (Builder $builder, string $filterElementKey, string $filterValue) {
                        $builder->mergeConstraintsFrom(UserModel::withNoAclResources(['trader']));
                    },
                ],
                'filter_is_enabled'      => [
                    'label'   => __('Enabled'),
                    'builder' => function (Builder $builder, string $filterElementKey, string $filterValue) {
                        //$builder->where('id', '<', 10);
                        $builder->where('is_enabled', '=', true)->where('is_deleted', '=', false);
                    },
                ],
                'filter_is_disabled'     => [
                    'label'   => __('Disabled'),
                    'builder' => function (Builder $builder, string $filterElementKey, string $filterValue) {
                        //$builder->where('id', '<', 10);
                        $builder->where(function ($q1) {
                            $q1->where('is_enabled', '=', false)->orWhere('is_deleted', '=', true);
                        });
                    },
                ],

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
