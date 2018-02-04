<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 2018-02-01
 * Time: 20:13
 */

namespace Prt\app\db;

use Prt\app\classes\Config;
use PDO;

/**
 * Class DataBase - Class for communicate with db.
 *
 * @package classes
 */
class Connection
{
    private static $_instance;
    private static $DB_HOST;
    private static $DB_NAME;
    private static $DB_USER;
    private static $DB_PASS;

    /**
     * DataBase constructor.
     */
    private static function instance()
    {
        self::$DB_HOST = Config::get('host');
        self::$DB_NAME = Config::get('db_name');
        self::$DB_USER = Config::get('user_name');
        self::$DB_PASS = Config::get('password');
        try {
            self::$_instance = new PDO(
                'mysql:host=' . self::$DB_HOST . ';dbname=' . self::$DB_NAME,
                self::$DB_USER,
                self::$DB_PASS,
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
            );
        } catch (PDOException $e) {
            die('Connection error: ' . $e->getMessage());
        }
    }

    /**
     * Close another access.
     */
    private function __construct()
    {
    }

    /**
     * Close another access.
     */
    private function __clone()
    {
    }

    /**
     * Close another access.
     */
    private function __wakeup()
    {
    }

    /**
     * Getter for db connection.
     *
     * @return DataBase|PDO
     */
    public static function getConnection()
    {
        if (self::$_instance == null) {
            self::instance();
        }
        return self::$_instance;
    }
}