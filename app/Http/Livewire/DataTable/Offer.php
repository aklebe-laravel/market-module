<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Modules\Acl\app\Models\AclResource;
use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;
use Modules\Form\app\Forms\Base\NativeObjectBase;
use Modules\Market\app\Models\Offer as OfferModel;
use Modules\Market\app\Services\OfferService;

class Offer extends BaseDataTable
{
    use BaseMarketDataTable;

    /**
     * Restrictions to allow this component.
     */
    public const array aclResources = [AclResource::RES_DEVELOPER, AclResource::RES_TRADER];

    /**
     * @var OfferService
     */
    protected OfferService $offerService;

    /**
     * @var string
     */
    public string $description = "Hier siehst du deine Angebote.";

    /**
     * Use mount instead of __construct to inject services.
     *
     * @param $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

        // @todo: mount injection not working?
        $this->offerService = app(OfferService::class);
    }

    /**
     * Runs once, immediately after the component is instantiated, but before render() is called.
     * This is only called once on initial page load and never called again, even on component refreshes
     *
     * @return void
     */
    protected function initMount(): void
    {
        parent::initMount();

        // force hide "add new row" button and selectables
        // but not for 'manage-data-all'
        if ($this->filterByParentOwner) {
            $this->canAddRow = false;
            $this->selectable = false;
        }
    }

    /**
     * @return void
     */
    protected function initFilters(): void
    {
        parent::initFilters();

        $this->addFilterElement('offer_filter_switch_completed', [
            'label'      => __('OFFER_STATUS_COMPLETED'),
            'default'    => NativeObjectBase::switch3Unused,
            'position'   => 1650, // between elements rows and search
            'soft_reset' => true,
            'css_group'  => 'col text-center',
            'css_item'   => '',
            'view'       => 'data-table::livewire.js-dt.filters.default-elements.3-switch',
            'builder'    => function (Builder $builder, string $filterElementKey, string $filterValue) {
                switch ($filterValue) {
                    case NativeObjectBase::switch3No:
                        $builder->where('status', '!=', OfferModel::STATUS_COMPLETED);
                        break;
                    case NativeObjectBase::switch3Yes:
                        $builder->where('status', '=', OfferModel::STATUS_COMPLETED);
                        break;
                }
            },
        ]);

        $this->addFilterElement('offer_filter_switch_closed', [
            'label'      => __('OFFER_STATUS_CLOSED'),
            'default'    => NativeObjectBase::switch3No,
            'position'   => 1660, // between elements rows and search
            'soft_reset' => true,
            'css_group'  => 'col text-center',
            'css_item'   => '',
            'view'       => 'data-table::livewire.js-dt.filters.default-elements.3-switch',
            'builder'    => function (Builder $builder, string $filterElementKey, string $filterValue) {
                switch ($filterValue) {
                    case NativeObjectBase::switch3No:
                        $builder->where('status', '!=', OfferModel::STATUS_CLOSED);
                        break;
                    case NativeObjectBase::switch3Yes:
                        $builder->where('status', '=', OfferModel::STATUS_CLOSED);
                        break;
                }
            },
        ]);

        $this->addFilterElement('offer_filter_switch_applied', [
            'label'      => __('OFFER_STATUS_APPLIED'),
            'default'    => NativeObjectBase::switch3Unused,
            'position'   => 1670, // between elements rows and search
            'soft_reset' => true,
            'css_group'  => 'col text-center',
            'css_item'   => '',
            'view'       => 'data-table::livewire.js-dt.filters.default-elements.3-switch',
            'builder'    => function (Builder $builder, string $filterElementKey, string $filterValue) {
                switch ($filterValue) {
                    case NativeObjectBase::switch3No:
                        $builder->where('status', '!=', OfferModel::STATUS_APPLIED);
                        break;
                    case NativeObjectBase::switch3Yes:
                        $builder->where('status', '=', OfferModel::STATUS_APPLIED);
                        break;
                }
            },
        ]);

    }

