<?php
/**
 * @PSR-0: Env\Data\Log
 * ====================
 *
 * @Filename Log.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Data;

class Log extends Collection
{

    protected function procese_get( $key )
    {
        $config = \Env\Config\Configure::getInstance();
        if ( ( $debug_level = $config->debug_level ) === false ) {
            return '[*] No hay definido el nivel de debug';
        }

        if( $debug_level == E_ALL ) {
            return $this->buffer;
        }

        if( $key < $debug_level ) {
                return $this->buffer[$key];
        }
    }
}
