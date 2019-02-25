<?php
namespace Mosaika\RadBundle;

use Symfony\Component\HttpFoundation\Request;


class BaseApiInput
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param string $key
     * @return boolean
     */
    public function has($key){
        return $this->get($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key){
        return $this->request->get($key);
    }

    /**
     * @param Request $request
     * @return self
     */
    public function loadRequest(Request $request){
        $this->request = $request;
        return $this;
    }
}