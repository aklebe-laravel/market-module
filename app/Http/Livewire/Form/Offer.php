<?php

namespace Modules\Market\app\Http\Livewire\Form;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Modules\Form\app\Http\Livewire\Form\Base\ModelBase;
use Modules\Market\app\Models\Offer as OfferModel;
use Modules\Market\app\Services\OfferService;

class Offer extends ModelBase
{
    /**
     * Set for example 'web_uri' or 'shared_id' to try load from this if is not numeric in initDataSource().
     * Model have to be trait by TraitBaseModel to become loadByFrontEnd()
     *
     * @var string
     */
    public const string frontendKey = 'shared_id';

    /**
     * Relations commonly built in with(...)
     * * Also used for:
     * * - blacklist for properties to clean up the object if needed
     * * - onAfterUpdateItem() to sync relations
     *
     * @var array[]
     */
    public array $objectRelations = [
        'offerItems',
        'prevOffer',
        'nextOffers.createdByUser',
        'nextOffers.addressedToUser',
    ];

    /**
     * Singular
     *
     * @var string
     */
    protected string $objectFrontendLabel = 'Offer';

    /**
     * Plural
     *
     * @var string
     */
    protected string $objectsFrontendLabel = 'Offers';

    /**
     * @var OfferService
     */
    protected OfferService $offerService;

    /**
     * Use mount instead of __construct to inject services.
     *
     * @param $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

        // @todo: mount injection not working?
        $this->offerService = app(OfferService::class);
    }

    /**
     * @return bool
     */
    public function isOwnUser(): bool
    {
        return ($this->getDataSource() && (($this->getDataSource()->created_by_user_id == Auth::id() || ($this->getDataSource()->addressed_to_user_id == Auth::id()))));
    }

    /**
     * @return bool
     */
    protected function canEditStatus(): bool
    {
        /** @var OfferService $offerService */
        $offerService = app(OfferService::class);
        $status = data_get($this->getDataSource(), 'status', '');

        // Is current offer status allowed to be edited?
        return $offerService->canEditStatus($status);
    }

    /**
     * Should be overwritten to decide the current object is owned by user
     * canEdit() can call canManage() but don't call canEdit() in canManage()!
     *
     * @return bool
     */
    public function canEdit(): bool
    {
        return ($this->canEditStatus() && $this->isOwnUser()); // || $this->canManage());
    }

