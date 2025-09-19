@extends($this->Layouts("dashboard"))
@section('title_dashboard','Gestión de sucursales')
@section('css')
    <style>
     #lista_sedes>thead>tr>th{
        padding: 20px;
        background:linear-gradient(to bottom, #6db3f2 17%,#54a3ee 41%,#54a3ee 54%,#3690f0 75%,#1e69de 100%);
      }
      td.hide_me
      {
      display: none;
      }
    </style>
@endsection
@section('contenido')
 <div class="row">
    <div class="col-12">
        <div class="card">
              <div class="card-header" style="background: linear-gradient(to bottom, #6db3f2 17%,#54a3ee 41%,#54a3ee 54%,#3690f0 75%,#1e69de 100%);">
                <h4 class="letra text-white">Gestionar Sucursales</h4>
              </div>
            <div class="card-body">
                <div class="card-text mt-3">
                    <button class="btn_blue mb-3 mt-2 col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12" id="create_sede">Agregar uno nuevo <i class="fas fa-plus"></i></button>
                    <table class="table table-bordered table-striped table-hover table-sm nowrap responsive" id="lista_sedes" style="width:100%">
                        <thead>
                            <tr>
                                <th class="letra text-white">Nombre sucursal</th>
                                <th class="letra text-white">Teléfono</th>
                                <th class="letra text-white">Correo</th>
                                <th class="letra text-white">Dirección</th>
                                <th class="letra text-white">Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
 </div>

 {{--- MODAL PARA CREAR NUEVAS SEDES----}}
 <div class="modal fade" id="modal_create_sede" data-bs-backdrop="static">
     <div class="modal-dialog modal-lg modal-dialog-scrolable">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(to bottom, #6db3f2 17%,#54a3ee 41%,#54a3ee 54%,#3690f0 75%,#1e69de 100%);">
                <h4 class="text-white letra">Registrar Sucursal</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-7 col-lg-7 col-12">
                        <div class="form-floating mb-2">
                             <input type="text" class="form-control" id="sedename" placeholder="Nombre sede.......">
                              <label for="sedename" >Nombre de la sucursal *</label>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-12">
                        <div class="form-floating mb-2">
                             <input type="text" class="form-control" id="phone" placeholder="Teléfono de la sede.......">
                              <label for="phone" >Teléfono|Celular</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-2">
                             <input type="text" class="form-control" id="correo" placeholder="Email de contacto para la sede.......">
                              <label for="correo" >Correo</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-floating mb-1">
                            <select name="departamento" id="departamento" class="form-select"></select>
                            <label for="departamento" class="departamento"  ><b>Departamento</b></label>
                    </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-floating">
                        <select name="provincia" id="provincia" class="form-select"></select>
                        <label for="provincia" class="provincia"><b>Provincia</b></label>
                    </div>
                    </div>
                    <div class="col-12 mb-2">
                      <div class="form-floating">
                         <select name="distrito" id="distrito" class="form-select"></select>
                         <label for="distrito" class="distrito"><b>Distrito</b></label>
                      </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-2">
                            <input type="text" id="direccion" class="form-control" placeholder="Dirección de la sucursal">
                            <label for="direccion">Dirección</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border">
                <div class="row">
                    <div class="col-auto">
                        <button class="btn btn-success" id="save_sedes"><b>Guardar <i class="fas fa-save"></i></b></button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-danger" id="cancel_sedes"><b>Cancelar X</b></button>
                    </div>
                </div>
            </div>
        </div>
     </div>
 </div>
 {{--MODAL PARA EDITAR LA SEDE ---}}
 <div class="modal fade" id="modal_editar_sede" data-bs-backdrop="static">
     <div class="modal-dialog modal-lg modal-dialog-scrolable">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(to bottom, #fceabb 0%,#fccd4d 50%,#f8b500 51%,#fbdf93 100%);">
                <h4 class="text-white letra">Editar Sucursal</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-7 col-lg-7 col-12">
                        <div class="form-floating mb-2">
                             <input type="text" class="form-control" id="sedename_editar" placeholder="Nombre sede.......">
                              <label for="sedename_editar" >Nombre de la sucursal *</label>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-12">
                        <div class="form-floating mb-2">
                             <input type="text" class="form-control" id="phone_editar" placeholder="Teléfono de la sede.......">
                              <label for="phone_editar" >Teléfono|Celular</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-2">
                             <input type="text" class="form-control" id="correo_editar" placeholder="Email de contacto para la sede.......">
                              <label for="correo_editar" >Correo</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group">
                            <label for="" class="departamento_editar" class="form-label"><b>Departamento</b></label>
                            <select name="departamento_editar" id="departamento_editar" class="form-select"></select>
                    </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group">
                        <label for="" class="provincia_editar" class="form-label"><b>Provincia</b></label>
                        <select name="provincia_editar" id="provincia_editar" class="form-select"></select>
                    </div>
                    </div>
                    <div class="col-12 mb-2">
                      <div class="form-group">
                         <label for="" class="distrito_editar" class="form-label"><b>Distrito</b></label>
                         <select name="distrito_editar" id="distrito_editar" class="form-select"></select>
                      </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-2">
                            <input type="text" id="direccion_editar" class="form-control" placeholder="Dirección de la sucursal">
                            <label for="direccion_editar">Dirección</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border">
                <div class="row">
                    <div class="col-auto">
                        <button class="btn btn-success" id="update_sedes"><b>Guardar <i class="fas fa-save"></i></b></button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-danger" id="cancel_sedes_editar"><b>Cancelar X</b></button>
                    </div>
                </div>
            </div>
        </div>
     </div>
 </div>
@endsection

@section('js')
  <script src="{{URL_BASE}}public/js/control.js"></script>
 <script>

  var TablaSedes;
  var URL_BASE="{{URL_BASE}}";
  var TOKEN = "{{$this->Csrf_Token()}}";
  var SedeId;
  $(document).ready(function(){
    mostrarSedes();

    EditarSede(TablaSedes,'#lista_sedes tbody');
    ConfirmBeforeEliminado(TablaSedes,'#lista_sedes tbody');
    ConfirmBeforeBorrado(TablaSedes,'#lista_sedes tbody');
    ClickButtonHabilitarSede(TablaSedes,'#lista_sedes tbody');
    /// MOSTRAR LOS DEPARTAMENTOS
    showDepartamentos("#departamento");
    showDepartamentos("#departamento_editar");
    showProvincias(0,'#provincia');
    showDistritos(0,'#distrito');

    enter("sedename","phone");
    enter("phone","correo");
    enter("correo","departamento");
    /// MOSTRAR LAS PROVINCIAS
    $('#departamento').change(function(){
        showDistritos(0,'#distrito');
        showProvincias($(this).val(),'#provincia');
        
    });

     $('#departamento_editar').change(function(){
        showDistritos(0,'#distrito_editar');
        showProvincias($(this).val(),'#provincia_editar');
        
    });

    /// MOSTRAR LOS DISTRITOS
    $('#provincia').change(function(){
      
        showDistritos($(this).val(),'#distrito');
         
    });
    $('#provincia_editar').change(function(){
      
        showDistritos($(this).val(),'#distrito_editar');
         
    });
 
    /// CREAR SEDE
    $('#create_sede').click(function(){
        $('#modal_create_sede').modal("show");
    });

    /// cancelar
    $('#cancel_sedes').click(function(){
         $('#departamento').prop("selectedIndex",0);
         showProvincias(0,'#provincia');
         showDistritos(0,'#distrito');
         $('#sedename').val("");
 
         $('#modal_create_sede').modal("hide");
    });

    $('#cancel_sedes_editar').click(function(){
         $('#distrito_editar').empty();
         $('#modal_editar_sede').modal("hide");
    })


    $('#save_sedes').click(function(){
        if($('#sedename').val().trim().length == 0){
            $('#sedename').focus();
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:"DEBES DE INGRESAR EL NOMBRE DE LA SUCURSAL!!",
                icon:"error",
                target:document.getElementById('modal_create_sede')
            });
        }else{
            if($('#distrito').val() == null){
                $('#departamento').focus();
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA",
                    text:"DEBES DE SELECCIONAR EN QUE DEPARTAMENTO-PROVINCIA-DISTRITO ESTA UBICADO LA SUCURSAL!!",
                    icon:"error",
                    target:document.getElementById('modal_create_sede')
                });
            }else{
                GuardarSede($('#sedename'),$('#phone'),$('#correo'),$('#distrito'),$('#direccion'));
            }   
        }
    });

    $('#update_sedes').click(function(){
     
     if($('#sedename_editar').val().trim().length == 0){
        $('#sedename_editar').focus();
     }else{
         UpdateSede($('#sedename_editar'),$('#phone_editar'),$('#correo_editar'),$('#distrito_editar'),$('#direccion_editar'));
     }
    });
  });
  
  /*MODIFICAR LA SEDE*/
  /*MOSTRAR LAS SEDES*/
  function mostrarSedes(){
    TablaSedes = $('#lista_sedes').DataTable({
        retrieve:true,
        response:true,
        language:SpanishDataTable(),
        ajax:{
            url:URL_BASE+"sedes/all",
            method:"GET",
            dataSrc:"sedes"
        },
        columns:[
            {"data":"namesede",render:function(sedename){
                return sedename.toUpperCase();
            }},
            {
                "data":"telefono_sede",render:function(phone){
                    return phone != null ? phone : "<span class='badge bg-danger'>NO ESPECIFICA</span>"
                }
            },
            {
                "data":"correo_sede",render:function(correo){
                    return correo != null ? correo : "<span class='badge bg-danger'>NO ESPECIFICA</span>"
                }
            },
            {
                "data":null,render:function(dato){
                    if(dato.direccion_sede != null && dato.distritosede_id != null){
                        return (dato.direccion_sede+"-"+dato.name_departamento+"-"+dato.name_provincia+"-"+dato.name_distrito).toUpperCase()
                    }else{
                        if(dato.distritosede_id != null && dato.direccion_sede == null){
                            return (dato.name_departamento+"-"+dato.name_provincia+"-"+dato.name_distrito).toUpperCase()
                        }else{
                            if(dato.direccion_sede != null && dato.distritosede_id == null){
                                return  dato.direccion_sede.toUpperCase()
                            }
                        }
                    }
                    return "<span class='badge bg-danger'>NO ESPECIFICA</span>"
                     
                }
            },
            {
                "data":null,render:function(dato){
                    if(dato.deleted_at == null){
                        return `
                         <button class='btn btn-outline-danger rounded btn-sm' id='eliminar'><i class="fas fa-trash-alt"></i></button>
                         <button class='btn btn-outline-warning rounded btn-sm' id='editar'><i class="fas fa-edit"></i></button>
                        
                    `;
                    }

                    return `
                         <button class='btn btn-outline-success rounded btn-sm' id="activar"><i class="fas fa-check"></i></button>
                         <button class='btn btn-outline-danger rounded btn-sm' id='borrar'><i class="fas fa-close"></i></button>
                    `;
                },className:"text-center"
            }
        ]
    }).ajax.reload();
  }

  /*CONFIRMAR ANTES DE ELIMINAR*/
