@extends($this->Layouts("dashboard"))

@section("title_dashboard","Pacientes")

@section('css')
    <style>
      #tabla-pacientes>thead>tr>th{
        padding: 20px;
        background:linear-gradient(to bottom, #6A5ACD 100%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
      }
      td.hide_me
      {
      display: none;
      }
    </style>
@endsection
@section('contenido')

<div class="">
  <div class="row">
    <div class="card shadow">
<div class="card-header" style="background:linear-gradient(to bottom, #9400D3 100%,#73b1e7 100%,#0a77d5 50%,#539fe1 7%,#87bcea 100%);">
  <h4 class="mb-0 d-xl-block d-lg-block d-md-block d-sm-block d-none float-start letra text-white">Listado de pacientes</h4>

  <button class="btn_blue float-end col-xl-3 col-lg-4 col-md-5 col-sm-6 col-12" id="modal-create-paciente"><b>Agregar
      uno nuevo <i class='bx bx-user-plus'></i></b></button>
</div>
      <div class="card-body" id="carga_">
       
          <div class="table table-responsive">
            <table class="table table-bordered table-hover table-striped nowrap" id="tabla-pacientes">
              <thead>
                <tr style="background: #6A5ACD">
                  <th class="text-white">#</th>
                  <th class="text-white">SEDE</th>
                  <th class="text-white">TIPO DOCUMENTO</th>
                  <th class="text-white"># DOCUMENTO</th>
                  <th class="text-white">PACIENTE</th>
                  <th class="text-white">GÉNERO</th>
                  <th class="text-white">FECHA NACIMIENTO</th>
                  <th class="text-white">DIRECCIÓN</th>
                  <th class="text-white">TELÉFONO</th>
                  <th class="text-white">FACEBOOK</th>
                  <th class="text-white">WHATSAPP</th>
                  <th class="text-white">ESTADO CIVIL</th>
                  <th class="text-white">EDAD</th>
                  <th class="text-white">APODERADO</th>
                  <th class="text-white">GESTIONAR</th>
                </tr>
              </thead>
            </table>
          </div>
      </div>

    </div>
  </div>
</div>

{{-- PESTAÑAS DE NAVEGACIÓN---}}

<div class="modal fade" id="modal-crear-paciente" tabindex="-1" aria-hidden="true"  >
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 51%,#539fe1 79%,#87bcea 100%);">
        <h4 class="text-white">Gestión de pacientes</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="salir_paciente"></button>
      </div>
      <div class="modal-body" id="form_paciente_data_window">
     {{--- modal para crear pacientes ----}}
          <div class="card-header bg bg-secondary text-white">
            <ul class="nav nav-tabs card-header-tabs border-primary" id="tab-paciente">
              <li class="nav-item bg">
                <a class="nav-link active text-primary" aria-current="true" href="#paciente" id="paciente__"><i class='bx bx-user-plus'></i></i>
                Paciente
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
                <a class="nav-link text-success" href="#mensaje_wasap" tabindex="-1" aria-disabled="true" id="mensajewasap__"><i class='bx bxl-whatsapp'></i>
               <b> Enviar Mensaje a Paciente via WhatsApp</b>
                </a>
              </li>
              

            </ul>
          </div>

          <div class="card-body mt-4">
            <div class="tab-content">
              {{--FORM CREAR PACIENTE---}}

              <div class="tab-pane active" id="paciente" role="tabpanel"  >

                <div class="alert alert-warning" id="mensaje_error_existe">
   
                </div>

                <form action="" method="post" id="form-paciente">
                  
                    <div class="col-12 mb-2">
                        <div class="form-group">
                          <label for="sede"><b class="letra">Sede</b></label>
                          <select name="sede" id="sede" class="form-select"></select>
                        </div>
                      </div>
                  
                  <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
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
                        <div class="input-group">
                          <input type="text" name="documento" id="documento" class="form-control" placeholder="DNI....">
                          <button class="btn btn-primary" id="search_personaapi"><i class="fas fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-12">
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
                    <div class="col-xl-2 col-lg-3 col-md-5 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="telefono" class="form-label float-start">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="*** *** ***">
                      </div>
                    </div>
        
                    <div class="col-xl-6 col-lg-4 col-md-4 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="facebok" class="form-label float-start">Facebook</label>
                        <input type="text" name="facebok" id="facebok" class="form-control" placeholder="Url Facebook">
                      </div>
                    </div>

                    <div class="col-xl-4 col-lg-5 col-md-3 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="wasap" class="form-label float-start">Whatsapp</label>
                        <input type="text" name="wasap" id="wasap" class="form-control" placeholder="Escriba el Whatsapp del paciente...">
                      </div>
                    </div>
        
                    <div class="col-xl-4 col-lg-5 col-md-5 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="estado-civil" class="form-label float-start">Estado Cívil(*)</label>
                        <select name="estado-civil" id="estado-civil" class="form-select">
                          <option value="s">Soltero</option>
                          <option value="c">Casado</option>
                          <option value="v">Viudo</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-xl-8 col-lg-7 col-md-7 col-12">
                      <div class="form-group">
                        <label for="apoderado" class="form-label float-start">Padre/Madre Apoderado</label>
                        <input type="text" name="apoderado" id="apoderado" class="form-control">
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
        
                    <div class="col-xl-4 col-lg-5 col-md-3 col-sm-6 col-12">
                      <div class="form-group">
                        <label for="password" class="form-label float-start">Password(*)</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                      </div>
                    </div>
                  </div>
                </form>
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

              <div class="tab-pane" id="mensaje_wasap" role="tabpanel" >
                <div class="row">
                  <div class="col">
                    <h1><p>Enviamos mensaje via whatsApp al paciente para que pueda asistir puntual a su cita médica</p></h1>
                  </div>
                 </div>
              </div>
            </div>
          </div>
      </div>

      <div class="modal-footer bg bg-default">
        <button class="btn rounded-pill btn-success" id="save">Guardar <i class='bx bxs-save'></i></button>
        <button class="btn rounded-pill btn-danger" id="cancelar">Cancelar <i class='bx bx-window-close'></i></button>
      </div>

    </div>
  </div>
