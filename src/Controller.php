<?php
namespace Lucinda\MVC\STDERR;

/**
 * Encapsulates an abstract MVC controller that routes exceptions that once extended will be useful to:
 * - enable exception-specific reporting policies
 * - allow exception-specific view setup (eg: exceptions that map to multiple views or views using templating)
 * - any other response-changing strategies
 */
abstract class Controller
{
    protected $application;
    protected $request;
    protected $response;
    protected $reporters;

    /**
     * Controller constructor.
     * @param Application $application Encapsulates application settings detected from xml and development environment.
     * @param Request $request Encapsulates error request, including exception/error itself and route that maps it.
     * @param Response $response Encapsulates response to send back to caller.
     */
    public function __construct(Application $application, Request $request, Response $response)
    {
        $this->application = $application;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Executes controller logic. Here is where each extending controller will need to write its logic to.
     */
    abstract public function run();
}
