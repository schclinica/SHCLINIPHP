@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestión de gastos')

@section('css')
    <style>
        #tabla_gastos>thead>tr>th {
             background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%); 
        }
        
    </style>
@endsection
@section('contenido')
    <div class="row">
        <div class="col-12">
            <div class="card">
               <div class="card-header" style="background: linear-gradient(to bottom, rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%);">
                  <h4 class="float-end text-white">Gestión de gastos</h4>
                     <a href="{{ $this->route('gasto/create') }}" class="btn btn-primary rounded">Agregar uno nuevo <i
                         class='bx bx-plus'></i></a>
                </div>
                <div class="card-body">
                    @if ($this->ExistSession("success"))
                    <div class="alert alert-success mt-1">
                        {{$this->getSession("success")}}
                    </div>
                    {{$this->destroySession("success")}}
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-sm nowrap responsive" id="tabla_gastos" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="py-3 text-white letra">Acciones</th>
                                    <th class="py-3 text-white letra">Usuario</th>
                                    <th class="py-3 text-white letra">ES UN GASTO DE ?</th>
                                    <th class="py-3 text-white letra">Tipo Gasto</th>
                                    <th class="py-3 text-white letra">Descripción</th>
                                    <th class="py-3 text-white letra">Valor Gasto</th>
                                    <th class="py-3 text-white letra">Sucursal</th>
                                    <th class="py-3 text-white letra">Fecha</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
    {{--MOdal para editar las categorias ---}}
    <div class="modal fade" id="modal_editar_gastos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(to bottom, rgba(255,183,107,1) 0%,rgba(255,167,61,1) 50%,rgba(255,124,0,1) 51%,rgba(255,127,4,1) 100%);">
                    <h5><b class="letra text-white">Editar Gasto</b></h5>
                </div>

                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" value="{{$this->Csrf_Token()}}">
                        <div class="card-text mt-3">
                            <div class="form-floating mb-2">
                                <select name="categoria" id="categoria" class="form-select">
                                    @foreach ($categorias as $cat)
                                    <option value="{{$cat->id_categoria_egreso}}">{{strtoupper($cat->name_categoria_egreso)}}</option>
                                    @endforeach
                                </select>
                                <label for="sede"><b>Tipo De Gasto <span class="text-danger">*</span></b></label>
                            </div>
                            @if ($this->profile()->rol === "admin_general")
                            <div class="form-floating mb-2">
                                <select name="tipo" id="tipo" class="form-select">
                                    <option value="c">CLINICA</option>
                                    <option value="f">FARMACIA</option>
                                </select>
                                <label for="tipo"><b>Donde desea registrar el gasto? <span class="text-danger">*</span></b></label>
                            </div>
                            <div class="form-floating mb-2">
                                <select name="sede" id="sede" class="form-select">
                                    @foreach ($sedes as $sededata)
                                    <option value="{{$sededata->id_sede}}">{{strtoupper($sededata->namesede)}}</option>
                                    @endforeach
                                </select>
                                <label for="sede"><b>Sucursal <span class="text-danger">*</span></b></label>
                            </div>
                            @endif
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="gasto_name" name="gasto_name"
                                    placeholder="Escriba la categóría..." value="{{$this->old(" gasto_name")}}">
                                <label for="categoria_name"><b>Nombre Gasto<span class="text-danger">*</span></b></label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control" id="monto_gasto" name="monto_gasto"
                                    placeholder="Ingrese el monto del gasto..." value="{{$this->old(" monto_gasto")}}">
                                <label for="monto_gasto"><b>Monto Del Gasto<span class="text-danger">*</span></b></label>
                            </div>
                            <div class="form-floating">
                                <input type="date" class="form-control" id="fecha" name="fecha" value="{{$this->FechaActual("
                                    Y-m-d")}}">
                                <label for="fecha"><b>Fecha</b></label>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-2">
                    <button class="btn_blue" id="update_gasto"><b>Guardar cambios<i class='bx bx-save' ></i></b></button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script src="{{ URL_BASE }}public/js/control.js"></script>
