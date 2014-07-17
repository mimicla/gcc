<?php
/**
 * @PSR-0: Env\Data\Definition\Mime
 * ================================
 *
 * @Filename 
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Data\Definition;

class Mime extends \Env\Object
{

    private static $mime = array(
        'text'       => 'text/plain',
        'html'       => 'text/html',
        'javascript' => 'text/javascript',
        'css'        => 'text/css',
        'json'       => 'application/json',
        'csv'        => 'application/vnd.ms-excel, text/plain',
        'form'       => 'application/x-www-form-urlencoded',
        'file'       => 'multipart/form-data',
        'xml'        => 'application/xml',
        'pdf'        => 'application/pdf' 
    );
    
    public static function setType( \Env\Network\Response $response )
    {
        $type = ( isset ( $response->type ) && isset( self::$mime[$response->type] ) ) ? self::$mime[$response->type] : self::$mime['html'];
       
        header( 'Content-type: ' . $type );
    }
}
