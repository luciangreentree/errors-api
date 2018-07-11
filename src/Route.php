<?php
namespace Lucinda\MVC\STDERR;

/**
 * Encapsulates a route that matches a handled exception
 */
class Route
{
    private $controller;
    private $view;
    private $httpStatus;
    private $errorType;
    private $contentType;

    /**
     * Gets controller class name that handles exception handled.
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Gets file that holds what is displayed when error response is rendered.
     *
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Gets HTTP status associated to exception handled.
     *
     * @return string
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    /**
     * Gets error type associated to exception handled.
     *
     * @return  ErrorType Enum of possible error types.
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * Gets content type associated to exception handled.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Sets controller class name that handles exception handled.
     *
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * Sets file that holds what is displayed when error response is rendered.
     *
     * @param string $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * Sets HTTP status associated to exception handled.
     *
     * @param integer $httpStatus
     */
    public function setHttpStatus($httpStatus)
    {
        $this->httpStatus = $httpStatus;
    }

    /**
     * Sets error type associated to exception handled.
     *
     * @param ErrorType $errorType Enum of possible error types.
     */
    public function setErrorType($errorType)
    {
        $this->errorType = $errorType;
    }

    /**
     * Sets content type associated to exception handled.
     *
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }
}

