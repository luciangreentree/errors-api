<?php
namespace Lucinda\MVC\STDERR;

require_once("ErrorReportersFinder.php");
require_once("ErrorRenderersFinder.php");
require_once("RoutesFinder.php");
require_once("ReportersList.php");

/**
 * Detects settings necessary to configure MVC Errors API based on contents of XML file and development environment:
 * - whether or not error details should be displayed
 * - default content types of rendered response
 * - location of controllers that map exceptions thrown
 * - location of views that map exceptions thrown
 * - possible objects to use in reporting error to
 * - possible objects to use in rendering response
 * - possible routes that map controllers/views to exception
 */
class Application
{
    private $simpleXMLElement;
    private $controllersPath, $viewsPath, $defaultContentType, $displayErrors = false;
    private $reporters=array(), $renderers=array(), $routes=array();

    /**
     * Performs detection process.
     *
     * @param string $xmlPath Relative location of XML file containing settings.
     * @param string $developmentEnvironment Development environment server is running into (eg: local, dev, live)
     * @throws Exception If detection fails due to an error.
     */
    public function __construct($xmlPath, $developmentEnvironment) {
        if(!file_exists($xmlPath)) throw new Exception("XML configuration file not found!");
        $this->simpleXMLElement = simplexml_load_file($xmlPath);

        $this->setDisplayErrors($developmentEnvironment);
        $this->setDefaultContentType();
        $this->setControllersPath();
        $this->setViewsPath();
        $this->setReporters($developmentEnvironment);
        $this->setRenderers();
        $this->setRoutes();
    }
    
    /**
     * Sets default response content type. Maps to tag application.default_default_content_type @ XML.
     */
    private function setDefaultContentType() {
        $this->defaultContentType = (string) $this->simpleXMLElement->application->default_content_type;
    }
    
    /**
     * Gets default response content type.
     *
     * @return string
     */
    public function getDefaultContentType() {
        return $this->defaultContentType;
    }
    
    /**
     * Sets path to controllers folder. Maps to tag application.paths.controllers @ XML.
     */
    private function setControllersPath() {
        $this->controllersPath = (string) $this->simpleXMLElement->application->paths->controllers;
    }
    
    /**
     * Gets path to controllers folder.
     *
     * @return string
     */
    public function getControllersPath() {
        return $this->controllersPath;
    }
    
    /**
     * Sets views folder. Maps to application.paths.views @ XML.
     */
    private function setViewsPath() {
        $this->viewsPath = (string) $this->simpleXMLElement->application->paths->views;
    }
    
    /**
     * Gets path to views folder.
     *
     * @return string
     */
    public function getViewsPath() {
        return $this->viewsPath;
    }
    
    /**
     * Sets whether or not error details should be displayed in rendered response. Maps to tag application.display_errors @ XML.
     * 
     * @param string $developmentEnvironment Environment application is running into (eg: live, dev, local)
     */
    private function setDisplayErrors($developmentEnvironment) {
        $this->displayErrors = ((string) $this->simpleXMLElement->application->display_errors->{$developmentEnvironment} ? true : false);
    }
    
    /**
     * Gets whether or not error details should be displayed in rendered response
     * 
     * @return boolean
     */
    public function getDisplayErrors() {
        return $this->displayErrors;
    }

    /**
     * Sets ErrorReporter instances that will later be used to report exception to. Maps to tag reporters @ XML.
     *
     * @param string $developmentEnvironment Environment application is running into (eg: live, dev, local)
     */
    private function setReporters($developmentEnvironment) {
        $erp = new ErrorReportersFinder(
            $this->simpleXMLElement->reporters->{$developmentEnvironment},
            (string) $this->simpleXMLElement->application->paths->reporters
            );
        $this->reporters = new ReportersList($erp->getReporters());
    }

    /**
     * Gets ErrorReporter instances that will later on be used to report exception to
     *
     * @return ReportersList Configurable list of error reporters.
     */
    public function getReporters() {
        return $this->reporters;
    }

    /**
     * Sets ErrorRenderer instances that will later be used to render response to exception. Maps to tag renderers @ XML.
     */
    private function setRenderers() {
        $erf = new ErrorRenderersFinder(
            $this->simpleXMLElement->renderers,
            (string) $this->simpleXMLElement->application->paths->renderers
            );
        $this->renderers = $erf->getRenderers();
    }

    /**
     * Gets ErrorRenderer instances that will later be used to render response to exception
     *
     * @return ErrorRenderer[string] List of error renderers by content type.
     */
    public function getRenderers() {
        return $this->renderers;
    }

    /**
     * Sets routes that map exceptions that will later on be used to resolve controller & view. Maps to tag exceptions @ XML
     */
    private function setRoutes() {
        $rf = new RoutesFinder($this->simpleXMLElement->exceptions);
        $this->routes = $rf->getRoutes();
    }

    /**
     * Gets routes that map exceptions that will later on be used to resolve controller & view.
     *
     * @return Route[string] List of routes by exception class name.
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * Gets a pointer to XML file reader.
     *
     * @return \SimpleXMLElement
     */
    public function getXML() {
        return $this->simpleXMLElement;
    }
}

