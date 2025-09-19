<?php 
$RaizApp = "app/";
spl_autoload_register(function($file) use($RaizApp){

    $file = str_replace("\\","/",$file);

    $RaizApp.=$file.".php";
    
    if(file_exists($RaizApp))
    {
        require $RaizApp;
    }
});
