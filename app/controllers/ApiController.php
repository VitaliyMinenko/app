<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 2018-01-31
 * Time: 12:52
 */

namespace Prt\app\controllers;

use Prt\app\models\Url;
use Prt\app\classes\Api;

/**
 * Class ApiController - Controller which get all api requests.
 *
 * @package Prt\app\controllers
 */
class ApiController
{
    public function getAllAction()
    {

        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET',
            'Access-Control-Allow-Credentials' => 'true',
            'Content-Type' => 'application/json',
        ];
        $data =  Url::getAll();
        if ($data['Status'] == 'Success') {
            $status = 'HTTP/1.1 200 OK';
            $api = new Api($headers, $status, $data);
        } else {
            $status = 'HTTP/1.1 500';
            $api = new Api($headers, $status, $data);
        }
        $api->execute();
    }
}