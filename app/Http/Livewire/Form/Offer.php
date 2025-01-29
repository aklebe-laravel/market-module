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

        if ($offer = OfferModel::with([])
            ->where($this->getFormInstance()::frontendKey, $offerSharedId)
            ->first()
        ) {
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

            //Log::debug(__METHOD__, [$sharedId, $this->getFormInstance()::frontendKey]);
            if ($offer = OfferModel::with([])
                ->where($this->getFormInstance()::frontendKey, $sharedId)
                ->first()
            ) {
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
