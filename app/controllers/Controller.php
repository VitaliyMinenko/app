<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 2018-01-20
 * Time: 19:01
 */

namespace Prt\app\controllers;

use Prt\app\classes\Config;

/**
 * Controller - Base controoler for all controllers.
 *
 * @package controllers
 */
class Controller
{

	/**
	 * Method for rendering view.
	 *
	 * @param      $tamplate
	 * @param null $args
	 */
	public function render($tamplate, $args = null, $message = null)
	{
		require_once('view/main.php');
	}

	/**
	 * Method for getting get params from url.
	 *
	 * @param $name
	 *
	 * @return null|string
	 */
	protected function get($name)
	{
		if(isset($_GET[$name])) {
			$param = isset($param)
				? $param
				: '';
			$str = $param;
			$str = trim($str);
			$str = stripslashes($str);
			$str = htmlspecialchars($str);

			return $str;
		} else {
			return null;
		}
	}

	/**
	 * Method for getting post params from request.
	 *
	 * @param $name
	 *
	 * @return null|string
	 */
	protected function post($name)
	{
		if(isset($_POST[$name])) {
			$param = isset($param)
				? $param
				: '';
			$str = $param;
			$str = trim($str);
			$str = stripslashes($str);
			$str = htmlspecialchars($str);

			return $str;
		} else {
			return null;
		}
	}

	/**
	 * Method for getting files from request.
	 *
	 * @param $name
	 *
	 * @return array
	 */
	protected function file($name)
	{
		if(isset($_FILES[$name]) && count($_FILES[$name]['name']) != '') {

			$file = $_FILES[$name];
			$fileName = $file['name'];
			$fileSize = $file['size'];

			$fileTmp = $file['tmp_name'];
			$fileExt = explode('.', $fileName);
			$response = [];

			if($fileSize > Config::get('maximal_file_size')) {
				$messge = 'Upload file is to large. ';
			}

			if(!in_array($fileExt[1], Config::get('extentions'))) {
				if(isset($messge)) {
					$messge .= 'Extension is not accepted.';
				} else {
					$messge = 'Extension is not accepted.';
				}
			}

			if(isset($messge)) {
				$response = [
					'Status'  => 'Error',
					'Message' => $messge,
				];
			} else {
				$response = [
					'Status'  => 'Ok',
					'Message' => 'Success',
					'File'    => $file,
				];
			}

			return $response;
		} else {
			return $response = [
				'Status'  => 'Error',
				'Message' => 'File not found.',
				'File'    => null,
			];
		}
	}

}