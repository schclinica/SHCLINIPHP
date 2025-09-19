@extends($this->Layouts("dashboard"))

@section("title_dashboard","Gestión de médicos")

@section('css')
 <style>
    #imagen_ {
      max-width: 70%;
      width: 220px;   
      height: 210px;
    }
    #nueva-especialidad,label
    {
      cursor: pointer;
    }
    .modal-header
    {
     background-color: #4169E1
    }
    #Tabla-asignar-horarios-medico>thead>tr>th{
      color:aliceblue;
    }
    #div_table_
    {
     overflow:scroll;
     height:290px;
     width:100%;
     border: 0.1rem solid blue;
    }

    #div_table_ table{
      width:100%;
      
    }
    #div_table_ph 
    {
     overflow:scroll;
     height:340px;
     width:100%;
     border: 0.1rem solid blue;
    }

    #div_table_ph table{
      width:100%;
      
    }

    #tabla-medicos>thead>tr>th
    {
      background:linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 50%,#539fe1 79%,#87bcea 100%);;
      padding: 24px;
      color:aliceblue;
    }
    td.hide_me
    {
      display: none;
    }

    #tabla_medico_esp_>thead>tr>th{
      background-color: #506c96;
      color:aliceblue;
    }
    
  </style>
@endsection

@section('contenido')
<div class="mx-3" id="content">
  <div class="row">
    <div class="card shadow mb-4 ">
      <div class="card-header d-flex justify-content-between align-items-center justify-content-between"
      style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);">
        <h4 class="mb-0 d-xl-block d-lg-block d-md-block text-white d-none letra">Listado de Médicos</h4>
        
        <button class="btn_blue float-end col-xl-3 col-lg-4 col-md-5  col-12"
          id="modal-create-docente"><b class="letra"> Agregar uno nuevo <i class='bx bxs-user-plus'></i></b></button>
      </div>

      <div class="card-body">
        <div class="card-text">
          <div class="table table-responsive">
            <table class="table table-bordered table-hover table-sm table-striped nowrap responsive" id="tabla-medicos">
              <thead>
                <tr>
                  <th>#</th>
                  <th>TIPO DOCUMENTO</th>
                  <th># DOCUMENTO</th>
                  <th>MÉDICO</th>
                  <th>GÉNERO</th>
                  <th>FECHA NACIMIENTO</th>
                  <th>DIRECCIÓN</th>
                  <th>TELÉFONO</th>
                  <th>UNIVERSIDAD GRADUADO</th>
                  <th>CMP</th>
                  <th>FOTO</th>
                  <th>SEDE</th>
                  <th>GESTIONAR</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- PESTAÑAS DE NAVEGACIÓN---}}

