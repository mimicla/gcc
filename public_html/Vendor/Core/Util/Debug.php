<?php
/**
 * @PSR-0:  Env\Util\Debug
 * ==========================
 *
 * @Filename 
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace  Env\Util;

class Debug extends \Env\Object
{
    public function show()
    {
        echo \Env\Data\Debug::getInstance()->show();
    } 
}
