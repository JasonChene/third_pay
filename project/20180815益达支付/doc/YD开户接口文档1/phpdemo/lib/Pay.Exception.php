<?php
 /**
 * 
 * API异常类
 *
 */
class PayException extends Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}
}
