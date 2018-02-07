<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 01.02.2018
 * Time: 11:27
 */

namespace Prt\app\controllers;

use Prt\app\models\Url;

class MainController extends Controller
{
    /**
     * Action for view base template.
     */
    public function indexAction()
    {
        $this->render('index');
    }

    /**
     * Action for uploading file.
     */
    public function uploadFileAction()
    {
        $file = $this->file('file');

        if ($file['Status'] == 'Ok' && isset($file['File'])) {

            $url = new Url($file['File']);
            $response = $url->put();
            return $this->render('index', null, $response);
        } else {
            $message = [
                'Status' => $file['Status'],
                'Message' => $file['Message'],

            ];

            return $this->render('index', null, $message);
        }
    }

    /**
     * Default Action if url not set in routing or bad method.
     */
    public function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        echo 'Page not found.';
    }

}