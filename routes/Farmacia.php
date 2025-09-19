<?php

/*========================================= inicializamos la sesion en caso no exista ==============================*/

use Http\controllers\{ClienteFarmaciaController, ComprasController, FarmaciaController,ProductoFarmaciaController, VentaFarmaciaController};
 
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

/**definimos las rutas de la app farmacia */
$route->get("/app/farmacia",[FarmaciaController::class,'index']);
/// guardar el tipo de productos de la farmacia
$route->post("/app/farmacia/save_tipo_producto",[FarmaciaController::class,'saveTipoProducto']);
/// mostrar los tipos de productos
$route->get("/app/farmacia/mostrar_tipo_productos/{mostrar}",[FarmaciaController::class,'mostrarTipoProductos']);

/// modificar los tipo de productos
$route->post("/app/farmacia/update/{id}",[FarmaciaController::class,'modificarTipoProducto']);
/// Habilitar e inhabilitar los tipos de productos
$route->post("/app/farmacia/habilitar_e_inhabilitar/tipo_producto/{id}/{condition}",[FarmaciaController::class,'HabilitarInhabilitarTipoProducto']);

/// eliminar de la base de datos a un tipo de producto seleccionado
$route->post("/app/farmacia/tipo_producto/delete/{id}",[FarmaciaController::class,'DeleteTipoProducto']);
/**
 * Rutas para las presentaciones
 */
/*1.- Ruta para registrar las presentaciones*/ 
$route->post("/app/farmacia/presentacion/save",[FarmaciaController::class,'savePresentacion']);
$route->get("/app/farmacia/presentaciones/all/{mostrar}",[FarmaciaController::class,'mostrarPresnetaciones']);
// modificar los datos de la presentación
$route->post("/app/farmacia/presentacion/update/{id}",[FarmaciaController::class,'updatePresentacion']);
/// Habilitar e inhabilitar las presentaciones
$route->post("/app/farmacia/habilitar_e_inhabilitar/presentacion/{id}/{condition}",[FarmaciaController::class,'HabilitarInhabilitarPresentaciones']);
// eliminar la presentación por completo
$route->post("/app/farmacia/presentacion/delete/{id}",[FarmaciaController::class,'DeletePresentacion']);
/** Rutas para laboratorio */
/** 1.- Ruta para registrar nuevo laboratorio */
$route->post("/app/farmacia/laboratorio/save",[FarmaciaController::class,'saveLaboratorio']);
/// mostrar todos los laboratorios
$route->get("/app/farmacia/laboratorio/all/{mostrar}",[FarmaciaController::class,'mostrarLaboratorios']);

/// modificar datos del laboratorio
$route->post("/app/farmacia/laboratorio/update/{id}",[FarmaciaController::class,'updateLaboratorio']);
/// Habilitar e inhabilitar datos del laboratorio
$route->post("/app/farmacia/laboratorio/habilitar_inhabilitar/{id}/{condition}",[FarmaciaController::class,'HabilitarInhabilitarLaboratorios']);
/// eliminar datos del laboratorio
$route->post("/app/farmacia/laboratorio/delete/{id}",[FarmaciaController::class,'DeleteLaboratorio']);
/**
 * Rutas para grupos terapeúticos
 * 1.- Ruta para registrar nuevo grupo
 */
