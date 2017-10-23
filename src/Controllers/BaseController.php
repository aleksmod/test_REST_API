<?php

namespace Controllers;

class BaseController
{

    /**
     * Contains decoded request body.
     *
     * @var array|null
     */
    protected $requestBody;


    /**
     * Instantiates controller
     *
     * @return self
     */
    public function __construct()
    {
        static::init();
    }

    /**
     * Holds required initialization for controller.
     *
     * @return void
     */
    protected function init()
    {
    }

    /**
     * Decodes JSON request body and sets result into `requestBody`.
     *
     * @param mixed $body Request body data.
     *
     * @return self
     */
    public function setRequestBody($body)
    {
        $decoded = json_decode($body, true);
        $this->requestBody = !empty($decoded) ? $decoded : null;
        return $this;
    }
}
