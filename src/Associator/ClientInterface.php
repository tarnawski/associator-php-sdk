<?php

namespace Associator;

interface ClientInterface
{
    public function request($url, $method = '', $data = []);
}