function ConfirmBeforeEliminado(Tabla,Tbody){
$(Tbody).on('click','#eliminar',function(){
let fila = $(this).parents("tr");
if(fila.hasClass("child")){
fila = fila.prev();
}

let Data = Tabla.row(fila).data();
SedeId = Data.id_sede;
Swal.fire({
  title: "Estas seguro?",
  text: "Al eliminar la sucursal seleccionado, ya no estará disponible en la lista!!!",
  icon: "question",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, eliminar!"
}).then((result) => {
  if (result.isConfirmed) {
    ProcesoEliminadoSede(SedeId);
  }
});
});
}
/*CONFIRMAR ANTES DE BORRAR LA SEDE*/
function ConfirmBeforeBorrado(Tabla,Tbody){
$(Tbody).on('click','#borrar',function(){
let fila = $(this).parents("tr");
if(fila.hasClass("child")){
fila = fila.prev();
}

let Data = Tabla.row(fila).data();
SedeId = Data.id_sede;
Swal.fire({
  title: "Estas seguro?",
  text: "Al borrar la sucursal seleccionado, ya no podrás recuperarlo!!!",
  icon: "question",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si, borrar!"
}).then((result) => {
  if (result.isConfirmed) {
    ProcesoBorradoSede(SedeId);
  }
});
});
}

