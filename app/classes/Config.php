<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 23.01.2018
 * Time: 10:02
 */

namespace Prt\app\classes;

/**
 * Class Config - Get config.
 *
 * @package classes
 */
class Config
{
	/**
	 * Init config array.
	 *
	 * @var array
	 */
	public static $config = [
		'title'             => 'Yaml application',
		'maximal_file_size' => 2000000,
		'extentions'        => [
			'yml',
			'yaml',
		],
		//Db config
		'host'              => '127.0.0.1',
		'port'              => '22',
		'user_name'         => 'root',
		'db_name'           => 'application',
		'password'          => '',
        //Statuses
        'Success'           =>'Success',
        'Error'           =>'Error',
	];

	/**
	 * Getter of config.
	 */
	public static function get($configName)
	{
		return self::$config[$configName];
	}

}