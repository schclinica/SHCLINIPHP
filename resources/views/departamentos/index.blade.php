@extends($this->Layouts("dashboard"))

@section("title_dashboard","Departamento")
@section('css')
    <style>
       #tabla_dep>thead>tr>th
        {
           background: linear-gradient(to bottom, #b3dced 15%,#29b8e5 60%,#bce0ee 100%);;
            color: #fff;
            padding: 20px;
        }
        #tabla_dep_eliminados>thead>tr>th
        {
            background-color: #F8F8FF;
            color: #000;
            padding: 17px;
        }
        #tabla_prov_eliminados>thead>tr>th
        {
            background: #acd3eb;
            color: #000;
            padding: 20px;
        }
        #tabla_prov>thead>tr>th
        {
           background: linear-gradient(to bottom, #6db3f2 17%,#54a3ee 54%,#54a3ee 70%,#54a3ee 70%,#3690f0 75%,#1e69de 100%);
            color: #fff;
            padding: 17px;
        }
        #tabla_distrito>thead>tr>th
        {
            background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 27%,#0a77d5 63%,#539fe1 81%,#87bcea 90%);;
            color: #fff;
            padding: 20px;
        }
        #tabla_comunas_eliminados>thead>tr>th
        {
             background-color: #00a3e3;
            color: #fff;
            padding: 17px;
        }
    </style>
@endsection
@section('contenido')
<div class="col-12" id="car">
  <div class="nav-align-top mb-4">
    <ul class="nav nav-tabs nav-fill" role="tablist" id="tab_ciudades">
      <li class="nav-item">
        <button
          type="button"
          class="nav-link active"
          role="tab"
          data-bs-toggle="tab"
          data-bs-target="#navs-justified-home"
          aria-controls="navs-justified-home"
          aria-selected="true"
          style="color: #4169E1"
          id="departamento_view"
        >
        <i class='bx bxs-buildings'></i> <b>Departamentos</b>
        </button>
      </li>
      <li class="nav-item">
        <button
          type="button"
          class="nav-link"
          role="tab"
          data-bs-toggle="tab"
          data-bs-target="#navs-justified-profile"
          aria-controls="navs-justified-profile"
          aria-selected="false"
          style="color:#FF4500"
          id="provincia_view"
        >
        <b>Municipios</b>
        </button>
      </li>
      <li class="nav-item">
        <button
          type="button"
          class="nav-link"
          role="tab"
          data-bs-toggle="tab"
          data-bs-target="#navs-justified-messages"
          aria-controls="navs-justified-messages"
          aria-selected="false"
          style="color:#0f1413"
          id="distrito_view"
        >
         <b>Comunas</b>
        </button>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 col-12 float-end">
          <button class="btn_blue" style="width: 100%" id="view_departamentos_eliminados"><b>Departamentos eliminados <i class='bx bxs-show'></i></b></button>
        </div>
        {{--- agregando boton para nuevo departamento ---}}
        <button class="btn btn-outline-success col-xl-3 col-lg-3 col-md-5 col-sm-5 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-sm-0 mt-1" id="add_departamento">Agregar uno nuevo
          <i class="fas fa-plus"></i>
        </button>
        {{--- fin boton agregar nuevo departamento ---}}

         <div class="table-responsive">
          <table class="table table-bordered table-hover table-sm table-striped responsive nowrap" id="tabla_dep" style="width: 100%">
            <thead>
              <tr>
                <th>#</th>
                <th>DEPARTAMENTO</th>
                <th>ACCIÓN</th>
              </tr>
            </thead>
          </table>
         </div>
      </div>
      <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-7 col-12 float-end">
          <button class="btn_blue" style="width: 100%" id="view_provincias_eliminados"><b>Municipios eliminados <i class='bx bxs-show'></i></b></button>
        </div>
        {{--- agregando boton para nuevo municipio ---}}
        <button class="btn btn-outline-success col-xl-3 col-lg-3 col-md-5 col-sm-5 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-sm-0 mt-1" id="add_provincia">Agregar uno nuevo
          <i class="fas fa-plus"></i>
        </button>
        {{--- fin boton agregar nuevo municipio ---}}
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-sm responsive table-striped nowrap" id="tabla_prov" style="width: 100%">
            <thead>
              <tr>
                <th>#</th>
                <th>MUNICIPIO</th>
                <th>DEPARTAMENTO</th>
                <th>ACCIÓN</th>
              </tr>
            </thead>
          </table>
         </div>
          
      </div>
    
      <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-7 col-12 float-end">
          <button class="btn_blue" style="width: 100%" id="view_distritos_eliminados"><b>Comunas eliminados <i class='bx bxs-show'></i></b></button>
        </div>
        {{--- agregando boton para nuevo comunas ---}}
        <button class="btn btn-outline-success col-xl-3 col-lg-3 col-md-5 col-sm-5 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-sm-0 mt-1" id="add_comuna">Agregar uno nuevo
          <i class="fas fa-plus"></i>
        </button>
        {{--- fin boton agregar nuevo comunas ---}}
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-sm table-striped responsive nowrap" id="tabla_distrito" style="width: 100%">
            <thead>
              <tr>
                <th>#</th>
                <th>COMUNA-MUNICIPIO-DEPARTAMENTO</th>
                <th>ACCIÓN</th>
              </tr>
            </thead>
          </table>
         </div>
      </div>
    </div>
  </div>
  </div>

  {{--- EDITAR LOS DEPARTAMENTOS--}}
  <div class="modal fade" id="modal_editar_dep" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, #e6f0a3 0%,#d2e638 50%,#c3d825 51%,#dbf043 100%);">
          <p class="h4 text-white">Editar departamento</p>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="departamento"><b>Departamento (*)</b></label>
            <input type="text" class="form-control" placeholder="Nombre departamento..." id="departamento">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-rounded btn-success" id="update_dep"><i class='bx bx-save' ></i> Guardar</button>
          <button class="btn btn-rounded btn-danger" id="cancel_dep"><i class='bx bx-x' ></i> Cancelar</button>
        </div>
      </div>
    </div>
  </div>

   {{--- DEPARTAMENTOS ELIMINADOS--}}
   <div class="modal fade" id="modal_dep_eliminados" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
          <p class="h5 text-white">Departamentos eliminados</p>
        </div>

        <div class="modal-body">
          <div class="alert alert-success" id="mensaje_dep_eliminados" style="display: none">
            <span class="text-success"><b>Departamento habilitado correctamente 😁😎</b></span>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped responsive nowrap" id="tabla_dep_eliminados" style="width: 100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>DEPARTAMENTO</th>
                  <th>ACCIÓN</th>
                </tr>
              </thead>
            </table>
           </div>
        </div>
        <div class="modal-footer border-2">
          <button class="btn btn-rounded btn-danger" id="cancel_dep_eliminados"><i class='bx bx-x' ></i> Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  {{---MODAL PARA EDITAR LAS PROVINCIAS---}}
  <div class="modal fade" id="modal_editar_provincia" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, #e6f0a3 0%,#d2e638 50%,#c3d825 51%,#dbf043 100%);">
          <p class="h4 text-white">Editar municipio</p>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="provincia"><b>Municipio (*)</b></label>
            <input type="text" class="form-control" placeholder="Nombre municipio..." id="provincia">
          </div>
          <div class="form-group">
            <label for="dep_show"><b>Departamento (*)</b></label>
            <select name="dep_show" id="dep_show" class="form-select"></select>
          </div>
        </div>
        <div class="modal-footer border-2">
          <button class="btn btn-rounded btn-success" id="update_prov"><i class='bx bx-save' ></i> Guardar</button>
          <button class="btn btn-rounded btn-danger" id="cancel_prov"><i class='bx bx-x' ></i> Cancelar</button>
        </div>
      </div>
    </div>
  </div>
  {{--- VER PROVINCIAS ELIMINADOS----}}
  <div class="modal fade" id="modal_prov_eliminados" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
          <p class="h5 text-white">Municipios eliminados</p>
        </div>

        <div class="modal-body">
          <div class="alert alert-success" id="mensaje_prov_eliminados" style="display: none">
            <span class="text-success"><b>Municipio habilitado correctamente 😁😎</b></span>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped responsive nowrap" id="tabla_prov_eliminados" style="width: 100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>MUNICIPIO</th>
                  <th>DEPARTAMENTO</th>
                  <th>ACCIÓN</th>
                </tr>
              </thead>
            </table>
           </div>
        </div>
        <div class="modal-footer border-2">
          <button class="btn btn-rounded btn-danger" id="cancel_prov_eliminados"><i class='bx bx-x' ></i> Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  {{--- MODAL PARA AGREGAR NUEVAS DEPARTAMENTOS ---}}
  <div class="modal fade" id="modal_add_departamento"  >
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #b7deed 0%,#71ceef 50%,#21b4e2 51%,#b7deed 100%);">
          <p class="h5 text-white">Agregar nuevo departamento</p>
        </div>

        <div class="modal-body">
          <form action="" method="post" id="form_add_departamento">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
              <label for="nombre_dep"><b>Nombre departamento <span class="text-danger">*</span></b></label>
              <input type="text" class="form-control" name="nombre_dep" id="nombre_dep" placeholder="Escriba aquí....">
            </div>
            
          </form>
        </div>
        <div class="modal-footer border-2">
          <button class="btn btn-success save_departamento">Guardar <i class="fas fa-save"></i></button>
        </div>
        
      </div>
    </div>
  </div>

   {{--- MODAL PARA AGREGAR NUEVAS MUNICIPIO ---}}
   <div class="modal fade" id="modal_add_provincia"  >
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
          <p class="h5 text-white">Agregar nuevo municipio</p>
        </div>

        <div class="modal-body">
          <form action="" method="post" id="form_add_provincia">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
              <label for="name_provincia"><b>Nombre municipio <span class="text-danger">*</span></b></label>
              <input type="text" class="form-control" name="name_provincia" id="name_provincia" placeholder="Escriba aquí....">
            </div>

            <div class="form-group">
              <label for="departamento_select"><b>Seleccione departamento <span class="text-danger">*</span></b></label>
              <select name="departamento_select" id="departamento_select" class="form-select"></select>
            </div>
            
          </form>
        </div>
        <div class="modal-footer border-2">
          <button class="btn btn-success save_provincia">Guardar <i class="fas fa-save"></i></button>
        </div>
        
      </div>
    </div>
  </div>
 {{---Agreagr nueva comuna ---}}
  <div class="modal fade" id="modal_add_comuna"  >
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
          <p class="h5 text-white">Agregar nueva comuna</p>
        </div>

        <div class="modal-body">
          <form action="" method="post" id="form_add_comuna">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
              <label for="departamento_select_comuna"><b>Seleccione departamento <span class="text-danger">*</span></b></label>
              <select name="departamento_select" id="departamento_select_comuna" class="form-select"></select>
            </div>
            <div class="form-group">
              <label for="provincia_select_dis"><b>Seleccione municipio <span class="text-danger">*</span></b></label>
              <select name="provincia_select_dis" id="provincia_select_dis" class="form-select"></select>
            </div>
            <div class="form-group">
              <label for="name_distrito"><b>Nombre comuna  <span class="text-danger">*</span></b></label>
              <input type="text" class="form-control" name="name_distrito" id="name_distrito" placeholder="Escriba aquí....">
            </div>            
          </form>
        </div>
        <div class="modal-footer border-2">
          <button class="btn btn-success save_comuna">Guardar <i class="fas fa-save"></i></button>
        </div>
        
      </div>
    </div>
  </div>

  {{---editar las comunas ----}}
  <div class="modal fade" id="modal_comuna_editar"  >
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #e6f0a3 0%,#d2e638 50%,#c3d825 51%,#dbf043 100%);">
          <p class="h5 text-white">Editar comuna</p>
        </div>

        <div class="modal-body">
          <form action="" method="post" id="form_comuna_editar">
            <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
            <div class="form-group">
              <label for="departamento_select_comuna_editar"><b>Seleccione departamento <span class="text-danger">*</span></b></label>
              <select name="departamento_select_comuna_editar" id="departamento_select_comuna_editar" class="form-select"></select>
            </div>
            <div class="form-group">
              <label for="provincia_select_dis_editar"><b>Seleccione municipio <span class="text-danger">*</span></b></label>
              <select name="provincia_select_dis_editar" id="provincia_select_dis_editar" class="form-select"></select>
            </div>
            <div class="form-group">
              <label for="name_distrito_editar"><b>Nombre comuna  <span class="text-danger">*</span></b></label>
              <input type="text" class="form-control" name="name_distrito_editar" id="name_distrito_editar" placeholder="Escriba aquí....">
            </div>            
          </form>
        </div>
        <div class="modal-footer border-2">
          <button class="btn btn-success update_comuna">Guardar <i class="fas fa-save"></i></button>
        </div>
        
      </div>
    </div>
  </div>

  {{--- Ver las comunas elimiandos ---}}
  <div class="modal fade" id="modal_comunas_eliminados" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
          <p class="h5 text-white">Comunas eliminados</p>
        </div>

        <div class="modal-body">
          <div class="alert alert-success" id="mensaje_prov_eliminados" style="display: none">
            <span class="text-success"><b>Comuna habilitado correctamente 😁😎</b></span>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped responsive nowrap" id="tabla_comunas_eliminados" style="width: 100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>COMUNA</th>
                  <th>MUNICIPIO</th>
                  <th>DEPARTAMENTO</th>
                  <th>ACCIÓN</th>
                </tr>
              </thead>
            </table>
           </div>
        </div>
        <div class="modal-footer border-2">
          <button class="btn btn-rounded btn-danger" id="cancel_comuna_eliminados"><i class='bx bx-x' ></i> Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  
@endsection

@section('js')
<script>
  var RUTA = "{{URL_BASE}}" // la url base del sistema
  var TOKEN = "{{$this->Csrf_Token()}}"
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
<script>
  var TablaDepartamento;
  var TablaDepartamentoEliminados;
  var TablaProvincia;
  var TablaDistrito;
  var TablaProvinciaEliminados,TablaComunasEliminados;
  var ID_DEP,ID_PROV,IDDIS;

  $(document).ready(function(){

    /// inicializando datos 

    let Departamento_Text = $('#departamento');let Provincia = $('#provincia');
    let Departamento_select = $('#dep_show');

    showDepartamentosExistentes();
  

    /// abrimos el modal de editar
    editarDepartamento(TablaDepartamento,'#tabla_dep tbody');

    cancelEdicionDepartamento(TablaDepartamento,'#tabla_dep tbody');

    showDepartamentosEnCombo('departamento_select');
    showDepartamentosEnCombo('departamento_select_comuna');

    enter("name_distrito","name_distrito");

    $('#tab_ciudades button').click(function(){
       const valor_Id = $(this)[0].id;
       
       if(valor_Id === 'departamento_view')
       {
        showDepartamentosExistentes();
       }
       else
       {
        if(valor_Id === 'provincia_view')
        {
          showDepartamentosEnCombo('departamento_select');///agregado
          showProvinciasExistentes();

          EditarProvincia(TablaProvincia,'#tabla_prov tbody');
          EliminarListaProvincia(TablaProvincia,'#tabla_prov tbody');
        }
        else
        {
          showDepartamentosEnCombo('departamento_select_comuna');/// agregado
          showDistritosExistentes();
          EliminarDistrito(TablaDistrito,'#tabla_distrito tbody');
          EditarComuna(TablaDistrito,'#tabla_distrito tbody')
        }
       }
    });
    /// cancelar la edición de departamentos
    $('#cancel_dep').click(function(){
      $('#modal_editar_dep').modal("hide");
    });
    $('#cancel_prov').click(function(){
      $('#modal_editar_provincia').modal("hide");
    });
    $('#cancel_dep_eliminados').click(function(){
      $('#modal_dep_eliminados').modal("hide");
      $('#mensaje_dep_eliminados').hide();
      showDepartamentosExistentes();
    });
    $('#cancel_prov_eliminados').click(function(){
      $('#modal_prov_eliminados').modal("hide");
      $('#mensaje_prov_eliminados').hide();
      showProvinciasExistentes();
    });

    /// guardar cambios 
    $('#update_dep').click(function(){
      updateDataDepartamento(ID_DEP,Departamento_Text)
    });

    $('#update_prov').click(function(){
      updateProvincia(ID_PROV,Provincia.val(),Departamento_select.val());
    });

    /// ver departamentos eliminados
    $('#view_departamentos_eliminados').click(function(){
      showDepartamentosEliminados();
      $('#modal_dep_eliminados').modal("show");


      ActualizarDepartamento(TablaDepartamentoEliminados,'#tabla_dep_eliminados tbody');
    });

    /// mostrar las provincias eliminados
    $('#view_provincias_eliminados').click(function(){
       showProvinciasEliminados();
      $('#modal_prov_eliminados').modal("show");
      ActivarProvincia(TablaProvinciaEliminados,'#tabla_prov_eliminados tbody');
    });

    $('#nombre_dep').keypress(function(ev){
     if(ev.which == 13){
      ev.preventDefault();
      if($(this).val().trim().length == 0){
        $(this).focus();
      }else{
        saveDepartamento('form_add_departamento');
      }
     }
    });

    $('#name_provincia').keypress(function(ev){
     if(ev.which == 13){
      ev.preventDefault();
      if($(this).val().trim().length == 0){
        $(this).focus();
      }else{
         $('#departamento_select').focus();
      }
     }
    });

    $('#name_distrito_editar').keypress(function(ev){
     if(ev.which == 13){
      ev.preventDefault();
      if($(this).val().trim().length == 0){
        $(this).focus();
      }else{
         updateDistrito('form_comuna_editar');
      }
     }
    });

    /// agregar nuevo departamento
    $('#add_departamento').click(function(){
     $('#modal_add_departamento').modal("show");
    });
    $('.save_departamento').click(function(){
      if($('#nombre_dep').val().trim().length == 0)
     {
      $('#nombre_dep').focus();
     }else{
      saveDepartamento('form_add_departamento');
     }
    });

    /// agregar nuevos municipios
    $('#add_provincia').click(function(){
     $('#modal_add_provincia').modal("show");
    });

    /// guardar los municipios
    $('.save_provincia').click(function(){
      if($('#name_provincia').val().trim().length == 0)
     {
      $('#name_provincia').focus();
     }else{
      saveProvincia('form_add_provincia');
     }
    });

    /// agregar nuevas comunas
    $('#add_comuna').click(function(){
      $('#modal_add_comuna').modal("show");
      showProvincias($('#departamento_select_comuna').val(),'provincia_select_dis');
    });

    $('#departamento_select_comuna').change(function(){
     showProvincias($(this).val(),'provincia_select_dis');
    });

    $('#departamento_select_comuna_editar').change(function(){
     showProvincias($(this).val(),'provincia_select_dis_editar');
    });

    $('.save_comuna').click(function(){
      if($('#provincia_select_dis').val() == null){
        $('#provincia_select_dis').focus();
      }else{
        if($('#name_distrito').val().trim().length == 0){
          $('#name_distrito').focus();
        }else{
          saveDistrito('form_add_comuna');
        }
      }
    });

    $('.update_comuna').click(function(){
      if($('#name_distrito_editar').val().trim().length == 0){
        $('#name_distrito_editar').focus();
      }else{
        updateDistrito('form_comuna_editar');
      }
    });

    $('#view_distritos_eliminados').click(function(){
     $('#modal_comunas_eliminados').modal("show");
     showDistritosEliminados();
     activeComuna(TablaComunasEliminados,'#tabla_comunas_eliminados tbody');
    });

    $('#cancel_comuna_eliminados').click(function(){
      $('#modal_comunas_eliminados').modal("hide");
    });
  });
 

/// mostrar las provincias en combo 
function showProvincias(id_dep,select_id)
{
  let option ="<option disabled selected>--- Seleccione ---</option>";
  
  let resultado = show(RUTA+"provincia/mostrar?token_="+TOKEN,{id_departamento:id_dep});
  
  if(resultado.length > 0)
  {
   resultado.forEach(provincia => {
    
    option+= "<option value="+provincia.id_provincia+">"+provincia.name_provincia.toUpperCase()+"</option>";

   });
   
  }
  $('#'+select_id).html(option);
}  
function saveDepartamento(form_id)
{
  let response = crud(RUTA+"departemento/save",form_id);
  

     if(response == 1)
     {
      MessageRedirectAjax('success','Mensaje del sistema','Depatamento registrado','modal_add_departamento')

      $('#'+form_id)[0].reset() /// reseteamos el form
      showDepartamentosExistentes();
      $('#nombre_dep').val("")
     }
     else
     {
      if(response === 'existe')
      {
        $('#nombre_dep').focus();
       MessageRedirectAjax('warning','Mensaje del sistema','El departamento que deseas registrar ya existe','modal_add_departamento')
       $('#nombre_dep').val("");
      }
      else
      {
        MessageRedirectAjax('error','Mensaje del sistema','Error al registrar departamento ):','modal_add_departamento')
      }
     }

}
/// Guardar las comunas
/// crear distritos
function saveDistrito(form_id)
{
  let response = crud(RUTA+"distrito/save",form_id);
  

  if(response == 1)
  {
   MessageRedirectAjax('success','Mensaje del sistema','Comuna registrado correctamente','modal_add_comuna')

  $('#form_add_comuna')[0].reset();
   showDistritosExistentes();
  }
  else
  {
   if(response === 'existe')
   {
    MessageRedirectAjax('warning','Mensaje del sistema','El Comuna que deseas registrar ya existe','modal_add_comuna')
   }
   else
   {
     MessageRedirectAjax('error','Mensaje del sistema','Error al registrar comuna ):','modal_add_comuna')
   }
  }
}

/// modificar datos de la comuna
function updateDistrito(form_id)
{
  let response = crud(RUTA+"distrito/update/"+IDDIS,form_id);
  

  if(response == 1)
  {
   MessageRedirectAjax('success','Mensaje del sistema','Datos de la comuna modificado','modal_comuna_editar')

  
   showDistritosExistentes();
  }
  else
  {
   if(response === 'existe')
   {
    MessageRedirectAjax('warning','Mensaje del sistema','No se realizó ningún cambio!!','modal_comuna_editar')
   }
   else
   {
     MessageRedirectAjax('error','Mensaje del sistema','Error al modificar comuna ):','modal_comuna_editar')
   }
  }
}

/// guradar las provincias existentes
/// registrar provincias

function saveProvincia(form_id)
{

  let response = crud(RUTA+"provincia/save",form_id);

  
  if(response == 1)
  {
   MessageRedirectAjax('success','Mensaje del sistema','Provincia registrado','modal_add_provincia')

   $('#'+form_id)[0].reset() /// reseteamos el form

    showProvinciasExistentes();
  }
  else
  {
   if(response === 'existe')
   {
    MessageRedirectAjax('warning','Mensaje del sistema','La Provincia que deseas registrar ya existe','modal_add_provincia')
   }
   else
   {
     MessageRedirectAjax('error','Mensaje del sistema','Error al registrar provincia ):','modal_add_provincia')
   }
  }
}
 
  function showDepartamentosEliminados()
  {
    TablaDepartamentoEliminados =$('#tabla_dep_eliminados').DataTable({
      retrieve:true,
      language:SpanishDataTable(),
      processing: true,
      "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    ajax:{
        url:RUTA+"departamento/eliminados?token_="+TOKEN,
        method:"GET",
        dataSrc:"response"
      },
      columns:[
        {"data":"name_departamento"},
        {"data":"name_departamento",render:function(dep){return dep.toUpperCase();}},
        {"defaultContent":`
        <div class="row">
         <div class="col-xl-3 col-lg-3 col-md-4 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-success btn-sm" id="activar_dep"><i class='bx bx-check'></i></button>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-4 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-danger btn-sm" id="destroy_dep"><i class='bx bx-down-arrow-alt'></i></button>
          </div>
        </div>
        `}
      ],
    }).ajax.reload(null,false);

    TablaDepartamentoEliminados.on( 'order.dt search.dt', function () {
    TablaDepartamentoEliminados.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
  }).draw();

  }
  /// método para mostrar los departamentos existentes
  function showDepartamentosExistentes()
  {
    TablaDepartamento =$('#tabla_dep').DataTable({
      retrieve:true,
      language:SpanishDataTable(),
      processing:true,
      "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    language: SpanishDataTable(),
    retrieve: true,
    responsive: true,
    processing: true,
      ajax:{
        url:RUTA+"departamento/mostrar?token_="+TOKEN,
        method:"GET",
        dataSrc:"response"
      },
      columns:[
        {"data":"name_departamento"},
        {"data":"name_departamento",render:function(dep){return dep.toUpperCase();}},
        {"defaultContent":`
        <div class="row">
         <div class="col-xl-2 col-lg-2 col-md-4 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-outline-warning btn-sm" id="editar_dep"><i class='bx bxs-edit-alt'></i></button>
          </div>
          <div class="col-xl-2 col-lg-2 col-md-4 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-outline-danger btn-sm" id="ocultar_departamento"><i class='bx bx-x'></i></button>
          </div>
        </div>
        `}
      ],
    }).ajax.reload();

    TablaDepartamento.on( 'order.dt search.dt', function () {
    TablaDepartamento.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
  }).draw();

  }

  /// método para mostrar las provincias existentes
  function showProvinciasExistentes()
  {
    TablaProvincia = $('#tabla_prov').DataTable({
      retrieve:true,
      language:SpanishDataTable(),
      processing:true,
      "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
      ajax:{
        url:RUTA+"/provincia/mostrartodo?token_="+TOKEN,
        method:"GET",
        dataSrc:"response",
      },
      columns:[
        {"data":"name_provincia",render:function(prov){return prov.toUpperCase()}},
        {"data":"name_provincia",render:function(prov){return prov.toUpperCase()}},
        {"data":"name_departamento",render:function(dep){return dep.toUpperCase()}},
        {"defaultContent":`
        <div class="row">
         <div class="col-xl-3 col-lg-3 col-md-4 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-outline-warning btn-sm" id="editar_prov"><i class='bx bxs-edit-alt'></i></button>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-4 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-outline-danger btn-sm" id="ocultar_prov"><i class='bx bx-x'></i></button>
          </div>
        </div>
        `}
      ]
    }).ajax.reload();

    TablaProvincia.on( 'order.dt search.dt', function () {
    TablaProvincia.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
  }).draw();
  }

  /// ver provincias eliminados
  function showProvinciasEliminados()
  {
    TablaProvinciaEliminados = $('#tabla_prov_eliminados').DataTable({
      retrieve:true,
      language:SpanishDataTable(),
      processing:true,
      "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
      ajax:{
        url:RUTA+"/provincia/mostrartodo/eliminados?token_="+TOKEN,
        method:"GET",
        dataSrc:"response",
      },
      columns:[
        {"data":"name_provincia",render:function(prov){return prov.toUpperCase()}},
        {"data":"name_provincia",render:function(prov){return prov.toUpperCase()}},
        {"data":"name_departamento",render:function(dep){return dep.toUpperCase()}},
        {"defaultContent":`
        <div class="row">
         <div class="col-xl-4 col-lg-4 col-md-5 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-outline-success btn-sm" id="habilitar_prov"><i class='bx bx-check'></i></button>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-5 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-outline-danger btn-sm" id="delete_prov"><i class='bx bx-x'></i></button>
          </div>
        </div>
        `}
      ]
    }).ajax.reload();

    TablaProvinciaEliminados.on( 'order.dt search.dt', function () {
    TablaProvinciaEliminados.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
  }).draw();
  }

  /// método para mostrar los distritos existentes
  function showDistritosExistentes()
  {
    TablaDistrito = $('#tabla_distrito').DataTable({
      retrieve:true,
      language:SpanishDataTable(),
      processing:true,
      "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
      ajax:{
        url:RUTA+"distritos/all?token_="+TOKEN,
        method:"GET",
        dataSrc:"response"
      },
      columns:[
        {"data":"name_distrito"},
        {"data":null,render:function(dta){return '<b class="badge bg-primary">'+dta.name_distrito+' - '+dta.name_provincia+' - '+dta.name_departamento+'</b>'}},
        {"defaultContent":`
        <div class="row">
         <div class="col-xl-4 col-lg-4 col-md-5 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-outline-warning btn-sm" id="editar_dis"><i class='bx bxs-edit-alt'></i></button>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-5 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-outline-danger btn-sm" id="ocultar_dis"><i class='bx bx-x'></i></button>
          </div>
        </div>
        `}
      ]
    }).ajax.reload();
    TablaDistrito.on( 'order.dt search.dt', function () {
    TablaDistrito.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
  }).draw();
  }

  function showDistritosEliminados()
  {
    TablaComunasEliminados = $('#tabla_comunas_eliminados').DataTable({
      retrieve:true,
      language:SpanishDataTable(),
      processing:true,
      "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
      ajax:{
        url:RUTA+"distristos/eliminados?token_="+TOKEN,
        method:"GET",
        dataSrc:"response"
      },
      columns:[
        {"data":"name_distrito"},
        {"data":"name_distrito",render:function(comuna){
          return comuna.toUpperCase();
        }},
        {"data":"name_provincia",render:function(prov){
          return prov.toUpperCase();
        }},
        {"data":"name_departamento",render:function(dep){
          return dep.toUpperCase();
        }},
        {"defaultContent":`
        <div class="row">
         <div class="col-xl-4 col-lg-4 col-md-5 col-12 m-xl-0 m-lg-0 m-md-0 m-1">
           <button class="btn btn-rounded btn-outline-success btn-sm" id="activar_dis"><i class='fas fa-check'></i></button>
          </div>
          
        </div>
        `}
      ]
    }).ajax.reload();
    TablaComunasEliminados.on( 'order.dt search.dt', function () {
    TablaComunasEliminados.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
    });
  }).draw();
  }

  function activeComuna(Tabla,Tbody)
  {
    $(Tbody).on('click','#activar_dis',function(){
      let filadis = $(this).parents('tr');

      if(filadis.hasClass('child'))
      {
        filadis = filadis.prev();
      }

      let Data = Tabla.row(filadis).data();
      IDDIS = Data.id_distrito;
      ProcesoActivarDistrito(IDDIS);
    });
  }

  /// activar distrito
  function ProcesoActivarDistrito(id)
  {
    $.ajax({
      url:RUTA+"distrito/activate/"+id,
      method:"POST",
      data:{token_:TOKEN},
      success:function(response)
      {
        response = JSON.parse(response);

        if(response.response == 1)
        {
          
          Swal.fire({
            title:"Mensaje del sistema!",
            text:"Comuna habilitado nuevamente",
            icon:"success",
            target:document.getElementById('modal_comunas_eliminados')
          }).then(function(){
           showDistritosEliminados();
           showDistritosExistentes();
          });
         
        }else{
          Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al eliminar las comunas",
            icon:"error",
            target:document.getElementById('modal_comunas_eliminados')
          });
        }
      },err:function(error){
        Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error al eliminar las comunas",
            icon:"error",
            target:document.getElementById('modal_comunas_eliminados')
          });
      }
    })
  }

  /// eliminado lògico de los distritos
  function EliminarDistrito(Tabla,Tbody)
  {
    $(Tbody).on('click','#ocultar_dis',function(){
      let filadis = $(this).parents('tr');

      if(filadis.hasClass('child'))
      {
        filadis = filadis.prev();
      }

      let Data = Tabla.row(filadis).data();
       IDDIS = Data.id_distrito;
      Swal.fire({
       title: "Estas seguro de eliminar de la lista, a la comuna de "+Data.name_distrito+" ?",
       text: "Al aceptar, automaticamente se eliminara de la lista de los distritos existentes!",
       icon: "question",
       showCancelButton: true,
       confirmButtonColor: "#3085d6",
       cancelButtonColor: "#d33",
       confirmButtonText: "Si, eliminar!"
      }).then((result) => {
     if (result.isConfirmed) {
       $.ajax({
        url:RUTA+"distrito/delete/"+IDDIS,
        method:"POST",
        data:{
          token_:TOKEN
        },
        dataType:"json",
        success:function(response)
        {
          if(response.response == 1)
          {
            Swal.fire({
              title:"Mensaje del sistema!",
              text:"Comuna eliminado correctamente!",
              icon:"success"
            }).then(function(){
              showDistritosExistentes();
            });
          }else{
            Swal.fire({
              title:"Mensaje del sistema!",
              text:"Error al eliminar la comuna!",
              icon:"error"
            });
          }
        },err:function(err){
          Swal.fire({
              title:"Mensaje del sistema!",
              text:"Error al eliminar la comuna!",
              icon:"error"
            });
        }
       }); 
     }
    });
    });
  }

  /// editar las comunas
  function EditarComuna(Tabla,Tbody)
  {
    $(Tbody).on('click','#editar_dis',function(){
      let filadis = $(this).parents('tr');

      if(filadis.hasClass('child'))
      {
        filadis = filadis.prev();
      }

      let Data = Tabla.row(filadis).data();
      IDDIS = Data.id_distrito;
      showDepartamentosEnCombo('departamento_select_comuna_editar');
      $('#departamento_select_comuna_editar').val(Data.id_departamento);
      showProvincias(Data.id_departamento,'provincia_select_dis_editar');
      $('#modal_comuna_editar').modal("show")
      $('#provincia_select_dis_editar').val(Data.id_provincia);
      $('#name_distrito_editar').val(Data.name_distrito);
    });
  }
  /// editar departamento
  function editarDepartamento(Tabla,Tbody)
  {
    $(Tbody).on('click','#editar_dep',function(){

      $('#modal_editar_dep').modal("show");

      let filaSeleccionado = $(this).parents('tr');

      if(filaSeleccionado.hasClass("child"))
      {
        filaSeleccionado = filaSeleccionado.prev();
      }

      let Datos = Tabla.row(filaSeleccionado).data();

      ID_DEP = Datos.id_departamento;

      $('#departamento').val(Datos.name_departamento);

      
    });
  }

  /// Editar las provincias existentes
  var EditarProvincia = function(Tabla,Tbody){

    $(Tbody).on('click','#editar_prov',function(){

       $('#modal_editar_provincia').modal("show");
       let filaSeleccionado = $(this).parents('tr');

       if(filaSeleccionado.hasClass('child'))
       {
        filaSeleccionado = filaSeleccionado.prev();
       }

       /// obtenemos los datos
       let Datos = Tabla.row(filaSeleccionado).data();

       ID_PROV = Datos.id_provincia; // obtenemos el id de la provincia para actualizar

       $('#provincia').val(Datos.name_provincia);

       showDepartamentosEnCombo('dep_show');

       $('#dep_show').val(Datos.id_departamento)
       
    });
  }

   /// Eliminar de la lista a provincias
  var EliminarListaProvincia = function(Tabla,Tbody){

$(Tbody).on('click','#ocultar_prov',function(){

   let filaSeleccionado = $(this).parents('tr');

   if(filaSeleccionado.hasClass('child'))
   {
    filaSeleccionado = filaSeleccionado.prev();
   }

   /// obtenemos los datos
   let Datos = Tabla.row(filaSeleccionado).data();

   ID_PROV = Datos.id_provincia;

   Swal.fire({
  html: '<h3>Estas seguro de eliminar de la lista al municipio de <b class="badge bg-primary">'+Datos.name_provincia+'</b> ?</h3>'
  +'Al eliminar de la lista a la provincia seleccionada, ya no podrás usarlo nuevamente.!',
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si, eliminar!'
}).then((result) => {
  if (result.isConfirmed) {
   ModifyStatusProvincia(ID_PROV,"2");
  }
}); 
   
});
}

  /// cancelar la edición de departamentos
  function cancelEdicionDepartamento(Tabla,Tbody)
  {
   $(Tbody).on('click','#ocultar_departamento',function(){
      let filaSeleccionado = $(this).parents('tr');

      if(filaSeleccionado.hasClass("child"))
      {
        filaSeleccionado = filaSeleccionado.prev();
      }

      let Datos = Tabla.row(filaSeleccionado).data();
      ID_DEP = Datos.id_departamento;
  Swal.fire({
  title: 'Estas seguro de eliminar de la lista al departamento '+Datos.name_departamento+' ?',
  text: "Al eliminar de la lista, ya no podrás usarlo nuevamente.!",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si, eliminar!'
}).then((result) => {
  if (result.isConfirmed) {
   CambiarEstadoDepartamento(ID_DEP,"2");
  }
});      
  });
  }

  /// actualizar datos del departamento seleccionado
  function updateDataDepartamento(id,depart_)
  {
    $.ajax({
      url:RUTA+"departamento/"+id+"/update",
      method:"POST",
      data:{token_:TOKEN,dep:depart_.val()},
      success:function(response)
      {
        response = JSON.parse(response);

        if(response.response == 1 || response.response === 'existe')
        {
          Swal.fire(
            {
              title:"Mensaje del sistema!",
              text:"Departamento actualizado",
              icon:"success",
              target:document.getElementById('modal_editar_dep')
            }
          ).then(function(){
            $('#modal_editar_dep').modal("hide");
            showDepartamentosExistentes();
          });
        }
        else
        {
          Swal.fire(
            {
              title:"Mensaje del sistema!",
              text:"Acaba de ocurrir un error al actualizar los datos del departamento",
              icon:"error",
              target:document.getElementById('modal_editar_dep')
            }
          )
        }
      }
    })
  }
  /// eliminar al departamento de la lista
  function CambiarEstadoDepartamento(id,estado)
  {
    $.ajax({
      url:RUTA+"departamento/"+id+"/cambiar_estado/"+estado,
      method:"POST",
      data:{token_:TOKEN},
      success:function(response)
      {
        response = JSON.parse(response);

        if(response.response == 1)
        {
         if(estado == 2)
         {
          Swal.fire({
            title:"Mensaje del sistema!",
            text:"Departamento eliminado exitosamente",
            icon:"success",
          }).then(function(){
            showDepartamentosExistentes();
          });
         }
         else{
           $('#mensaje_dep_eliminados').show(120);
           showDepartamentosEliminados();
         }
        }
        else
        {
          Swal.fire({
            title:"Mensaje del sistema!",
            text:"Error, el proceso falló, intentelo de nuevo o más tarde",
            icon:"error"
          })
        }
      }
    })
  }

  /// activar el departamento
  function ActualizarDepartamento(Tabla,Tbody)
  {
    $(Tbody).on('click','#activar_dep',function(){

      let filaSeleccionado = $(this).parents('tr');

      if(filaSeleccionado.hasClass("child"))
      {
        filaSeleccionado = filaSeleccionado.prev();
      }

      let Datos = Tabla.row(filaSeleccionado).data();

      ID_DEP = Datos.id_departamento;

      CambiarEstadoDepartamento(ID_DEP,"1");
      
    });
  }

  /// mostrar los departamentos en comboBox
  var showDepartamentosEnCombo = (idCombo)=>{
    let option = '';

    let response =  show(RUTA+"departamento/mostrar?token_="+TOKEN);

    response.forEach(deps => {
      option+='<option value='+deps.id_departamento+'>'+deps.name_departamento+'</option>';
    });

    // mostramos en el combo
    $('#'+idCombo).html(option);
   
  }
  /// Actualizar datos de la provincia
  var updateProvincia = (id,provincia_,departamento_)=>{
    $.ajax({
      url:RUTA+"provincia/"+id+"/update",
      method:"POST",
      data:{token_:TOKEN,prov:provincia_,dep:departamento_},
      success:function(response)
      {
        response = JSON.parse(response);

        if(response.response == 1)
        {
          Swal.fire({
            title:"Mensaje del sistema !",
            text:"Municipio actualizado correctamente",
            icon:'success',
            target:document.getElementById('modal_editar_provincia')
          }).then(function(){
            showProvinciasExistentes();
            $('#modal_editar_provincia').modal('hide')
          });
        }
        else
        {
          if(response.response === 'existe')
          {
            Swal.fire({
            title:"Mensaje del sistema !",
            text:"Usted! no realizó ningún cambio, sus datos permanecen de la misma manera",
            icon:'info',
            target:document.getElementById('modal_editar_provincia')
          })
          } else
           {
          Swal.fire({
            title:"Mensaje del sistema !",
            text:"Error al intentar actualizar datos del municipio seleccionado",
            icon:'error',
            target:document.getElementById('modal_editar_provincia')
          })
           }
        }
      }
    })
  }

  /// Cambiar de estado a la provincia
  var ModifyStatusProvincia = (id,estatus_)=>
  {
    $.ajax({
      url:RUTA+"provincia/"+id+"/modifystatus/"+estatus_,
      method:"POST",
      data:{token_:TOKEN},
      success:function(response)
      {
        response = JSON.parse(response);

        if(response.response == 1)
        {
          if(estatus_ === "2")
          {
            Swal.fire({
            title:'Mensaje del sistema !',
            text:'Municipio eliminado de la lista',
            icon:'success',
          }).then(function(){
            showProvinciasExistentes();
          });
          }
          else
          {
           $('#mensaje_prov_eliminados').show(120);
           showProvinciasEliminados();
          }
        }
        else
        {
          
            Swal.fire({
            title:'Mensaje del sistema !',
            text:'Error, el proceso falló, inténtelo de nuevo o más tarde',
            icon:'error',
          }) 
        }
      }
    })
  }

  /**
   *Activar la provincia 
   */
  var ActivarProvincia = (Tabla,Tbody)=>{
    $(Tbody).on('click','#habilitar_prov',function(){
      let filaSeleccionado = $(this).parents('tr');

      if(filaSeleccionado.hasClass('child'))
      {
        filaSeleccionado = filaSeleccionado.prev();
      }

      let Datas = Tabla.row(filaSeleccionado).data();

      ModifyStatusProvincia(Datas.id_provincia,"1");

    })
  }
</script>
@endsection