<div class="modal fade" id="modal-crear-docente" tabindex="-1" aria-hidden="true"  >
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 51%,#539fe1 79%,#87bcea 100%);">
        <h4 class="text-white">Gestión de Médicos <b><i class='bx bx-plus-medical' ></i></b></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
     {{--- modal para crear docentes ----}}
          <div class="card-header  text-white" style="background: #40E0D0">
            <ul class="nav nav-tabs card-header-tabs border-primary" id="tab-medico">
              <li class="nav-item bg">
                <a class="nav-link active text-primary" aria-current="true" href="#medico" id="medico__"><i class='bx bx-user-plus'></i></i>
                Médico
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-primary" href="#tipodoc" tabindex="-1" aria-disabled="true" id="tipodoc__"><i class='bx bxs-message-rounded-add'></i>
                Tipo documento
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-primary" href="#distrito_" tabindex="-1" id="distrito__"><i class='bx bxs-message-rounded-add'></i> Distrito</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-primary" href="#provincia_" tabindex="-1" aria-disabled="true" id="provincia__"><i class='bx bxs-message-rounded-add'></i>
                Provincia
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-primary" href="#departamento_" tabindex="-1" aria-disabled="true" id="departamento__"><i class='bx bxs-message-rounded-add'></i>
                Departamento
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-primary" href="#atencion" tabindex="-1" aria-disabled="true" id="programarhorarios__"><i class='bx bx-calendar'></i>
               <b> Gestión de Horarios y especialidades</b>
                </a>
            </li>

            </ul>
          </div>

          <div class="card-body mt-4">
            <div class="tab-content">
              {{--FORM CREAR PACIENTE---}}

              <div class="tab-pane active" id="medico" role="tabpanel"  >

                <div class="alert alert-warning" id="mensaje_error_existe">
   
                </div>
                <form action="" method="post" id="form_medico" enctype="multipart/form-data">
                  <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                  @if ($this->profile()->rol === 'admin_general')
                    <div class="card-text mb-2">
                        <h5 class="text-primary">Sede</h5>
                        <select name="sede" id="sede" class="form-select"></select>
                      </div>
                  @endif
                  <div class="card-text">
                    <h5 class="text-primary">Datos Personales</h5>
                  </div>
                  <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="tipo_doc" class="form-label float-start">Tipo documento (*)</label>
                        <select name="tipo_doc" id="tipo_doc" class="form-select">
                           
                        </select>
                      </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-8 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="documento" class="form-label float-start">Documento (*)</label>
                        <input type="text" name="documento" id="documento" class="form-control" placeholder="DNI....">
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-12">
                      <div class="form-group">
                        <label for="apellidos" class="form-label float-start">Apellidos (*)</label>
                        <input type="text" name="apellidos" id="apellidos" class="form-control border-primary" placeholder="APELLIDOS....">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="nombres" class="form-label float-start">Nombres (*)</label>
                        <input type="text" name="nombres" id="nombres" class="form-control border-info" placeholder="NOMBRES....">
                      </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-6 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="fecha_nac" class="form-label float-start">fecha de nacimiento (*)</label>
                        <input type="date" name="fecha_nac" id="fecha_nac" class="form-control border-info" value="{{$this->addRestFecha("Y-m-d")}}">
                      </div>
                    </div>
        
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="genero" class="form-label float-start">Género (*)</label>
                        <select name="genero" id="genero" class="form-select">
                          <option value="1">Masculino</option>
                          <option value="2">Femenino</option>
                        </select>
                      </div>
                    </div>
        
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="departamento" class="form-label float-start">Departamento (*)</label>
                        <select name="departamento" id="departamento" class="form-select">
                        </select>
                      </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="provincia" class="form-label float-start">Provincia (*)</label>
                        <select name="provincia" id="provincia" class="form-select">
                        </select>
                      </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-12">
                      <div class="form-group">
                        <label for="distrito" class="form-label float-start">Distrito (*)</label>
                        <select name="distrito" id="distrito" class="form-select">
                        </select>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="form-group">
                        <label for="direccion" class="form-label float-start">Dirección (*)</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" placeholder="DIRECCION....">
                      </div>
                    </div>

                  </div>
                  <br>
                  <div class="card-text">
                    <h5 class="text-primary">Datos secundarios</h5>
                  </div>
                  <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-7 col-12">
                      <div class="form-group">
                        <label for="telefono" class="form-label float-start">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="*** *** ***">
                      </div>
                    </div>
        
                    <div class="col-xl-4 col-lg-4 col-md-5 col-12">
                      <div class="form-group">
                        <label for="facebok" class="form-label float-start">Universidad graduado</label>
                        <input type="text" name="universidad" id="universidad" class="form-control" placeholder="Url Facebook">
                      </div>
                    </div>

                    <div class="col-xl-4 col-lg-4  col-12">
                      <div class="form-group">
                        <label for="cmp" class="form-label float-start"># Colegio médico (*)</label>
                        <input type="text" name="cmp" id="cmp" class="form-control" placeholder="CMP....">
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="form-group">
                        <label for="wasap" class="form-label float-start">Experiencia</label>
                        <textarea name="experiencia" id="experiencia" cols="30" rows="10" class="form-control border-primary" placeholder="Escriba la experiencia del médico"></textarea>
                      </div>
                    </div>
         
                  </div>
                  <div class="card-text mt-4">
                    <h5 class="text-primary">Datos de usuario</h5>
                  </div>
                  <div class="row">
                    <div class="col-xl-2 col-lg-3 col-md-5 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="username" class="form-label float-start">Nombre de usuario (*)</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="*** *** ***">
                      </div>
                    </div>
        
                    <div class="col-xl-6 col-lg-4 col-md-4 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="email" class="form-label float-start">Email(*)</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email...">
                      </div>
                    </div>
        
                    <div class="col-xl-4 col-lg-5 col-md-3   col-12 mb-2">
                      <div class="form-group">
                        <label for="password" class="form-label float-start">Password(*)</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                      </div>
                    </div>
                  </div>
                  {{-- foto del usuario----}}
                  <div class="row justify-content-center mb-1">
                     <img src="{{$this->asset("img/avatars/anonimo_2.png")}}" alt="" class="img-fluid img-thumbnail" 
                     style="border-radius: 50%;border: solid 2px blue" id="imagen_">
                  </div>

                  <div class="row justify-content-center">
                    <button class="btn btn-rounded btn-primary col-xl-3 col-lg-3 col-md-4 col-sm-8 col-12" id="upload"><b>Seleccionar Foto</b> <i class='bx bx-cloud-upload'></i></button>
                    <input type="file" name="foto" id="foto" class="d-none">
                  </div>
                </form>
               <div class="alert alert-danger mt-2" id="alerta_errores_medico" style="display: none">
                  <ul id="errores_medico">
                
                  </ul>
                </div>
              </div>

              {{--- FORM CREAR TIPO DOCUMENTOS---}}

              <div class="tab-pane" id="tipodoc" role="tabpanel"  >
               <div class="row">
                <div class="col">
                  <form action="" method="post" id="form-tipo-doc">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <label for="tipo-doc" class="form-label float-start">Tipo Documento (*)</label>
                    <input type="text" class="form-control" id="tipo-doc" name="tipo-doc" placeholder="Escriba....">
                  </form>
                </div>
               </div>
              </div>

              {{-- FORM PARA CREAR DISTRITOS--}}

              <div class="tab-pane" id="distrito_" role="tabpanel" >
                <div class="col">
                  <form action="" method="post" id="form-distrito">
                    <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-12">
                        <label for="departamento_select_dis" class="form-label float-start">Seleccione departamento (*)</label>
                        <select name="departamento_select_dis" id="departamento_select_dis" class="form-select">

                        </select>
                      </div>
                      <div class="col-xl-4 col-lg-4  col-12">
                        <label for="provincia_select_dis" class="form-label float-start">Seleccione provincia (*)</label>
                        <select name="provincia_select_dis" id="provincia_select_dis" class="form-select">

                        </select>
                      </div>
                      <div class="col-xl-4 col-lg-4   col-12">
                        <label for="name_distrito" class="form-label float-start">Nombre distrito (*)</label>
                        <input type="text" class="form-control" id="name_distrito" name="name_distrito" placeholder="Escriba....">
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              {{-- FORM PARA CREAR PROVINCIAS---}}

              <div class="tab-pane" id="provincia_" role="tabpanel" >
                  <div class="col">
                    <form action="" method="post" id="form-provincia">
                      <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                      <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-6 col-12">
                          <label for="name_provincia" class="form-label float-start">Nombre provincia (*)</label>
                          <input type="text" class="form-control" id="name_provincia" name="name_provincia" placeholder="Escriba....">
                        </div>
                        
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                          <label for="departamento_select" class="form-label float-start">Seleccione departamento (*)</label>
                          <select name="departamento_select" id="departamento_select" class="form-select">

                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
              </div>

              {{-- FORM PARA CREAR DEPARTAMENTOS ---}}

              <div class="tab-pane" id="departamento_" role="tabpanel" >
                <div class="row">
                  <div class="col">
                    <form action="" method="post" id="form-departamento">
                      <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                      <label for="nombre_dep" class="form-label float-start">Nombre departamento (*)</label>
                      <input type="text" class="form-control" id="nombre_dep" name="nombre_dep" placeholder="Escriba....">
                    </form>
                  </div>
                 </div>
              </div>

              <div class="tab-pane" id="atencion" role="tabpanel" >
                  <div class="table table-responsive">
                   <table class="table table-bordered responsive nowrap" id="tabla-medico-programacion-horarios__" style="width: 100%">
                    <thead style="border: 3px solid #483D8B">
                        <tr>
                            <th><b>#</b></th>
                            <th><b>MÉDICO</b></th>
                            <th><b>ESPECIALIDADES</b></th>
                            <th><b>GESTIONAR</b></th>
                        </tr>
                    </thead>
                   </table>
                  </div>
                 <div class="row form_horario_asign_medico" style="display: none">
                  <div class="col-12">
                      <div class="card-text">
                        <h5 style="color: #4169E1">Horario de atención médica</h5>
                    </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6  mb-1">
                    <div class="card-text">
                      <div class="form-group">
                        <label for="dia" class="form-label"><b>Seleccione el Día <i class='bx bxs-calendar'></i></b></label>
                        <select name="dia" id="dia" class="form-select">
                          
                          
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="card-text">
                      <div class="form-group">
                        <label for="medico_asignar" class="form-label"><b>Médico </b> <i class='bx bxs-user-detail'></i></label>
                        <input type="text" class="form-control" name="medico_asignar" id="medico_asignar" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 col-12">
                    <div class="card-text">
                      <div class="form-group">
                        <label for="hora_inicial" class="form-label"><b>Hora Inicio</b> <i class='bx bxs-hourglass-top' ></i></label>
                        <input type="time" class="form-control" name="hora_inicial" id="hora_inicial">
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-4 col-lg-4 col-md-4 col-12 mb-2">
                    <div class="card-text">
                      <div class="form-group">
                        <label for="hora_final" class="form-label"><b>Hora Cierre</b> <i class='bx bxs-hourglass-top' ></i></label>
                        <input type="time" class="form-control" name="hora_final" id="hora_final">
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-4 col-lg-4 col-md-4 col-12 mb-2">
                    <div class="card-text">
                      <div class="form-group">
                        <label for="tiempo_atencion" class="form-label"><b>Tiempo de atención</b> <i class='bx bxs-hourglass-top' ></i></label>
                        <input type="number" class="form-control" name="tiempo_atencion" id="tiempo_atencion">
                      </div>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="card-text table-responsive" id="div_table_">
                       <table class="table table-bordered nowrap table-sm" id="Tabla-asignar-horarios-medico">
                        <thead style="background: #4169E1">
                          <tr>
                            <th >Día</th>
                            <th >Empezando a las</th>
                            <th >Culminando a las</th> 
                            <th>Quitar</th>
                          </tr>
                        </thead>
                        <tbody id="lista_horario_medico__">

                        </tbody>
                       </table>
                    </div>
                  </div>

                 </div>
              </div>
            </div>
          </div>
      </div>

      <div class="modal-footer bg bg-default">
        <button class="btn_success_person" id="save">Guardar <i class='bx bxs-save'></i></button>
        <button class="btn rounded-pill btn-danger" id="cancelar">Cancelar <i class='bx bx-window-close'></i></button>
      </div>

    </div>
  </div>
