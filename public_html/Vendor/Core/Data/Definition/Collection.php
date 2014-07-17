<?php
/**
 * @PSR-0:  Env\Data\Definition\Collection
 * =======================================
 *
 * @Filename Collection.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace   Env\Data\Definition;

interface Collection
{
    public function procese_set( $key, $value );

    public function procese_get( $key );

}
