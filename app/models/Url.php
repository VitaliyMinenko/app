<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 2018-02-01
 * Time: 20:23
 */

namespace Prt\app\models;

use Prt\app\db\Connection;
use PDO;
use PDOException;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Prt\app\classes\Config;
use Prt\app\classes\Http;

/**
 * Class Url - Model for work with url instance.
 *
 * @package Prt\app\models
 */
class Url
{
    private $file;
    private $content;
    private static $name = 'urls';

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Start work with urls array.
     */
    public function put()
    {
        $http = new Http();
        $file = $this->file;
        $fileTmp = $file['tmp_name'];
        $message = $this->yamlParser($fileTmp);

        if ($message['Status'] == Config::get('Success')) {
            $content = $this->content['urls'];
            foreach ($content as $k => $url) {
                $res = $http->request($url['url'], $url['delay']);
                $content[$k] = array_merge($content[$k], $res);
            }
            $this->content = $content;
            $message = $this->save();
        }
        return $message;
    }

    /**
     * Save result to db
     */

    protected function save()
    {
        $content = $this->content;
        $response = [
            'Status' => Config::get('Success'),
            'Message' => 'File was parsed correctly and save'
        ];
        foreach ($content as $key => $url) {
            try {
                $tableName = self::$name;
                $connection = Connection::getConnection();
                $query = "INSERT INTO " . $tableName . " (`url`,`delay`,`code`,`time`) VALUES (?,?,?,?)";
                $stmt = $connection->prepare($query);
                $stmt->bindValue(1, $url['url'], PDO::PARAM_STR);
                $stmt->bindValue(2, $url['delay'], PDO::PARAM_INT);
                $stmt->bindValue(3, $url['status'], PDO::PARAM_STR);
                $stmt->bindValue(4, $url['time'], PDO::PARAM_STR);
                $data = $stmt->execute();
            } catch (PDOException $Exception) {
                $response['Status'] = Config::get('Error');
            }

        }
        if ($response['Status'] == Config::get('Success') && $stmt->errorInfo()['0'] == '00000') {
            return $response;
        } else {
            $response['Status'] = Config::get('Error');
            $response['Message'] = 'Error with database.';
            return $response;
        }

    }

    /**
     * Parse our yml file to array.
     * @param $fileTmp
     *
     * @return array
     */
    private function yamlParser($fileTmp)
    {
        try {
            $yaml = new Parser();
            $content = $yaml->parse(file_get_contents($fileTmp));
            $this->content = $content;
            return [
                'Status' => Config::get('Success'),
            ];
        } catch (ParseException $e) {
            return [
                'Status' => Config::get('Error'),
                'Message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get all static method;
     * @return mixed
     */
    public static function getAll()
    {
        $response['Status'] = Config::get('Success');
        try {
            $tableName = self::$name;
            $connection = Connection::getConnection();
            $query = "SELECT `url`,`delay`,`code`,`time` FROM " . $tableName;
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $Exception) {
            $response['Status'] = Config::get('Error');
        }
        if ($response['Status'] == Config::get('Success') && $stmt->errorInfo()['0'] == '00000') {
            $response['Data'] = $data;
            return $response;
        } else {
            $response['Message'] = 'Error with database.';
        }

    }

}