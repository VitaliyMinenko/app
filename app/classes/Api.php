<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 01.02.2018
 * Time: 14:26
 */

namespace Prt\app\classes;

/**
 * Class Api - wrapper for responce api.
 * @package Prt\app\classes
 */
class Api
{
	private $headers;
	private $data;

	/**
	 * Api constructor. - Initialization of apt wrapper.
	 *
	 * @param $header
	 * @param $status
	 * @param $data
	 */
	public function __construct($header, $status, $data)
	{
		$this->headers = $header;
		$this->status = $status;
		$this->data = $data;
	}

	/**
	 *  Send response in json format.
	 */
	public function execute()
	{
		foreach($this->headers as $name => $val) {
			header($name . ':' . $val);
		}
		header($this->status);
		echo json_encode($this->data);
	}

}