<?php

namespace Modules\Market\app\Listeners;

use Modules\WebsiteBase\app\Models\Address;

class ImportRowAddress extends ImportRowMarket
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->requiredTypesSingular[] = 'address';
    }

    /**
     * Handle the event.
     *
     * @param  \Modules\DeployEnv\app\Events\ImportRow  $event
     * @return bool  false to stop all following listeners
     */
    public function handle(\Modules\DeployEnv\app\Events\ImportRow $event): bool
    {
        if (!$this->isRequiredType($event->type)) {
            return true;
        }

        if (!($id = data_get($event->row, 'id'))) {
            // @todo: if no id try to find address by same data ... ?

            // return true;
        }

        // get Address by id or sku
        $address = Address::with([])->where('id', $id);

        // validate data
        $validated = $this->validateRow($event->row);

        // user is required
        if ($validated['user_id'] === null) {
            return true;
        }

        // save the base Address
        /** @var Address $address */
        $address = $address->first();
        if ($address) {
            // Log::debug("Address found.");
            $address->update($validated);
        } else {
            // Log::debug("Address not found.");
            /** @var Address $address */
            if ($address = Address::create($validated)) {
            }
        }

        if (!$address || !$address->getKey()) {
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
            'user_id'  => $this->getCalculatedUserColumnAsId($row),
            /**
             * get user_id by id or email
             */
            'parent_id'  => $this->getCalculatedUserColumnAsId($row, 'parent'),
        ]);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_enabled', default: true);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_public', default: false);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'title');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'firstname');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'lastname');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'email');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'phone');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'country_iso');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'street');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'city');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'region');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'zip');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'user_description');

        return $validatedRow;
    }

}
