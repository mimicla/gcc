<?php
/**
 * @PSR-0: Env\Data\Collection
 * ============================
 *
 * @Filename Collection.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Data;

class Collection extends \Env\Object
        implements Definition\Collection 
{
    private $buffer;

    public function procese_set( $key, $value )
    {
        $this->buffer[$key] = $value;
    }

    public function procese_get( $key )
    {
        if( false !== ( $buffer = $this->__find( $key ) ) ) {
            return $buffer;
        }
        return false;
    }    

    public function __find( $key )
    {
        if( isset( $this->buffer[$key] ) )
            return $this->buffer[$key];

        // permitir que se busque estilo xpath
        if( false !== ( $hasLevels = strpos( $key, '.' ) ) ) {

            $path = explode( '.', $key ) ;
            $tmpBuffer = $this->buffer;

            foreach( $path AS $index => $evaluateKey ) {
                
                if( !isset( $tmpBuffer[$evaluateKey] ) ) {
                    return false;
                }
                
                $tmpBuffer = $tmpBuffer[$evaluateKey];
                
            }

            return $tmpBuffer;
        }
    }

    public function __set($key, $value)
    {
        $this->procese_set( $key, $value );
    }

    public function __get( $key )
    {
        return $this->procese_get( $key );
        
    }

}