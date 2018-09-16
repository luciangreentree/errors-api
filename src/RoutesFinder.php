<?php
namespace Lucinda\MVC\STDERR;

require_once("Route.php");
require_once("ErrorType.php");

/**
 * Locates based on exceptions tag @ XML and instances found routes able to log error info to a storage medium.
 */
class RoutesFinder {
    private $routes = array();

    /**
     * RouteFinder constructor.
     *
     * @param \SimpleXMLElement $xml
     * @throws Exception If tag fails.
     */
    public function __construct(\SimpleXMLElement $xml) {
        $this->setRoutes($xml);
    }

    /**
     * Locates route from XML exceptions tag or latter's exception tag child.
     *
     * @param Application $application
     * @throws Exception
     */
    private function setRoutes(\SimpleXMLElement $xml) {
        // get default route
        $this->routes[""] = $this->compileRoute($xml);
        
        // override with specific route, if set
        $tmp = (array) $xml;
        if(empty($tmp["exception"])) return;
        $tmp = $tmp["exception"];
        if(!is_array($tmp)) $tmp = array($tmp);
        foreach($tmp as $info) {
            $currentClassName = (string) $info['class'];
            if(!$currentClassName) throw new Exception("Exception class not defined!");
            $this->routes[$currentClassName] = $this->compileRoute($info);
        }
    }

    /**
     * Compiles a route based on XML exception/exceptions tag properties.
     *
     * @param \SimpleXMLElement $info
     * @return Route
     */
    private function compileRoute(\SimpleXMLElement $info) {
        $route = new Route();
        $route->setController((string) $info["controller"]);
        $route->setView((string) $info["view"]);
        $route->setHttpStatus((string) $info["http_status"]);
        $route->setContentType((string) $info["content_type"]);
        $route->setErrorType((integer) $info["error_type"]);
        return $route;        
    }

    /**
     * Gets routes detected.
     *
     * @return Route[string] List of routes found by handled exception they match.
     */
    public function getRoutes() {
        return $this->routes;
    }
}