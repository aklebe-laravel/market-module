<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Modules\Acl\app\Models\AclResource;
use Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable;
use Modules\Market\app\Services\OfferService;

class Offer extends BaseDataTable
{
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
        if ($this->useCollectionUserFilter) {
            $this->canAddRow = false;
            $this->selectable = false;
        }
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
     * Usually used for all collections (default, selected, unselected), but can overwritten.
     *
     * @param  string  $collectionName
     *
     * @return Builder|null
     * @throws Exception
     */
    public function getBaseBuilder(string $collectionName): ?Builder
    {
        $builder = parent::getBaseBuilder($collectionName);

        // blacklist history items
        $builder->withCount(['nextOffers'])->having('next_offers_count', '<', '1');

        // filter user offers only
        $builder->where(function (Builder $b) {
            $b->where('created_by_user_id', '=', $this->getUserId())
              ->orWhere('addressed_to_user_id', '=', $this->getUserId());
        });

        return $builder;
    }

    public function canItemRemoved($item): bool
    {
        return $this->offerService->hasOfferActions($item);
    }


}
