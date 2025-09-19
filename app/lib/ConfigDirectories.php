<?php 
namespace lib;

trait ConfigDirectories 
{
    //// directorio raiz para los componentes
    private string $raizComponents = "resources.views.components.";

    /// directorio node modules 

    private string $raizModuleNode = "node_modules/";

    /// directorio raiz para el asset public del dashboard
  
    private string $raizAsset = "public/asset/";
  
    private string $raizLayout = "resources.views.layouts.";
    public function getComponents(string $file)
    {
      return  str_replace(".","/",$this->raizComponents.$file).".blade.php";
    }

    public function Layouts(string $file)
    {
      return str_replace(".","/",$this->raizLayout.$file).".blade.php";
    }

    public function asset(string $file)
    {
      return $_ENV['BASE_URL'].$this->raizAsset.$file;
    }

    public function NodeModule(string $directorio):string
    {
     return URL_BASE.$this->raizModuleNode.$directorio;
    }

}