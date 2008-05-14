<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Myclasses
{
    /**
     * includes the directory application\my_classes\ in your includes directory
     *
     */
    function index()
    {
        //includes the directory application\my_classes\
       //for PHP/APACHE on windows platforms change the ':' before BASEPATH to ';'
        ini_set('include_path', ini_get('include_path').':'.BASEPATH.'application/my_classes/');
    }
}

?>