<?php
/**
 * @PSR-0: Env\View\Page
 * =====================
 *
 * @Filename Page.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\View;

class Page extends \Env\Object 
{

    /**
     * Scripts que se van a cargar en cada pagina hija y que luego sera
     * tomada por el template para cargar sus correspondientes scripts
     * 
     * @var array
     */
    
    public $scripts = array();

    /**
     * Estilos que se cargaran en las paginas hijas, y luego se recorrera
     * en el template para cargar los correspondientes estilos
     * 
     * @var array
     */
    public $stylesheets = array();

    /**
     * Bloques de scripts, a diferencia con $scripts es que el primero son archivos js
     * y estos serian codigos javascript a ejecutar
     * @var array
     */
    public $scriptBlocks = array();

    /**
     * Ayudantes para el manejo de templates
     * @var array
     */
    public $helpers = array();


    public $menu = array();


    public $Meta = array(
        'title'         => 'Definir un titulo !',
        'description'   => '',
        'keywords'      => ''
    );
 

    public function addScript($filename, $attrs = '')
    {
        $this->scripts[] = array(
            'filename'  => $filename,
            'attrs'     => $attrs
        );
        return $this;
    }

    public function addStylesheet($filename, $attrs = '')
    {
        $this->stylesheets[] = array(
            'filename'  => $filename,
            'attrs'     => $attrs
        );
        return $this;
    }

    public function addScriptBlock($data, $is_file = false) 
    {
        $content = ($is_file) ? $data : $data; // implementar una lectura de archivo js 
        $this->scriptBlocks[] = $data;
        return $this;
    }

    public function setMetaData( array $content = array() )
    {
        $this->Meta = array_merge( $this->Meta, $content );
        return $this;
    }


    public function debug()
    {
        $instance = Page::getInstance();
        return print_r($instance, true);
    }
}