    /**
     *
     * @return array
     */
    public function getFormElements(): array
    {
        $parentFormData = parent::getFormElements();

        $canEdit = $this->canEdit();

        return [
            ... $parentFormData,
            'title'        => $this->makeFormTitle($this->getDataSource(), 'shared_id'),
            'tab_controls' => [
                'main' => [
                    'tab_pages' => [
                        [
                            'disabled' => !$this->isOwnUser(),
                            'tab'      => [
                                'label' => __('Common'),
                            ],
                            'content'  => [
                                'form_elements' => [
                                    'id'                     => [
                                        'html_element' => 'hidden',
                                        'label'        => __('ID'),
                                        'validator'    => ['nullable', 'integer'],
                                    ],
                                    'status'                 => [
                                        'html_element' => 'market::offer-status',
                                        'label'        => __('Status'),
                                        'validator'    => ['nullable', 'string', 'Max:100'],
                                        'css_group'    => 'col-12 col-md-6 pt-4 pb-4',
                                    ],
                                    'createdByUser.avatar'   => [
                                        'html_element' => 'image',
                                        'label'        => __('Creator'),
                                        'description'  => __('User created this offer'),
                                        'css_group'    => 'text-center col-6 col-md-3',
                                    ],
                                    'addressedToUser.avatar' => [
                                        'html_element' => 'image',
                                        'label'        => __('Product Owner'),
                                        'description'  => __('User addressed this offer'),
                                        'css_group'    => 'text-center col-6 col-md-3',
                                    ],
                                    'offerItems'             => [
                                        'html_element' => 'element-dt-selected-with-form',
                                        'label'        => __('Offer Items'),
                                        'description'  => __('Offer Items'),
                                        'css_group'    => 'col-12',
                                        'options'      => [
                                            'form'          => 'market::form.offer-item',
                                            'table'         => 'market::data-table.offer-item',
                                            'table_options' => [
                                                'headerView'  => '', // hide header
                                                'footerView'  => '', // hide footer
                                                'hasCommands' => $canEdit,
                                                'editable'    => $canEdit,
                                                'removable'   => $canEdit,
                                            ],
                                        ],
                                    ],
                                    'prev_offer'             => [
                                        'html_element' => 'market::offer-prev-offer',
                                        'label'        => __('Prev Offer'),
                                        'description'  => __('Prev Offer Description'),
                                        'css_group'    => 'col-12',
                                        'visible'      => function () {
                                            return !!$this->getDataSource()->prevOffer()->count();
                                        },
                                    ],
                                    'next_offers'            => [
                                        'html_element' => 'market::offer-next-offers',
                                        'label'        => __('Following Offers'),
                                        'description'  => __('Following Offers Description'),
                                        'css_group'    => 'col-12',
                                        'visible'      => function () {
                                            return !!$this->getDataSource()->nextOffers()->count();
                                        },
                                    ],
                                    'description'            => [
                                        'html_element' => 'textarea',
                                        'label'        => __('Offer Information'),
                                        'description'  => __('Offer Information Description'),
                                        'validator'    => ['nullable', 'string', 'Max:30000'],
                                        'css_group'    => 'col-12',
                                    ],
                                    'expired_at'             => [
                                        'html_element' => 'datetime-local',
                                        'label'        => __('Expired At'),
                                        'description'  => __('Offer Expired At Description'),
                                        'validator'    => ['nullable', 'date'],
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

    /**
     * Gather action buttons depend on status
     *
     * @return array|string[]
     */
    protected function calcFormActionButtons(): array
    {
        $result = [];

        $status = data_get($this->dataTransfer, 'status', '');

        foreach ($this->offerService::statusActionMap as $property => $statusActions) {
            if ($this->dataSource->$property == Auth::id()) {
                foreach ($statusActions as $formActionStatus => $formActions) {
                    if ($status == $formActionStatus) {
                        foreach ($formActions['form_actions'] as $k => $formAction) {
                            $result[$formAction] = (is_string($k) ? $k : 'market').'::components.form.actions.'.$formAction;
                        }
                    }
                }
            }
        }

        $this->actionable = !!$result;

        return $result;
    }

    /**
     * Emit Event
     *
     * @param  mixed  $livewireId
     * @param  mixed  $offerSharedId
     *
     * @return void
     */
    #[On('offer-suspend')]
    public function offerSuspend(mixed $livewireId, mixed $offerSharedId): void
    {
        if (!$this->checkLivewireId($livewireId)) {
            return;
        }

        if ($offerSharedId) {
            if ($offer = OfferModel::with([])->where('shared_id', '=', $offerSharedId)->first()) {
                if ($this->offerService->disbandOfferToCartItems($offer)) {
                    //            $this->redirectRoute('manage-data', ['modelName' => 'Offer']);
                } else {
                    // @todo: error
                }
            }
        }

        // @todo: update cart ..

        //
        $this->closeFormAndRefreshDatatable();
    }

    /**
     * Dispatch: Create Offer
     *
     * @param  mixed  $livewireId
     * @param  mixed  $offerSharedId
     *
     * @return void
     */
    #[On('create-offer-binding')]
    public function createOfferBinding(mixed $livewireId, mixed $offerSharedId): void
    {
        if (!$this->checkLivewireId($livewireId)) {
            return;
        }
        $this->switchStatusByFormAction(OfferModel::STATUS_NEGOTIATION, $offerSharedId);
    }

    /**
     * Emit Action Reject Offer
     *
     * @param  mixed  $livewireId
     * @param  mixed  $offerSharedId
     *
     * @return void
     */
    #[On('reject-offer')]
    public function rejectOffer(mixed $livewireId, mixed $offerSharedId): void
    {
        if (!$this->checkLivewireId($livewireId)) {
            return;
        }

        $this->switchStatusByFormAction(OfferModel::STATUS_REJECTED, $offerSharedId);
    }

    /**
     * Emit Action Accept Offer
     *
     * @param  mixed  $livewireId
     * @param  mixed  $offerSharedId
     *
     * @return void
     */
    #[On('accept-offer')]
    public function acceptOffer(mixed $livewireId, mixed $offerSharedId): void
    {
        if (!$this->checkLivewireId($livewireId)) {
            return;
        }

        $this->switchStatusByFormAction(OfferModel::STATUS_COMPLETED, $offerSharedId);
    }


    /**
     * Emit Event
     *
     * @param  mixed  $livewireId
     * @param  mixed  $offerSharedId
     *
     * @return void
     */
    #[On('re-offer')]
    public function reOffer(mixed $livewireId, mixed $offerSharedId): void
    {
        // @todo: generalize
        if (!$this->checkLivewireId($livewireId)) {
            return;
        }

        if ($offer = OfferModel::with([])->where(static::frontendKey, $offerSharedId)->first()) {
            $this->offerService->reOffer($offer);
        }

        $this->closeFormAndRefreshDatatable();

    }

    /**
     * Switch status and dispatch the switch service if exists.
     *
     * @param  string  $status
     * @param  string  $sharedId
     *
     * @return void
     */
    protected function switchStatusByFormAction(string $status, string $sharedId): void
    {
        // maybe do not assign this before switchStatus() ...
        //$this->dataTransfer['status'] = $status;

        // save form data
        $res = $this->saveFormData();
        if (!$res->hasErrors()) {

            if ($offer = OfferModel::with([])->where(static::frontendKey, $sharedId)->first()) {
                if (!$this->offerService->switchStatus($offer, $status, false)) {
                    $this->addErrorMessage(__('Unable to switch offer status.'));

                    return;
                }
            } else {
                $this->addErrorMessage(__('Unable to load offer.'));

                return;
            }

            $this->closeFormAndRefreshDatatable();

            return;

        } else {

            $this->addErrorMessages($res->getErrors());

        }

        //
        $this->closeFormAndRefreshDatatable();
    }

}
