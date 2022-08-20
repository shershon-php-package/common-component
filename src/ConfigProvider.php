<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Shershon\Common;


use Shershon\Common\Adapter\Hyperf\CommonListener;
use Shershon\Common\Util\Support;

class ConfigProvider
{
    public function __invoke(): array
    {

        return [
            'dependencies' => Support::listDefaultDependent(),
            'listeners'    => [
                CommonListener::class,
            ],
        ];
    }
}
