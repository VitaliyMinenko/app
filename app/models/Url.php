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
        $file = $this->file;
        $fileTmp = $file['tmp_name'];
        $message = $this->yamlParser($fileTmp);

        if ($message['Status'] == Config::get('Success')) {
            $content = $this->content['urls'];
            foreach ($content as $k => $url) {
                $res = $this->request($url['url'], $url['delay']);
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
     * Try to execute requests from url array.
     * @param $url
     */
    private function request($url)
    {
        $start = microtime(true);
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
        curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
        curl_setopt($c, CURLOPT_MAXREDIRS, 10);
        $follow_allowed = (ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
        if ($follow_allowed) {
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
        curl_setopt($c, CURLOPT_REFERER, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, 60);
        curl_setopt($c, CURLOPT_AUTOREFERER, true);
        curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
        $data = curl_exec($c);
        $status = curl_getinfo($c);
        curl_close($c);
        preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si', $status['url'], $link);
        $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si', '$1=$2' . $link[0] . '$3$4$5', $data);
        $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si', '$1=$2' . $link[1] . '://' . $link[3] . '$3$4$5', $data);
        $result = round(microtime(true) - $start, 3);

        return ['status' => $status['http_code'], 'time' => $result];
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
            $ontent = $yaml->parse(file_get_contents($fileTmp));

            $this->content = $ontent;
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