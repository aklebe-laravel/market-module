@php
    /**
     * @var \Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable $this
     * @var \Illuminate\Database\Eloquent\Model $item
     * @var string $name
     * @var mixed $value
     **/
    $currency = data_get($item, 'currency_code', data_get($item, 'currency', ''));
@endphp
<div class="offer-status-{{ $value }}">
    {{ __('OFFER_STATUS_' . $value) }}
</div>

