<?php
/**
 * @PSR-0: Env\Network\Response
 * ============================
 *
 * @Filename Response.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Network;

class Response extends \Env\Object
{

    const STATUS_OK = 200;

    private $header;

    public $body;

    public $type = 'html';

    public $status = 200;

    public function on_construct()
    {
        $this->status = static::STATUS_OK;
    }

    public function render()
    {
        
        \Env\Data\Definition\Mime::setType( $this );
        echo $this->body;
    }
}
