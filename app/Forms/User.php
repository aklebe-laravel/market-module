<?php

namespace Modules\Market\app\Forms;

use Illuminate\Support\Facades\Auth;

/**
 *
 */
class User extends \Modules\WebsiteBase\app\Forms\User
{
    /**
     * Relations for using in with().
     * Don't add fake relations or relations should not be updated!
     *
     * Will be used as:
     * - Blacklist of properties, to save the plain model
     * - onAfterUpdateItem() to sync() the relations
     *
     * @var array[]
     */
    protected array $objectRelations = [
        'mediaItems',
        'aclGroups',
        'products',
    ];

    /**
     *
     * @return array
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        $defaultSettings = $this->getDefaultFormSettingsByPermission();

        $extraAttributeTab = $this->getTabExtraAttributes($this->getDataSource());

        $userRatingVisible = Auth::user()->hasAclResource('rating.user.visible');

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
                                    'id'                    => [
                                        'html_element' => 'hidden',
                                        'label'        => __('ID'),
                                        'validator'    => [
                                            'nullable',
                                            'integer',
                                        ],
                                    ],
                                    'is_enabled'            => [
                                        'html_element' => 'switch',
                                        'label'        => __('Enabled'),
                                        'description'  => __('Disable to lock this user, prevent him from login and log him out if he is already online.'),
                                        'validator'    => [
                                            'nullable',
                                            'bool',
                                        ],
                                        'css_group'    => 'col-12 col-md-6 col-lg-3',
                                    ],
                                    'order_to_delete_at'    => [
                                        'html_element' => 'datetime-local',
                                        'label'        => __('Prepare Deletion'),
                                        'description'  => __('Deletion step 1/3: Enter a date to mark user wanted to delete. User becomes invalid and is unable to login.'),
                                        'validator'    => ['nullable', 'date'],
                                        'css_group'    => 'col-12 col-md-6 col-lg-3',
                                    ],
                                    'is_deleted'            => [
                                        'html_element' => 'switch',
                                        'label'        => __('User deleted'),
                                        'description'  => __('Deletion step 2/3: Soft delete. User becomes invalid and is unable to login.'),
                                        'validator'    => [
                                            'nullable',
                                            'bool',
                                        ],
                                        'css_group'    => 'col-12 col-md-6 col-lg-3',
                                    ],
                                    '__is_deleted_info'     => [
                                        'html_element' => 'website-base::user_active_info',
                                        'label'        => __('User deleted'),
                                        'validator'    => [
                                            'nullable',
                                            'bool',
                                        ],
                                        'css_group'    => 'col-12 col-md-6 col-lg-3',
                                    ],
                                    'name'                  => [
                                        'html_element' => 'text',
                                        'label'        => __('Username'),
                                        'description'  => __('Username'),
                                        'validator'    => [
                                            'required',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'email'                 => [
                                        'html_element' => 'email',
                                        'label'        => __('Email'),
                                        'description'  => __('User Email'),
                                        'validator'    => [
                                            'required',
                                            'email',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'password'              => [
                                        'html_element' => 'password',
                                        'label'        => __('Password'),
                                        //                                        'description'  => __('Passwort'),
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    '__confirm__password'   => [
                                        'html_element' => 'password',
                                        'label'        => __('Confirm Password'),
                                        //                                        'description'  => __('Confirm Password'),
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'shared_id'             => [
                                        'html_element' => 'label_and_hidden',
                                        'label'        => __('Shared ID'),
                                        'description'  => __('Shared ID'),
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'    => 'col-12',
                                    ],
                                    'imageMaker.final_url'  => [
                                        'html_element' => 'image',
                                        'label'        => __('Image'),
                                        'description'  => __('Image'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'media_file_upload'     => [
                                        'html_element' => 'website-base::media_item_file_upload_images',
                                        'label'        => __('Media Upload'),
                                        'description'  => __('media_user_upload_description'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'rating5'               => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $userRatingVisible,
                                        'disabled'     => true,
                                        'label'        => __('User Total Rating'),
                                        'description'  => __('User Total Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
                                    ],
                                    'rating5_trust'         => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $userRatingVisible,
                                        'disabled'     => true,
                                        'label'        => __('User Trust Rating'),
                                        'description'  => __('User Trust Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
                                    ],
                                    'rating5_well_known'    => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $userRatingVisible,
                                        'disabled'     => true,
                                        'label'        => __('User Well Known Rating'),
                                        'description'  => __('User Well Known Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
                                    ],
                                    'rating5_offer_success' => [
                                        'html_element' => 'market::rating5',
                                        'visible'      => $userRatingVisible,
                                        'disabled'     => true,
                                        'label'        => __('User Offer Rating'),
                                        'description'  => __('User Offer Rating Description'),
                                        'validator'    => ['nullable', 'numeric', 'Max:5'],
                                        'css_group'    => 'col-12',
                                    ],
                                ],
                            ],
                        ],
                        [
                            'visible'  => $defaultSettings['can_edit'],
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Addresses'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'addresses' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Addresses'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'website-base::form.address',
                                            'form_options'  => [
                                                //                                                'readonly'   => !$canEdit,
                                                //                                                'actionable' => $canEdit,
                                            ],
                                            'table'         => 'website-base::data-table.address',
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
                        [
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Images'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'mediaItems' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Images'),
                                        'description'  => __('Images assigned to this product'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'website-base::form.media-item',
                                            //'table'         => 'website-base::data-table.media-item-image-user-avatar',
                                            'table'         => 'website-base::data-table.media-item-image',
                                            //'table'         => 'website-base::data-table.media-item',
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
                        [
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Products'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'products' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Products'),
                                        'description'  => __('Products assigned to this user'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'market::form.product',
                                            'table'         => 'market::data-table.product',
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
                        [
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Acl Groups'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'aclGroups' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Acl Groups'),
                                        'description'  => __('Acl Groups'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'acl::form.acl-group',
                                            'table'         => 'acl::data-table.acl-group',
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
                        [
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Acl Resources'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'aclResources' => [
                                        'html_element' => 'element-dt-selected-no-interaction',
                                        'label'        => __('Acl Resources'),
                                        'description'  => __('Acl Resources'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'table' => 'acl::data-table.acl-resource',
                                        ],
                                        'validator'    => [
                                            'nullable',
                                            'array',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Tokens'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'tokens' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Tokens'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'website-base::form.token',
                                            'table'         => 'website-base::data-table.token',
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