</div>

{{-- MODAL PARA ASIGNAR ESPECIALIADES AL MÉDICO----}}
<div class="modal fade" id="modal-asignar-especialidad" data-bs-backdrop="static">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 51%,#539fe1 79%,#87bcea 100%);">
        <h5 class="float-end text-white letra">Gestión de Especialidades</h5>
        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closse"></button>
      </div>
      <div class="modal-body">

        {{-- CARD NDE NAVEGACIÓN PARA LA GESTIÓN DE ESPECIALDADES---}}
         
            <ul class="nav nav-tabs card-header-tabs" id="tab_especialidad">
              <li class="nav-item">
                <a class="nav-link active " style="color: #FF4500" aria-current="true" href="#asignar_especialidad__" id="asignar_especialidad_">
                  <i class='bx bx-alarm-add' ></i>
                  Asignar Especialidades
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#gestion_especialidad__" style="color:#FF4500" id="gestion_especialidad_">
                  <i class='bx bxs-copy-alt'></i>
                  Gestionar Especialidades</a>
              </li>
              
            </ul>
            <div class="tab-content">
            <div class="tab-pane fade show active" id="asignar_especialidad__" role="tabpanel" aria-labelledby="pills-home-tab">
              <div class="col mb-2">
                <label for="medico_seleccionado" class="form-label">
                  <b>Médico</b>
                </label>
                <input type="text" name="medico_seleccionado" id="medico_seleccionado" readonly class="form-control">
              </div>
                <div class="col-12">
                      <div class="row">
                        <div class="card-text mx-2">Especialidades
                          <div class="form-check form-switch float-end">
                            <input class="form-check-input" type="checkbox" id="nueva-especialidad">
                            <label class="form-check-label" for="nueva-especialidad" ><span id="title_new_especialidad">Nuevo</span> <i class='bx bxs-alarm-add'></i></label>
                          </div>
                        </div>
                      </div>
                      <br>
                      <div class="card-text mb-3" id="form-new-especialidad">
                        <form action="" method="post" id="form_especialidad">
                          <input type="hidden" class="form-control" name="token_" value="{{$this->Csrf_Token()}}">
                          <div class="row">
                            <div class="col-12 mb-1">
                              <div class="form-group">
                                <label for="especialidad"><b>Nombre especialidad (*)</b></label>
                                <div class="input-group">
                                <input type="text" class="form-control" placeholder="Escriba una especialidad nueva..." name="especialidad" id="especialidad">
                                 <button class="btn btn-rounded btn-success" id="save_especialidad"> Guardar <i class='bx bx-save' ></i></button>  
                              </div>
                                </div>
                            </div>
                            {{-- <div class="col-xl-4 col-lg-4 col-md-5 col-12 mb-1">
                              <div class="form-group">
                                <label for="precio"><b>{{count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda:'S/.'}}</b></label>
                                <input type="text" class="form-control" placeholder="S/. 0.00" name="precio" id="precio" value="0.00">
                                </div>
                            </div> --}}
                            
                          </div>
                        </form>
                      </div>
                        <div class="table-responsive">
                          <table class="table table-bordered table-striped table-sm">
                            <thead>
                              <th colspan="3">
                                <label class="float-end text-primary" for="seleccion_all" id="label_selecciona_all" >
                                  Seleccionar Todo
                                </label>
                                <div class="form-check form-switch float-end">
                                  <input class="form-check-input" type="checkbox" id="seleccion_all">
                                </div>
                              </th>
                              <tr>
                                <th>#</th>
                                <th class="col-xl-7 col-lg-7 col-6">
                                  <input type="text" class="form-control buscador" placeholder="Buscar una especialidad...">
                                </th>
                                <th>PRECIO</th>
                                <th>Gestionar</th>
                              </tr>
                            </thead>
                            <tbody id="lista-especialidades"></tbody>
                          </table>
                        </div>
                         {{--- ALERTAS----}}
                       <div class="m-1">
                        @include($this->getComponents("alertas"))
                       </div>                  
              </div>
            </div>

            <div class="tab-pane" id="gestion_especialidad__" role="tabpanel">
             <div class="col table-responsive">
              <table class="table table-bordered table-striped responsive nowrap table-sm" style="width: 100%" id="Table_especialidades_gestion">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>ESPECIALIDAD</th>
                    <th>ESTADO</th>
                    <th>Gestionar</th>
                  </tr>
                </thead>
               </table>
             </div>
            </div>

          </div>
        {{---F----------IN CARD NAVEGACIÓN  ---------------------}}
        
      </div>
      <div class="modal-footer">
        <button class="btn-save" id="asignar_especialidad"  ><b>Guardar</b> <i class='bx bx-check'></i></button>
        <button class="btn-cancel" id="Cancel_edicion" style="display: none"><b>Cancelar Edición</b> <i class='bx bx-pencil'></i> <i class='bx bxs-message-square-x'></i></button>
      </div>
    </div>
  </div>
</div>

