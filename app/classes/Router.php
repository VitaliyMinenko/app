<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 2018-01-20
 * Time: 14:33
 */

namespace Prt\app\classes;

use Prt\app\controllers\ApiController;

/**
 * Class Router - Set all available routs.
 *
 * @package classes
 */
class Router
{

    /**
     * Base method fpr route.
     */
    public function start()
    {
        $route = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $routing = [
            //  Routs for app.
            '/' => [
                'controller' => 'Main',
                'action' => 'index',
            ],
            '/upload' => [
                'controller' => 'Main',
                'action' => 'uploadFile',
                'method' => 'POST',
            ],
            //  Routs for api.
            '/Api/getAll' => [
                'controller' => 'Api',
                'action' => 'getAll',
                'method' => 'GET',
            ],
        ];

        $method = $_SERVER['REQUEST_METHOD'];
        $routingMethod = isset($routing[$route]['method'])
            ? $routing[$route]['method']
            : 'GET';
        if (isset($routing[$route]) && $this->chekMethod($method, $routingMethod)) {
            $controllerName = $routing[$route]['controller'] . 'Controller';
            $action = $routing[$route]['action'] . 'Action';
        } else {
            $controllerName = 'MainController';
            $action = 'notFound';
        }

        $controller = 'Prt\\app\\controllers\\' . $controllerName;
        $objectController = new $controller();
        $objectController->$action();
    }

    /**
     * Check http method.
     * @param $method
     * @param $ourMethod
     *
     * @return bool
     */
    private function chekMethod($method, $ourMethod)
    {
        if (strtolower($method) == strtolower($ourMethod)) {
            return true;
        } else {
            return false;
        }
    }

} 