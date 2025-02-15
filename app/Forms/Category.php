<?php

namespace Modules\Market\app\Forms;

use Modules\SystemBase\app\Services\SystemService;
use Modules\WebsiteBase\app\Forms\Base\ModelBaseExtraAttributes;
use Modules\WebsiteBase\app\Models\Store;

class Category extends ModelBaseExtraAttributes
{
    /**
     * Relation method if parent form exists.
     */
    const string PARENT_RELATION_METHOD_NAME = 'categories';

    /**
     * Set for example 'web_uri' or 'shared_id' to try load from this if is not numeric in initDataSource().
     * Model have to be trait by TraitBaseModel to become loadByFrontEnd()
     *
     * @var string
     */
    public const string frontendKey = 'web_uri';

    /**
     * Relations commonly built in with(...)
     * * Also used for:
     * * - blacklist for properties to clean up the object if needed
     * * - onAfterUpdateItem() to sync relations
     *
     * @var array[]
     */
    protected array $objectRelations = [
        'mediaItems',
        'userProducts',
    ];

    /**
     * Singular
     *
     * @var string
     */
    protected string $objectFrontendLabel = 'Category';

    /**
     * Plural
     *
     * @var string
     */
    protected string $objectsFrontendLabel = 'Categories';

    /**
     * @return array
     */
    public function makeObjectInstanceDefaultValues(): array
    {
        return array_merge(parent::makeObjectInstanceDefaultValues(), [
            'is_enabled' => 1,
            'is_public'  => 1,
            'store_id'   => app('website_base_settings')->getStoreId(),
            'web_uri'    => uniqid('category_'),
        ]);
    }

    /**
     *
     * @return array
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        /** @var SystemService $systemService */
        $systemService = app('system_base');

        $defaultSettings = $this->getDefaultFormSettingsByPermission();

        $extraAttributeTab = $this->getTabExtraAttributes($this->getDataSource());

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->getDataSource(), 'name'),
            'tab_controls' => [
                'base_item' => [
                    'tab_pages' => [
                        [
                            'tab'     => [
                                'label' => __('Common'),
                            ],
                            'content' => [
                                'form_elements' => [
                                    'id'                   => [
                                        'html_element' => 'hidden',
                                        'label'        => __('ID'),
                                        'validator'    => [
                                            'nullable',
                                            'integer',
                                        ],
                                    ],
                                    'is_enabled'           => [
                                        'html_element' => 'switch',
                                        'label'        => __('Enabled'),
                                        'description'  => __('enabled_categories'),
                                        'validator'    => [
                                            'nullable',
                                            'bool',
                                        ],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'is_public'            => [
                                        'html_element' => 'switch',
                                        'label'        => __('Public'),
                                        'description'  => __('public_categories'),
                                        'validator'    => [
                                            'nullable',
                                            'bool',
                                        ],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'name'                 => [
                                        'html_element' => 'text',
                                        'label'        => __('Name'),
                                        'description'  => __('Category name'),
                                        'validator'    => [
                                            'required',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12',
                                    ],
                                    'parent_id'            => [
                                        'html_element' => 'select',
                                        'label'        => __('Parent'),
                                        'options'      => $systemService->toHtmlSelectOptions(\Modules\Market\app\Models\Category::orderBy('name',
                                            'ASC')->get(),
                                            [
                                                'id',
                                                'name',
                                            ],
                                            'id',
                                            $systemService->selectOptionsSimple[$systemService::selectValueNoChoice]),
                                        'description'  => __('Parent category'),
                                        'validator'    => [
                                            'nullable',
                                            'integer',
                                        ],
                                        'css_group'    => 'col-6',
                                    ],
                                    'store_id'             => [
                                        'html_element' => 'select',
                                        'label'        => __('Store'),
                                        'options'      => $systemService->toHtmlSelectOptions(Store::orderBy('code',
                                            'ASC')->get(),
                                            [
                                                'id',
                                                'code',
                                            ],
                                            'id',
                                            $systemService->selectOptionsSimple[$systemService::selectValueNoChoice]),
                                        'description'  => __('The Store assigned to the category'),
                                        'validator'    => [
                                            'nullable',
                                            'integer',
                                        ],
                                        'css_group'    => 'col-6',
                                    ],
                                    'web_uri'              => [
                                        'html_element' => 'text',
                                        'label'        => __('Web URI'),
                                        'description'  => __('Have to be unique.'),
                                        'validator'    => [
                                            'required',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12',
                                    ],
                                    'imageMaker.final_url' => [
                                        'html_element' => 'image',
                                        'label'        => __('Image'),
                                        'description'  => __('Image'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'media_file_upload'    => [
                                        'html_element' => 'website-base::media_item_file_upload_images',
                                        'label'        => __('Media Upload'),
                                        'description'  => __('Media Upload'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'tab'     => [
                                'label' => __('Texts'),
                            ],
                            'content' => [
                                'form_elements' => [
                                    'description'      => [
                                        'html_element' => 'textarea',
                                        'label'        => __('Description'),
                                        'description'  => __('Detailed description'),
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:30000',
                                        ],
                                        'css_group'    => 'col-12',
                                    ],
                                    'meta_description' => [
                                        'html_element' => 'textarea',
                                        'label'        => __('Meta description'),
                                        'description'  => __('Meta description used for all kind of searching'),
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-6',
                                    ],
                                ],
                            ],
                        ],
                        //[
                        //    'disabled' => !$this->getDataSource()->getKey(),
                        //    'tab'      => [
                        //        'label' => __('Images'),
                        //    ],
                        //    'content'  => [
                        //        'form_elements' => [
                        //            'mediaItems' => [
                        //                'html_element'  => 'element-dt-split-default',
                        //                'label'         => __('Images'),
                        //                'description'   => __('Images assigned to this product'),
                        //                'css_group'     => 'col-12',
                        //                'options'       => [
                        //                    'table' => 'website-base::data-table.media-item-image-category',
                        //                ],
                        //                'table_options' => [
                        //                    'hasCommands' => $defaultSettings['can_manage'],
                        //                ],
                        //                'validator'     => [
                        //                    'nullable',
                        //                    'array',
                        //                ],
                        //            ],
                        //        ],
                        //    ],
                        //],
                        $extraAttributeTab,
                    ],
                ],
            ],
        ];
    }

}