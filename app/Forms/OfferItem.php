<?php

namespace Modules\Market\app\Forms;

use Modules\Form\app\Forms\Base\ModelBase;

class OfferItem extends ModelBase
{
    /**
     * Relations commonly built in with(...)
     * * Also used for:
     * * - blacklist for properties to clean up the object if needed
     * * - onAfterUpdateItem() to sync relations
     *
     * @var array[]
     */
    protected array $objectRelations = [];

    /**
     * Singular
     * @var string
     */
    protected string $objectFrontendLabel = 'Offer Item';

    /**
     * Plural
     * @var string
     */
    protected string $objectsFrontendLabel = 'Offer Items';

    /**
     *
     * @return array
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->jsonResource, 'product_name'),
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
                                    'id'                            => [
                                        'html_element' => 'hidden',
                                        'label'        => __('ID'),
                                        'validator'    => ['nullable', 'integer'],
                                    ],
                                    'product_name'                  => [
                                        'html_element' => 'label',
                                        'label'        => __('Name'),
                                        'description'  => __('Product name'),
                                        'validator'    => ['required', 'string', 'Max:255'],
                                        'css_group'    => 'col-12',
                                    ],
                                    'product.image_maker.final_url' => [
                                        'html_element' => 'image',
                                        'label'        => __('Image'),
                                        'description'  => __('Image'),
                                        'css_group'    => 'col-12',
                                    ],
                                    'price'                         => [
                                        'html_element' => 'number',
                                        'label'        => __('Price'),
                                        'description'  => __('Price'),
                                        'validator'    => ['nullable', 'numeric'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'currency_code'                 => [
                                        'html_element' => 'select',
                                        'label'        => __('Currency'),
                                        'options'      => app('system_base')->toHtmlSelectOptions(\Modules\WebsiteBase\app\Models\Currency::orderBy('code',
                                            'ASC')->get(), ['code', 'name'], 'code',
                                            [self::UNSELECT_RELATION_IDENT => '['.__('No choice').']']),
                                        'description'  => __('Currency'),
                                        'validator'    => ['nullable', 'string'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'payment_method_id'             => [
                                        'html_element' => 'market::payment_method',
                                        'label'        => __('Payment Method'),
                                        'description'  => __('Payment Method'),
                                        'validator'    => ['nullable', 'integer'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'shipping_method_id'            => [
                                        'html_element' => 'market::shipping_method',
                                        'label'        => __('Shipping Method'),
                                        'description'  => __('Shipping Method'),
                                        'validator'    => ['nullable', 'integer'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'description'                   => [
                                        'html_element' => 'textarea',
                                        'label'        => __('Item Information'),
                                        'description'  => __('Item Information Description'),
                                        'validator'    => ['nullable', 'string', 'Max:30000'],
                                        'css_group'    => 'col-12',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

}