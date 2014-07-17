<?php

    include __DIR__.'/loader.php';    
    register_shutdown_function( array( 'Env\\Aplication\\Dispatch', 'shutdown' ) );
 	
