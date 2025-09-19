<?php 
namespace models;
use report\implementacion\Model;

use function Windwalker\where;

class ProductoFarmacia extends Model
{
    protected string $Table = "productos_farmacia "; 
    protected $Alias = "as prod_far ";

    protected string $PrimayKey = "id_producto";

    /** Mostrar los productos existentes */
    public function mostrar(string $fechaActual,$rol,$sede)
    {
       
       if($rol !== "admin_farmacia"){
        return $this->query()->Join("tipo_producto_farmacia as tpf","prod_far.tipo_id","=","tpf.id_tipo_producto")
       ->Join("presentacion_farmacia as pf","prod_far.presentacion_id","=","pf.id_pesentacion")
       ->Join("laboratorio_farmacia lf","prod_far.laboratorio_id","=","lf.id_laboratorio")
       ->Join("grupo_terapeutico_farmacia as gtf","prod_far.grupo_terapeutico_id","=","gtf.id_grupo_terapeutico")
       ->Join("embalaje_farmacia ef","prod_far.empaque_id","=","ef.id_embalaje")
       ->Join("proveedores_farmacia as prof","prod_far.proveedor_id","=","prof.id_proveedor")
       ->LeftJoin("almacen_productos as ap","ap.producto_id","=","prod_far.id_producto")
       ->select("prod_far.id_producto","prod_far.nombre_producto","prod_far.precio_venta",
               "if(sum(ap.stock) is null,0,sum(ap.stock)) as stock","prod_far.stock_minimo",
               "tpf.name_tipo_producto","pf.name_presentacion",
               "lf.name_laboratorio","gtf.name_grupo_terapeutico","ef.name_embalaje","prof.proveedor_name","prod_far.deleted_at_prod",
               "prod_far.code_barra","tpf.id_tipo_producto","pf.id_pesentacion","lf.id_laboratorio",
               "gtf.id_grupo_terapeutico","ef.id_embalaje","prof.id_proveedor","prod_far.lote")
       ->GroupBy(["prod_far.id_producto"])
       ->get();
       }

       return $this->query()->Join("tipo_producto_farmacia as tpf","prod_far.tipo_id","=","tpf.id_tipo_producto")
       ->Join("presentacion_farmacia as pf","prod_far.presentacion_id","=","pf.id_pesentacion")
       ->Join("laboratorio_farmacia lf","prod_far.laboratorio_id","=","lf.id_laboratorio")
       ->Join("grupo_terapeutico_farmacia as gtf","prod_far.grupo_terapeutico_id","=","gtf.id_grupo_terapeutico")
       ->Join("embalaje_farmacia ef","prod_far.empaque_id","=","ef.id_embalaje")
       ->Join("proveedores_farmacia as prof","prod_far.proveedor_id","=","prof.id_proveedor")
       ->LeftJoin("almacen_productos as ap","ap.producto_id","=","prod_far.id_producto")
       ->select("prod_far.id_producto","prod_far.nombre_producto","prod_far.precio_venta",
               "if(sum(ap.stock) is null,0,sum(ap.stock)) as stock","prod_far.stock_minimo",
               "tpf.name_tipo_producto","pf.name_presentacion",
               "lf.name_laboratorio","gtf.name_grupo_terapeutico","ef.name_embalaje","prof.proveedor_name","prod_far.deleted_at_prod",
               "prod_far.code_barra","tpf.id_tipo_producto","pf.id_pesentacion","lf.id_laboratorio",
               "gtf.id_grupo_terapeutico","ef.id_embalaje","prof.id_proveedor","prod_far.lote")
        ->Where("ap.sede_id","=",$sede)
       ->GroupBy(["prod_far.id_producto"])
       ->get();
    }

    /** 
     * Registrar nuevo producto
     */
    public function saveProducto(string $productoName,float $precio,int|null $stockminimo,
    int $tipo,int $presentacion,int $laboratorio,int $grupo,int $empaque,int $proveedorid,string|null $code,$lote)
    {
        $ExistePoroducto  = $this->query()->Where("nombre_producto","=",$productoName)
        ->Or("code_barra","=",$code)->first();
       
        if($ExistePoroducto)
        {
            return 'existe';
        }

        return  $this->Insert([
            "code_barra" => $code,
            "nombre_producto" => $productoName,"precio_venta" => $precio,
            "stock_minimo" => is_null($stockminimo)? 5:$stockminimo,
            "tipo_id" => $tipo,"presentacion_id" => $presentacion,
            "laboratorio_id" => $laboratorio,"grupo_terapeutico_id" => $grupo,
            "empaque_id" => $empaque,"proveedor_id" => $proveedorid,
            "lote" => $lote
        ]);
    }

     /** 
     * Modificar nuevo producto
     */
    public function updateProducto(string $productoName,float $precio,int|null $code,int|null $stockminimo,
    int $tipo,int $presentacion,int $laboratorio,int $grupo,int $empaque,int $proveedorid,int $id,string $lote)
    {
         
        return  $this->Update([
            "id_producto" => $id,
            "nombre_producto" => $productoName,"precio_venta" => $precio,
            "code_barra" => $code,"stock_minimo" => is_null($stockminimo)? 5:$stockminimo,
            "tipo_id" => $tipo,"presentacion_id" => $presentacion,
            "laboratorio_id" => $laboratorio,"grupo_terapeutico_id" => $grupo,
            "empaque_id" => $empaque,"proveedor_id" => $proveedorid,"lote" => $lote
        ]);
    }

    /*Borrar por completo el productos registrado*/
    public function Borrar(int $id)
    {
        return $this->delete($id);
    }

    /** Habilitar e inhabilitar los productos */
    public function HabilitarInhabilitarProductosRegistrados(int $id,string $Condition,string $fechaActual)
    {
        return $this->Update([
            "id_producto" => $id,
            "deleted_at_prod" =>  $Condition === 'i' ? $fechaActual:null
        ]);
    }

}