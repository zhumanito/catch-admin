<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher;

use think\Service;

abstract class ModuleService extends Service
{
    abstract public function loadRouteFrom();

    /**
     * 注册
     *
     * @time 2020年07月02日
     * @return void
     */
    public function register()
    {
        $this->app->make('routePath')->loadRouterFrom($this->loadRouteFrom());

        $this->registerEvents();

        $this->registerCommands();;
    }

    /**
     * 事件注册
     *
     * @time 2020年06月24日
     * @return void
     */
    protected function registerEvents()
    {
        if (method_exists($this, 'loadEvents')) {
            $this->app->event->listenEvents($this->loadEvents());
        }
    }

    /**
     * 注册commands
     *
     * @time 2020年07月02日
     * @return void
     */
    protected function registerCommands()
    {
        if (method_exists($this,'loadCommands') && $this->app->runningInConsole()) {
            list($namespace, $path) = $this->loadCommands();

            if ($this->app->has('catch\console')) {
                $catchConsole = $this->app['catch\console'];

                $this->commands($catchConsole->setNamespace($namespace)
                    ->path($path)
                    ->commands());
            }
        }
    }

}