<?php

namespace Modules\Market\app\Forms;

use Modules\WebsiteBase\app\Forms\Base\ModelBaseExtraAttributes;

class Category extends ModelBaseExtraAttributes
{
    /**
     * Relation method if parent form exists.
     */
    const PARENT_RELATION_METHOD_NAME = 'categories';

    /**
     * Set for example 'web_uri' or 'shared_id' to try load from this if is not numeric in getJsonResource().
     * Model have to be trait by TraitBaseModel to become loadByFrontEnd()
     *
     * @var string
     */
    public string $frontendKey = 'web_uri';

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
        'userProducts'
    ];

    /**
     * Singular
     * @var string
     */
    protected string $objectFrontendLabel = 'Category';

    /**
     * Plural
     * @var string
     */
    protected string $objectsFrontendLabel = 'Categories';

    /**
     * @return array
     */
    public function makeObjectModelInstanceDefaultValues(): array
    {
        return array_merge(parent::makeObjectModelInstanceDefaultValues(), [
            'is_enabled' => true,
            'is_public'  => true,
            'store_id'   => app('website_base_settings')->getStore()->getKey() ?? null,
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

        $defaultSettings = $this->getDefaultFormSettingsByPermission();

        $extraAttributeTab = $this->getTabExtraAttributes($this->jsonResource);

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->jsonResource, 'name'),
            'css_classes'  => 'form-edit',
            'livewire'     => 'formObjectAsArray',
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
                                            'integer'
                                        ],
                                    ],
                                    'is_enabled'           => [
                                        'html_element' => 'switch',
                                        'label'        => __('Enabled'),
                                        'description'  => __('enabled_categories'),
                                        'validator'    => [
                                            'nullable',
                                            'bool'
                                        ],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'is_public'            => [
                                        'html_element' => 'switch',
                                        'label'        => __('Public'),
                                        'description'  => __('public_categories'),
                                        'validator'    => [
                                            'nullable',
                                            'bool'
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
                                            'Max:255'
                                        ],
                                        'css_group'    => 'col-12',
                                    ],
                                    'parent_id'            => [
                                        'html_element' => 'select',
                                        'label'        => __('Parent'),
                                        'options'      => app('system_base')->toHtmlSelectOptions(\Modules\Market\app\Models\Category::orderBy('name',
                                            'ASC')->get(), [
                                            'id',
                                            'name'
                                        ], 'id', [self::UNSELECT_RELATION_IDENT => __('No choice')]),
                                        'description'  => __('Parent category'),
                                        'validator'    => [
                                            'nullable',
                                            'integer'
                                        ],
                                        'css_group'    => 'col-6',
                                    ],
                                    'store_id'             => [
                                        'html_element' => 'select',
                                        'label'        => __('Store'),
                                        'options'      => app('system_base')->toHtmlSelectOptions(\Modules\WebsiteBase\app\Models\Store::orderBy('code',
                                            'ASC')->get(), [
                                            'id',
                                            'code'
                                        ], 'id', [self::UNSELECT_RELATION_IDENT => __('No choice')]),
                                        'description'  => __('The Store assigned to the category'),
                                        'validator'    => [
                                            'nullable',
                                            'integer'
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
                                            'Max:255'
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
                                        'html_element' => 'website-base::file_upload',
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
                                            'Max:30000'
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
                                            'Max:255'
                                        ],
                                        'css_group'    => 'col-6',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'disabled' => !$this->jsonResource->getKey(),
                            'tab'      => [
                                'label' => __('Images'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'mediaItems' => [
                                        'html_element'  => 'element-dt-split-default',
                                        'label'         => __('Images'),
                                        'description'   => __('Images assigned to this product'),
                                        'css_group'     => 'col-12',
                                        'options'       => [
                                            'table' => 'website-base::data-table.media-item-image-category',
                                        ],
                                        'table_options' => [
                                            'hasCommands' => $defaultSettings['can_manage'],
                                        ],
                                        'validator'     => [
                                            'nullable',
                                            'array'
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        $extraAttributeTab,
                    ],
                ],
            ],
        ];
    }

}