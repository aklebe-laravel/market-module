<?php

namespace Modules\Market\app\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\DeployEnv\app\Events\ImportRow;
use Modules\WebsiteBase\app\Models\Store;

class ImportRowStore extends ImportRowMarket
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->requiredTypesSingular[] = 'store';
    }

    /**
     * Handle the event.
     *
     * @param  ImportRow  $event
     * @return bool  false to stop all following listeners
     */
    public function handle(ImportRow $event): bool
    {
        if (!$this->isRequiredType($event->type)) {
            return true;
        }

        $id = data_get($event->row, 'id');
        $code = data_get($event->row, 'code');
        if (!$id && !$code) {
            return true;
        }

        // get store by id or sku
        $store = Store::with([]);
        if ($id) {
            $store->where('id', $id);
        } elseif ($code) {
            $store->where('code', $code);
        }

        // validate data
        $validated = $this->validateRow($event->row);

        // save the base Store
        /** @var Store $store */
        $store = $store->first();
        if ($store) {
            Log::debug("Store found.");
            $store->update($validated);
        } else {
            Log::debug("Store not found.");
            /** @var Store $store */
            if ($store = Store::create($validated)) {
            }
        }

        if (!$store || !$store->getKey()) {
            return true;
        }

        return true;
    }

    /**
     * @param $row
     * @return array
     */
    protected function validateRow(&$row): array
    {
        $validatedRow = array_merge(parent::validateRow($row), [
            /**
             * get user_id by id or email
             */
            'user_id'   => $this->getCalculatedUserColumnAsId($row),
            /**
             * get user_id by id or email
             */
            'parent_id' => $this->getCalculatedStoreColumnAsId($row, 'parent'),
        ]);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_enabled', default: true);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_public', default: false);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'code');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'url');

        return $validatedRow;
    }

}
