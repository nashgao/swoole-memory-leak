<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Listener;

use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\AfterWorkerStart;
use Swoole\Coroutine;
use Swoole\Server\Admin;

#[Listener]
class MemoryListener implements ListenerInterface
{

    public function listen(): array
    {
        return [
            AfterWorkerStart::class,
        ];
    }

    /**
     * @param AfterWorkerStart $event
     */
    public function process(object $event): void
    {
        for ($i=0;$i<1000;$i++) {
            Coroutine::create(
                function () {
                    $chan = new Coroutine\Channel();
                    while (true) {
                        $chan->pop();
                    }
                }
            );
        }


        swoole_timer_tick(1000 * 60 * 3, function () use ($event) {
            var_dump(gc_mem_caches());
        });
    }
}
