<?php

namespace Modules\Market\app\Services;

use Modules\Market\app\Models\User;
use Illuminate\Support\Carbon;
use Modules\Market\app\Models\Product;
use Modules\SystemBase\app\Services\Base\BaseService;

class SystemInfoService extends BaseService
{
    public function getSystemInfo(): array
    {
        return [
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
                        'content' => User::with([])->count(),
                    ],
                ],
                [
                    'key'   => [
                        'content' => 'Users active today',
                    ],
                    'value' => [
                        'content' => User::with([])
                                         ->where('last_visited_at', '>', Carbon::today())
                                         ->count(),
                    ],
                ],
                [
                    'key'   => [
                        'content' => 'Users active since 7 days',
                    ],
                    'value' => [
                        'content' => User::with([])
                                         ->where('last_visited_at', '>', Carbon::today()->subDays(7))
                                         ->count(),
                    ],
                ],
                [
                    'key'   => [
                        'content' => 'Products',
                    ],
                    'value' => [
                        'content' => Product::with([])->count(),
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
    }
}