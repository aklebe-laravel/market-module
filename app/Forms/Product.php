<?php

namespace Modules\Market\app\Forms;

use Illuminate\Support\Facades\Auth;
use Modules\Form\app\Http\Livewire\Form\Base\NativeObjectBase;
use Modules\Form\app\Services\FormService;
use Modules\Market\app\Services\MarketFormService;
use Modules\SystemBase\app\Services\SystemService;
use Modules\WebsiteBase\app\Forms\Base\ModelBaseExtraAttributes;
use Modules\WebsiteBase\app\Services\CoreConfigService;

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
        'categories',
        'mediaItems',
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
            'is_enabled'         => 1,
            'is_public'          => 0,
            'is_test'            => 0,
            'is_individual'      => 1, // default true for jumble sales
            'force_public'       => 0,
            'user_id'            => $this->getOwnerUserId(),
            'store_id'           => app('website_base_settings')->getStoreId(),
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

        /** @var FormService $formService */
        $formService = app(FormService::class);

        $defaultSettings = $this->getDefaultFormSettingsByPermission();

        $extraAttributeTab = $this->getTabExtraAttributes($this->getDataSource());
        $extraAttributeTab['visible'] = $this->formLivewire->viewModeAtLeast();


        $productRatingVisible = Auth::user()->hasAclResource('rating.product.visible');

        /** @var CoreConfigService $websiteBaseConfig */
        $websiteBaseConfig = app('website_base_config');

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->getDataSource(), 'name'),
            'tab_controls' => [
                'base_item' => [
                    //                    'disabled'  => true, // works for all elements
                    'tab_pages' => [
                        'common_simple' => [
                            'visible' => $this->formLivewire->viewModeAtMaximum(NativeObjectBase::viewModeSimple),
                            'tab'     => [
                                'label' => __('Common'),
                            ],
                            'content' => [
                                'form_elements' => [
                                    'id'                        => [
                                        'html_element' => 'hidden',
                                        'label'        => __('ID'),
                                        'validator'    => [
                                            'nullable',
                                            'integer',
                                        ],
                                    ],
                                    'user_id'                   => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'required',
                                            'integer',
                                        ],
                                    ],
                                    'store_id'                  => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'required',
                                            'integer',
                                        ],
                                    ],
                                    'web_uri'                   => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255',
                                        ],
                                    ],
                                    '_tmp123'                   => [
                                        'html_element' => 'market::product_valid_info',
                                        'css_group'    => 'col-12',
                                    ],
                                    'name'                      => [
                                        'html_element' => 'text',
                                        'label'        => __('Name'),
                                        'description'  => __('Product name'),
                                        'validator'    => [
                                            'required',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12',
                                        'dusk'         => 'product-name',
                                    ],
                                    'extra_attributes.price'    => $this->getExtraAttributeElement($this->getDataSource()->getModelAttributeAssigmentCollection()->where('modelAttribute.code', 'price')->first()),
                                    'extra_attributes.currency' => $this->getExtraAttributeElement($this->getDataSource()->getModelAttributeAssigmentCollection()->where('modelAttribute.code', 'currency')->first()),
                                    'imageMaker.final_url'      => [
                                        'html_element' => 'image',
                                        'label'        => __('Current Maker Image'),
                                        'description'  => __('Current Maker Image'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'media_file_upload'         => [
                                        'html_element' => 'website-base::media_item_file_upload_images',
                                        'label'        => __('Image Upload'),
                                        'description'  => __('media_product_upload_description'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'payment_method_id'         => $formService->getFormElement('payment_method'),
                                    'shipping_method_id'        => $formService->getFormElement('shipping_method'),
                                ],
                            ],
                        ],
                        'common'        => [
                            'visible' => $this->formLivewire->viewModeAtLeast(),
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
                                            'integer',
                                        ],
                                    ],
                                    'user_id'                => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'required',
                                            'integer',
                                        ],
                                    ],
                                    'store_id'               => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'required',
                                            'integer',
                                        ],
                                    ],
                                    'web_uri'                => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255',
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
                                            'Max:255',
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
                                            'bool',
                                        ],
                                        'css_group'    => 'col-12 col-md-4',
                                    ],
                                    'is_public'              => [
                                        'html_element' => 'switch',
                                        'label'        => __('Public'),
                                        'description'  => __('public_products'),
                                        'validator'    => [
                                            'nullable',
                                            'bool',
                                        ],
                                        'css_group'    => 'col-12 col-md-4',
                                    ],
                                    'is_test'                => [
                                        'html_element' => 'switch',
                                        'label'        => __('Test'),
                                        'description'  => __('test_products'),
                                        'validator'    => [
                                            'nullable',
                                            'bool',
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
                                            'bool',
                                        ],
                                        'css_group'    => 'col-12 col-md-4',
                                    ],
                                    'force_public'           => [
                                        'disabled'     => config('website-base.module_website_public', false)
                                                          || !$websiteBaseConfig->getValue('product.force_public.enabled',
                                                false),
                                        'html_element' => 'switch',
                                        'label'        => __('Force Public'),
                                        'description'  => __('force_public_products'),
                                        'validator'    => [
                                            'nullable',
                                            'bool',
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
                                        'html_element' => 'website-base::media_item_file_upload_images',
                                        'label'        => __('Image Upload'),
                                        'description'  => __('media_product_upload_description'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'payment_method_id'      => $formService->getFormElement('payment_method'),
                                    'shipping_method_id'     => $formService->getFormElement('shipping_method'),
                                    'started_at'             => [
                                        'visible'      => $this->formLivewire->viewModeAtLeast(NativeObjectBase::viewModeExtended),
                                        'html_element' => 'datetime-local',
                                        'label'        => __('Started At'),
                                        'description'  => __('Product Started At Description'),
                                        'validator'    => ['nullable', 'date'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'expired_at'             => [
                                        'visible'      => $this->formLivewire->viewModeAtLeast(NativeObjectBase::viewModeExtended),
                                        'html_element' => 'datetime-local',
                                        'label'        => __('Expired At'),
                                        'description'  => __('Product Expired At Description'),
                                        'validator'    => ['nullable', 'date'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'user.avatar'            => [
                                        'visible'      => $this->formLivewire->viewModeAtLeast(NativeObjectBase::viewModeExtended),
                                        'html_element' => 'image',
                                        'label'        => __('Owner'),
                                        'description'  => __('Owner'),
                                        'css_group'    => 'col-12 col-sm-6 col-md-4',
                                    ],
                                    'rating5'                => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $productRatingVisible && $this->formLivewire->viewModeAtLeast(NativeObjectBase::viewModeExtended),
                                        'label'        => __('Product Total Rating'),
                                        'description'  => __('Product Total Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
                                    ],
                                    'rating5_condition'      => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $productRatingVisible && $this->formLivewire->viewModeAtLeast(NativeObjectBase::viewModeExtended),
                                        'label'        => __('Product Condition Rating'),
                                        'description'  => __('Product Condition Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
                                    ],
                                    'rating5_public_product' => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $productRatingVisible && $this->formLivewire->viewModeAtLeast(NativeObjectBase::viewModeExtended),
                                        'label'        => __('Product Public Rating'),
                                        'description'  => __('Product Public Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
                                    ],
                                ],
                            ],
                        ],
                        'texts'         => [
                            'visible' => $this->formLivewire->viewModeAtLeast(),
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
                                            'Max:30000',
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
                                            'Max:255',
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
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12 col-lg-6',
                                    ],
                                ],
                            ],
                        ],
                        'categories'    => [
                            // don't show if creating a new object ...
                            'visible'  => $this->formLivewire->viewModeAtLeast(NativeObjectBase::viewModeExtended),
                            'disabled' => !$this->getDataSource()->getKey(),
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
                                                'editable'    => $defaultSettings['can_manage'],
                                                'canAddRow'   => $defaultSettings['can_manage'],
                                                'removable'   => $defaultSettings['can_manage'],
                                            ],
                                        ],
                                        'validator'    => [
                                            'nullable',
                                            'array',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'images'        => [
                            // don't show if creating a new object ...
                            'visible'  => $this->formLivewire->viewModeAtLeast(),
                            'disabled' => !$this->getDataSource()->getKey(),
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
                                            'table'         => 'market::data-table.media-item-image-product',
                                            'table_options' => [
                                                'hasCommands' => $defaultSettings['can_manage'],
                                                'editable'    => $defaultSettings['can_manage'],
                                                'canAddRow'   => $defaultSettings['can_manage'],
                                                'removable'   => $defaultSettings['can_manage'],
                                            ],
                                        ],
                                        'validator'    => [
                                            'nullable',
                                            'array',
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