$route->post("/app/farmacia/grupo_terapeutico/save",[FarmaciaController::class,'saveGrupoTerapeutico']);
// mostrar los grupos terapeúticos
$route->get("/app/farmacia/grupo_terapeutico/all/{mostrar}",[FarmaciaController::class,'mostrarGruposTerapeuticos']);
/// modificar grupos terapeuticos
$route->post("/app/farmacia/grupo_terapeutico/update/{id}",[FarmaciaController::class,'updateGrupoTerapeutico']);
// Habilitar e inhabilitar lso grupos terapeúticos
$route->post("/app/farmacia/grupo_terapeutico/habilitar_inhabilitar/{id}/{condition}",[FarmaciaController::class,'HabilitarInhabilitarGrupos']);
/// eliminar grupo terapeutico
$route->post("/app/farmacia/grupo_terapeutico/delete/{id}",[FarmaciaController::class,'DeleteGrupos']);
/** 
 * Rutas para empaques o embalajes
 * 1.- Registrar nuevo embalaje o empaque
 */

 $route->post("/app/farmacia/empaques/save",[FarmaciaController::class,'saveEmbalaje']);
 $route->get("/app/farmacia/empaques/all/{mostrar}",[FarmaciaController::class,'mostrarEmbalajes']);
 $route->post("/app/farmacia/empaques/update/{id}",[FarmaciaController::class,'updateEmpaque']);
 $route->post("/app/farmacia/empaques/habilitar_inhabilitar/{id}/{condition}",[FarmaciaController::class,'HabilitarInhabilitarEmbalajes']);
 $route->post("/app/farmacia/empaques/delete/{id}",[FarmaciaController::class,'DeleteEmbalajes']);

 /** 
  * Rutas para los proveedores
  * 1.- Ruta para registrar a nuevos proveedores
  */
  $route->post("/app/farmacia/proveedor/save",[FarmaciaController::class,'saveProveedor']);
  // mostrar todos los proveedores
  $route->get("/app/farmacia/proveedores/all/{mostrar}",[FarmaciaController::class,'mostrarProveedores']);

  /** Modificar datos de los proveedores */
  $route->post("/app/farmacia/proveedor/update/{id}",[FarmaciaController::class,'updateProveedor']);
  /** Borrar datos del proveedor */
  $route->post("/app/farmacia/proveedor/delete/{id}",[FarmaciaController::class,'BorrarProveedor']);

  /** Habilitar e inhabilitar proveedores */
  $route->post("/app/farmacia/proveedor/habilitar_inhabilitar/{id}/{condition}",[FarmaciaController::class,'HabilitarInhabilitarProveedores']);

  /** Rutas para productos
   * 1.- Mostrar todos los productos
   */
  $route->get("/app/farmacia/productos/all",[ProductoFarmaciaController::class,'all']);

  /// registrar productos
  $route->post("/app/farmacia/producto/store",[ProductoFarmaciaController::class,'store']);
  /// modificar datos de los productos
  $route->post("/app/farmacia/udpate/{id}",[ProductoFarmaciaController::class,'update']);
  /// ruta para borrar por completo al producto
  $route->post("/app/farmacia/delete/{id}",[ProductoFarmaciaController::class,'destroy']);

  /// Habilitar e inhbailitar productos registrados
  $route->post("/app/farmacia/habilitar/deshabilitar/productos/{id}/{condition}",[ProductoFarmaciaController::class,'HabilitaInhabilitaProducto']);

  /**
   * Rutas para los clientes
   * 1.- Ruta para registrar nuevo cliente
   */
  $route->post("/app/farmacia/cliente/store",[ClienteFarmaciaController::class,'store']);

  /// mostrar a todos los clientes
  $route->get("/app/farmacia/clientes/all",[ClienteFarmaciaController::class,'mostrarClientes']);
  /// modificar datos del cliente
  $route->post("/app/farmacia/cliente/update/{id}",[ClienteFarmaciaController::class,'update']);

  /// Borrar cliente de la base de datos
  $route->post("/app/farmacia/cliente/delete/{id}",[ClienteFarmaciaController::class,'destroy']);

  /** Habilitar e Inhabilitar clientes */
  $route->post("/app/farmacia/cliente/habilitar/inhabilitar/{id}/{condition}",[ClienteFarmaciaController::class,'HabilitarInhabilitarCliente']);

/** 
 * Rutas para el proceso de la venta en la farmacia
 */
/// ruta pra buscar cliente
$route->get("/app/farmacia/buscar_cliente/{num_doc}",[VentaFarmaciaController::class,'BuscarCliente']);

