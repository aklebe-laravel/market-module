@php
    /**
     * @var string $searchStringLike
     * @var string $renderMode
     * @var array $livewireTableOptions
     */

    $relevantUserId = Auth::id();
    $objectModelInstanceDefaultValues = [
        'user_id' => $relevantUserId,
    ];
    $livewireTableOptions = $livewireTableOptions + [
        'editable' => false,
        'selectable' => false,
        'hasCommands' => false,
        'searchStringLike' => $searchStringLike,
        'renderMode' => $renderMode,
    ];
@endphp
@if ($searchString)
    {{--    <div>--}}
    {{--        <h2>{{ __('Search Results in CMS Pages') }}</h2>--}}
    {{--        <div class="text-danger dec">--}}
    {{--            {{ __('Search') . ': ' . $searchString }}--}}
    {{--        </div>--}}
    {{--        @php $livewireTable = 'market::data-table.cms-page'; @endphp--}}
    {{--        @include('website-base::components.data-tables.tables.dt-simple')--}}
    {{--    </div>--}}
    <div>
        <h2>{{ __('Search Results in Categories') }}</h2>
        <div class="text-danger dec">
            {{ __('Search') . ': ' . $searchString }}
        </div>
        @php $livewireTable = 'market::data-table.category-search'; @endphp
        @include('website-base::components.data-tables.tables.dt-simple')
    </div>
    <div>
        <h2>{{ __('Search Results in Products') }}</h2>
        <div class="text-danger dec">
            {{ __('Search') . ': ' . $searchString }}
        </div>
        @php $livewireTable = 'market::data-table.product-search'; @endphp
        @include('website-base::components.data-tables.tables.dt-simple')
    </div>
    <div>
        <h2>{{ __('Search Results in Users') }}</h2>
        <div class="text-danger dec">
            {{ __('Search') . ': ' . $searchString }}
        </div>
        @php $livewireTable = 'website-base::data-table.user-search'; @endphp
        @include('website-base::components.data-tables.tables.dt-simple')
    </div>
@else
    <div class="alert alert-warning">
        {{ __('Invalid Search Input.') }}
    </div>
@endif
