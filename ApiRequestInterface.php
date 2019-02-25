<?php
namespace Mosaika\RadBundle;

use Symfony\Component\HttpFoundation\Request;


interface ApiRequestInterface
{
    public function isValid();

    public function has($key);

    public function get($key);

    public function loadRequest(Request $request);

    public function all();
}