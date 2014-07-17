<?php

namespace Env\Exception;

class CouchDBException extends \Exception
{
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }    
}
