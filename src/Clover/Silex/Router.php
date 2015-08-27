<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 15/06/22
 * Time: 14:07
 */

namespace Clover\Silex;

abstract class Router
{
    abstract public function route();

    /**
     * @var string
     */
    protected $namespace = 'YourAppName';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var bool
     */
    protected $allHttps = false;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    protected function get($path, $controllerName, $bindName = '', $requireHttps = null)
    {
        $leaveTheFlow = $this->allHttps && is_null($requireHttps);
        if ($leaveTheFlow || $requireHttps === true) {
            if ($bindName !== '') {
                $this->app->get($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                    ->bind($bindName)
                    ->requireHttps();
            } else {
                $this->app->get($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                    ->requireHttps();
            }
        } else {
            if ($bindName !== '') {
            $this->app->get($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                ->bind($bindName);
            } else {
            $this->app->get($path, "\\{$this->namespace}\\Controller\\" . $controllerName);
            }
        }
    }

    protected function post($path, $controllerName, $bindName = '', $requireHttps = false)
    {
        if ($this->allHttps || $requireHttps) {
            if ($bindName !== '') {
                $this->app->post($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                    ->bind($bindName)
                    ->requireHttps();
            } else {
                $this->app->post($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                    ->requireHttps();
            }
        } else {
            if ($bindName !== '') {
                $this->app->post($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                    ->bind($bindName);
            } else {
                $this->app->post($path, "\\{$this->namespace}\\Controller\\" . $controllerName);
            }
        }
    }

    protected function put($path, $controllerName, $bindName = '', $requireHttps = false)
    {
        if ($this->allHttps || $requireHttps) {
            $this->app->put($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                ->bind($bindName)
                ->requireHttps();
        } else {
            $this->app->put($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                ->bind($bindName);
        }
    }

    protected function delete($path, $controllerName, $bindName = '', $requireHttps = false)
    {
        if ($this->allHttps || $requireHttps) {
            $this->app->delete($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                ->bind($bindName)
                ->requireHttps();
        } else {
            $this->app->delete($path, "\\{$this->namespace}\\Controller\\" . $controllerName)
                ->bind($bindName);
        }
    }

    protected function redirect($path, $bindName)
    {
        $app = $this->app;
        $this->app->get($path, function() use ($app, $bindName) {
            return $app->redirect($app->url($bindName), 301);
        });
    }

}
