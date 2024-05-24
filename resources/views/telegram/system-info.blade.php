@php
    use Modules\Market\app\Services\SystemInfoService;
    /** @var \Modules\Market\app\Services\SystemInfoService $systemInfoService */
    $systemInfoService = app(SystemInfoService::class);
    $tableData = $systemInfoService->getSystemInfo();
@endphp
{{--telegram HTML mode supports: <b></b>, <i></i>, <s></s>, <u></u>--}}
@foreach ($tableData['rows'] as $row)
    {{ data_get($row, 'key.content') }}: {{ data_get($row, 'value.content') }}
@endforeach