{{--- MODAL PARA PROGRAMACIÓN DE HORARIOS--}}
<div class="modal fade" id="model_programar_horario_medico">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" id="modal_content_ph_">
      <div class="modal-header" style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 51%,#539fe1 79%,#87bcea 100%);">
        <h5 class="float-end text-white letra">Programación de horarios</h5>
        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closse_ph"></button>
      </div>

      <div class="modal-body">
        <div class="col-12 mb-2">
          <label for="medico_ph" class="form-label"><b>MÉDICO</b></label>
          <input type="text" name="medico_ph" id="medico_ph" class="form-control" readonly>
        </div>
        <div class="col-12 table-responsive">
          <table class="table table-bordered table-striped nowrap table-sm" id="tabla_programacion_horario" style="width: 100%">
            <thead class="border-primary">
              <tr>
                <th>#</th>
                <th  class="d-none">ID</th>
                <th>DIA ATENCIÓN</th>
                <th>HORA INICIO</th>
                <th>HORA CIERRE</th>
                <th>TIEMPO</th>
                <th>GESTIONAR</th>
              </tr>
            </thead>
          </table>
        </div>
        <br>
        <div class="row" id="form_ph" style="display:none">
          <div class="card-text text-primary">
           <b> <ins>Programación de horarios </ins></b>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="form-group">
              <label for="hora_in" class="form-label"><b>Empezar desde </b></label>
              <input type="text" name="hora_in" id="hora_in" class="form-control" readonly>
            </div>
          </div>

          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="form-group">
              <label for="hora_fn" class="form-label"><b>Finaliza en </b></label>
              <input type="text" name="hora_fn" id="hora_fn" class="form-control" readonly>
            </div>
          </div>

          <div class="col-xl-4 col-lg-4 col-12">
            <div class="form-group">
              <label for="tiempo_atencion_paciente_" class="form-label"><b>Tiempo de atención </b></label>
              <input type="text" name="tiempo_atencion_paciente_" id="tiempo_atencion_paciente_" class="form-control">
            </div>
          </div>
        </div>
        <div class="row m-1 div_ph_" style="display: none">
          <div class="col" id="div_table_ph">
            <table class="table table-bordered table-sm" id="tabla_ph">
              <thead style="background-color: #4169E1">
                <tr>
                  <th class="text-white">DESDE</th>
                  <th class="text-white">HASTA</th>
                  <th class="text-white text-center">QUITAR</th>
                </tr>
              </thead>
              <tbody id="lista_generate_horario">

              </tbody>
            </table>
            
          </div>
        </div>
   
          <div class="col text-center">
            <img id="loading_program_horario"  src="{{$this->asset('img/gif/loading2.gif')}}" alt="" style="display:none;width: 30%;height: 80%;">
          </div>
        </div>
      

      <div class="modal-footer" id="modal_footer_program_horarios" style="display: none">
        <button class="btn_blue" id="generate_horario"><b>Generar horarios <i class='bx bxs-stopwatch'></i></b></button>
        <button class="button-store" id="save_ph__" style="display:none"><b>Guardar <i class='bx bx-save'></i></b></button>
       
      </div>
    </div>
  </div>
</div>
<!--- MODAL PARA MOSTRAR LOS PROCEDIMIENTOS DE UN MÉDICO---->
<div class="modal fade" id="modal_service_list_medico">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
    <div class="modal-content">
      <div class="modal-header">
       <span class="float-start text-white"> Procedimientos</span>
       <button class="btn btn-rounded btn-danger btn-sm" id="cerrar_modal__services">X</button>
      </div>

      <div class="modal-body">
        <div class="col mb-2">
          <div class="form-group">
            <label for="medico_esp_"><b>MÉDICO</b></label>
            <input type="text" class="form-control" id="medico_esp_" disabled>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="tabla_medico_services_">
            <thead>
             <tr>
               <th>#</th>
               <th class="d-none">ID</th>
               <th>PROCEDIMIENTO</th>
               <th>GESTIONAR</th>
             </tr>
            </thead>
            
         </table>
        </div>
        
      </div>
    </div>
  </div>
