<?php

namespace Modules\Market\app\Forms;

use Illuminate\Support\Facades\Auth;
use Modules\WebsiteBase\app\Forms\Base\ModelBaseExtraAttributes;
use Modules\WebsiteBase\app\Services\Config;

/**
 *
 */
class Product extends ModelBaseExtraAttributes
{
    /**
     * Relation method if parent form exists.
     */
    const string PARENT_RELATION_METHOD_NAME = 'products';

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
        'categories',
        'mediaItems'
    ];

    /**
     * Singular
     *
     * @var string
     */
    protected string $objectFrontendLabel = 'Product';

    /**
     * Plural
     *
     * @var string
     */
    protected string $objectsFrontendLabel = 'Products';

    /**
     * @return array
     */
    public function makeObjectInstanceDefaultValues(): array
    {
        $settings = app('market_settings');
        return array_merge(parent::makeObjectInstanceDefaultValues(), [
            'is_enabled'         => true,
            'is_public'          => false,
            'is_individual'      => true, // default true for jumble sales
            'user_id'            => $this->getOwnerUserId(),
            'store_id'           => app('website_base_settings')->getStore()->getKey() ?? null,
            'payment_method_id'  => $settings->getDefaultPaymentMethod()->getKey(),
            'shipping_method_id' => $settings->getDefaultShippingMethod()->getKey(),
            'web_uri'            => uniqid('product_'),
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

        $productRatingVisible = Auth::user()->hasAclResource('rating.product.visible');

        /** @var Config $websiteBaseConfig */
        $websiteBaseConfig = app('website_base_config');

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->jsonResource, 'name'),
            'tab_controls' => [
                'base_item' => [
                    //                    'disabled'  => true, // works for all elements
                    'tab_pages' => [
                        [
                            'tab'     => [
                                'label' => __('Common'),
                            ],
                            'content' => [
                                'form_elements' => [
                                    'id'                     => [
                                        'html_element' => 'hidden',
                                        'label'        => __('ID'),
                                        'validator'    => [
                                            'nullable',
                                            'integer'
                                        ],
                                    ],
                                    'user_id'                => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'required',
                                            'integer'
                                        ],
                                    ],
                                    'store_id'               => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'required',
                                            'integer'
                                        ],
                                    ],
                                    'web_uri'                => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255'
                                        ],
                                    ],
                                    '_tmp123'                => [
                                        'html_element' => 'market::product_valid_info',
                                        'css_group'    => 'col-12',
                                    ],
                                    'name'                   => [
                                        'html_element' => 'text',
                                        'label'        => __('Name'),
                                        'description'  => __('Product name'),
                                        'validator'    => [
                                            'required',
                                            'string',
                                            'Max:255'
                                        ],
                                        'css_group'    => 'col-12',
                                        'dusk'         => 'product-name',
                                    ],
                                    'is_enabled'             => [
                                        'html_element' => 'switch',
                                        'label'        => __('Enabled'),
                                        'description'  => __('enabled_products'),
                                        'validator'    => [
                                            'nullable',
                                            'bool'
                                        ],
                                        'css_group'    => 'col-12 col-md-4',
                                    ],
                                    'is_public'              => [
                                        'html_element' => 'switch',
                                        'label'        => __('Public'),
                                        'description'  => __('public_products'),
                                        'validator'    => [
                                            'nullable',
                                            'bool'
                                        ],
                                        'css_group'    => 'col-12 col-md-4',
                                    ],
                                    'is_test'                => [
                                        'html_element' => 'switch',
                                        'label'        => __('Test'),
                                        'description'  => __('test_products'),
                                        'validator'    => [
                                            'nullable',
                                            'bool'
                                        ],
                                        'css_group'    => 'col-12 col-md-4',
                                    ],
                                    'is_individual'          => [
                                        'html_element' => 'switch',
                                        'label'        => __('Individual'),
                                        // 'default'      => true, // default true for jumble sales
                                        'description'  => __('individual_products'),
                                        'validator'    => [
                                            'nullable',
                                            'bool'
                                        ],
                                        'css_group'    => 'col-12 col-md-4',
                                    ],
                                    'force_public'           => [
                                        'disabled'     => $websiteBaseConfig->get('site.public',
                                                false) || !$websiteBaseConfig->get('product.force_public.enabled',
                                                false),
                                        'html_element' => 'switch',
                                        'label'        => __('Force Public'),
                                        'description'  => __('force_public_products'),
                                        'validator'    => [
                                            'nullable',
                                            'bool'
                                        ],
                                        'css_group'    => 'col-12 col-md-4',
                                    ],
                                    'imageMaker.final_url'   => [
                                        'html_element' => 'image',
                                        'label'        => __('Image'),
                                        'description'  => __('Image'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'media_file_upload'      => [
                                        'html_element' => 'website-base::file_upload',
                                        'label'        => __('Media Upload'),
                                        'description'  => __('Media Upload'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'payment_method_id'      => [
                                        'html_element' => 'market::payment_method',
                                        'label'        => __('Payment Method'),
                                        'description'  => __('Payment Method'),
                                        'validator'    => [
                                            'nullable',
                                            'integer'
                                        ],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'shipping_method_id'     => [
                                        'html_element' => 'market::shipping_method',
                                        'label'        => __('Shipping Method'),
                                        'description'  => __('Shipping Method'),
                                        'validator'    => [
                                            'nullable',
                                            'integer'
                                        ],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'started_at'             => [
                                        'html_element' => 'datetime-local',
                                        'label'        => __('Started At'),
                                        'description'  => __('Product Started At Description'),
                                        'validator'    => ['nullable', 'date'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'expired_at'             => [
                                        'html_element' => 'datetime-local',
                                        'label'        => __('Expired At'),
                                        'description'  => __('Product Expired At Description'),
                                        'validator'    => ['nullable', 'date'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'user'                   => [
                                        'html_element' => 'user_info',
                                        'label'        => __('Owner'),
                                        'description'  => __('Owner'),
                                        'css_group'    => 'col-12 col-sm-6 col-md-4',
                                    ],
                                    'rating5'                => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $productRatingVisible,
                                        'disabled'     => true,
                                        'label'        => __('Product Total Rating'),
                                        'description'  => __('Product Total Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
                                    ],
                                    'rating5_condition'      => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $productRatingVisible,
                                        'disabled'     => true,
                                        'label'        => __('Product Condition Rating'),
                                        'description'  => __('Product Condition Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
                                    ],
                                    'rating5_public_product' => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $productRatingVisible,
                                        'disabled'     => true,
                                        'label'        => __('Product Public Rating'),
                                        'description'  => __('Product Public Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
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
                                    'description'       => [
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
                                    'short_description' => [
                                        'html_element' => 'textarea',
                                        'label'        => __('Short description'),
                                        'description'  => __('Short and meaningful description'),
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255'
                                        ],
                                        'css_group'    => 'col-12 col-lg-6',
                                    ],
                                    'meta_description'  => [
                                        'html_element' => 'textarea',
                                        'label'        => __('Meta description'),
                                        'description'  => __('Meta description used for all kind of searching'),
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255'
                                        ],
                                        'css_group'    => 'col-12 col-lg-6',
                                    ],
                                ],
                            ],
                        ],
                        [
                            // don't show if creating a new object ...
                            'disabled' => !$this->jsonResource->getKey(),
                            // works for all elements
                            'tab'      => [
                                'label' => __('Categories'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'categories' => [
                                        'html_element' => 'element-dt-split-default',
                                        'label'        => __('Categories'),
                                        'description'  => __('Categories assigned to this product'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'table'         => 'market::data-table.category',
                                            'table_options' => [
                                                'hasCommands' => $defaultSettings['can_manage'],
                                            ],
                                        ],
                                        'validator'    => [
                                            'nullable',
                                            'array'
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            // don't show if creating a new object ...
                            'disabled' => !$this->jsonResource->getKey(),
                            'tab'      => [
                                'label' => __('Images'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'mediaItems' => [
                                        'html_element' => 'element-dt-split-default',
                                        'label'        => __('Images'),
                                        'description'  => __('Images assigned to this product'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'table' => 'website-base::data-table.media-item-image-product',
                                        ],
                                        'validator'    => [
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