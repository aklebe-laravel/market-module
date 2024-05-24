<?php

namespace Modules\Market\app\Services;

use Illuminate\Support\Carbon;
use Modules\SystemBase\app\Services\Base\BaseService;

class SystemInfoService extends BaseService
{
    public function getSystemInfo(): array
    {
        $result = [
            'headers' => [
                'key'   => [
                    'label' => 'Key',
                ],
                'value' => [
                    'label' => 'Value',
                ],
            ],
            'rows'    => [
                [
                    'key'   => [
                        'content' => 'Users total',
                    ],
                    'value' => [
                        'content' => \App\Models\User::with([])->count(),
                    ],
                ],
                [
                    'key'   => [
                        'content' => 'Users active today',
                    ],
                    'value' => [
                        'content' => \App\Models\User::with([])
                            ->where('last_visited_at', '>', Carbon::today())
                            ->count(),
                    ],
                ],
                [
                    'key'   => [
                        'content' => 'Users active since 7 days',
                    ],
                    'value' => [
                        'content' => \App\Models\User::with([])
                            ->where('last_visited_at', '>', Carbon::today()->subDays(7))
                            ->count(),
                    ],
                ],
                [
                    'key'   => [
                        'content' => 'Products',
                    ],
                    'value' => [
                        'content' => \Modules\Market\app\Models\Product::with([])->count(),
                    ],
                ],
                [
                    'key'   => [
                        'content' => 'Test ...',
                    ],
                    'value' => [
                        'content' => 'r2d2',
                    ],
                ],
            ],
        ];


        return $result;
    }
}