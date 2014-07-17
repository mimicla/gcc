<?php
/**
 * @PSR-0: Env\Exception\Object
 * ============================
 *
 * @Filename 
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Exception;

class Object extends \Exception
{
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }    
}
