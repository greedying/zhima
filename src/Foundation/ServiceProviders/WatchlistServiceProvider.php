<?php

/*
 * This file is part of the greedying/zhima.
 *
 * (c) greedying <greedying@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Greedying\Zhima\Foundation\ServiceProviders;

use Greedying\Zhima\Watchlist\Watchlist;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class WatchlistServiceProvider.
 */
class WatchlistServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['watchlist'] = function ($pimple) {
            return new Watchlist($pimple);
        };
    }
}
