<?php namespace Doitonlinemedia\Translatableroutes;

class TranslatableRoute
{
    public function __construct()
    {
        \App::make('translator')->addNamespace('TranslatableRoute', __DIR__.'/resources/lang');
    }

    public function resource($name, $translation, $controller, $except = array())
    {
        $routes = $this->GetDefaultResourceRoutes($name);
        foreach($routes as $method => $options) {
            if (in_array($method, $except)) {
                continue;
            }
            $this->GetRoute($name, $controller, $method, $options['type'], $options['name'], $translation);
        }
    }

    public function GetRoute($route, $controller, $method, $type, $name, $named) {
        $router = \App::make('router');
        $prefix = str_replace('/', '.', ltrim($router->getLastGroupPrefix(), '/'));
        \Route::$type($named.'/'.$name, ['as' => (($prefix) ? $prefix.'.' : '').$route.'.'.$method, 'uses' => $controller.'@'.$method]);
    }

    public function GetDefaultResourceRoutes($route) {
        $index = (trans('TranslatableRoute::routes.index') === 'TranslatableRoute::routes.index') ? '' : trans('TranslatableRoute::routes.index');
        $name = \str_replace('-', '_', \Str::singular(\Str::afterLast($route, '.')));
        return [
            'store' => [
                'type' => 'post',
                'name' => ''
            ],
            'index' => [
                'type' => 'get',
                'name' => $index
            ],
            'create' => [
                'type' => 'get',
                'name' => trans('TranslatableRoute::routes.create')
            ],
            'update' => [
                'type' => 'put',
                'name' => '{'.$name.'}'
            ],
            'show' => [
                'type' => 'get',
                'name' => '{'.$name.'}'
            ],
            'destroy' => [
                'type' => 'delete',
                'name' => '{'.$name.'}'
            ],
            'edit' => [
                'type' => 'get',
                'name' => '{'.$name.'}/'.trans('TranslatableRoute::routes.edit')
            ]
        ];
    }
}