/*CLICK BOTON HABILITAR LA SEDE*/
function ClickButtonHabilitarSede(Tabla,Tbody){
$(Tbody).on('click','#activar',function(){
let fila = $(this).parents("tr");
if(fila.hasClass("child")){
fila = fila.prev();
}

let Data = Tabla.row(fila).data();
SedeId = Data.id_sede;
ProcesoHabilitarSede(SedeId);
});
}

/*PROCESO DE HABILITAR LA SEDE*/
function ProcesoHabilitarSede(id){
    let FormHabilitarSede = new FormData();
    FormHabilitarSede.append("token_",TOKEN);
   
    axios({
        url:URL_BASE+"sede/"+id+"/habilitar",
        method:"POST",
        data:FormHabilitarSede
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
            })
        }else{
            
             Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.response,
                icon:"success",
            }).then(function(){
                mostrarSedes();
            });
           }
    });
}
/*PROCESO DE ELIMINADO*/
function ProcesoEliminadoSede(id){
    let FormEliminadoSede = new FormData();
    FormEliminadoSede.append("token_",TOKEN);
   
    axios({
        url:URL_BASE+"sede/"+id+"/papelera",
        method:"POST",
        data:FormEliminadoSede
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
            })
        }else{
            
             Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.response,
                icon:"success",
            }).then(function(){
                mostrarSedes();
            });
           }
    });
  }
  /**PROCESO DE BORRADO DE LA SEDE*/ 
  function ProcesoBorradoSede(id){
    let FormBorradoSede = new FormData();
    FormBorradoSede.append("token_",TOKEN);
   
    axios({
        url:URL_BASE+"sede/"+id+"/borrar",
        method:"POST",
        data:FormBorradoSede
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
            })
        }else{
            
             Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.response,
                icon:"success",
            }).then(function(){
                mostrarSedes();
            });
           }
    });
  }
  /*EDITAR LA SEDE*/
  function EditarSede(Tabla,Tbody){
    $(Tbody).on('click','#editar',function(){
        let fila = $(this).parents("tr");
        if(fila.hasClass("child")){
            fila = fila.prev();
        }

        let Data = Tabla.row(fila).data();
        SedeId = Data.id_sede;
        $('#sedename_editar').val(Data.namesede);
        $('#phone_editar').val(Data.telefono_sede);
        $('#correo_editar').val(Data.correo_sede);
        $('#departamento_editar').val(Data.id_departamento);
        $('#direccion_editar').val(Data.direccion_sede);
        showProvincias($('#departamento_editar').val(),'#provincia_editar');
        $('#modal_editar_sede').modal("show");
    });
  }
  /*MOSTRAR LOS DEPARTAMENTOS*/
  function showDepartamentos(selectid){
    let option='<option disabled selected> ----- Seleccione ----- </option>';
     axios({
        url:URL_BASE+"departamento/mostrar?token_="+TOKEN,
        method:"GET",
     }).then(function(dato){
        if(dato.data.response.length > 0){
            dato.data.response.forEach(departamento => {
                option+=`<option value=`+departamento.id_departamento+`>`+departamento.name_departamento.toUpperCase()+`</option>`;
            });

            $(selectid).html(option);
        }
     })
  }

  /// MOSTRAR LAS PROVINVIAS
   function showProvincias(id,selectid){
    let option='<option disabled selected> ----- Seleccione ----- </option>';
     axios({
        url:URL_BASE+"provincia-por-departamento/show/"+id+"?token_="+TOKEN,
        method:"GET",
     }).then(function(dato){
        if(dato.data.response.length > 0){
            dato.data.response.forEach(provincia => {
                option+=`<option value=`+provincia.id_provincia+`>`+provincia.name_provincia.toUpperCase()+`</option>`;
            });
        }
         $(selectid).html(option);
     })
  }

  /// MOSTRAR LOS DISTRITOS
  function showDistritos(id,selectid){
    let option='<option disabled selected> ----- Seleccione ----- </option>';
     axios({
        url:URL_BASE+"distritos-por-provincia/show/"+id+"?token_="+TOKEN,
        method:"GET",
     }).then(function(dato){
        if(dato.data.response.length > 0){
            dato.data.response.forEach(distrito => {
                option+=`<option value=`+distrito.id_distrito+`>`+distrito.name_distrito.toUpperCase()+`</option>`;
            });
        }
         $(selectid).html(option);
     })
  }

  /// GUARDAR LA SEDE
  function GuardarSede(sedeName,telefonoSede,correoSede,distritoSede,direccionSede){
    let FormSaveSede = new FormData();
    FormSaveSede.append("token_",TOKEN);
    FormSaveSede.append("namesede",sedeName.val());
    FormSaveSede.append("phone",telefonoSede.val());
    FormSaveSede.append("correo",correoSede.val());
    FormSaveSede.append("distrito",distritoSede.val());
    FormSaveSede.append("direccion",direccionSede.val());
    axios({
        url:URL_BASE+"sede/store",
        method:"POST",
        data:FormSaveSede
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('modal_create_sede')
            })
        }else{
           if(response.data.existe != undefined){
             Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.existe,
                icon:"warning",
                target:document.getElementById('modal_create_sede')
             });
           }else{
             Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.response,
                icon:"success",
                target:document.getElementById('modal_create_sede')
            }).then(function(){
             $('#departamento').prop("selectedIndex",0);
                showProvincias(0,'#provincia');
                showDistritos(0,'#distrito');
                mostrarSedes();
                 
                $('#sedename').val("");
                telefonoSede.val("");
                correoSede.val("");
                direccionSede.val("");
            });
           }
        }
    });
  }

  /*MODIFICAR LA SEDE*/
  function UpdateSede(sedeName,telefonoSede,correoSede,distritoSede,direccionSede){
    let FormUpdateSede = new FormData();
    FormUpdateSede.append("token_",TOKEN);
    FormUpdateSede.append("namesede",sedeName.val());
    FormUpdateSede.append("phone",telefonoSede.val());
    FormUpdateSede.append("correo",correoSede.val());
    FormUpdateSede.append("distrito",distritoSede.val());
    FormUpdateSede.append("direccion",direccionSede.val());
    axios({
        url:URL_BASE+"sede/"+SedeId+"/update",
        method:"POST",
        data:FormUpdateSede
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.error,
                icon:"error",
                target:document.getElementById('modal_editar_sede')
            })
        }else{
            
             Swal.fire({
                title:"MENSAJE DEL SISTEMA!!!",
                text:response.data.response,
                icon:"success",
            }).then(function(){
                mostrarSedes();
                $('#provincia_editar').empty();
                $('#distrito_editar').empty();
                sedeName.val("");
                telefonoSede.val("");
                correoSede.val("");
                direccionSede.val("");
            });
            $('#modal_editar_sede').modal("hide");
           }
    });
  }
 </script>   
@endsection