</div>
{{--- MODAL PARA EDITAR A LOS MEDICOS------}}
<div class="modal fade" id="modal_editar_medico">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(to bottom, #1E90FF)">
        <p class="h4 text-white">Editar médico</p>
        <button type="button" class="btn-close close_editar_paciente" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
       
        <div class="modal-body">
          <div class="row">
            <div class="col-xl-8 col-lg-8 col-12">
              <label class="text-primary form-label"><b>Sede</b></label>
              <select name="sedeeditar" id="sedeeditar" class="form-select" @php echo $this->profile()->rol==='Director'? 'disabled' :'' @endphp></select>
            </div>
            <div class="col-xl-4 col-lg-4 col-12">
              <label for="tipo_doc_editar" class="form-label"><b>Tipo documento <span class="text-danger">(*)</span></b></label>
              <select id="tipo_doc_editar" class="form-select">
                
              </select>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 col-12">
              <label for="dni_editar" class="form-label"><b># documento <span class="text-danger">(*)</span></b></label>
              <input type="text" id="dni_editar" class="form-control">
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
              <label for="apellido_editar" class="form-label"><b>Apellidos <span class="text-danger">(*)</span></b></label>
              <input type="text" id="apellido_editar" class="form-control">
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
              <label for="nombre_editar" class="form-label"><b>Nombres <span class="text-danger">(*)</span></b></label>
              <input type="text" id="nombre_editar" class="form-control">
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6  col-12">
              <label for="genero_editar" class="form-label"><b>Género <span class="text-danger">(*)</span></b></label>
             <select id="genero_editar" class="form-select">
              <option value="1">Masculino</option>
              <option value="2">Femenino</option>
             </select>
            </div>
            <div class="col-xl-9 col-lg-9 col-12">
              <label for="direccion_editar" class="form-label"><b>Dirección</b></label>
              <input type="text" id="direccion_editar" class="form-control">
            </div>
            <div class="col-xl-3 col-lg-4 col-md-5 col-12">
              <label for="fechanac_editar" class="form-label"><b>Fecha nacimiento</b></label>
              <input type="date" id="fechanac_editar" class="form-control">
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 sm-6 col-12">
              <label for="dep_editar" class="form-label"><b>Departamento</b></label>
             <select id="dep_editar" class="form-select"></select>
            </div>
            <div class="col-xl-3 col-lg-5 col-md-3  col-12">
              <label for="prov_editar" class="form-label"><b>Provincia</b></label>
            <select  id="prov_editar" class="form-select"></select>
            </div>
            <div class="col-xl-3 col-12">
              <label for="distrito_editar" class="form-label"><b>Distrito</b></label>
            <select  id="distrito_editar" class="form-select"></select>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
              <div class="form-group">
                <label for="telefono_editar"><b>Teléfono</b></label>
                <input type="text" id="telefono_editar" class="form-control">
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
              <div class="form-group">
                <label for="cmp_editar"><b>Colegiatura</b></label>
                <input type="text" id="cmp_editar" class="form-control">
              </div>
            </div>
             
            <div class="col-12">
              <div class="form-group">
                <label for="universidad_editar"><b>Univerdad graduado </b></label>
                 <textarea  id="universidad_editar" cols="30" rows="2" class="form-control" placeholder="Escriba aquí... "></textarea>
              </div>
            </div>
            
            <div class="col-12">
              <div class="form-group">
                <label for="experiencia_editar"><b>Experiencia *</b></label>
                 <textarea  id="experiencia_editar" cols="30" rows="5" class="form-control" placeholder="Escriba aquí... "></textarea>
              </div>
            </div>
          </div>
  
        </div>
        <div class="modal-footer">
 
          <button class="btn btn-rounded btn-success"   id="update_medico"><b>Guardar <i class='bx bx-save'></i></b></button>
          <button class="btn btn-danger btn-rounded"  onclick="$('#modal_editar_medico').modal('hide')"><b>Cancelar <i class='bx bx-x' ></i></b></button>
        </div>
      
    </div>
  </div>
</div>
{{--MODAL PARA VISUALIZAR LAS ESPECIALIDADES ASIGNADAS A CAD AMEDICO--}}
<div class="modal fade" id="modal_view_especialidades_medico">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 51%,#539fe1 79%,#87bcea 100%);">
           <h4 class="text-white letra">Especialidades</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
               <label for=""><b>MEDICO</b></label>
               <input type="text" class="form-control" id="medicotextespdatos" readonly>
            </div>
            <div class="table-responsive">
               <table class="table table-bordered table-striped table-hover table-sm nowrap responsive" id="Tabla_med_especialidades_datos"
               style="width: 100%">
                  <thead style="background: #b6d4f7">
                     <tr>
                      <th class="py-4 d-none">ID</th>
                       <th class="py-4">ESPECIALIDAD</th>
                       <th class="py-4">QUITAR</th>
                     </tr>
                  </thead>
               </table>
            </div>
        </div>
      </div>
   </div>
</div>
{{--- INGRESAR LA FIRMA DEL MEDICO---}}
<div class="modal fade" id="ventana_ingresar_firma" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
       <div class="modal-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
         <h5 class="text-white float-start">Subir firma</h5>
         <button class="btn btn-danger floa-end" id="salir_subida_firma"><b>X</b></button>
       </div>
       <div class="modal-body">
         <form action="" method="POST" id="subida_firma_form">
          <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
           <div class="form-group">
             <label for="firma_imagen"><b>Seleccione su firma [200 X 150 px]</b></label>
             <input type="file" name="firma_imagen" id="firma_imagen" class="form-control">
             <span class="text-danger" id="error_subida_firma"></span>
              <span class="text-success" id="success_subida_firma"></span>
           </div>
         </form>
         <div class="row justify-content-center mt-2">
           <img src="{{$this->asset('img/default.png')}}" alt="" class="img-fluid rounded" id="imagen_firma_preview" style="width: 140px; border:solid 2px blue">
         </div>
       </div>
       <div class="modal-footer border-2">
         <button class="btn btn-success" id="save_firma_medico" style="display:none" disabled><b>Subir Firma <i class="fas fa-upload"></i></b></button>
          <button class="btn btn-danger" id="eliminar_imagen" style="display:none"><b>Eliminar X</b></button>
        </div>
    </div>
  </div>
</div>
@endsection

@section('js')
{{-- JS ADICIONALES ---}}
<script>
  var RUTA = "{{URL_BASE}}" // la url base del sistema
  var TOKEN = "{{$this->Csrf_Token()}}"
  var RUTAFOTO = "{{$this->asset('foto/')}}";
  var ID_MEDICO_SELECT;
  var MEDICO;
  var IDESPECIALIDAD;
  var ID_ATENCION_;
  var ID_MEDICO_ESPECIALIDAD;
  var CONTAR = 0;
  var ID_SERVICE ;
  var MEDICOID,PERSONAID;
  var TablaMedicoEspecialidadesAll;
  var PROFILE = "{{$this->profile()->rol}}"
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
<script src="{{URL_BASE}}public/js/medico.js"></script>
<script>
  var Control = 'medico__';
  var Control_Especialidad = null;
  var Control_Especialidad = 'save';

  $(document).ready(function(){

    let BotonSave= $('#save');
 

    let BotonCancelar = $('#cancelar');

    let Tiempo_Atencion_Medica = $('#tiempo_atencion');

    /// desactivar controles
    $('#name_distrito').attr("disabled",true)
    ShowTipoDocumentosIdElement('tipo_doc_editar');
 
    /// control de los navs
    $('#tab-medico a').on('click',function(evento){
      evento.preventDefault();
      
      /// asignamos nuevo atributo a los botones de guardar y cancelar

      Control = $(this)[0].id;

      if(Control=== 'programarhorarios__')
      {
        BotonSave.hide()
        $('.form_horario_asign_medico').hide();
        $('#dia').val("not")
        $('#hora_inicial').val("")
        $('#hora_final').val("")
      }
      else
      {
        BotonSave.show()
      }

      $(this).tab("show");
      MostrarMedicosEspecialidades_()
    })

    $('#firma_imagen').change(function(event){
     let tipoImagen = this.files[0].type;
      tipoImagen = tipoImagen.split("/")[1];
      Foto = this.files[0];
      
      
      if(tipoImagen !== 'jpeg'){
        $('#save_firma_medico').attr("disabled",true);
      Swal.fire({
      title:"Mensaje del sistema!",
      text:"Error,debe de seleccionar una imágen tipo JPG",
      icon:"error",
      target:document.getElementById('subida_firma_form')
      });
      $('#imagen_firma_preview').attr("src","{{$this->asset('img/default.png')}}");
      return;
      }
      var imagen = new Image();
      
      imagen.onload = function(){
      let DimensionX = this.width; let DimensionY = this.height;
      
      if(DimensionX != 200 && DimensionY != 150){
      $('#save_firma_medico').attr("disabled",true);
      Swal.fire({
      title:"¡ADVERTENCIA!",
      text:"La imágen debe tener una medida de [200 X 150 px]",
      icon:"warning",
      target:document.getElementById('subida_firma_form')
      });
      return;
      }
      $('#save_firma_medico').attr("disabled",false);
      };
      
      imagen.src = URL.createObjectURL(Foto);
      $('#imagen_firma_preview').attr("src",URL.createObjectURL(Foto))
      //ReadImagenTree('#firma_imagen','imagen_firma_preview',archivo,"ventana_ingresar_firma");
    });

    $('#save_firma_medico').click(function(){
      let FormUploadFirma = new FormData(document.getElementById("subida_firma_form"))
      axios({
        url:RUTA+"medico/subir-firma/"+ID_MEDICO_SELECT,
        method:"POST",
        data:FormUploadFirma
      }).then(function(response){
         if(response.data.error != undefined){
          $('#error_subida_firma').text(response.data.error);
          $('#success_subida_firma').text("");
         }else{
          $('#error_subida_firma').text("");
          $('#success_subida_firma').text(response.data.success);
          ShowMedicos();
         }
      });
    });
    $('#salir_subida_firma').click(function(){
      $('#ventana_ingresar_firma').modal("hide");
       $('#imagen_firma_preview').attr("src","{{$this->asset('img/default.png')}}");
       $('#firma_imagen').val("");
       $('#save_firma_medico').attr("disabled",true);
       $('#error_subida_firma').text(""); $('#success_subida_firma').text("");
    });
    $('#eliminar_imagen').click(function(){
      let FormEliminarImagenFirma = new FormData();
      FormEliminarImagenFirma.append("token_",TOKEN);
       axios({
         url:RUTA+"medico/firma/eliminar/"+ID_MEDICO_SELECT,
         method:"POST",
         data:FormEliminarImagenFirma
       }).then(function(response){
         if(response.data.error != undefined){
           Swal.fire({
             title:"MENSAJE DEL SISTEMA!!",
             text:response.data.error,
             icon:"error",
             target:document.getElementById("ventana_ingresar_firma")
           })
         }else{
          Swal.fire({
             title:"MENSAJE DEL SISTEMA!!",
             text:response.data.success,
             icon:"success",
             target:document.getElementById("ventana_ingresar_firma")
           }).then(function(){
            ShowMedicos();
            $('#imagen_firma_preview').attr("src",RUTA+"public/asset/img/default.png");
           });
         }
       });
    });
    /// control del nav especialidades
    $('#tab_especialidad a').on('click',function(evento){
      evento.preventDefault();
      /// asignamos nuevo atributo a los botones de guardar y cancelar

      Control_Especialidad= $(this)[0].id;
      
      if(Control_Especialidad === 'asignar_especialidad_')
      {
        Control_Especialidad = 'save';
        $('#asignar_especialidad').show();
      }
      else
      {
        $('#asignar_especialidad').hide();
        subidaScroll('.modal-body,html', 400)
      }
      $(this).tab("show")
    })

    /// quitar la especialidad del medico
    $('#Tabla_med_especialidades_datos').on('click','#quitar_esp_medico_tabla',function(){
      let fila = $(this).closest("tr");

      if(fila.hasClass("child")){
         fila = fila.prev();
      }
      let IdSeleccionado = fila.find("td").eq(0).text();

Swal.fire({
      title: "ESTAS SEGURO?",
      text: "Al aceptar, la especialidad selecconado del médico será quitado de su lista d especialidades!!!!",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
      target:document.getElementById("modal_view_especialidades_medico")
      }).then((result) => {
      if (result.isConfirmed) {
          DeleteEspecialidadMedico(IdSeleccionado);
      }
      });       
    })
/*
 modificarDatosDelPaciente($('#dni_editar'),$('#apellido_editar'),$('#nombre_editar'),
      $('#genero_editar'),$('#direccion_editar'),$('#fechanac_editar'),$('#tipo_doc_editar'),$('#distrito_editar'),$('#telefono_editar'),$('#universidad_editar'),$('#experiencia_editar'),PERSONAID,MEDICOID);
*/
    $('#update_medico').click(function(){
      if($('#sedeeditar').val() == null){
        MessageRedirectAjax('info','Mensaje del sistema','Seleccione la sede para el médico seleccionado!!','modal_editar_medico');
      }else{
        if($('#dni_editar').val().trim().length == 0){
        $('#dni_editar').focus();
      }else{
        if($('#apellido_editar').val().trim().length == 0){
          $('#apellido_editar').focus();
        }else{
          if($('#nombre_editar').val().trim().length == 0){
            $('#nombre_editar').focus();
          }else{
            if($('#fechanac_editar').val().trim().length == 0){
              $("#fechanac_editar").focus();
            }else{
              if($('#dep_editar').val() == null){
                $('#dep_editar').focus();
              }else{
                if($('#prov_editar').val() == null){
                   $('#prov_editar').focus();
                }else{
                  if($('#distrito_editar').val() == null){
                   $('#distrito_editar').focus();
                   }else{
                    modificarDatosDelPaciente($('#dni_editar'),$('#apellido_editar'),$('#nombre_editar'),
                    $('#genero_editar'),$('#direccion_editar'),$('#fechanac_editar'),$('#tipo_doc_editar'),$('#distrito_editar'),$('#telefono_editar'),$('#universidad_editar'),$('#experiencia_editar'),PERSONAID,MEDICOID,
                    $('#cmp_editar'),$('#sedeeditar'));
                   }
                }
              }
            }
          }
        }
      }
      }
    });

    /// mostrar a los pacientes existentes
   ShowMedicos()

   ShowTipoDocumentos(); /// muestra los tipos de documentos en select 

   ShowDepartamentos('departamento'); /// mostrar los departamentos existentes para formulario de medicos
   ShowDepartamentos('dep_editar'); 
   ShowDepartamentos('departamento_select') /// mostrar los departamentos existentes para formulario de provincias 

   ShowDepartamentos('departamento_select_dis');

   /// mostrar especialidades en DataTable
   MostrarEspecialidades_();
   
   /// mostramos a los médicos y especialidades
   MostrarMedicosEspecialidades_();

  deleteHorarioMedico();/// quitar horarios generados
  deleteHorarioAtencionMedico();/// quitar de la lista de horarios de atención médica
  escuchaEspecialidadSelect();
  deleteProcedimiento();
  /// mostrar las sedes para laedicion
  sedesDisponibles("#sedeeditar");
  /// ocultar el formulario de especialidades
  $('#form-new-especialidad').hide()

   /// ocultar mensajes de existencia

   $('#mensaje_error_existe').hide()
   ProgramarHorarioMedico('#tabla_programacion_horario');
 
   /// pasar enter
   enter('nombre_dep','nombre_dep');
   enter('name_provincia','departamento_select');
   enter('name_distrito','name_distrito');
   enter('documento','apellidos');
   enter('apellidos','nombres');
   enter('nombres','direccion');
   enter('direccion','telefono');
   enter('telefono','facebok');
   enter('facebok','wasap');
   enter('wasap','apoderado');
   enter('apoderado','username');
   enter('username','email');
   enter('email','password');
   enter('password','password');
   enter('tipo-doc','tipo-doc');
   enter('servicio','servicio');
   enter('especialidad','precio');
   enter('precio','precio');

   $('#servicio').keypress(function(event){
    if(event.which == 13)
    {
      event.preventDefault();
      if($(this).val().trim().length == 0)
      {
        $(this).focus();
      }
      else
      {
        /// antes verificamos si el procedimiento a agregar existe
        if(!VerificarProdimientoExistencia(ID_MEDICO_ESPECIALIDAD,$(this).val())){
          $('.mensaje_procedim').hide();
          addProcedimiento($(this).val());
          $(this).val("");$(this).focus();
        }
        else
        {
          $('.mensaje_procedim').show(700);
        }
      }
    }
   });

   /// mostramos las provincias al seleccionar un departamento
   $('#departamento').change(function(e){
 
   showProvincias($(this).val(),'provincia');
   });

   $('#dep_editar').change(function(e){
 
    showProvincias($(this).val(),'prov_editar');
    showDistritos_Provincia("hola",'distrito_editar')
   });
   

   // guardar la programación de horarios del médico
$('#save_ph__').click(function(){
  loading('#modal_content_ph_','#4169E1','chasingDots') ;
  setTimeout(function() {
  $('#modal_content_ph_').loadingModal('hide');
  $('#modal_content_ph_').loadingModal('destroy');
  saveProgaramarHorarios();
  }, 2000);
  });

   /// mostrar los distritos deacuerdo a la provincia
   $('#provincia').change(function(){
    
    showDistritos_Provincia($(this).val(),'distrito')
   });

   $('#prov_editar').change(function(){
    
    showDistritos_Provincia($(this).val(),'distrito_editar')

   });

   $('#departamento_select_dis').change(function(){
    
    $('#provincia_select_dis').focus();
    showProvincias($(this).val(),'provincia_select_dis');
    });

    /// al seleccionar provincia , al crear un distrito deberá ir el foco el el input distrito
    $('#provincia_select_dis').change(function(){
    
    $('#name_distrito').attr("disabled",false)
    $('#name_distrito').focus();
    });
   
   $('#modal-create-docente').click(function(){
    sedesDisponibles("#sede");
    openModalPacienteCreate();
   });

   /// al dar click en select tipo documnetos debe de pasar a input dni
   $('#tipo_doc').change(function(){
    $('#documento').focus()
   });

   /// al dar click en el boton Upload  
   $('#upload').click(function(evento){
    evento.preventDefault();
    $('#foto').click();
   });

   $('.btn-close').click(function(){
    $('#form_ph').hide(200);
    $('#modal_footer_program_horarios').hide(200);
    $('.div_ph_').hide(200);
    $('#lista_generate_horario').empty();
    $('#save_ph__').hide();
 
   });

   $('#generate_horario').click(function(){
    generateHorario();
    $('#save_ph__').show(200)
   });

   /// al dar click en el input foto
   $('#foto').change(function(){
    
    /// leeemos la imagen
    ReadImagen(this,'imagen_',event.target.files[0]);
     
     
   });

  $('#cerrar_modal__').click(function(){

    $('#modal_asignar_servicios_medico').modal('hide');
    $('#div_medico_esp_').hide(500);
    $('.mensaje_procedim').hide();
    $('#servicio').val("");
    $('.body_procedimiento_').empty();
  });

  /// guardar los procedimientos listados del médico con respecto a una especialidad
  $('.guardar_form_medico_esp').click(function(){
     if($('.body_procedimiento_ tr').length == 0)
     {
      Swal.fire({
          title:"¡ADVERTENCIA!",
          text:"Para guardar los procedimientos, primero agregue por lo menos un procedimiento a una especialidad del médico",
          icon:"error",
          target: document.getElementById("modal_asignar_servicios_medico")
       });
     }
     else{
      if(saveProcedimientosMedico() == 1)
      {
        Swal.fire({
          title:"Mensaje del sistema",
          text:"Los procedimientos asignados han sido guardados correctamente",
          icon:"success",
          target: document.getElementById("modal_asignar_servicios_medico")
        }).then(function(){
          $('#servicio').val("");
          $('#servicio').focus();
          $('.body_procedimiento_').empty();
          $('#modal_asignar_servicios_medico').modal('hide');
          $('#div_medico_esp_').hide(500);
          $('.mensaje_procedim').hide();
        });
      }
      else
      {
        Swal.fire({
          title:"Mensaje del sistema",
          text:"Acaba de ocurrir un error al guardar los procedimientos",
          icon:"error",
          target: document.getElementById("modal_asignar_servicios_medico")
        });
      }
     }
  });

   /// crear pacientes(Boton control de guardar dependiendo del tipo de navgación)
    BotonSave.click(function(){
    
      if(Control == 'medico__')
      {
        if($('#sede').val()== null && PROFILE === 'admin_general'){
         $('#sede').focus();   
         MessageRedirectAjax('info','Mensaje del sistema','Seleccione la sede en que operará el médico!','modal-crear-docente');
       }else{
        /// crear pacientes
        if($('#tipo_doc').val() == null)
        {
        $('#tipo_doc').focus();
        MessageRedirectAjax('warning','¡ADVERTENCIA!','Seleccione un tipo de documento','modal-crear-paciente');
        }
        else
        {
        if($('#documento').val().trim().length ==0 )
        {
        $('#documento').focus()
        }
        else
        {
        if($('#apellidos').val().trim().length == 0)
        {
        $('#apellidos').focus();
        }
        else
        {
        if($('#nombres').val().trim().length == 0)
        {
        $('#nombres').focus();
        }
        else{
        if($('#distrito').val() == null)
        {
        $('#distrito').focus();
        MessageRedirectAjax('warning','¡ADVERTENCIA!','Seleccione el distrito del médico','modal-crear-paciente');
        
        }else{
        if($('#username').val().trim().length == 0)
        {
        $('#username').focus();
        }
        else
        {
        if($('#email').val().trim().length ==0)
        {
        $('#email').focus();
        }
        else
        {
        if($('#password').val().trim().length == 0)
        {
        $('#password').focus();
        }
        else
        {
        let data = new FormData(form_medico);
        saveMedico(data);
        }
        }
        }
        }
        }
        }
        }
        
        }
        }
      }
      //  
      else{
        if(Control == 'tipodoc__')
        {
          // crear tipo documento

          if($('#tipo-doc').val().trim().length == 0)
          {
            $('#tipo-doc').focus()
          }
          else
          {
            saveTipoDocumento('form-tipo-doc');
          }

        }
        else{
          if(Control == 'distrito__')
          {
            /// crear distritos
           if($('#departamento_select_dis').val() == null)
           {
            $('#departamento_select_dis').focus();
           }
           else
           {
            if($('#provincia_select_dis').val() == null)
           {
            $('#provincia_select_dis').focus();
           }
           else
           {
            if($('#name_distrito').val().trim().length == 0)
            {
              $('#name_distrito').focus();
            }
            else{
              saveDistrito('form-distrito');
            }
           }
           }
          }
          else{
            if(Control == 'provincia__')
            {
             
              // crear provincias

              if($('#name_provincia').val().trim().length == 0)
              {
                $('#name_provincia').focus()
              }
              else{
                saveProvincia('form-provincia');
              }
            }else{

              // crear departamentos
              if(Control === 'departamento__')
              {
                if($('#nombre_dep').val().trim().length == 0)
              {
                $('#nombre_dep').focus();
              }
              else
              {
                saveDepartamento('form-departamento')
              }
              }
              else{
               if($('#lista_horario_medico__ tr').length>0)
               {

              if(Tiempo_Atencion_Medica.val().trim().length >0)
                {
                if(asignarHorarioMedico(Tiempo_Atencion_Medica.val()))
               {
                MessageRedirectAjax('success','Mensaje del sistema','Los horarios de atención para el médico '+MEDICO+' an sido asignados correctamente','modal-crear-docente')
                $('.form_horario_asign_medico').hide();
                $('#dia').val("not")
                $('#hora_inicial').val("")
                $('#hora_final').val("")
                Tiempo_Atencion_Medica.val("");
                $('#save').hide();
                $('#lista_horario_medico__').empty();
               } 
                }
                else
                {
                  Tiempo_Atencion_Medica.focus();
                  MessageRedirectAjax('warning','Mensaje del sistema','Ingrese el tiempo de atención médica para '+MEDICO,'modal-crear-docente')
                }
               }
               else{
                
                $('#dia').focus()
                MessageRedirectAjax('error','Mensaje del sistema','Llene los horarios de atención para el medico '+MEDICO,'modal-crear-docente')
               }
              }

            }
          }
        }
      }
    })

     /// Boton de cancelar
    BotonCancelar.click(function(){
    
    if(Control == 'paciente__')
    {
      /// cancelar pacientes

      $('#form-paciente')[0].reset()

    }
    else{
      if(Control == 'tipodoc__')
      {
        // cancelar tipo documento

        $('#form-tipo-doc')[0].reset()
      }
      else{
        if(Control == 'distrito__')
        {
          /// cancelar distritos
          $('#name_distrito').val("")
          $('#name_distrito').attr("disabled",true);

        }
        else{
          if(Control == 'provincia__')
          {
           
            // cancelar provincias
          }else{
            // cancelar departamentos
            
          }
        }
      }
    }
  })

  /// abrimos el formulario de escpecialidades
  $('#nueva-especialidad').change(function(){
    if($(this).is(":checked"))
    {
      $('#title_new_especialidad').text("Ocultar")
      $('#form-new-especialidad').show(1500);
      $('#especialidad').val("")
      $('#precio').val("0.00");
      $('#especialidad').focus()
    }
    else{
      $('#title_new_especialidad').text("Nuevo")
      $('#form-new-especialidad').hide(1500);
      $('#especialidad').val("")
    }
    Control_Especialidad='save';
  });

  /// Boton de crear especialidad
  $('#save_especialidad').click(function(evento){
   evento.preventDefault();
   if($('#especialidad').val().trim().length == 0)
   {
    $('#especialidad').focus();
   }
   else{
    if(Control_Especialidad === 'save')
     {
      let DataFormEspecialidad = new FormData(form_especialidad);
      CrearEspecialidades(DataFormEspecialidad);
     }
     else{
      /// modificamos la especialidad
      modificarEspecialidad(IDESPECIALIDAD);
     }
   }
  })

  $('#closse').click(function(){
    $('#form-new-especialidad').hide();$('#nueva-especialidad').prop('checked',false);$('#title_new_especialidad').text('Nuevo');

    $('#label_selecciona_all').text('Seleccionar Todo')

    $('#lista-especialidades input[type=checkbox]').prop('checked',false)
    $('#seleccion_all').prop('checked',false)
    $('#Cancel_edicion').hide()
    /// limpiar la tabla de especialidades no asignados a médicos

    $('#lista-especialidades').empty()

    ocultarAlert();
  })

  $('#Cancel_edicion').click(function(){
   $('#Cancel_edicion').hide(800)
   $('#especialidad').val("");
   $('#form-new-especialidad').hide(800)
  });

  /// proceso de seleccionar todo
  $('#seleccion_all').change(function(){
   if($(this).is(':checked'))
   {
    $('#label_selecciona_all').text('Desmarcar Todo')
    $('#lista-especialidades input[type=checkbox]').prop('checked',true)
   }
   else
   {
    $('#label_selecciona_all').text('Seleccionar Todo')
    $('#lista-especialidades input[type=checkbox]').prop('checked',false)
   }
   });

   /// buscador de las especialidades no asignados a los médicos
   $('.buscador').keyup(function(){
    VerEspecialidadesNoAsignadosDelMedico(ID_MEDICO_SELECT,$(this).val())
   });

   /// Boton asignar especialidades a médicos

   $('#asignar_especialidad').click(function(){
   if(ContarEspecialidadSeleccionado('#lista-especialidades') > 0)
   {
    AsignarEspecialidadesMedico();
    
   }
   else
   {
        /// subimos el scroll hacia arriba
     BajadaScroll('.modal-body,html', 400)
     $('#error_alerta').show(800);

     $('#texto_alerta_error').text('Error, Seleccione por lo menos una especialidad para asignar al médico '+MEDICO)
   }
   });
     
   /// mostrar horario con respecto al día
   $('#dia').change(function(){
     
    showHorario($(this).val());
   });

   $('#hora_final').keypress(function(evento){
    
    if(evento.which === 13)
    {
      evento.preventDefault();

      if(!ExisteDato($('#dia').val())){
      
      listHourMedico($('#dia').val(),$('#hora_inicial').val(),$('#hora_final').val());
      $('#dia').focus();
      
      }else
      {
        MessageRedirectAjax('warning','¡ADVERTENCIA!','El horario para el día '+$('#dia').val()+" ya esta ingresado",'modal-crear-docente');
      }

    }
   });
   });

  function openModalPacienteCreate()
  {
    FocusInputModal('modal-crear-docente','documento')
  }
  
  function ocultarAlert()
  {
    $('#error_alerta').hide();

    $('#texto_alerta_error').text('')
  }
  /*MOSTRAR LAS SEDES DISPONIBLES*/
  function sedesDisponibles(sedeid){
    let option = '<option disabled selected>--- Seleccione ----</option>';
    axios({
      url:RUTA+"sedes/all/disponibles",
      method:"GET",
    }).then(function(response){
        if(response.data.sedes.length > 0){
           response.data.sedes.forEach(sede => {
              option+=`<option value=${sede.id_sede}>${sede.namesede.toUpperCase()}</option>`;
           });
        }
        $(sedeid).html(option);
    });
  }
</script>
@endsection