<script>
  var RUTA = "{{ URL_BASE }}" // la url base del sistema
  var TOKEN = "{{ $this->Csrf_Token() }}";
  var PROFILE_ = "{{ $this->profile()->rol }}";
  var TablaGastos; 
  var GASTO_ID;
 
  
 $(document).ready(function(){
  MostrarGastos();
  ConfirmEliminadoGasto(TablaGastos,'#tabla_gastos tbody');
  EditarGasto(TablaGastos,'#tabla_gastos tbody');

  $('#update_gasto').click(function(){
    if($('#gasto_name').val().trim().langth == 0){
        $('#gasto_name').focus();
    }else{
      if($('#monto_gasto').val().trim().length == 0){
        $('#monto_gasto').focus();
      }else{
        UpdateGasto(GASTO_ID);
      }
    }
  });
  $('#ir_caja').click(function(){
    location.href= RUTA+"apertura/caja";
  });
 });

 function MostrarGastos(){
    TablaGastos = $('#tabla_gastos').DataTable({
        retrieve:true,
        responisve:true,
         language: SpanishDataTable(),
        ajax:{
            url:RUTA+"show/gastos/all",
            method:"GET",
            dataSrc:"gastos"
        },
        columns:[
            {"data":null,render:function(){
                return `<button class='btn btn-danger btn-sm' id='eliminar'><i class='bx bx-x'></i></button>
                        <button class='btn btn-outline-warning btn-sm' id='editar'><i class='bx bxs-edit-alt'></i></button>
                `;
            }},
            {"data":null,render:function(dato){
                return (dato.name+"["+dato.rol+"]").toUpperCase();
            }},
            {"data":"tipo",render:function(tipo){
                return tipo=== 'c' ? 'LA CLINICA' : 'LA FARMACIA';
            }},
            {"data":"name_categoria_egreso",render:function(name_categoria_egreso){
                return name_categoria_egreso.toUpperCase();
            }},
            {"data":"name_gasto",render:function(namesub){
                return namesub.toUpperCase();
            }},
            {"data":"valor_gasto",className:"text-center"},
            {"data":"namesede",render:function(sucursal){
                return sucursal.toUpperCase();
            }},
            {"data":"fecha"}
        ]
    }).ajax.reload();
 }
 //egreso/subcategoria/delete/{id}
 /*CONFIRMAR ANTES DE ELIMINAR EL GASTO*/
 function ConfirmEliminadoGasto(Tabla,Tbody){
  $(Tbody).on('click','#eliminar',function(){
    let fila =  $(this).parents("tr");

    if(fila.hasClass("child")){
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    GASTO_ID = Data.id_subcategoria;
Swal.fire({
    title: "ESTAS SEGURO?",
    text: "AL ACEPTAR SE QUITARÁ DE LA LISTA AL GASTO, Y YA NO SE APLICARÁ COMO UN EGRESO!!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!",
    cancelButtonText:"Cancelar"
    }).then((result) => {
    if (result.isConfirmed) {
     processDeleteSubCategoria(GASTO_ID);
    }
    });
  })
 }
 /// EDITAR LOS GASTOS
  function EditarGasto(Tabla,Tbody){
  $(Tbody).on('click','#editar',function(){
    let fila =  $(this).parents("tr");

    if(fila.hasClass("child")){
        fila = fila.prev();
    }

    let Data = Tabla.row(fila).data();
    GASTO_ID = Data.id_subcategoria;
    let Fecha = Data.fecha.split("/");

    $('#categoria').val(Data.categoriaegreso_id);
    $('#sede').val(Data.sede_id);
    $('#tipo').val(Data.tipo);
    $('#gasto_name').val(Data.name_gasto);
    $('#monto_gasto').val(Data.valor_gasto);
    $('#fecha').val(Fecha[2]+"-"+Fecha[1]+"-"+Fecha[0]);
    $('#modal_editar_gastos').modal("show");
  })
 }
 /// proceso de eliminar a la subcategoria
function processDeleteSubCategoria(id)
{
    $.ajax({
        url:RUTA+"egreso/subcategoria/delete/"+id,
        method:"POST",
        data:{
            _token:TOKEN,
            
        },
        dataType:"json",
        success:function(response)
        {
            if(response.response == 1)
            {
                 Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Sub categoría eliminado correctamente!",
                    icon:"success",
                 }).then(function(){
                    MostrarGastos();
                 });
            }else{
                Swal.fire({
                    title:"Mensaje del sistema!",
                    text:"Error al eliminar la sub categoría seleccionado!",
                    icon:"error" 
                 })
            }
        },error:function(){
            Swal.fire({
                title:"Mensaje del sistema!",
                text:"Error al eliminar la sub categoría seleccionado!",
                icon:"error" 
             });
        }
      });
}

/// MODIFICAR AL GASTO
function UpdateGasto(id){
    let FormUpdateGasto = new FormData();
    FormUpdateGasto.append("_token",TOKEN);
    FormUpdateGasto.append("gasto_name",$('#gasto_name').val());
    FormUpdateGasto.append("valor_gasto",$('#monto_gasto').val());
    FormUpdateGasto.append("categoria",$('#categoria').val());
    FormUpdateGasto.append("sede",$('#sede').val());
    FormUpdateGasto.append("fecha",$('#fecha').val());
    FormUpdateGasto.append("tipo",$('#tipo').val());
    axios({
        url:RUTA+"egreso/subcategoria/update/"+id,
        method:"post",
        data:FormUpdateGasto,
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById("modal_editar_gastos")
            });
        }else{
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.success,
                icon:"success",
                target:document.getElementById("modal_editar_gastos")
            }).then(function(){
                MostrarGastos();
            });
        }
    })
}

</script>
@endsection