@php
    /**
     * @var mixed $value
     */

    $name = 'rating5';

    $ratingContainerName = 'ratingContainer';
    $ratingContainer = [
        $ratingContainerName => [
            $name => $value,
            'show_value' => false,
        ]
    ];

@endphp
<div x-data="{!! htmlspecialchars(json_encode($ratingContainer)) !!}" class="mb-3 cursor-default">
    @include('form::components.alpine.rating', ['ratingAlpineName' => 'ratingContainer.'.$name])
</div>
