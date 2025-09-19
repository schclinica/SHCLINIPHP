<?php 
namespace models;

use report\implementacion\Model;

class RedesSocialesClinicaMedico extends Model
{
    protected string $Table = "redes_sociales_clinica_medico ";

    protected $Alias = "as rscm ";

    protected string $PrimayKey = "id_red_social_clim";
}