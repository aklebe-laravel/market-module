<?php

namespace Modules\Market\app\Listeners;

use Modules\Market\app\Models\Category;

class ImportRowCategory extends ImportRowMarket
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->requiredTypesSingular[] = 'category';
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

        $id = data_get($event->row, 'id');
        $code = data_get($event->row, 'code');
        if (!$id && !$code) {
            return true;
        }

        // get Category by id or sku
        $category = Category::with([]);
        if ($id) {
            $category->where('id', $id);
        } elseif ($code) {
            $category->where('code', $code);
        }

        // validate data
        $validated = $this->validateRow($event->row);
        // Log::debug(print_r($validated, true));

        // save the base Category
        /** @var Category $category */
        $category = $category->first();
        if ($category) {
            // Log::debug("Category found.");
            $this->setExtraAttributes($category, $event->row);
            $category->update($validated);
        } else {
            // Log::debug("Category not found.");
            /** @var Category $category */
            if ($category = Category::create($validated)) {
                $this->setExtraAttributes($category, $event->row);
                $category->saveModelAttributeTypeValues();
            }
        }

        if (!$category || !$category->getKey()) {
            return true;
        }

        // save the category relations
        $this->doMediaRelations($category, $event->row);

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
             * get store_id by id or store code
             */
            'store_id' => $this->getCalculatedStoreColumnAsId($row),
        ]);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_enabled', default: true);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_public', default: false);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'code');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'name');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'description');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'meta_description');

        return $validatedRow;
    }

}
