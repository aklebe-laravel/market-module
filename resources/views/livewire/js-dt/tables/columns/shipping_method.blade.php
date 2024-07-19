@php
    /**
     * @var \Modules\DataTable\app\Http\Livewire\DataTable\Base\BaseDataTable $this
     * @var \Illuminate\Database\Eloquent\Model $item
     * @var string $name
     * @var mixed $value
     **/
@endphp
<div class="text-muted">
    @if ($item->shippingMethod)
        {{ __('shipping_method_' . $item->shippingMethod->code) }}
    @endif
</div>