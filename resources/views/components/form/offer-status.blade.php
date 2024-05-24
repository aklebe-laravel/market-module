@php
    /**
     *
     * @var string $name
     * @var string $label
     * @var \App\Models\User $value
     * @var bool $read_only
     * @var string $description
     * @var string $css_classes
     * @var string $x_model
     * @var string $xModelName
     * @var array $html_data
     * @var array $x_data
     */

    $xModelName = (($x_model) ? ($x_model . '.' . $name) : '');
@endphp
<div class="form-group form-label-group {{ $css_group }}">
    <div class="box-offer-status-{{ $value }} p-4">
        <div class=" text-lg">
            @unless(empty($label))
                <label>{{ $label }}</label>:
            @endunless
            <span class="offer-status-{{ $value }}">
                {{ __('OFFER_STATUS_' . $value) }}
            </span>
        </div>
        <div class="form-text decent">
            {{ __('OFFER_STATUS_' . $value . '_DESCRIPTION') }}<br/>
            {{ $description }}
        </div>
    </div>
</div>