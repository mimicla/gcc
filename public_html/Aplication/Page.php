<?php
/**
 * @PSR-0: Env\Aplication\Page
 * ===========================
 *
 * @Filename Page.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Aplication;

\Env\Module\Loader::getInstance()->load('Session',  'Env\Module' );

use Env\Config\Configure AS Config,
    Env\Module;

class Page extends \Env\Controller\Controller {

    /**
     * No permiter ninguna accion por defecto
     * @var array
     */
    protected $allow = '*';

    public function before_action()
    {

        
        
        // if( is_array ( $this->allow ) && ! in_array( $this->request->origen['action'] , $this->allow ) ) {
        //     if ( Module\Session\Session::get( 'login' ) === false ) {
        //         header('HTTP/1.1 401 Unauthorized');
        //         header('url: ' . $this->request->base . '/login' );
        //         header('Location: ' . $this->request->base . '/login' );
        //         die;
        //     }
        // }

        $this->Page = \Env\View\Page::getInstance();
        $this->Page -> addStylesheet( $this->request->base . '/assets/css/theme.css')
                    -> addStylesheet( $this->request->base . '/assets/font/fontello.css')
                    -> setMetaData( array( 'title' => 'Hotel Platjador') )
                    -> addScript ( '//cdnjs.cloudflare.com/ajax/libs/mustache.js/0.7.2/mustache.min.js')
                    -> addScript ( $this->request->base .'/assets/js/jquery.min.js')
                    -> addScript ( $this->request->base .'/assets/js/bootstrap.min.js')
                    //-> addScript ( '//code.jquery.com/jquery-ui.min.js')
                    // -> addScript ( $this->request->base .'/assets/js/jquery.min.js' )
                    -> addScript ( $this->request->base .'/assets/js/jquery-ui.min.js' )
                    -> addScript ( $this->request->base .'/assets/js/page.js' )
                    ;

        $this->Page->menu = array(
            
            array(
                'link'      => $this->request->base,
                'active'    => $this->request->base == $this->request->request_uri ,
                'icon'      => 'icon-home-1',
                'label'     => 'Inicio'
            ), 

            array(
                'link'      => $this->request->base . '/documentos',
                'active'    => $this->request->base . '/documentos' == $this->request->request_uri ,
                'icon'      => 'icon-quote',
                'label'     => 'Contenidos'
            ),

            array(
                'link'      => $this->request->base . '/opiniones',
                'active'    => $this->request->base . '/opiniones' == $this->request->request_uri ,
                'icon'      => 'icon-calendar-1',
                'label'     => 'Agenda'
            ),

            array(
                'link'      => $this->request->base . '/configuracion',
                'active'    => $this->request->base . '/configuracion' == $this->request->request_uri ,
                'icon'      => 'icon-cog-alt',
                'label'     => 'Configuración'
            ),

            array(
                'link'      => $this->request->base . '/logout',
                'active'    => $this->request->base . '/logout' == $this->request->request_uri ,
                'icon'      => 'icon-logout',
                'label'     => 'Cerrar Sesión',
                'attr'      => array(
                    array( 'key' => 'no-trigger', 'value' => true )
                )
            )

        );

    }

    public function menu()
    {

        return function( $content )
        {
            $ret_menu = array();

            foreach( $this->Page->menu AS $item ) {

                if( $item['link'] == $this->request->request_uri ) {
                    $item['active'] = true;
                }
                $item['label'] = $this->request->request_uri;
                $ret_menu[] = $item;
            }
            
            return $ret_menu;
        };
    }
    
    public function index() {}

    /**
     * Accion global en caso de no encontrase en el dispatcher
     * */
    public function _global()
    {
        $this->index();
    }


    public function lang()
    {
        if( !isset( $this->request->origen['lang'] ) ) {
            return;
        }
        
        Module\Session\Session::set('_lang', $this->request->origen['lang'] );

        Module\Loader::getInstance()->load('Fastchecking',  'Env\Module' );
        
        $api = new Module\Fastchecking\Api();

        if ( ( $id_ficha = $api->getIdDocuments( $this->request->origen['lang'] ) ) !== false ) {
            Module\Session\Session::get( '_docId', $id_ficha['int'] );
        }




        return;
    }

    public function file()
    {
        if( !isset( $this->request->origen['id'] ) ) {
            return;
        }

        \Env\Module\Loader::getInstance()->load('Fastchecking',  'Env\Module' );

        $api = new Module\Fastchecking\API();
        $id  = $this->request->origen['id'];

        if( ( $document = $api->getDocumentById( $id ) ) === false ) {
            header('Location: '. $this->request->base .'/');
            die;
        }

        $file = base64_decode($document); 
        header("Content-type: pdf"); 
        header("Content-Disposition: attachment; filename=documento_".$id.".pdf"); 
        exit($file);


    }

    public function ficha_ingreso()
    {
        
        $lang = ( ( $lang = Module\Session\Session::get('_lang') ) !== false ) ? $lang : 'es';
        \Env\Module\Loader::getInstance()->load('Fastchecking',  'Env\Module' );
        
        $api = new Module\Fastchecking\API();

        if( ( $data = $api->getIdDocuments($lang) ) === false ) {
            die('No se ha podido procesar la solicitud ');
        }

        $this->request->origen['id'] = $data['int'];
        $this->file();

    }

    public function ficha_policial()
    {
        if( Module\Session\Session::get( '_docId' ) === false ) {
            //die();
        }
        $lang = ( ( $lang = Module\Session\Session::get('_lang') ) !== false ) ? $lang : 'es';
        \Env\Module\Loader::getInstance()->load('Fastchecking',  'Env\Module' );
        
        $api = new Module\Fastchecking\API();
        $api->set(array(
                'resGuid' => Module\Session\Session::get( '_reserva' )
            ))
            ->call('GetFichaPolicia')
            ->debug();


        /*
        if( ( $data = $api->getIdDocuments($lang) ) === false ) {
            die('No se ha podido procesar la solicitud ');
        }

        $this->request->origen['id'] = $data['int'];
        $this->file();
        */

    }

    
    public function after_action()
    {
        $this->render();
    }

}
