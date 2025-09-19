<?php 
 namespace lib;
 use Windwalker\Edge\Edge;
 use Windwalker\Edge\Loader\EdgeFileLoader;

 class View 
 {
    private static string $RaizViewApp = "resources.views.";


    public static function View_ (string $vista,array $data=[])
    {
     self::$RaizViewApp = str_replace(".","/",self::$RaizViewApp.$vista).".blade.php";
     
     if(file_exists(self::$RaizViewApp))
     {
        $Blade = new Edge(new EdgeFileLoader());

       echo $Blade->render(self::$RaizViewApp,$data);
       
     }
     else{

        echo "error 404";
     }
     
    }
 }