<?php

namespace Http\controllers;

use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Compania;
use models\Cuota;
use models\Paciente;
use models\Poliza;
use models\Prima_Pagos;
use models\Producto;
use models\Ramo;
use models\Usuario;

use function PHPSTORM_META\map;

class PolizaController extends BaseController
{
 
    /// método para visualizar la página de poliza
    public static function index($id)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            /// consultamos paciente por su ID
            $clienteRow = new Paciente;
            $TodasPolizasPorCliente = $clienteRow->procedure("proc_show_polizas", "c", [$id]);



            $cliente = $clienteRow->query()
                ->join("persona as p", "pc.id_persona", "=", "p.id_persona")
                ->join("tipo_documento as td", "p.id_tipo_doc", "=", "td.id_tipo_doc")
                ->Where("pc.id_paciente", "=", $id)->get();

            self::View_("paciente.poliza", compact("cliente", "TodasPolizasPorCliente"));
        }else{
            PageExtra::PageNoAutorizado();
        }
    }

    /// Agregar nuevas polizas al cliente
    public static function create($id)
    {
        self::NoAuth();

        if (self::profile()->rol === 'Director') {

            $clienteRow = new Paciente;
            $cliente = $clienteRow->query()
                ->join("persona as p", "pc.id_persona", "=", "p.id_persona")
                ->join("tipo_documento as td", "p.id_tipo_doc", "=", "td.id_tipo_doc")
                ->Where("pc.id_paciente", "=", $id)->get();


            /// mostrar los vendedores
            $User = new Usuario;
            $vendedores = $User->query()->where("rol", "=", "vendedor")->get();

            /// mostrar las compañias
            $Compania = new Compania;
            $companias = $Compania->query()
                ->join("paises as ps", "com.pais_id", "=", "ps.id_pais")
                ->where("ps.name_pais", "=", "PERU")
                ->get();

            /// mostrar los RAMOS
            $Ramo = new Ramo;
            $Ramos = $Ramo->query()
                ->join("paises as ps", "ram.pais_id", "=", "ps.id_pais")
                ->where("ps.name_pais", "=", "PERU")
                ->get();

            /// mostramos a los ejecutivos de la cuenta
            $UserEjec = new Usuario;
            $ejecutivos = $UserEjec->query()->where("rol", "=", "ejecutivo")->get();

            self::View_("paciente.create_poliza", compact("cliente", "vendedores", "companias", "Ramos", "ejecutivos"));
        }else{
            PageExtra::PageNoAutorizado();
        }
    }


    /**
     * Mostrar los productos por ramo seleccionado
     */
    public static function showProductosPorRamo($id)
    {
        self::NoAuth();

        $producto = new Producto;

        $productos = $producto->query()
            ->join("ramos as ram", "prod.ramo_id", "=", "ram.id_ramo")
            ->where("prod.ramo_id", "=", $id)
            ->get();

        self::json(["response" => $productos]);
    }

    /**REGISRAR POLIZA */
    public static function store($id)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            $poliza = new Poliza;

            $polizaNew = $poliza->Insert([
                "num_poliza" => self::post("num_poliza"),
                "asegurado" => self::post("asegurado"),
                "cli_id" => $id,
                "sub_agente" => self::post("sub_agente"),
                "compania_id" => self::post("compania"),
                "producto_id" => self::post("producto"),
                "comi_compania" => self::post("comision_compania"),
                "comi_sub_agente" => self::post("comision_agente"),
                "tipo_vigencia" => self::post("tipo_vigencia"),
                "vigencia_inicio" => self::post("vigencia_inicio"),
                "vigencia_fin" => self::post("vigencia_fin"),
                "fecha_emision" => self::post("fecha_emision"),
                "moneda" => self::post("moneda"),
                "desc_asegurada" => self::post("desc_aseguradora"),
                "ejecutivo_id" => self::post("ejecutivo")
            ]);


            if ($polizaNew) {
                self::Session("mensaje", "ok");
                /// redirigimos a la misma ruta
                self::RedirectTo("polizas/" . $id);
            } else {
                self::Session("mensaje_error", "error");
                /// redirigimos a la misma ruta
                self::RedirectTo("polizas/" . $id);
            }
        }
    }

    /// ver pagona de plande pagos primas
    public static function plan_pagos_get($id)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            $poliza = new Poliza;

            $polizaSelect = $poliza->query()->where("id_poliza", "=", $id)->get();

            /// mostrar los pagos
            $pago = new Prima_Pagos;

            $pagosPolizas = $pago->procedure("proc_show_prima_pagospoliza", "c", [$id]);

            self::View_("pago_poliza.prima_pagos", compact("polizaSelect", "pagosPolizas"));
        }
    }

    public static function createpagosPrimaPoliza($id)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            $poliza = new Poliza;
            /// mostrar los vendedores
            $User = new Usuario;
            $vendedores = $User->query()->where("rol", "=", "vendedor")->get();

            $polizaSelect = $poliza->procedure("proc_show_polizas_desc", "c", [$id]);

            self::View_("pago_poliza.create", compact("polizaSelect", "vendedores"));
        }
    }

    /**
     * Método para registrar los pagos prima poliza
     */
    public static function storePagosPoliza($id)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            $primapagos = new Prima_Pagos;

            $primapagosNew = $primapagos->Insert([
                "poliza_id" => $id,
                "num_primera_cuota" => self::post("num_primera_cuota"),
                "tipo_doc" => self::post("tipo_doc"),
                "fecha_solicitud_seguro" => self::post("fecha_solicitud"),
                "fecha_entrega_poliza" => self::post("fecha_entrega"),
                "suma_cobertura_principal" => self::post("suma_asegurada"),
                "tipo_pago" => self::post("tipo_pago"),
                "prima_comercial" => self::post("prima_comercial"),
                "prima_neta" => self::post("prima_neta"),
                "prima_total_bruta" => self::post("prima_totalbruta"),
                "motivo" => self::post("motivo"),
                "num_operacion" => self::post("num_operacionseguros"),
                "importe_comision_compania" => self::post("importecomisioncompania"),
                "importe_comision_subagente" => self::post("importecomisionsubagente"),
                "num_cuotas" => self::post("num_cuotas"),
                "num_operacion_primera_cuota" => self::post("num_primera_cuota"),
                "importe_de_cada_cuota" => self::post("importe_primera_cuota"),
                "fecha_primer_vencimiento" => self::post("fecha_primera_cuota")
            ]);


            if ($primapagosNew) {
                self::Session("mensaje", "ok");

                $cuota = new Poliza;
                $i = 1;
                $num_cuotainit = self::post("num_cuotas");
                while ($i <= self::post("num_cuotas")) {


                    $cuota->procedure("proc_cuotas_registrate", "insertar", [
                        $i, $num_cuotainit, self::post("fecha_primera_cuota"),
                        self::post("importe_primera_cuota"), null, null, $i
                    ]);
                    $num_cuotainit++;
                    $i++;
                }

                self::RedirectTo("poliza/" . $id . "/prima/plan-pagos");
            } else {
                self::Session("mensaje_error", "error");
                /// redirigimos a la misma ruta
                self::RedirectTo("poliza/" . $id . "/prima/plan-pagos");
            }
        }
    }

    public static function cuotasPoliza($id)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            $pagoscuota = new Poliza;
            $sumaimporte = 0;

            $cuotasPoliza = $pagoscuota->procedure("proc_cuotas_prima_poliza", "c", [$id]);
            foreach ($cuotasPoliza as $cuota) {
                $sumaimporte += $cuota->importe;
            }
            self::View_("pago_poliza.cuotas", compact("cuotasPoliza", "sumaimporte"));
        }
    }

    public static function editarCuota($id)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            $cuota = new Cuota;
            $cuotaData = $cuota->query()->where("id_cuota", "=", $id)->get();
            return self::View_("pago_poliza.editar_cuotas_poliza", compact("cuotaData"));
        }
    }

    /**
     * Modificar la cuota
     */
    public static function update($id, $id_prima)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            $cuota = new Cuota;
            $cuotaData = $cuota->Update([
                "id_cuota" => $id,
                "num_cupon" => self::post("num_cupon"),
                "fecha_vencimiento" => self::post("fechav"),
                "importe" => self::post("importe"),
                "fecha_pago" => self::post("fecha_pago"),
                "factura" => self::post("factura"),
                "observacion" => self::post("observacion")
            ]);
            if ($cuotaData) {
                self::Session("mensaje", "ok");
                self::RedirectTo("poliza/plan-pagos/cuotas/" . $id_prima);
            } else {
                self::Session("mensaje_error", "error");
                /// redirigimos a la misma ruta
                self::RedirectTo("poliza/plan-pagos/cuotas/" . $id_prima);
            }
        }
    }

    /// ver documentos de la poliza
    public static function documentos($id)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            $pagoscuota = new Poliza;
            $cuotasPoliza = $pagoscuota->procedure("proc_cuotas_prima_poliza", "c", [$id]);
            
            $documentos = new Prima_Pagos;

            $documentosData = $documentos->query()->where("id_prima_pago","=",$id)->get();
            self::View_("pago_poliza.documentos", compact("cuotasPoliza","documentosData"));
        }
    }


    /// agregar nuevos documentos a las polizas de pagos
    public static function updateplapagosadddocumentos($id)
    {
        self::NoAuth();
        if (self::profile()->rol === 'Director') {
            
            if(self::file_size("documento") > 0 and self::file_Type("documento") ==="application/pdf")
            {
                $DestinoDoc = "public/asset/documentos_pdf/";

                  if(!file_exists($DestinoDoc))
                  {
                     mkdir($DestinoDoc);
                  }

                  $Nombre_archivo = self::file_Name("documento");
                
                  $NuevoNameDoc = explode(".",$Nombre_archivo);
                  $NuevoNameDoc =date("YmdHis").".".$NuevoNameDoc[1];
 
                  
                  $DestinoDoc.=$NuevoNameDoc;

                  if(move_uploaded_file(self::ContentFile("documento"),$DestinoDoc)){
                  $polizapago = new Prima_Pagos;

                  $data = $polizapago->Update([
                    "id_prima_pago"=>$id,
                    "documento" => $NuevoNameDoc
                  ]);

                  if($data)
                  {
                    self::Session("mensaje", "ok");
                    self::RedirectTo("poliza/plan-pagos/documenstos/".$id);
                } else {
                    self::Session("mensaje_error", "error");
                    /// redirigimos a la misma ruta
                    self::RedirectTo("poliza/plan-pagos/documenstos/".$id);
                }
                
            }else{
                self::Session("error","Seleccione un archivo pdf");
            }
        }
            
        }
    }
}
