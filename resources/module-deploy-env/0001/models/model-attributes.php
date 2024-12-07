<?php

use Modules\WebsiteBase\app\Models\ModelAttribute;

return [
    // class of eloquent model
    "model"   => ModelAttribute::class,
    // update data if exists and data differ (default false)
    "update"  => true,
    // columns to check if data already exists (AND WHERE)
    "uniques" => [],
    // data rows itself
    "data"    => []
];