/// consultar productos para la venta
$route->get("/app/farmacia/consultar_productos",[VentaFarmaciaController::class,'ConsultarProductos']);
// Agregar productos a la cesta
$route->post("/app/farmacia/venta/add_cesta/{id}/{priceident}",[VentaFarmaciaController::class,'addCestaProducto']);

/** Mostrar los datos del carrito */
$route->get("/app/farmacia/venta/show_productos_cesta",[VentaFarmaciaController::class,'showProductosCesta']);

/// quitar productos del carrito
$route->post("/app/farmacia/venta/quitar_producto_cesta/{producto}/{priceident}",[VentaFarmaciaController::class,'QuitarProductoCesta']);

/// modificar cantidad de un producto añadido a la cesta del carrito
$route->post("/app/farmacia/venta/update_cantidad__producto_cesta/{producto}/{priceindent}",[VentaFarmaciaController::class,'ModifyCantidadProductoCesta']);

/// Cancelar la venta
$route->post("/app/farmacia/venta/cancel",[VentaFarmaciaController::class,'CancelVentaFarmacia']);

/// obtener la serie de la venta
$route->get("/app/farmacia/venta/num_serie",[VentaFarmaciaController::class,'getSerieVenta']);

/// guardar la venta
$route->post("/app/farmacia/venta/save",[VentaFarmaciaController::class,'saveVenta']);

/// mostrar la historia de las ventas
$route->get("/app/farmacia/historia_ventas/all",[VentaFarmaciaController::class,'mostrarHistorialVentas']);

/// generamos el ticket de venta en pdf
$route->get("/app/farmacia/tiecket_de_venta",[VentaFarmaciaController::class,'GenerateTicket']);

/***
 * Rutas para el proceso de las compras
 * 1.- Ruta para obtener la serie de la compra
 */
$route->get("/app/farmacia/compra/num_serie",[ComprasController::class,'getSerieCompra']);

/// mostrar los productos existentes
$route->get("/app/farmacia/compra/productos/existentes",[ComprasController::class,'showProductosExistentes']);

/// añadir a la cesta de la compra
$route->post("/app/farmacia/compra/add_cesta_productos/{id}",[ComprasController::class,'addCestaProductoCompra']);

/// ver productos de la cesta compra
$route->get("/app/farmacia/compra/show_productos_cesta",[ComprasController::class,'showProductosCestaCompra']);
/// quitar productos de la cesta
$route->post("/app/farmacia/compra/quitar_productos_cesta",[ComprasController::class,'QuitarProductoCestaCompra']);
/// modificar la cantidad del producto añadido a la cesta
$route->post("/app/farmacia/compra/modify_cantidad_producto_cesta",[ComprasController::class,'ModifyCantidadProductoCestaCompra']);
/// modificar el precio del producto añadido a la cesta
$route->post("/app/farmacia/compra/modify_precio_producto_cesta",[ComprasController::class,'ModifyPriceProductoCestaCompra']);
$route->post("/app/farmacia/compra/store",[ComprasController::class,'saveCompra']);
/// cancelar la compra
$route->post("/app/farmacia/compras/cancel",[ComprasController::class,'CancelCompraFarmacia']);

/// reporte
$route->get("/app/farmacia/reporte/productos_para_ganancias",[VentaFarmaciaController::class,'showGananciasRepoProductos']);


/// reporte de ventas
$route->get("/app/farmacia/reporteventas/graficas/estadisticos/mes/anual/{tipo}",[VentaFarmaciaController::class,'reporteVentasGraficoEstadicstico']);

$route->get("/app/farmacia/reporteventas/graficas/estadisticos/cantidad_ventas/producto/{tipo}",[VentaFarmaciaController::class,'CantidadVentasPorProducto']);


/// ver reporte de productos por vencer

$route->get("/app/farmacia/productos/por/vencer",[ProductoFarmaciaController::class,'reporteProductosPorVencer']);