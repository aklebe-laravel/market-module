<?php

namespace Modules\Market\app\Forms;

use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserProfile extends \Modules\WebsiteBase\app\Forms\UserProfile
{
    /**
     * Relation method if parent form exists.
     */
    const string PARENT_RELATION_METHOD_NAME = 'users';

    /**
     * Needed in Userprofile because the Classname differ
     *
     * @var string|null
     */
    protected ?string $objectEloquentModelName = \Modules\Market\app\Models\User::class;

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
        'avatars',
        'aclGroups',
    ];

    /**
     * Singular
     *
     * @var string
     */
    protected string $objectFrontendLabel = 'User';

    /**
     * Plural
     *
     * @var string
     */
    protected string $objectsFrontendLabel = 'Users';

    /**
     * @return mixed
     */
    public function getOwnerUserId(): mixed
    {
        return $this->getDataSource()->getKey();
    }

    /**
     * @return bool
     */
    public function isOwnUser(): bool
    {
        return $this->getDataSource() && ($this->getOwnerUserId() == Auth::id());
    }

    /**
     * Should be overwritten to decide the current object is owned by user
     * canEdit() can call canManage() but don't call canEdit() in canManage()!
     *
     * @return bool
     */
    public function canEdit(): bool
    {
        return ($this->isOwnUser() || $this->canManage());
    }

    /**
     * @return array
     */
    public function makeObjectInstanceDefaultValues(): array
    {
        return array_merge(parent::makeObjectInstanceDefaultValues(), [
            'shared_id' => uniqid('js_suid_'),
        ]);
    }

    /**
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        $defaultSettings = $this->getDefaultFormSettingsByPermission();

        if ($defaultSettings['can_edit']) {
            $extraAttributeTab = $this->getTabExtraAttributes($this->getDataSource());
        } else {
            $extraAttributeTab = [];
        }

        $userRatingVisible = Auth::user()->hasAclResource('rating.user.visible');

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->getDataSource(), 'name'),
            'tab_controls' => [
                'base_item' => [
                    'disabled'  => $defaultSettings['disabled'],
                    'tab_pages' => [
                        'common' => [
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
                                    'shared_id'             => [
                                        'html_element' => 'hidden',
                                        'validator'    => [
                                            'nullable',
                                            'string',
                                            'Max:255',
                                        ],
                                    ],
                                    'name'                  => [
                                        'html_element' => $defaultSettings['can_edit'] ? 'text' : 'label',
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
                                        'html_element' => $defaultSettings['can_edit'] ? 'email' : 'label',
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
                                        'visible'       => $defaultSettings['can_edit'],
                                        'auto_complete' => false,
                                        'html_element'  => 'password',
                                        'label'         => __('Password'),
                                        'description'   => __('leave_password_blank'),
                                        'validator'     => [
                                            'nullable',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'     => 'col-12 col-md-6',
                                    ],
                                    '__confirm__password'   => [
                                        'visible'       => $defaultSettings['can_edit'],
                                        'auto_complete' => false,
                                        'html_element'  => 'password',
                                        'label'         => __('Confirm Password'),
                                        'description'   => __('leave_password_blank'),
                                        'validator'     => [
                                            'nullable',
                                            'string',
                                            'Max:255',
                                        ],
                                        'css_group'     => 'col-12 col-md-6',
                                    ],
                                    'imageMaker.final_url'  => [
                                        'html_element' => 'image',
                                        'label'        => __('Image'),
                                        'description'  => __('Image'),
                                        'css_group'    => 'col-12 col-md-6',
                                    ],
                                    'media_file_upload'     => [
                                        'visible'      => $defaultSettings['can_edit'],
                                        'html_element' => 'website-base::media_item_file_upload_images',
                                        'label'        => __('Media Upload'),
                                        'description'  => __('Media Upload'),
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
                        'avatars' => [
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Avatars'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'avatars' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Avatars'),
                                        'description'  => __('Avatars by this user'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'website-base::form.media-item',
                                            'table'         => 'website-base::data-table.media-item-image-user-avatar',
                                            'table_options' => [
                                                'hasCommands' => $defaultSettings['can_manage'],
                                                'editable'    => $defaultSettings['can_manage'],
                                                'canAddRow'   => $defaultSettings['can_manage'],
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
                                            ],
                                            'table'         => 'website-base::data-table.address',
                                            'table_options' => [
                                                'hasCommands' => $defaultSettings['can_manage'],
                                                'editable'    => $defaultSettings['can_manage'],
                                                'canAddRow'   => $defaultSettings['can_manage'],
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
                            'visible'  => app('website_base_config')->getValue('users.profiles.media.enabled', false),
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Images'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'images' => [
                                        'html_element' => $defaultSettings['element_dt'],
                                        'label'        => __('Images'),
                                        'description'  => __('Images assigned to this product'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'website-base::form.media-item',
                                            'table'         => 'website-base::data-table.media-item-image-user-avatar',
                                            'table_options' => [
                                                'hasCommands' => $defaultSettings['can_manage'],
                                                'editable'    => $defaultSettings['can_manage'],
                                                'canAddRow'   => $defaultSettings['can_manage'],
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
                            'visible'  => app('website_base_config')->getValue('users.profiles.products.enabled', false),
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
                            'visible'  => $defaultSettings['can_manage'] || ($defaultSettings['can_edit'] && $this->getDataSource()->aclGroups->count() > 0),
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
                                            'table_options' => [],
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
                            'visible'  => $defaultSettings['can_manage'] || ($defaultSettings['can_edit'] && $this->getDataSource()->aclGroups->count() > 0),
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
                                            'form'          => 'acl::form.acl-resource',
                                            'table'         => 'acl::data-table.acl-resource',
                                            'table_options' => [],
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
                            'visible'  => $defaultSettings['can_edit'],
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Tokens'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'tokens' => [
                                        'html_element' => 'element-dt-selected-no-interaction',
                                        'label'        => __('Tokens'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'website-base::form.token',
                                            'table'         => 'website-base::data-table.token',
                                            'table_options' => [
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
                            'visible'  => true, // everyone should see this
                            'disabled' => !$this->getDataSource()->getKey(),
                            'tab'      => [
                                'label' => __('Surety'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'parentReputations' => [
                                        'html_element' => 'element-dt-selected-no-interaction',
                                        'label'        => __('Surety'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'table' => 'market::data-table.parent-reputation',
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