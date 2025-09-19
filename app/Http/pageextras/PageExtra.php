<?php 
namespace Http\pageextras;
use lib\BaseController;
use lib\View;

class PageExtra 
{
    /// redireccionar a una página de no autorizado

    public static function PageNoAutorizado()
    {
     View::View_("pageExtra.no-autorizado");
    }

    /// error 403

    public static function Page403()
    {
        View::View_("pageExtra.page403");
    }

    /// error 404
    public static function Page404()
    {
        View::View_("pageExtra.page404");
    }

}