<?php

namespace Modules\Market\app\Http\Livewire\Form;

use Modules\Form\app\Http\Livewire\Form\Base\ModelBase;
use Modules\Form\app\Services\FormService;
use Modules\Market\app\Models\Base\ExtraAttributeModel;
use Modules\SystemBase\app\Services\SystemService;
use Modules\WebsiteBase\app\Models\Currency;

class ShoppingCartItem extends ModelBase
{
    /**
     * Relations commonly built in with(...)
     * * Also used for:
     * * - blacklist for properties to clean up the object if needed
     * * - onAfterUpdateItem() to sync relations
     *
     * @var array[]
     */
    public array $objectRelations = [];

    /**
     * Singular
     *
     * @var string
     */
    protected string $objectFrontendLabel = 'Cart Item';

    /**
     * Plural
     *
     * @var string
     */
    protected string $objectsFrontendLabel = 'Cart Items';

    /**
     *
     * @return array
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        /** @var SystemService $systemService */
        $systemService = app('system_base');
        /** @var FormService $formService */
        $formService = app(FormService::class);

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->getDataSource(), 'product_name'),
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
                                        'css_group'    => 'col-12 col-md-8',
                                    ],
                                    'product.image_maker.final_url' => [
                                        'html_element' => 'image',
                                        'label'        => __('Image'),
                                        'description'  => __('Image'),
                                        'css_group'    => 'col-12 col-md-4',
                                    ],
                                    'price'                         => [
                                        'html_element' => 'number',
                                        'label'        => __('Price'),
                                        'description'  => __('Price'),
                                        'validator'    => ['required', 'numeric', 'Max:255'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'currency_code'                 => [
                                        'html_element' => 'select',
                                        'label'        => __('Currency'),
                                        'options'      => $systemService->toHtmlSelectOptions(Currency::orderBy('code',
                                            'ASC')->get(),
                                            ['code', 'name'],
                                            'code',
                                            $systemService->selectOptionsSimple[$systemService::selectValueNoChoice]),
                                        'description'  => __('Currency'),
                                        'validator'    => ['nullable', 'string'],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'payment_method_id'             => $formService->getFormElement(ExtraAttributeModel::ATTR_PAYMENT_METHOD),
                                    'shipping_method_id'            => $formService->getFormElement(ExtraAttributeModel::ATTR_SHIPPING_METHOD),
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