    /**
     * Overwrite to init your sort orders before session exists
     *
     * @return void
     */
    protected function initSort(): void
    {
        $this->setSortAllCollections('updated_at', 'desc');
    }

    /**
     * Runs on every request, after the component is mounted or hydrated, but before any update methods are called
     *
     * @return void
     */
    protected function initBooted(): void
    {
        parent::initBooted();

        $this->addBaseWebsiteMessageBoxes();

        $this->rowCommands = [
            'edit'   => 'data-table::livewire.js-dt.tables.columns.buttons.edit',
            'delete' => 'data-table::livewire.js-dt.tables.columns.buttons.delete',
        ];
    }

    /**
     * @return array[]
     */
    public function getColumns(): array
    {
        return [
            [
                'name'       => 'id',
                'label'      => __('ID'),
                'visible'    => false,
                'searchable' => true,
                'sortable'   => true,
                'format'     => 'number',
                'css_all'    => 'text-muted font-monospace text-end w-5',
            ],
            [
                'name'    => 'createdByUser',
                'label'   => __('Source'),
                'format'  => 'number',
                'view'    => 'data-table::livewire.js-dt.tables.columns.user',
                'css_all' => 'hide-mobile-show-sm text-center w-5',
                'icon'    => 'person-dash',
            ],
            [
                'name'    => 'addressedToUser',
                'label'   => __('Destination'),
                'format'  => 'number',
                'view'    => 'data-table::livewire.js-dt.tables.columns.user',
                'css_all' => 'hide-mobile-show-sm text-center w-5',
                'icon'    => 'person-plus',
            ],
            [
                'name'       => 'status',
                'label'      => __('Status'),
                'view'       => 'market::livewire.js-dt.tables.columns.offer-status',
                'searchable' => true,
                'sortable'   => true,
                'css_all'    => 'w-20',
                'icon'       => 'list-check',
            ],
            [
                'label'   => __('Offer Information'),
                'view'    => 'market::livewire.js-dt.tables.columns.offer-info',
                'css_all' => 'hide-mobile-show-lg text-center w-50',
                'icon'    => 'tag',
                'options' => [
                    'observe_info_data' => [
                        'description',
                    ],
                ],
            ],
            [
                'name'       => 'updated_at',
                'label'      => __('Updated At'),
                'view'       => 'data-table::livewire.js-dt.tables.columns.datetime-since',
                'searchable' => true,
                'sortable'   => true,
                'css_all'    => 'text-end w-10',
                'icon'       => 'arrow-clockwise',
            ],
            [
                'name'       => 'expired_at',
                'label'      => __('Expired At'),
                'view'       => 'data-table::livewire.js-dt.tables.columns.datetime-since',
                'searchable' => true,
                'sortable'   => true,
                'css_all'    => 'hide-mobile-show-md text-end w-10',
                'icon'       => 'alarm',
            ],
            [
                'name'    => 'offerItems',
                'label'   => __('Items'),
                'view'    => 'data-table::livewire.js-dt.tables.columns.count',
                'css_all' => 'text-center w-10',
                'icon'    => 'diagram-3',
            ],
            [
                'name'       => 'description',
                'visible'    => false,
                'searchable' => true,
            ],
        ];
    }

    /**
     * The base builder before all filter manipulations.
     * Usually used for all collections (default, selected, unselected), but can be overwritten.
     *
     * @param  string  $collectionName
     *
     * @return Builder|null
     * @throws Exception
     */
    public function getBaseBuilder(string $collectionName): ?Builder
    {
        $builder = parent::getBaseBuilder($collectionName);

        // filter user offers only
        $builder->where(function (Builder $b) {
            $b->where('created_by_user_id', '=', $this->getUserId())
                ->orWhere(function (Builder $b2) {
                    $b2->where('addressed_to_user_id', '=', $this->getUserId()) // offer to me
                        ->where('status', '!=', OfferModel::STATUS_APPLIED); // but not the prepared ones
                });
        });

        return $builder;
    }

    public function canItemRemoved($item): bool
    {
        return $this->offerService->hasOfferActions($item);
    }


}
