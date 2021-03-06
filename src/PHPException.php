<?php
namespace Lucinda\Framework\STDERR;

/**
 * Exception caught automatically when a PHP error is encountered.
 */
class PHPException extends \Exception {
	
	/**
	 * Object to which error handling will be delegated to.
	 * 
	 * @var ErrorHandler $errorHandler
	 */
	private static $errorHandler;
	

	/**
	 * Sets object to which error handling will be delegated to.
	 * 
	 * @param ErrorHandler $errorHandler
	 */
	public static function setErrorHandler(ErrorHandler $errorHandler) {
		self::$errorHandler = $errorHandler;
	}

	/**
	 * Gets object to which error handling are delegated to. 
	 * 
	 * @return ErrorHandler
	 */
	public static function getErrorHandler() {
		return self::$errorHandler;
	}
	
	/**
	 * Function called automatically when a non-fatal PHP error is encountered.
	 * 
	 * @param integer $errorNumber
	 * @param string $message
	 * @param string $file
	 * @param integer $line
	 */
	public static function nonFatalError($errorNumber, $message, $file, $line) {
		$e = new self($message, $errorNumber);
		$e->line = $line;
	    $e->file = $file;
	    if(!self::$errorHandler) die($message);
	    self::$errorHandler->handle($e);
	   	die(); // prevents double-reporting if exception is caught
	}
	
	/**
	 * Function called automatically when a fatal PHP error is encountered.
	 */
	public static function fatalError() {
		$error = error_get_last();
		if($error!==NULL) {
			$e = new self($error['message'],0);
			$e->line = $error['line'];
			$e->file = $error['file'];
			if(!self::$errorHandler) die($error['message']);
			self::$errorHandler->handle($e);
		}
	}
}
