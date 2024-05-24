<?php

namespace Modules\Market\app\Http\Livewire\DataTable;

class ParentReputation extends User
{
    /**
     * @var string
     */
    public string $description = 'dt_surety_description';

    /**
     * Determine whether command has buttons like "add new row" in header.
     * @var bool
     */
    public bool $canAddRow = false;

    /**
     * Enables checkbox and bulk actions.
     *
     * @var bool
     */
    public bool $selectable = false;

    /**
     * Overwrite to init your sort orders before session exists
     * @return void
     */
    protected function initSort(): void
    {
        $this->setSortAllCollections('pivot.created_at', 'desc');
    }

    /**
     * Runs on every request, after the component is mounted or hydrated, but before any update methods are called
     *
     * @return void
     */
    protected function initBooted(): void
    {
        parent::initBooted();

        if (!$this->canEdit()) {
            $this->rowCommands = [];
        }
    }

    /**
     * @return array[]
     */
    public function getColumns(): array
    {
        return [
            [
                'name'       => 'last_visited_at',
                'label'      => 'Online',
                'css_all'    => 'text-center w-5',
                'view'       => 'data-table::livewire.js-dt.tables.columns.online',
                'searchable' => true,
                'sortable'   => true,
            ],
            [
                'name'    => 'id',
                'label'   => 'Link',
                'format'  => 'number',
                'css_all' => 'text-center',
                'view'    => 'data-table::livewire.js-dt.tables.columns.user',
            ],
            [
                'name'       => 'name',
                'label'      => 'Name',
                'searchable' => true,
                'sortable'   => true,
            ],
            [
                'name'       => 'pivot.created_at',
                'label'      => 'Created',
                'css_all'    => 'w-10',
                'view'       => 'data-table::livewire.js-dt.tables.columns.datetime-since',
                'searchable' => true,
                'sortable'   => true,
            ],
        ];
    }

    /**
     * @param  string  $collectionName
     * @return \Illuminate\Support\Collection|null
     */
    public function getFixCollection(string $collectionName): ?\Illuminate\Support\Collection
    {
        if ($this->parentData['id']) {
            $user = app(\App\Models\User::class)->with([])->find($this->parentData['id']);
            $c = $user->parentReputations;
            $this->addSortToCollectionOrBuilder($collectionName, $c);
            $this->addSearchToCollectionOrBuilder($collectionName, $c);
            return $c;
        }

        return parent::getFixCollection($collectionName);
    }

}