</div>
{{-- MODAL PARA EDITAR LOS DATOS ESPÉCIFICOS DEL PACIENTE--}}
<div class="modal fade" id="modal_editar_paciente">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(to bottom, #9400D3)">
        <p class="h4 text-white">Editar paciente</p>
        <button type="button" class="btn-close close_editar_paciente" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
       
        <div class="modal-body">
          <div class="row">
            <div class="col-xl-8 col-lg-8 col-12 mb-2">
                <div class="form-group">
                    <label for="sedeeditar" class="form-label"><b class="letra">Sede * </b></label>
                    <select name="sedeeditar" id="sedeeditar" class="form-select"></select>
                </div>
                </div>
            <div class="col-xl-4 col-lg-4 col-12">
              <label for="tipo_doc_editar" class="form-label"><b>Tipo documento <span class="text-danger">(*)</span></b></label>
              <select id="tipo_doc_editar" class="form-select">
                
              </select>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
              <label for="dni_editar" class="form-label"><b># documento <span class="text-danger">(*)</span></b></label>
              <input type="text" id="dni_editar" class="form-control">
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
              <label for="apellido_editar" class="form-label"><b>Apellidos <span class="text-danger">(*)</span></b></label>
              <input type="text" id="apellido_editar" class="form-control">
            </div>
            <div class="col-xl-6 col-lg-6 col-md-4 col-12">
              <label for="nombre_editar" class="form-label"><b>Nombres <span class="text-danger">(*)</span></b></label>
              <input type="text" id="nombre_editar" class="form-control">
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12">
              <label for="genero_editar" class="form-label"><b>Género <span class="text-danger">(*)</span></b></label>
             <select id="genero_editar" class="form-select">
              <option value="1">Masculino</option>
              <option value="2">Femenino</option>
             </select>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-7 col-12">
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
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
              <div class="form-group">
                <label for="telefono_editar"><b>Teléfono</b></label>
                <input type="text" id="telefono_editar" class="form-control">
              </div>
            </div>
            <div class="col-xl-6 col-lg-4 col-md-8 col-sm-6 col-12">
              <div class="form-group">
                <label for="facebok_editar"><b>Facebook</b></label>
                <input type="text" id="facebok_editar" class="form-control">
              </div>
            </div>
  
            <div class="col-xl-3 col-lg-5 col-12">
              <div class="form-group">
                <label for="wasap_editar"><b>WhatsApp</b></label>
                <input type="text" id="wasap_editar" class="form-control">
              </div>
            </div>
  
            <div class="col-xl-4 col-lg-4 col-md-5 col-sm-6 col-12">
              <div class="form-group">
                <label for="telefono_editar"><b>Estado civil <span class="text-danger">(*)</span></b></label>
               <select id="estado_civil_editar" class="form-select">
                <option value="se">Sin especificar</option>
                <option value="s">Soltero</option>
                <option value="c">Casado</option>
                <option value="v">Viudo</option>
               </select>
              </div>
            </div>
  
            <div class="col-xl-8 col-lg-8 col-md-7 col-sm-6 col-12">
              <div class="form-group">
                <label for="apoderado_editar"><b>Apoderado</b></label>
                <input type="text" id="apoderado_editar" class="form-control">
              </div>
            </div>
          </div>
  
        </div>
        <div class="modal-footer border-2">
 
          <button class="btn btn-rounded btn-success"   id="update_paciente"><b>Guardar <i class='bx bx-save'></i></b></button>
          <button class="btn btn-danger btn-rounded" type="reset"><b>Cancelar <i class='bx bx-x' ></i></b></button>
        </div>
      
    </div>
  </div>
</div>

{{--- 
SUBIR ESTE CAMBIO AL HOSTING
MODAL PARA SUBIR ARCHIVOS PARA EL PACIENTE
---}}
<div class="modal fade" id="modal_upload_files" data-bs-backdrop="static">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header" style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 51%,#539fe1 79%,#87bcea 100%);">
           <h4 class="text-white">Subir documento</h4>
         </div>

         <div class="modal-body" id="bodyupload">
           <div class="form-group">
             <label for="paciente" class="form-label"><b>Paciente</b></label>
             <input type="text" class="form-control" id="pacientepreview" readonly>
           </div>

            <form action="{{$this->route("paciente/upload/documentos")}}" method="post" enctype="multipart/form-data" id="form_upload_documentos">
              <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">

              <div class="form-group mt-3">
                <label for="fecha" class="form-label"><b>Fecha subida <span class="text-danger">*</span></b></label>
                <input type="date" name="fecha" class="form-control" value="{{$this->FechaActual("Y-m-d")}}">
              </div>
              <div class="form-group mt-1">
                 <label for="files"><b>Seleccione los archivos[PDF,IMÁGENES(jpg|png|jpeg)] <span class="text-danger">*</span></b></label>
                 <input type="file" class="form-control"  id="documentos_file"  name="documentos[]" multiple>
              </div>

              <div class="alert alert-success mt-2" style="display: none" id="alert_upload_doc">
                 <b>Archivos subidos correctamente! <i class='bx bx-check'></i></b>
              </div>

              <div class="alert alert-danger mt-2" style="display: none" id="alert_upload_doc_error">
                <b> Error al subir los archivos, solo se aceptan [PDF|IMÁGENES(jpg|jpeg|png)]! <i class='bx bx-x'></i></b>
             </div>

             <div class="alert alert-warning mt-2" style="display: none" id="alert_upload_doc_vacio">
              <b> Seleccione por lo menos un archivo [PDF|IMÁGENES(jpg|jpeg|png)]! <i class='bx bxs-error-alt'></i></b>
             </div>
              
              <div class="mt-5 text-end">
                 <button class="btn btn-success rounded" id="upload_documents">Subir documetos <i class='bx bx-upload'></i></button>
                 <button class="btn btn-danger rounded" id="cerra_upload_doc"><b>Cerrar X</b></button>
                </div>
            </form>
         </div>
      </div>
   </div>
</div>

{{-- VISUALIZAR EN UN IFRAME---}}
<div class="modal fade" id="modal_preview_archivos" data-bs-backdrop="static">
  <div class="modal-dialog modal-xl modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header bg-primary"><h5><span class="text-success letra">PACIENTE: </span> <span id="paciente_text" class="text-white letra"></span></h5></div>
      <div class="modal-body">
        <iframe id="frame_doc" type="application/pdf" style="float:right;"  width='100%' height='500' allowfullscreen webkitallowfullscreen></iframe>
      </div>

      <div class="modal-footer">
        <button class="btn btn-danger" id="cerrar_preview_archivo">Cerrar X</button>
      </div>
    </div>
  </div>
</div>


{{--- MODAL PARA VISUALIZAR LOS DOCUMENTOS SUBIDOS---}}
<div class="modal fade" id="modal_preview_upload_files" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
     <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(to bottom, #d0e4f7 0%,#73b1e7 24%,#0a77d5 51%,#539fe1 79%,#87bcea 100%);">
          <h4 class="text-white">Documentos subidos</h4>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="paciente" class="form-label"><b>Paciente</b></label>
            <input type="text" class="form-control" id="pacientepreview_two" readonly>
          </div>
          <div class="input-group mt-2" id="desc_editar" style="display: none">
 
            <input type="text" class="form-control" id="descripcion_editar" placeholder="Descripción....">
            <button class="btn btn-success btn-sm" id="save_desc_upload">Guardar <i class='bx bx-save'></i></button>
          </div>

          <div class="mt-4 table table-responsive">
              <table class="table table-bordered table-striped nowrap responsive" id="tabladocumentos" style="width: 100%">
                  <thead>
                     <tr>
                       <th>#</th>
                       <th>Fecha</th>
                       <th>Descripción</th>
                       <th>Acciones</th>
                       <th class="d-none">ID</th>
                       <th class="d-none">DOCUMENTO</th>
                     </tr>
                  </thead>
              </table>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-danger rounded" id="cerra_show_doc"><b>Cerrar X</b></button>
        </div>
     </div>
  </div>
</div>
@endsection

@section('js')
{{-- JS ADICIONALES ---}}
<script>
  var RUTA = "{{URL_BASE}}" // la url base del sistema
  var TOKEN = "{{$this->Csrf_Token()}}";
  var PACIENTE_ID;
  var PERSONA_ID;
  var PROFILE = "{{$this->profile()->rol}}";
  var SEDEDATA = "{{$this->profile()->sede_id}}";
  var DISTRITO_ID;
  var PACIENTETEXT;
</script>
<script src="{{URL_BASE}}public/js/control.js"></script>
<script src="{{URL_BASE}}public/js/Paciente.js"></script>
<script>
  var Control = 'paciente__';
  // subir host
  var TablaDocumentos;
  var PACIENTEDOCID;

  $(document).ready(function(){

    let BotonSave= $('#save'); let DocumentoEditar = $('#dni_editar'); let ApellidoEditar = $('#apellido_editar');
    let NombreEditar = $('#nombre_editar'); let GeneroEditar = $('#genero_editar'); let DireccionEditar = $('#direccion_editar'); let FechaNacimientoEditar = $('#fechanac_editar'); 
    let DistritoEditar = $('#distrito_editar'); let TelefonoEditar = $('#telefono_editar');
     let FacebookEditar = $('#facebok_editar');
    let WasapEditar = $('#wasap_editar'); EstadoCivilEditar = $('#estado_civil_editar');
    let ApoderadoEditar = $('#apoderado_editar');let TipoDocEditar = $('#tipo_doc_editar');
 
    /// Boton para subida de documentos pdf del paciente(subir hosting)
    $('#upload_documents').click(function(evento){
      evento.preventDefault();
      let FormUploadDc = new FormData(document.getElementById("form_upload_documentos"));
      FormUploadDc.append("paciente",PACIENTE_ID);
         // loading
       loading('#bodyupload','#4169E1','chasingDots');
        $.ajax({
        url:RUTA+"paciente/upload/documentos",
        method:"POST",
        data:FormUploadDc,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(response)
        {
           if(response.response === 'success'){
          setTimeout(() => {
            $('#bodyupload').loadingModal('hide');
            $('#bodyupload').loadingModal('destroy');
            $('#alert_upload_doc').show(400);
            $('#alert_upload_doc_vacio').hide();
            $('#alert_upload_doc_error').hide();
            $('#documentos_file').val("");
          },1000);
           }else{
            $('#bodyupload').loadingModal('hide');
            $('#bodyupload').loadingModal('destroy');
            if(response.response === 'vacio'){
              $('#alert_upload_doc_vacio').show(400);
              $('#alert_upload_doc').hide();
              $('#alert_upload_doc_error').hide();
            }else{
              $('#bodyupload').loadingModal('hide');
              $('#bodyupload').loadingModal('destroy');
              $('#alert_upload_doc_error').show(400);
              $('#alert_upload_doc').hide();
              $('#alert_upload_doc_vacio').hide();
            }
           }
        }
      });
    });
    
    $('#cerra_upload_doc').click(function(evento){
      evento.preventDefault();
      $('#documentos_file').val("");
      $('#alert_upload_doc').hide();
      $('#alert_upload_doc_vacio').hide();
      $('#alert_upload_doc_error').hide();
      $('#modal_upload_files').modal("hide");
    });
    $('#salir_paciente').click(function(){
      $('#provincia').empty();
      $('#distrito').empty();
      $('#departamento').prop("selectedIndex",0);
    })
    $('#cerra_show_doc').click(function(evento){
      evento.preventDefault();
       $('#desc_editar').hide();
       $('#descripcion_editar').val("");
       $('#modal_preview_upload_files').modal("hide")
    });

    /// buscamos persona por api peru
    $('#search_personaapi').click(function(evento){
      let formSearchPersona = new FormData();
    
      evento.preventDefault();
  
      if($('#tipo_doc option:selected').text().toUpperCase() === 'DNI'){
        if($('#documento').val().trim().length < 8 || $('#documento').val().trim().length > 8){
          Swal.fire({
            title:"MENSAJE DEL SISTEMA!!",
            text:"Debes de ingresar 8 dígitos para realizar la consulta!!",
            icon:"error",
            target:document.getElementById('modal-crear-paciente')
          });
          $('#documento').focus();
        }else{
          BuscarPersonaPorDni($('#documento').val());
        }
      }
    });


    $('#cerrar_preview_archivo').click(function(){
      $('#modal_preview_upload_files').modal("show");
      $("#modal_preview_archivos").modal("hide");
    });

    $('#save_desc_upload').click(function(){
      $.ajax({
        url:RUTA+"paciente/update/descripcion/upload-document/"+PACIENTEDOCID,
        method:"POST",
        data:{
          token_:TOKEN,
          doc_desc:$('#descripcion_editar').val()
        },
        dataType:"json",
        success:function(response){
          if(response.response === 'ok'){
            Swal.fire({
              title:"Mensaje del sistema!",
              text:"Descripción actualizada correctamente!",
              icon:"success",
              target:document.getElementById('modal_preview_upload_files')
            }).then(function(){
              $('#desc_editar').hide();
              $('#descripcion_editar').val("");
              showDocumentosSubidos(PACIENTE_ID);
            });
          }
        }
      })
    });

    /*FIN SUBIDA HOSTING*/

    let BotonCancelar = $('#cancelar');

    /// desactivar controles
    $('#name_distrito').attr("disabled",true)

 
    /// control de los navs
    $('#tab-paciente a').on('click',function(evento){
      evento.preventDefault();
      /// asignamos nuevo atributo a los botones de guardar y cancelar

      Control = $(this)[0].id;

      $(this).tab("show")
    })

    

    /// mostrar a los pacientes existentes
   loading('#carga_',"#4169E1",'chasingDots')

   setTimeout(() => {
  
   $('#carga_').loadingModal('hide');
   $('#carga_').loadingModal('destroy');
   mostrarPacientes();
   ShowTipoDocumentos('tipo_doc'); /// muestra los tipos de documentos en select 
   ShowTipoDocumentos('tipo_doc_editar'); /// muestra los tipos de documentos en select 

   ShowDepartamentos('departamento'); /// mostrar los departamentos existentes para formulario de pacientes
   ShowDepartamentos('dep_editar'); /// mostrar los departamentos existentes para formulario de pacientes

   ShowDepartamentos('departamento_select') /// mostrar los departamentos existentes para formulario de provincias 

   ShowDepartamentos('departamento_select_dis');

   showProvincias('ANCASH','prov_editar');
   showDistritos_Provincia('CARHUAZ','distrito_editar');
  // showProvincias('ANCASH','provincia');
   //showDistritos_Provincia('CARHUAZ','distrito');
   sedesDisponibles("#sedeeditar");
   sedesDisponibles("#sede");
 
   }, 1000);
  
   /// ocultar mensajes de existencia

   $('#mensaje_error_existe').hide()

 
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

   /// mostramos las provincias al seleccionar un departamento
   $('#departamento').change(function(e){
     showProvincias($(this).val(),'provincia');
     $('#distrito').empty();
   });

   /// mostrar los distritos deacuerdo a la provincia
   $('#provincia').change(function(){
    
    showDistritos_Provincia($(this).val(),'distrito')

   });

   $('#dep_editar').change(function(){
    showProvincias(0,'prov_editar');
    showDistritos_Provincia(0,'distrito_editar');
    showProvincias($(this).val(),'prov_editar');
   });

   $('#prov_editar').change(function(){
    showDistritos_Provincia($(this).val(),'distrito_editar');
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
   
   $('#modal-create-paciente').click(function(){
     if(PROFILE === "Admisión" || PROFILE === "Director"){
       $('#sede').attr("disabled",true);
       $('#sede').val(SEDEDATA)
     }
     openModalPacienteCreate()
   });

   /// al dar click en select tipo documnetos debe de pasar a input dni
   $('#tipo_doc').change(function(){
    $('#documento').focus()
   });

   $('#update_paciente').click(function(evento){
    evento.preventDefault();
    if(DocumentoEditar.val().trim().length == 0)
    {
      DocumentoEditar.focus();
    }
    else
    {
      if(ApellidoEditar.val().trim().length == 0)
      {
        ApellidoEditar.focus();
      }
      else
      {
        if(NombreEditar.val().trim().length == 0)
        {
          NombreEditar.focus();
        }
        else
        {
          modificarDatosDelPaciente(DocumentoEditar,ApellidoEditar,NombreEditar,GeneroEditar,DireccionEditar,FechaNacimientoEditar,TipoDocEditar,DistritoEditar,TelefonoEditar,FacebookEditar,WasapEditar,EstadoCivilEditar,ApoderadoEditar,PERSONA_ID,PACIENTE_ID,$('#sedeeditar'));
        }
      }
    }
   });

   /// crear pacientes(Boton control de guardar dependiendo del tipo de navgación)
    BotonSave.click(function(){
    
      if(Control == 'paciente__')
      {
       if($("#sede").val() == null){
          $('#sede').focus();
         MessageRedirectAjax('warning','¡ADVERTENCIA!','SELECCIONE LA SEDE PARA ESTE PACIENTE!!','modal-crear-paciente');
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
        MessageRedirectAjax('warning','¡ADVERTENCIA!','Seleccione el distrito del paciente','modal-crear-paciente');
        
        }else{
        savePaciente('form-paciente');
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
              if($('#nombre_dep').val().trim().length == 0)
              {
                $('#nombre_dep').focus();
              }
              else
              {
                saveDepartamento('form-departamento')
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

     
   });

  function openModalPacienteCreate()
  {
    FocusInputModal('modal-crear-paciente','documento')
  }

  /*CONSULTAR PERSONA POR DNI DESDE LA API PERU*/
  function BuscarPersonaPorDni(docnum){
    $.ajax({
        url:RUTA+"persona/seach/api-peru",
        method:"POST",
        data:{token_:TOKEN,dni:docnum},
        
        success:function(response){
          response = JSON.parse(response);
           
          if(response.data != undefined){
            loading('#form_paciente_data_window','#4169E1','chasingDots');
            setTimeout(() => {
              $('#form_paciente_data_window').loadingModal('hide');
              $('#form_paciente_data_window').loadingModal('destroy');
              $('#apellidos').val(response.data.apellido_paterno+" "+response.data.apellido_materno);
              $('#nombres').val(response.data.nombres);
              $('#direccion').val(response.data.direccion);
              $('#departamento').focus();
            }, 1000);
          }else{
            loading('#form_paciente_data_window','#4169E1','chasingDots');
            setTimeout(() => {
              $('#form_paciente_data_window').loadingModal('hide');
              $('#form_paciente_data_window').loadingModal('destroy');
              Swal.fire({
              title:"MENSAJE DEL SISTEMA!!",
              text:"NO SE ENCONTRÓ A LA PERSONA CON ESE # DNI",
              icon:"error",
              target:document.getElementById('modal-crear-paciente')
            }).then(function(){
              $('#apellidos').val("");
              $('#nombres').val("");
              $('#direccion').val("");
            });
            $('#documento').val("");
            $('#documento').focus();
            }, 1000);
          }
        }
       }) 
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