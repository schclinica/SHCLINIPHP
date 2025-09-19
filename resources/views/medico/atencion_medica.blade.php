@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Atención médica')

@section('css')
    <style>
        #tabla_pacientes_atm>thead>tr>th {
            background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
            color:aliceblue;
            padding: 18px;
        }

        #tabla_receta>thead>tr>th {
            background-color: #1b5ab1;
            color: aliceblue;
            padding: 18px;
        }

        #div_table_ {
            overflow: scroll;
            height: 240px;
            width: 100%;
            border: 0.1rem solid #4169E1;
        }

        #div_table_ table {
            width: 100%;

        }

        #tabla_pacientes_atendidos>thead>tr>th {
            background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
            color:aliceblue;
            padding: 18px;
        }

        label {
            cursor: pointer;
        }

        input[type=radio] {
            width: 23px;
            height: 23px;
        }

        td.hide_me {
            display: none;
        }

        #lista_historial_clinico_paciente>thead>tr>th {
            background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%);
            color: azure;
            padding: 20px;
        }

        #tabla_lista_evaluacion>thead>tr>th {
            background-color: #4169E1;
            color: azure;
            padding: 20px;
        }
    </style>
@endsection
@section('contenido')
    <div class="col-12" id="car">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs nav-fill" role="tablist" id="tab_atencion_medico">
                @if ($this->profile()->rol === 'Médico')
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#plan_atencion" aria-controls="navs-justified-home" aria-selected="true"
                        style="color: #4169E1" id="plan_atencion_">
                        <i class='bx bxs-donate-heart'></i> Plan de atención médica

                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#pacientes_atendidos" aria-controls="navs-justified-profile" aria-selected="false"
                        style="color:#FF4500" id="pacientes_atendidos_">
                        <i class='bx bxs-user-detail'></i> Pacientes atendidos
                    </button>
                </li>
                @endif

                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#historial_clinico" aria-controls="navs-justified-profile" aria-selected="false"
                        style="color:#20cba9" id="historial_clinico_">
                        <i class='bx bxs-file-archive'></i> Historial Clínico
                    </button>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade @if($this->profile()->rol === 'Médico') show active @endif" id="plan_atencion" role="tabpanel">
                    <div class="card-text p-3">
                        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-5 col-12">
                            <button class="btn_info_tw col-12"
                                id="pacientes_atencion_medica_fresh"><b>Refrescar <i class='bx bx-refresh'></i></b></button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped nowrap" id="tabla_pacientes_atm"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PACIENTE</th>
                                        <th class="text-center">HORA DE LA CITA</th>
                                        <th class="text-center">CONSULTORIO</th>
                                        <th class="text-center">ACCIÓN</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                    <div class="card-text px-3 py-2" style="display: none" id="form_paciente_atencion_medica">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-2">
                                <label for=""><b>#Folio Historial Clínico</b></label>
                                <input type="text" class="form-control" id="num_expediente">
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-6 col-12 mb-2">
                                <label for=""><b>Fecha de la atención</b></label>
                                <input type="date" class="form-control" id="fecha_atencion"
                                min="{{ $this->addRestFecha('Y-m-d', '+ 0 day') }}"
                                value="{{$this->FechaActual("Y-m-d")}}">
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b># Documento</b></label>
                                    <input type="text" class="form-control" id="documento" readonly>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b>Paciente| Cliente</b></label>
                                    <input type="text" class="form-control" id="paciente" readonly>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-12">
                                <div class="form-group">
                                    <label for=""><b>GÉNERO</b></label>
                                    <input type="text" class="form-control" id="paciente_genero" readonly>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-2 col-12">
                                <div class="form-group">
                                    <label for=""><b>Edad del paciente</b></label>
                                    <input type="text" class="form-control" id="edad" readonly>
                                </div>
                            </div>



                            <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b>Consultorio</b></label>
                                    <input type="text" class="form-control" id="consultorio_" readonly>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b>Especialista</b></label>
                                    <input type="text" class="form-control" id="especialista"
                                        value="{{ $this->profile()->apellidos }} {{ $this->profile()->nombres }}" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-12">
                                <div class="form-group">
                                    <label for=""><b>Presión arterial- mm Hg</b></label>
                                    <input type="text" class="form-control" id="pa" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="" class="form-label"><b>Servicio</b></label>
                                    <input type="text" class="form-control" id="servicio_del_pacienteview" readonly>
                                </div>
                            </div>


                            <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b>Temperatura [°C]</b></label>
                                    <input type="text" class="form-control" id="temp" readonly>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b>Frecuencia cardiaca[T/minuto]</b></label>
                                    <input type="text" class="form-control" id="fc" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-12">
                                <div class="form-group">
                                    <label for=""><b>Frecuencia respiratoria[T/minuto]</b></label>
                                    <input type="text" class="form-control" id="frecart" readonly>
                                </div>
                            </div>


                            <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b>Saturación de oxigeno[%] </b></label>
                                    <input type="text" class="form-control" id="so" readonly>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b>Talla [Cm]</b></label>
                                    <input type="text" class="form-control" id="talla" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-12">
                                <div class="form-group">
                                    <label for=""><b>Peso[Kg]</b></label>
                                    <input type="text" class="form-control" id="peso" readonly>
                                </div>
                            </div>

                            <div class="col-xl-5 col-lg-5 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b>IMC</b></label>
                                    <input type="text" class="form-control" id="imc" readonly>
                                </div>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-6 col-12">
                                <div class="form-group">
                                    <label for=""><b>ESTADO IMC</b></label>
                                    <input type="text" class="form-control" id="estadoimc" readonly>
                                </div>
                            </div>
                            {{-- - DATOS DE LA ATENCIÓN MÉDICA-- --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for=""><b>Motivo de la consulta</b></label>
                                    <textarea name="motivo" id="motivo" cols="30" rows="2" class="form-control" placeholder="Escriba aquí el motivo de la consulta....."></textarea>
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-7 col-12">
                                <div class="form-group">
                                    <label for=""><b>Antecedentes <span class="text-danger">(*)</span></b></label>
                                    <textarea name="antecedentes" id="antecedentes" cols="30" rows="2" class="form-control"
                                        placeholder="Describa los antecedentes del paciente..."></textarea>
                                </div>
                            </div>
                            {{-- -modificamos la vista desde este punto para el hosting-- --}}
                            <div class="col-xl-4 col-lg-4 col-md-5 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                                <div class="form-group">
                                    <label for=""><b>Tiempo de la enfermedad <span class="text-danger">(*)</span>
                                        </b></label>
                                    <input type="text" class="form-control" id="tiempo">
                                </div>
                            </div>
                            
                            <div class="col-12 ">
                                <div class="form-group">
                                    <label for="antedcedente_familiares"><b>Antecedentes familiares <span
                                                class="text-danger"> </span> </b></label>
                                    <textarea name="antecedente_familiares" id="antecedente_familiares" cols="30" rows="2"
                                        class="form-control" placeholder="Describa los antecedentes traumáticos..."></textarea>
                                </div>
                            </div>
                           <div class="col-xl-2 col-lg-2 col-md-4 col-12 mt-2" id="divgestante" style="display: none">
                                <div class="form-check form-switch mt-3">
                                    <input class="form-check-input" type="checkbox" id="gestante">
                                    <label class="form-check-label" for="gestante"> ES GESTANTE ?</label>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-12 mt-2" id="edadgestacionaldiv" style="display:none">
                                 <div class="form-floating mb-2">
                                    <input type="text" name="edad_gestacional" id="edad_gestacional" class="form-control" placeholder="EDAD GESTACIONAL..">
                                    <label for="edad_gestacional">EDAD GESTACIONAL</label>
                                 </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-4 col-12 mt-2" id="fechadivparto" style="display:none">
                                 <div class="form-floating mb-2">
                                    <input type="date" name="fecha_parto" id="fecha_parto" class="form-control"
                                    value="{{$this->FechaActual("Y-m-d")}}">
                                    <label for="fecha_parto">FECHA PROBABLE DE PARTO</label>
                                 </div>
                            </div>
                            <div class="card-text mt-3"><b class="text-primary">Antecedentes patológicos</b></div>
                             <div class="col-xl-3 col-lg-3 col-md-3 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                                 <div class="form-group">
                                     <label for="diabetes"><b>Diabetes ? <span class="text-danger"></span> </b></label>
                                     <select name="diabetes" id="diabetes" class="form-select">
                                         <option value="no">no</option>
                                         <option value="si">si</option>
                                     </select>
                                 </div>
                             </div>
                             <div class="col-xl-3 col-lg-3 col-md-3 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                                <div class="form-group">
                                    <label for="hipertension"><b>Hipertensión arterial ? <span class="text-danger"></span> </b></label>
                                    <select name="hipertension" id="hipertension" class="form-select">
                                        <option value="no">no</option>
                                        <option value="si">si</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                                <div class="form-group">
                                    <label for="transfusiones"><b>Transfusiones? <span class="text-danger"></span> </b></label>
                                    <select name="transfusiones" id="transfusiones" class="form-select">
                                        <option value="no">no</option>
                                        <option value="si">si</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                                <div class="form-group">
                                    <label for="cirujias_previas"><b>Cirujías previas <span class="text-danger"></span> </b></label>
                                    <textarea name="cirujias_previas" id="cirujias_previas" cols="30" rows="2"
                                        class="form-control" placeholder="Describa las cirujias previas..."></textarea>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                                <div class="form-group">
                                    <label for="medicamentos_actuales"><b>Medicamentos actuales <span class="text-danger"></span> </b></label>
                                    <textarea name="medicamentos_actuales" id="medicamentos_actuales" cols="30" rows="2"
                                        class="form-control" placeholder="Describa los medicamentos actuales..."></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for=""><b>Alergías del paciente</span></b></label>
                                    <textarea name="alergias" id="alergias" cols="30" rows="3" class="form-control"
                                        placeholder="Describa las alergías del paciente..."></textarea>
                                </div>
                            </div>
 

                            <div class="col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                                <div class="form-group">
                                    <label for="opcional_habito"><b>Otros <span class="text-danger"></span> </b></label>
                                    <textarea name="opcional_habito" id="opcional_habito" cols="30" rows="2" class="form-control"
                                        placeholder="Describir si el paciente tiene otros hábitos (opcional)..."></textarea>
                                </div>
                            </div>
 

                            <div class="col-xl-3 col-lg-3 col-md-3 col-12 mt-xl-5 mt-lg-5 mt-md-5 mt-0">
                                <div class="form-group">
                                    <label for=""><b>Vacunas completos ? <span
                                                class="text-danger">(*)</span></b></label>
                                    <select name="vacunas" id="vacunas" class="form-select">
                                        <option value="se">SIN ESPECIFICAR</option>
                                        <option value="si">Si</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-9 col-12">
                                <div class="form-group">
                                    <label for=""><b>Resultado del exámen físico <span
                                                class="text-danger">(*)</span></b></label>
                                    <textarea name="examen_fisico" id="examen_fisico" cols="30" rows="6" class="form-control"
                                        placeholder="Describa los resultados del exámen físico del paciente..."></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for=""><b>Procedimiento </b></label>
                                    <textarea name="procedimiento" id="procedimiento" cols="30" rows="3" class="form-control"
                                        placeholder="Describa el procedimiento..."></textarea>
                                </div>
                            </div>

                            <div class="col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                                <label for="enfermedad_diagnosticado"><b class="text-primary">Diagnósticos <span class="text-danger"></span> </b></label>
                                <br>
                                <div class="col-12 mt-2">
                                    <div class="form-group">
                                        <label for="diagnostico_general"><b>Diagnóstico general <span
                                                    class="text-danger"> </span> </b></label>
                                        <textarea name="diagnostico_general" id="diagnostico_general" cols="30" rows="2" class="form-control"
                                            placeholder="Describa el diagnóstico general..."></textarea>
                                    </div>
                                </div>
                         
                                <div class="table-responsive"  >
                                    <button class="btn_blue" id="add_diagnostico">Agregar <i class="fas fa-plus"></i></button> 
                                    <div class="table-responsive" id="div_table_">
                                    <table class="table table-bordered table-striped table-hover table-sm" >
                                        <thead>
                                            <tr class="bg-primary">
                                                <th class="p-4 text-white letra text-center">Quitar</th>
                                                <th class="d-none">ID</th>
                                                <th class="p-4 text-white letra">Enfermedad</th>
                                                <th class="p-4 text-white letra">Tipo</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista_diagnostico_paciente"></tbody>
                                    </table>
                                    </div>
                                 </div>
                            </div>
                            

                            <div class="card-text"><b class="text-primary">Tratamiento</b></div>
                            <div class="col-xl-3 col-lg-3 col-12 mt-xl-5 mt-lg-5 mt-md-5 mt-0">
                                <div class="form-group">
                                    <label for=""><b>Tiempo de tratamiento</b></label>
                                    <input type="text" id="tiempo_tratamiento" class="form-control">
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-12 mb-5">
                                <div class="form-group">
                                    <label for=""><b>Describir el tratamiento</b></label>
                                    <textarea name="tratamiento" id="tratamiento" cols="30" rows="6" class="form-control"
                                        placeholder="Describa el tratamiento para el paciente..."></textarea>
                                </div>
                            </div>
                           
                            <div class="col-xl-9 col-lg-9 col-md-6 col-12 mt-4">
                                <div class="form-group">
                                    <b>Indicar la próxima cita </b>
                                    <input type="date" class="form-control" id="fecha_proxima_cita"
                                        value="{{ $this->addRestFecha('Y-m-d', '+ 7 day') }}"
                                        min="{{ $this->addRestFecha('Y-m-d', '+ 7 day') }}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-4">
                                <div class="form-group">
                                    <b>Importe Total a pagar {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </b>
                                    <input type="text" class="form-control" id="importe_pagar_cita_medica">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-4">
                                <div class="form-group  d-none">
                                    <b>Monto Medico{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </b>
                                    <input type="text" class="form-control" readonly id="importe_pagar_monto_medico">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-4">
                                <div class="form-group d-none">
                                    <b>Monto Clinica{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </b>
                                    <input type="text" class="form-control" readonly id="importe_pagar_monto_clinica">
                                </div>
                            </div>

                            <div class="col p-3">
                                <button class="btn btn-rouded btn-success" id="save_paciente_plan_atencion">Guardar <i
                                        class='bx bx-save'></i></button>
                                <button class="btn btn-rounded btn-danger" id="cancel">Cancelar <i
                                        class='bx bx-x'></i></button>
                                      
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pacientes_atendidos" role="tabpanel">
                    <div class="row mt-2">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="diario" class="form-label"><input type="radio" id="diario"
                                        name="opcion" checked><b> Pacientes de hoy</b></label>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="ayer" class="form-label"><input type="radio" id="ayer"
                                        name="opcion"><b> Pacientes de Ayer</b></label>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="semana_pasada" class="form-label"><input type="radio" id="semana_pasada"
                                        name="opcion"><b> Pacientes de esta semana</b></label>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-5 col-12">
                            <div class="form-group">
                                <label for="mes_pasado" class="form-label"><input type="radio" id="mes_pasado"
                                        name="opcion"><b> Pacientes de este mes</b></label>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-5 col-12">
                            <div class="form-group">
                                <label for="fecha_atencion_medica" class="form-label"><input type="radio"
                                        id="fecha_atencion_medica" name="opcion"><b> Fecha personalizado</b></label>
                            </div>
                        </div>

                        <div class="col-12" id="fecha_select" style="display:none">
                            <div class="form-group">
                                <label for=""><b>Seleccione una fecha</b></label>
                                <input type="date" id="fecha_atenciondata" class="form-control"
                                    value="{{ $this->FechaActual('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="table table-responsive">
                        <table class="table table-bordered responsive table-striped table-hover nowrap responsive"
                            id="tabla_pacientes_atendidos" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th># DOCUMENTO</th>
                                    <th>PACIENTE</th>
                                    <th>FECHA</th>
                                    <th>SERVICIO | CONSULTORIO</th>
                                    <th>PRÓXIMA CITA</th>
                                    <th class="d-none">ID ATENCION</th>
                                    <th class="d-none">ID_PACIENTE</th>
                                    <th class="d-none">ID_MEDICO</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                        </table>

                    </div>

                    <div class="row justify-content-end">
                        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-7 col-12">
                            <table class="table table-bordered">
                                <thead style="background-color: #FF4500">
                                    <th colspan="2" class="text-white">Leyenda</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Generar receta</td>
                                        <td><button class="btn rounded btn-primary btn-sm"><i
                                                    class='bx bxs-file-pdf'></i></button></td>
                                    </tr>
                                    <tr>
                                        <td>Ver receta médica</td>
                                        <td><button class="btn rounded btn-warning btn-sm"><i
                                                    class='bx bxs-file-blank'></i></button></td>
                                    </tr>
                                    <tr>
                                        <td>Ver órdern de laboratorio</td>
                                        <td><button class="btn rounded btn-danger btn-sm"> <i
                                                    class='bx bx-file'></i></button></td>
                                    </tr>
                                    <tr>
                                        <td>Crear informe médico</td>
                                        <td> <button class="btn rounded btn-info btn-sm text-white"><i
                                                    class='bx bxs-file-blank'></i> <b></b></button></td>
                                    </tr>

                                    <tr>
                                        <td>Crear órden de laboratorio</td>
                                        <td>
                                            <button class="btn rounded btn-success btn-sm text-white"> <i
                                                    class='bx bx-street-view'></i> <b></b></button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade  @if($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Enfermera-Triaje' ) show active @endif" id="historial_clinico" role="tabpanel">
                    <div class="row">
                        @if ($this->profile()->rol === 'Médico')
                        <div class="col">
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    placeholder="Buscar por # documento del paciente.." id="doc_paciente">
                                <button class="btn btn-outline-primary btn-rounded" id="search_pacientes_medico"><i
                                        class='bx bx-search'></i> Buscar</button>
                            </div>
                            <span class="text-danger mt-1" style="display: none"
                                id="error_doc_paciente_historial">Complete por lo menos 8 dígitos 😁</span>
                        </div>
                        @endif

                        @if ($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Enfermera-Triaje')
                        <div class="col">
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    placeholder="Datos del paciente.." readonly id="pacientedatos">
                                <button class="btn btn-outline-primary btn-rounded" id="seleccionar_paciente"><i
                                        class='bx bx-search'></i> Buscar </button>
                            </div>
                            
                        </div>
                        @endif

                        <div class="col-12">
                            <div class="table table-responsive">
                                <table class="table table-bordered table-striped nowrap responsive"
                                    id="lista_historial_clinico_paciente" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PACIENTE</th>
                                            <th>FECHA DE LA CITA</th>
                                            <th>ESPECIALIDAD</th>
                                            <th>HISTORIAL</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <div class="modal fade" id="detalle_receta" data-bs-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1b5ab1;color:">
                    <h4 class="modal-start text-white">Receta médica</h4>
                    <button type="button" class="btn-close salir_receta" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col" id="alerta_existe_medicamento_receta" style="display: none">
                        <div class="alert alert-danger">
                            <b>Ya existe ese medicamento en la lista</b>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="medicamento">MEDICAMENTO <span class="text-danger">(*)</span></label>
                        <input type="text" id="medicamento" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="dosis">FRECUENCIA <span class="text-danger">(*)</span></label>
                        <textarea name="dosis" id="dosis" cols="30" rows="4" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="duracion_">DURACIÓN<span class="text-danger">(*)</span></label>
                        <input type="text" id="duracion_" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">CANTIDAD <span class="text-danger">(*)</span></label>
                        <input type="number" id="cantidad" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- -MODAL PARA GENERAR EL INFORME MÉDICO DEL PACIENTE ATENDIDO -- --}}

    <div class="modal fade" id="modal_informe_medico" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg bg-primary">
                    <p class="h4 text-white"><span id="text_informe">Generar informe médico</span></p>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="paciente_informe"><b>Paciente</b></label>
                        <input type="text" id="paciente_informe" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label for="doc_paciente_informe"><b>N° - DOCUMENTO </b></label>
                        <input type="text" id="doc_paciente_informe" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label for="detalle_informe"><b>Descripción del informe <span
                                    class="text-danger">(*)</span></b></label>
                        <textarea name="detalle_informe" id="detalle_informe" cols="30" rows="10" class="form-control"
                            placeholder="Escriba aquí......." style="border: #4169E1 solid 1px"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-rounded btn-success" id="save_informe"><b>Generar <i
                                class='bx bx-save'></i></b></button>
                    <button class="btn btn-rounded btn-danger" id="cancel_informe"><b>Cancelar <i
                                class='bx bx-save'></i></b></button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PARA MOSTRAR A LOS PACIENTES QUE YA HAN SIDO ATENDIDOS PARA VER SU HISTORIAL CLINICO-- --}}
    <div class="modal fade" id="modal-pacientes_medico" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%)">
                        <h4 class="text-white">Mis pacientes</h4>
                </div>
                <div class="modal-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered table-striped nowrap responsive" id="lista_pacientes_medico_"
                            style="width: 100%">
                            <thead style="background: linear-gradient(to bottom, #6db3f2 0%,#54a3ee 50%,#3690f0 51%,#1e69de 100%)">
                                <tr>
                                    <th class="py-4 letra text-white"># DOCUMENTO</th>
                                    <th class="py-4 letra text-white">PACIENTE</th>
                                    <th class="py-4 letra text-white">SELECCIONAR</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer border-2">
                    <button class="btn btn-danger" onclick="$('#modal-pacientes_medico').modal('hide')"><i
                            class='bx bx-x'></i> Salir</button>
                </div>
            </div>
        </div>
    </div>


    {{--MODAL PARA MOSTRAR A LOS PACIENTES QUE YA AN SIDO ATENDIDOS Y VER SU HISTORIAL CLINICO--}}

{{-- MODAL PARA MOSTRAR A LOS PACIENTES QUE YA HAN SIDO ATENDIDOS PARA VER SU HISTORIAL CLINICO-- --}}
<div class="modal fade" id="modal-pacientes_historial" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
         <div class="card-header"
                    style="background: linear-gradient(to bottom, #3b679e 0%,#2b88d9 50%,#207cca 51%,#7db9e8 100%)">
                    <h4 class="letra text-white">Pacientes</h4>
         </div>
            <div class="modal-body">
                <div class="table table-responsive">
                    <table class="table table-bordered table-striped table-sm nowrap responsive" id="lista_pacientes_para_historial_"
                        style="width: 100%">
                        <thead style="background: linear-gradient(to bottom, #3b679e 0%,#2b88d9 50%,#207cca 51%,#7db9e8 100%)">
                            <tr>
                                <th class="p-4 text-white">TIPO DOCUMENTO</th>
                                <th class="p-4 text-white"># DOCUMENTO</th>
                                <th class="p-4 text-white">PACIENTE</th>
                                <th class="p-4 text-white">GÉNERO</th>
                                <th class="p-4 text-white">SELECCIONAR</th>
                            </tr>
                        </thead>
 
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-danger" onclick="$('#modal-pacientes_historial').modal('hide')"><i
                        class='bx bx-x'></i> Salir</button>
            </div>
        </div>
    </div>
</div>
    {{-- FIN ----}}

{{-- - MODAL PARA EL REGISTRO DE LA EVALUACIÓN PRE-OPERATORIA- --}}
    <div class="modal fade" id="modal_evaluacion_pre_operatoria">
        <div class="modal-dialog modal-xl modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(to bottom, rgba(255,183,107,1) 0%,rgba(255,167,61,1) 50%,rgba(255,124,0,1) 51%,rgba(255,127,4,1) 100%);">
                    <h5 class="text-white">Órden Médico</h5>
                    <div class="float-end">
                        <img src="{{$this->asset('img/icons/unicons/laboratorio.ico')}}" class="menu-icon" alt="">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-12">
                            <div class="form-group">
                                <label for="serieorden"><b>Orden N°</b></label>
                                <input type="text" id="serieorden" class="form-control">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-12">
                            <div class="form-group">
                                <label for="paciente_lab_update"><b>Paciente</b></label>
                                <input type="text" id="paciente_lab_update" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-12">
                            <div class="form-group">
                                <label for="fecha_atencion_lab_update"><b>Fecha de atención</b></label>
                                <input type="text" id="fecha_atencion_lab_update" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <button class="btn_blue mb-2" id="agregar_nueva_orden">Agregar nueva órden</button>
                                <table class="table table-bordered table-striped table-hover table-sm">
                                    <thead style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                                        <tr>
                                            <th class="text-white px-2 py-3 letra">Cantidad</th>
                                            <th class="text-white px-2 py-3 letra">Código</th>
                                            <th class="text-white px-2 py-3 letra">Descripción Orden</th>
                                            <th class="text-white px-2 py-3 letra">Tipo</th>
                                            <th class="text-white px-2 py-3 letra">Categoría</th>
                                            <th class="text-white px-2 py-3 letra">Precio {{ count($this->BusinesData()) ==
                                                1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                            <th class="text-white px-2 py-3 letra">Importe {{ count($this->BusinesData()) ==
                                                1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                            <th class="text-white px-1 py-3 letra">Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista_orders_paciente"></tbody>
                                </table>
                            </div>
                        </div>
    
                    </div>
                </div>
    
                <div class="modal-footer border-2">
                    <button class="btn btn-outline-success rounded" id="save_evaluacion"><b>Guardar orden médica <i
                                class='bx bx-save'></i></b></button>
                    <button class="btn btn-danger" id="cerrar_orden_lab">Salir X</button>
                </div>
            </div>
        </div>
    </div>
    
    {{-- modal para ver los examenes para asignar la orden medica al paciente----}}
    {{---modal para visualizar las enfermedades----}}
    <div class="modal fade" id="modal_view_examenes" data-bs-backdrop="static">
        <div class="modal-dialog modal-fullscreen modal-dialog-center modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                    <h4 class="text-white">Lista de exámenes</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-1">
                                <label for=""><b>SELECCIONE TIPO</b></label>
                                <select name="tipo_examen_reporte" id="tipo_examen_reporte" class="form-select"></select>
                            </div>
                        </div>
                       
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm nowrap responsive" id="lista_examenes_orden"
                            style="width: 100%">
                            <thead style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                                <tr>
                                    <th  class="d-none">ID</th>
                                    <th class="px-1 py-3 text-white letra">CÓDIGO</th>
                                    <th class="px-1 py-3 text-white letra">EXÁMEN</th>
                                    <th class="px-1 py-3 text-white letra text-center">TIPO</th>
                                    <th class="px-1 py-3 text-white letra text-center">CATEGORIA</th>
                                    <th class="px-1 py-3 text-white letra">PRECIO {{ count($this->BusinesData()) == 1 ?
                                        $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                                    <th class="p-3 text-white letra">SELECCIONAR</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
    
                <div class="modal-footer border-2">
                    <button class="btn btn-danger" id="salir_ventana_examenes">Salir X</button>
                </div>
            </div>
        </div>
    </div>
    
    {{---MODAL PARA CREAR NUEVA ENFERMEDAD----}}
    <div class="modal fade" id="modal_create_enfermedad">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header badge bg-primary">
                    <h5 class="text-white">Registrar enfermedad</h5>
                    <div class="float-end">
                        <img src="{{$this->asset('img/icons/unicons/laboratorio.ico')}}" class="menu-icon" alt="">
                    </div>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="form_save_enfermedad">
                        <input type="hidden" name="token_" value="{{$this->Csrf_Token()}}">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="enfermedad"><b>Nombre de la enfermedad</b></label>
                                    <input type="text" name="enfermedad" id="enfermedad" class="form-control">
                                    <span class="text-danger" id="text_error_enfermedad"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="descripcion"><b>Descripción</b></label>
                                    <textarea name="descripcion" id="descripcion" cols="30" rows="5" class="form-control"
                                        placeholder="Escriba aquí..."></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
    
                </div>
    
                <div class="modal-footer">
                    <button class="btn btn-outline-success rounded" id="save_enfermedad"><b>Guardar <i
                                class='bx bx-save'></i></b></button>
                </div>
            </div>
        </div>
    </div>
    {{---modal para visualizar las enfermedades----}}
    <div class="modal fade" id="modal_view_enfermedades" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-center modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                    <h4 class="text-white letra">Enfermedades</h4>
                </div>
                <div class="modal-body">
                        <table class="table table-bordered table-striped table-sm table-hover nowrap responsive" id="lista_enfermedades"
                            style="width: 100%">
                            <thead>
                                <tr style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                                    <th class="p-3 text-white letra">CÓDIGO</th>
                                    <th class="p-3 text-white letra">ENFERMEDAD</th>
                                    <th class="p-3 text-white letra">SELECCIONAR</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                <div class="modal-footer border-2">
                    <button class="btn btn-danger" onclick="$('#modal_view_enfermedades').modal('hide')">Salir X</button>
                </div>
            </div>
        </div>
    </div>

 {{--- MODAL PARA GENERAR LA RECETA MEDICA DEL PACIENTE---}}
 <div class="modal fade" id="modal_receta_medica">
    <div class="modal-dialog modal-xl modal-fullscreen modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                <h5 class="text-white">Generar receta médica</h5>
                <div class="float-end">
                    <img src="{{$this->asset('img/icons/unicons/receta.ico')}}" class="menu-icon" alt="">
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-12">
                        <div class="form-group">
                            <label for="seriereceta"><b>RECETA N° <span class="text-danger">*</span></b></label>
                            <input type="text" id="seriereceta" class="form-control" placeholder="00000001">
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-7 col-12">
                        <div class="form-group">
                            <label for="medicamento_receta"><b>Medicamento <span class="text-danger">*</span></b></label>
                            <div class="input-group">
                                <input type="text" id="medicamento_receta" class="form-control" placeholder="Medicamento....">
                                <button class="btn btn-primary" id="buscar_producto_receta"> <b>Buscar</b> <i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-12">
                        <div class="form-group">
                            <label for="fecha_vencimiento"><b>Fecha vencimiento <span class="text-danger">*</span></b></label>
                                <input type="date" id="fecha_vencimiento" class="form-control"
                                value="{{$this->addRestFecha("Y-m-d","+7 day")}}" min="{{$this->FechaActual("Y-m-d")}}">
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-7 col-12">
                        <div class="form-group">
                            <label for="indicaciones_receta"><b>Indicaciones <span class="text-danger">*</span></b></label>
                            <input type="text" id="indicaciones_receta" class="form-control" placeholder="Escriba las indicaciones aquí">
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-5 col-12">
                        <div class="form-group">
                            <label for="cantidad_receta"><b>Cantidad a recetar <span class="text-danger">*</span></b></label>
                            <input type="number" id="cantidad_receta" class="form-control" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                         <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <thead style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                                    <tr>
                                        <th class="d-none">ID</th>
                                        <th  class="text-white px-2 py-4  text-center h2">Cantidad</th>
                                        <th class="text-white px-2 py-4  h2">Descripción</th>
                                        <th class="text-white px-2 py-4  h2">Indicaciones</th>
                                        <th class="text-white px-1 py-4  text-center h2">Quitar</th>
                                    </tr>
                                </thead>
                                <tbody id="lista_receta_medica_paciente"></tbody>
                            </table>
                         </div>
                    </div>
                     
                </div>
            </div>

            <div class="modal-footer border-2">
                <button class="btn btn-outline-success rounded" id="save_receta_medica"><b>Guardar receta médica<i
               class='bx bx-save'></i></b></button>
               <button class="btn btn-danger" id="cerrar_receta_medica">Salir X</button>
            </div>
        </div>
    </div>
</div>

  {{--- MODAL PARA MOSTRAR LOS MEDICAMENTOS EXISTENTES----}}
  <div class="modal fade" id="modal_search_producto_receta">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="card-header px-4" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
                <h4 class="text-white">Buscar producto</h4>
            </div>
            <div class="modal-body">
                <div class="card-text px-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm table-hover nowrap responsive" id="tabla_productos_search"
                        style="width: 100%">
                           <thead style="background: linear-gradient(to bottom, rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%);">
                            <tr>
                                <th class="py-3 text-dark letra">#</th>
                                <th class="py-3 text-dark letra">Tipo</th>
                                <th class="py-3 text-dark letra">Producto</th>
                                <th class="py-3 text-dark letra">Recetar</th>
                            </tr>
                           </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

 {{-- MOTIVO DE LA CONSULTA----}}
 <div class="modal fade" id="modal_editar_atencion_medica">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="letra"><b class="text-white">Editar Atención médica</b></h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-5 col-12">
                        <div class="form-group">
                            <b># FOLIO(HISTORIAL CLINICO)</b>
                            <input type="text" class="form-control" id="expediente_editar" name="expediente_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-7 col-12">
                        <div class="form-group">
                            <b>FECHA DE LA ATENCIÓN</b>
                            <input type="date" class="form-control" id="fecha_atencion_editar" name="fecha_atencion_editar">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-5 col-12">
                        <div class="form-group">
                            <b># DOCUMENTO</b>
                            <input type="text" class="form-control" id="num_doc_editar" name="num_doc_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-5 col-lg-5 col-md-7 col-12">
                        <div class="form-group">
                            <b>PACIENTE</b>
                            <input type="text" class="form-control" id="paciente_editar" name="paciente_editar" readonly>
                        </div>
                    </div>


                    <div class="col-xl-6 col-lg-6 col-md-7 col-12">
                        <div class="form-group">
                            <b>FECHA NACIMIENTO</b>
                            <input type="date" class="form-control" id="fecha_nac_editar" name="fecha_nac_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-5 col-12">
                        <div class="form-group">
                            <b>Edad</b>
                            <input type="text" class="form-control" id="edad_editar" name="edad_editar" readonly >
                        </div>
                    </div>


                    <div class="col-xl-3 col-lg-3 col-md-5 col-12">
                        <div class="form-group">
                            <b>CONSULTORIO</b>
                            <input type="text" class="form-control" id="consultorio_editar" name="consultorio_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-7 col-12">
                        <div class="form-group">
                            <b>MÉDICO</b>
                            <input type="text" class="form-control" id="medico_editar" name="medico_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-12">
                        <div class="form-group">
                            <b>SERVICIO</b>
                            <input type="text" class="form-control" id="servicio_editar" name="servicio_editar" readonly>
                        </div>
                    </div>


                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="form-group">
                            <b>TEMPERATURA</b>
                            <input type="text" class="form-control" id="temperatura_editar" name="temperatura_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="form-group">
                            <b>FRECUENCIA CARDIACA[T/minuto]</b>
                            <input type="text" class="form-control" id="frecuencia_cardiaca_editar" name="frecuencia_cardiaca_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-12">
                        <div class="form-group">
                            <b>FRECUENCIA RESPIRATORIA[T/minuto]</b>
                            <input type="text" class="form-control" id="frecuencia_respiratoria_editar" name="frecuencia_respiratoria_editar" readonly>
                        </div>
                    </div>


                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="form-group">
                            <b>SATURACION DE OXIGENO</b>
                            <input type="text" class="form-control" id="saturacion_oxigeno_editar" name="saturacion_oxigeno_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="form-group">
                            <b>TALLA [Mt]</b>
                            <input type="text" class="form-control" id="talla_editar" name="talla_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-12">
                        <div class="form-group">
                            <b>PESO [Kg]</b>
                            <input type="text" class="form-control" id="peso_editar" name="peso_editar" readonly>
                        </div>
                    </div>


                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="form-group">
                            <b>IMC</b>
                            <input type="text" class="form-control" id="imc_editar" name="imc_editar" readonly>
                        </div>
                    </div>

                    <div class="col-xl-8 col-lg-8 col-md-6 col-12">
                        <div class="form-group">
                            <b>ESTADO IMC</b>
                            <input type="text" class="form-control" id="estado_imc_editar" name="estado_imc_editar" readonly>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <b>MOTIVO DE LA CONSULTA</b>
                            <textarea name="motivo_consulta_editar" id="motivo_consulta_editar" cols="30" rows="3"
                            placeholder="Escriba aquí el motivo de la consulta..." class="form-control"></textarea>
                        </div>
                    </div>
                    
                    <div class="col-xl-2 col-lg-2 col-md-4 col-12 mt-2" id="divgestanteeditar" style="display: none">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="gestanteeditar">
                            <label class="form-check-label" for="gestanteeditar"> ES GESTANTE ?</label>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12 mt-2" id="edadgestacionaldiveditar" style="display:none">
                        <div class="form-floating mb-2">
                            <input type="text" name="edad_gestacionaleditar" id="edad_gestacionaleditar" class="form-control"
                                placeholder="EDAD GESTACIONAL..">
                            <label for="edad_gestacional">EDAD GESTACIONAL</label>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-4 col-12 mt-2" id="fechadivpartoeditar" style="display:none">
                        <div class="form-floating mb-2">
                            <input type="date" name="fecha_partoeditar" id="fecha_partoeditar" class="form-control" value="{{$this->FechaActual("
                                Y-m-d")}}">
                            <label for="fecha_parto">FECHA PROBABLE DE PARTO</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for=""><b>Antecedentes <span class="text-danger">(*)</span></b></label>
                            <textarea name="antecedentes_editar" id="antecedentes_editar" cols="30" rows="2" class="form-control"
                                placeholder="Describa los antecedentes del paciente..."></textarea>
                        </div>
                    </div>
                    {{-- -modificamos la vista desde este punto para el hosting-- --}}
                    <div class="col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                        <div class="form-group">
                            <label for=""><b>Tiempo de la enfermedad <span class="text-danger">(*)</span>
                                </b></label>
                            <input type="text" class="form-control" id="tiempo_editar">
                        </div>
                    </div>
                    
                    <div class="col-12 ">
                        <div class="form-group">
                            <label for="antedcedente_familiares"><b>Antecedentes familiares <span
                                        class="text-danger"> </span> </b></label>
                            <textarea name="antecedente_familiares_editar" id="antecedente_familiares_editar" cols="30" rows="2"
                                class="form-control" placeholder="Describa los antecedentes familiares..."></textarea>
                        </div>
                    </div>
                    <div class="card-text mt-3"><b class="text-primary">Antecedentes patológicos</b></div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                         <div class="form-group">
                             <label for="diabetes_editar"><b>Diabetes ? <span class="text-danger"></span> </b></label>
                             <select name="diabetes_editar" id="diabetes_editar" class="form-select">
                                 <option value="no">no</option>
                                 <option value="si">si</option>
                             </select>
                         </div>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                        <div class="form-group">
                            <label for="hipertension_editar"><b>Hipertensión arterial ? <span class="text-danger"></span> </b></label>
                            <select name="hipertension_editar" id="hipertension_editar" class="form-select">
                                <option value="no">no</option>
                                <option value="si">si</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                        <div class="form-group">
                            <label for="transfusiones_editar"><b>Transfusiones? <span class="text-danger"></span> </b></label>
                            <select name="transfusiones_editar" id="transfusiones_editar" class="form-select">
                                <option value="no">no</option>
                                <option value="si">si</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                        <div class="form-group">
                            <label for="cirujias_previas_editar"><b>Cirujías previas <span class="text-danger"></span> </b></label>
                            <textarea name="cirujias_previas_editar" id="cirujias_previas_editar" cols="30" rows="2"
                                class="form-control" placeholder="Describa las cirujias previas..."></textarea>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                        <div class="form-group">
                            <label for="medicamentos_actuales_editar"><b>Medicamentos actuales <span class="text-danger"></span> </b></label>
                            <textarea name="medicamentos_actuales_editar" id="medicamentos_actuales_editar" cols="30" rows="2"
                                class="form-control" placeholder="Describa los medicamentos actuales..."></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for=""><b>Alergías del paciente</span></b></label>
                            <textarea name="alergias_editar" id="alergias_editar" cols="30" rows="3" class="form-control"
                                placeholder="Describa las alergías del paciente..."></textarea>
                        </div>
                    </div>


                    <div class="col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                        <div class="form-group">
                            <label for="opcional_habito_editar"><b>Otros <span class="text-danger"></span> </b></label>
                            <textarea name="opcional_habito_editar" id="opcional_habito_editar" cols="30" rows="2" class="form-control"
                                placeholder="Describir si el paciente tiene otros hábitos (opcional)..."></textarea>
                        </div>
                    </div>


                    <div class="col-xl-3 col-lg-3 col-md-3 col-12 mt-xl-5 mt-lg-5 mt-md-5 mt-0">
                        <div class="form-group">
                            <label for=""><b>Vacunas completos ? <span
                                        class="text-danger">(*)</span></b></label>
                            <select name="vacunas_editar" id="vacunas_editar" class="form-select">
                                <option value="se">SIN ESPECIFICAR</option>
                                <option value="si">Si</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-12">
                        <div class="form-group">
                            <label for=""><b>Resultado del exámen físico <span
                                        class="text-danger">(*)</span></b></label>
                            <textarea name="examen_fisico_editar" id="examen_fisico_editar" cols="30" rows="6" class="form-control"
                                placeholder="Describa los resultados del exámen físico del paciente..."></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for=""><b>Procedimiento </b></label>
                            <textarea name="procedimiento_editar" id="procedimiento_editar" cols="30" rows="3" class="form-control"
                                placeholder="Describa el procedimiento..."></textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-xl-3 mt-lg-3 mt-md-3 mt-0">
                        <label for="enfermedad_diagnosticado_editar"><b class="text-primary">Diagnósticos <span class="text-danger"></span> </b></label>
                        <br>
                        <div class="col-12 mt-2">
                            <div class="form-group">
                                <label for="diagnostico_general"><b>Diagnóstico general <span
                                            class="text-danger"> </span> </b></label>
                                <textarea name="diagnostico_general_editar" id="diagnostico_general_editar" cols="30" rows="2" class="form-control"
                                    placeholder="Describa el diagnóstico general..."></textarea>
                            </div>
                            <div class="table-responsive"  >
                                <button class="btn_blue" id="add_diagnostico_editar">Agregar <i class="fas fa-plus"></i></button> 
                                <div class="table-responsive" id="div_table_">
                                <table class="table table-bordered table-striped table-hover table-sm" >
                                    <thead>
                                        <tr class="bg-primary">
                                            <th class="p-4 text-white letra text-center">Quitar</th>
                                            <th class="d-none">ID</th>
                                            <th class="p-4 text-white letra">Enfermedad</th>
                                            <th class="p-4 text-white letra">Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista_diagnostico_paciente_editar"></tbody>
                                </table>
                                </div>
                             </div>
                        </div>
                    <!--- FIN--->
                </div>

                <div class="card-text"><b class="text-primary">Tratamiento</b></div>
                <div class="col-xl-3 col-lg-3 col-12 mt-xl-5 mt-lg-5 mt-md-5 mt-0">
                    <div class="form-group">
                        <label for=""><b>Tiempo de tratamiento</b></label>
                        <input type="text" id="tiempo_tratamiento_editar" class="form-control">
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-12 mb-5">
                    <div class="form-group">
                        <label for=""><b>Describir el tratamiento</b></label>
                        <textarea name="tratamiento_editar" id="tratamiento_editar" cols="30" rows="6" class="form-control"
                            placeholder="Describa el tratamiento para el paciente..."></textarea>
                    </div>
                </div>
               
                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-4">
                    <div class="form-group">
                        <b>Indicar la próxima cita </b>
                        <input type="date" class="form-control" id="fecha_proxima_cita_editar"
                            
                            min="{{ $this->addRestFecha('Y-m-d', '+ 7 day') }}">
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-4">
                    <div class="form-group">
                        <b>Importe Total a pagar {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </b>
                        <input type="text" class="form-control" id="importe_pagar_cita_medica_editar">
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-4">
                    <div class="form-group">
                        <b>Monto Medico{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </b>
                        <input type="text" class="form-control" readonly id="importe_pagar_monto_medico_editar">
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mt-4">
                    <div class="form-group">
                        <b>Monto Clinica{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} </b>
                        <input type="text" class="form-control" readonly id="importe_pagar_monto_clinica_editar">
                    </div>
                </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success rounded" id="update_atencion_medica"><b>Guardar cambios <i class="fas fa-save"></i></b></button>
                <button class="btn btn-primary rounded" id="show_historial_medico"><b>Ver Historial <i class="fas fa-file-pdf"></i></b></button>
                <button class="btn btn-danger rounded" id="cerrar_atencion_medica"><b>Salir X</b></button>
            </div>
        </div>
    </div>
 </div>

 {{-- modal para editar los diagnosticos asignando nuevas enfermedades---}}
 <div class="modal fade" id="modal_view_enfermedades_editar" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-center modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#4169E1">
                <h4 class="text-white">Enfermedades</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive" id="lista_enfermedades_editar"
                        style="width: 100%">
                        <thead>
                            <tr style="background-color: #F0FFF0">
                                <th class="p-3 text-blue letra">CÓDIGO</th>
                                <th class="p-3 text-blue letra">ENFERMEDAD</th>
                                <th class="p-3 text-blue letra">SELECCIONAR</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-danger" onclick="$('#modal_view_enfermedades_editar').modal('hide')">Salir X</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script src="{{URL_BASE}}public/js/recetamedica.js"></script>
    <script src="{{ URL_BASE }}public/js/editaratencion.js"></script>
    <script>
        var RUTA = "{{ URL_BASE }}" // la url base del sistema
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var USER = "{{ $this->profile()->id_usuario }}";
        var ALGORITMO = "{{ env('ALGORITMO') }}";
        var KEY_ENCRIPT = "{{ env('CLAVE_ENCRYPT') }}";
        var CITA_ID = null;
        var HORARIO_ID = null;
        var MONTOPORCENTAJEMEDICOEDITAR;
        var MONTOCLINICAPORCENTAJEEDITAR;
        var ATENCIONEDITARID;
        var TRIAJE_ID = null;
        var PACIENTE_ATENDIDO_ID;
        var INFORME_ID = null;
        var ATENCION_ID_OPERATORIA;
        var Control_Save_Informe = 'save';
        var tabla_receta = document.getElementById('lista_receta');
        var TablaConsultarProductos;
        var TablaPacientesAtencionMedica;
        var TablaPacientesAtendidos;
        var TablaEnfermedades;
        var TablaHistorialClinico;
        var PacientesDelMedico;
        var TablaListaEvaluacion;
        var PROFILE_ = "{{$this->profile()->rol}}";
        var MONTOPAGARMEDICO;
        var MONTOPAGARCLINICA;
        var TablaPacientesHistorial;
        var TablaListaExamenesOrden;
        var ProductoFarmaciaId;
        var ProductoIdLista;
        var ATENCIONID_;
        var MEDICOIDDATA_;
        var TIPOREPO = 1;
        var PACIENTEIDDATA_;
        var LISTA_DE_PACIENTES;
        var TablaEnfermedadesEdition_;
        var PacienteIdSeleccionado;
        var EdadGestacional,FechaDeParto;
        var Gestante,IndicationGestante = 'no';
        var FECHAACTUALS = "{{$this->FechaActual('Y-m-d')}}"
        $(document).ready(function() {
            /*DATOS PARA LA ATENCION MÉDICA*/
            let Antecedente = $('#antecedentes');
            let TiempoEnfermedad = $('#tiempo');
            let Alergias = $('#alergias');
            let Intervenciones = $('#intervenciones');
            let Vacunas = $('#vacunas');
            let ExamenFisico = $('#examen_fisico');
            let Diagnostico = $('#diagnostico_general');
            let AnalisisConfirm = $('#requiere_analisis');
            let Analisis = $('#analisis');
            let TiempoTratamiento = $('#tiempo_tratamiento');
            let MontoAPagar = $('#importe_pagar_cita_medica');
            let MontoMedicoTotal = $('#importe_pagar_monto_medico');
            let MontoClinicaTotal = $('#importe_pagar_monto_clinica');
            let Tratamiento = $('#tratamiento');
            let MotivoConsulta = $('#motivo');
            let LaProximaCitaPaciente = $('#fecha_proxima_cita');
            let DetalleInforme = $('#detalle_informe');
            let PacienteInforme = $('#paciente_informe');
            let NumeroExpediente = $('#num_expediente');
            let Procedimiento = $('#procedimiento');

            /// variables a modificar en el hosting
            let Ant_medicos_ = $('#antecedente_medico');
            let Ant_trauma_ = $('#antecedente_traumas');
            let Ant_gineco_obs_ = $('#antecedente_gin_obs');
            let Ant_fam_ = $('#antecedente_familiares');
            let Diabetes = $('#diabetes');
            let Hipertension = $('#hipertension');
            let Transfusiones = $('#transfusiones');
            let CirujiasPrevias = $('#cirujias_previas');
            let MedicamentosActuales = $('#medicamentos_actuales')

            let Otros_ = $('#opcional_habito');
            /*** DATOS PARA LA EVALUACION**/
            let Ev_Indicaciones = $('#indicaciones');
            let Ev_ant_importantes = $('#ant_importantes');
            let Ev_molestias_importantes = $('#molestias_importantes');
            let Ev_Pa = $('#pa');
            let Ev_fcc = $('#fcc');
            let Ev_Fr = $('#fr');
            let Ev_To = $('#to');
            let Ev_sato_dos = $('#sato_dos');
            let Ev_Peso = $('#peso');
            let Ev_exa_fisico = $('#exa_fisico');
            let Ev_resultados_est = $('#resultados_est');
            let Ev_goldman = $('#goldman');
            let Ev_Asa = $('#asa');
            let Fecha_de_la_atencion = $('#fecha_atencion');
            let EnfermedadDiagnosticado = $('#enfermedad_diagnosticado');
            let Ev_Sugerencias = $('#sugerencias');

            if(PROFILE_ === 'Médico'){
                GenerarFolioHistorialClinico();
                showPacientesEnAtencionMedica();
        
                atencionMedica(TablaPacientesAtencionMedica, '#tabla_pacientes_atm tbody');
                showDiagnosticosAsignadosDelPaciente();

                showEnfermedadesParaDiagnostico();
                AsignarDiagnostico(TablaEnfermedades,'#lista_enfermedades tbody');
 
                showExamenesOrden(0);
                AsignarExamenPaciente(TablaListaExamenesOrden,'#lista_examenes_orden tbody');

                showOrdersAsignadosDelPaciente();



                showEnfermedadesParaDiagnosticoEdition();
                AsignarDiagnosticoEdition(TablaEnfermedadesEdition_,'#lista_enfermedades_editar tbody');
                
            }else{
                MostrarHistorialPaciente("XXXXXX")
            }
            /// control de los navs
            $('#tab_atencion_medico button').on('click', function(evento) {
                evento.preventDefault();

                /// asignamos nuevo atributo a los botones de guardar y cancelar

                Control = $(this)[0].id;

                if (Control === 'pacientes_atendidos_') {
                    pacientesAtendidos(1, "2023-08-20");
                    $('#diario').prop("checked", true);
                    GenerarInforme(TablaPacientesAtendidos, '#tabla_pacientes_atendidos tbody');
                    GenerarOrdenLaboratorio(TablaPacientesAtendidos, '#tabla_pacientes_atendidos tbody');
                    OpenModalEvaluacionPreOperatoria(TablaPacientesAtendidos,
                        '#tabla_pacientes_atendidos tbody')
                        EditarAtencionMedica(TablaPacientesAtendidos,
                        '#tabla_pacientes_atendidos tbody');

                    GenerateRecetaMedicaPaciente(TablaPacientesAtendidos,
                    '#tabla_pacientes_atendidos tbody')
                } else {
                    if (Control === 'historial_clinico_') {
                        $('#doc_paciente').val("");
                        $('#doc_paciente').attr('disabled', false);
                        $('#doc_paciente').focus();
                        MostrarHistorialPaciente("xxxxxxxx");
                    }
                }
                GenerarFolioHistorialClinico();
                $(this).tab("show");
                MostrarMedicosEspecialidades_()
            });

            $('#cancel').click(function() {
                $('#form_paciente_atencion_medica').hide(700);
                $('#lista_receta tr').empty();
            });

            $('#cerrar_orden_lab').click(function(){
                $('#modal_evaluacion_pre_operatoria').modal('hide');
                pacientesAtendidos(TIPOREPO, "2023-08-20");
            });

            $('#salir_ventana_examenes').click(function(){
                $('#modal_view_examenes').modal('hide');
                showExamenesOrden(0);
                ShowCategoryDisponiblesTipoParaOrdenMedica(0);
                $('#tipo_examen_reporte').val(null);
            });

            $('#cerrar_receta_medica').click(function(){
                $('#modal_receta_medica').modal('hide');
                $('#indicaciones_receta').val("");
                $('#medicamento_receta').val("");
                $('#cantidad_receta').val("");
                ProductoFarmaciaId = null;
                $('#fecha_vencimiento').val(`{{$this->addRestFecha("Y-m-d","+7 day")}}`);
                pacientesAtendidos(TIPOREPO, "2023-08-20");
                //pacientesAtendidos(1, "2023-08-20");
            })

            /// agregar nueva orden
            $('#agregar_nueva_orden').click(function(){
                $('#modal_view_examenes').modal("show");
                showTipoDiagnosticosReporte();
               showExamenesOrden(null);
                
            });

            $('#tipo_examen_reporte').change(function(){
                //ShowCategoryDisponiblesTipoParaOrdenMedica($(this).val());
                showExamenesOrden($(this).val());
            });

           /// verificar si es gentante
           $('#gestante').change(function(){
             let check = $(this).is(":checked");

             if(check){
                $('#edadgestacionaldiv').show(200);
                $('#fechadivparto').show(200);
                Gestante = true; 
             }else{
                $('#edadgestacionaldiv').hide(200);
                $('#fechadivparto').hide(200);
                Gestante = false;
             }
           })

           $('#gestanteeditar').change(function(){
             let check = $(this).is(":checked");

             if(check){
                $('#edadgestacionaldiveditar').show(200);
                $('#fechadivpartoeditar').show(200);
             }else{
                $('#edadgestacionaldiveditar').hide(200);
                $('#fechadivpartoeditar').hide(200);
             }
           })

            $('#lista_orders_paciente').on('keypress','#precio_examen_medico',function(evento){

                if(evento.which == 13){

                    if($(this).val().trim().length == 0){
                        $(this).focus();
                    }else{
                        if($(this).val() <= 0){
                            Swal.fire({
                                title:"MENSAJE DEL SISTEMA!!!",
                                text:"PARA CONTINUAR, DEBES DE INGRESAR UN PRECIO > 0",
                                icon:"error",
                                target:document.getElementById("modal_evaluacion_pre_operatoria")
                            });
                        }else{
                            let Fila = $(this).closest("tr");
                     
                     let PrecioActualModify = $(this).val();
 
                     let CodigoData = Fila.find("td").eq(1).text();
 
                     let FormModificarPrecioOrde = new FormData();
                     FormModificarPrecioOrde.append("token_",TOKEN);
                     FormModificarPrecioOrde.append("codeorden",CodigoData);
                     FormModificarPrecioOrde.append("new_precio",PrecioActualModify);
 
                      axios({
                         url:RUTA+"modificar-precio-de-la-orden-asignada-paciente",
                         method:"POST",
                         data:FormModificarPrecioOrde
                      }).then(function(response){
                         if(response.data.error != undefined){
                             Swal.fire({
                                 title:"MENSAJE DEL SISTEMA!!",
                                 text:response.data.error,
                                 icon:"error",
                                 target:document.getElementById("modal_evaluacion_pre_operatoria")
                             });
                         }else{
                             Swal.fire({
                                 title:"MENSAJE DEL SISTEMA!!",
                                 text:response.data.response,
                                 icon:"success",
                                 target:document.getElementById("modal_evaluacion_pre_operatoria")
                             }).then(function(){
                                 showOrdersAsignadosDelPaciente();
                             });
                         }
                        });
                        }
                    }
                }
            });

            /// GUARDAR LA RECETA MEDICA
            $('#save_receta_medica').click(function(){
                 
                if($('#seriereceta').val().trim().length == 0){
                    $('#seriereceta').focus();
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!",
                        text:"INGRESE EL # DE LA RECETA!!",
                        icon:"error",
                        target:document.getElementById('modal_receta_medica')
                    });
                }else{
                    if($('#lista_receta_medica_paciente tr').length > 0){
                        saveRecetaElecronica();
                    }else{
                        Swal.fire({
                            title:"MENSAJE DEL SISTEMA!!!",
                            text:"INGRESE POR LO MENOS UN MEDICAMENTO EN EL DETALLE DE LA RECETA PARA PODER CONTINUAR!!",
                            icon:"error",
                            target:document.getElementById('modal_receta_medica')
                        })
                    }
                }
            });

            /// CREAR LA ENFERMEDAD
            $('#create_enfermedad').click(function(){
                $('#form_save_enfermedad')[0].reset();
                $('#text_error_enfermedad').text("");
                $('#modal_create_enfermedad').modal("show")
            });

            /// MOSTRAR LAS ENFERMEDADES PARA EL DIAGNOSTICO MEDICO
            $('#add_diagnostico').click(function(){
                $('#modal_view_enfermedades').modal("show");
            });

          
            /// registrar la enfermedad
            $('#save_enfermedad').click(function(){
             saveEnfermedad();
            });

            $('#lista_diagnostico_paciente').on('change','#tipo',function(){
            
             let fila = $(this).closest('tr');

             let EnfermedaId = fila.find('td').eq(1).text();
             
             let TipoChange = $(this).val();
             changeTipoDiagnosticoPaciente(EnfermedaId,TipoChange);
             
            });     
            /// consultar el historial clínico del paciente
            $('#doc_paciente').keypress(function(evento) {
                if (evento.which === 13) {
                    if ($(this).val().trim().length >= 8) {
                        $('#error_doc_paciente_historial').hide();
                        $(this).removeClass("is-invalid");
                        $(this).addClass('is-valid');

                        MostrarHistorialPaciente($(this).val());
                    } else {
                        $(this).removeClass('is-valid');
                        $(this).addClass('is-invalid');
                        $('#error_doc_paciente_historial').show(270);
                    }
                }
            });

            $('#seleccionar_paciente').click(function(){
                $('#modal-pacientes_historial').modal("show");
                /// MOSTRAR PACIENTES PARA VER SUS HISTORIAS CLINICAS
                ShowPacientesHistoriasClinicas();
                SelectPacienteTwo(LISTA_DE_PACIENTES,'#lista_pacientes_para_historial_ tbody');
            });

            /// cancelar informe médico
            $('#cancel_informe').click(function() {

                DetalleInforme.val("")
                $('#modal_informe_medico').modal("hide");

                Control_Save_Informe = 'save';
            });

            /// mostramos el modal de pacientes del médico
            $('#search_pacientes_medico').click(function() {
                $('#modal-pacientes_medico').modal('show')
                ShowPacientes_();
                SelectPaciente(PacientesDelMedico, '#lista_pacientes_medico_ tbody')
            });

            /// Guardar el informe médico del paciente

            $('#save_informe').click(function() {
                if (DetalleInforme.val().trim().length == 0) {
                    DetalleInforme.focus();
                    Swal.fire({
                        title: 'Mensaje del sistema!',
                        text: 'Ingrese la descripción del informe',
                        icon: 'error',
                        target: document.getElementById('modal_informe_medico')
                    });
                } else {
                    if (Control_Save_Informe === 'save') {
                        $.ajax({
                            url: RUTA + "informe_medico/" + PACIENTE_ATENDIDO_ID + "/save",
                            method: "POST",
                            data: {
                                token_: TOKEN,
                                detalle_informe: DetalleInforme.val()
                            },
                            success: function(response) {
                                response = JSON.parse(response);

                                if (response.response === 'ok') {
                                    Swal.fire({
                                        title: 'Mensaje del sistema!',
                                        text: 'Informe médico del paciente ' +
                                            PacienteInforme +
                                            ' se a registrado correctamente',
                                        icon: 'success',
                                        target: document.getElementById(
                                            'modal_informe_medico')
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Mensaje del sistema!',
                                        text: 'Error al registrar el informe médico del paciente ' +
                                            PacienteInforme,
                                        icon: 'success',
                                        target: document.getElementById(
                                            'modal_informe_medico')
                                    });
                                }
                            }
                        })
                    } else {
                        actualizarInforme(INFORME_ID);
                    }
                }


            });

            $('#save_evaluacion').click(function() {
                
                if($('#lista_orders_paciente tr').length>0){
                    updateOrdenLaboratorio_(ATENCION_ID_OPERATORIA);
                }else{
                    Swal.fire({
                        title:"MENSAJE DEL SISTEMA!!",
                        text:"NO TIENES NADA AGREGADO A LA LISTA PARA GENERAR LA ÓRDEN MÉDICA!!!",
                        icon:"error",
                        target:document.getElementById('modal_evaluacion_pre_operatoria')
                    })
                }
                
            });

            $('#nueva_receta').click(function() {

                $('#medicamento').focus();
                $('#detalle_receta').modal('show');
            });



            $('.salir_receta').click(function() {
                $('#medicamento').val("");
                $('#dosis').val("");
                $('#duracion_').val("");
                $('#cantidad').val("");
            });

            $('#diagnostico_search').click(function() {

            });

            /// reporte de atencion médica
            $('#diario').click(function() {
                TIPOREPO = 1;
                $('#fecha_select').hide(600);
                pacientesAtendidos(1, "2023-08-20");
                GenerarInforme(TablaPacientesAtendidos, '#tabla_pacientes_atendidos tbody');
            });

            /// pacienets en atención médica (REFRESH)
            $('#pacientes_atencion_medica_fresh').click(function() {
                showPacientesEnAtencionMedica();
            });

            $('#ayer').click(function() {
                TIPOREPO = 2;
                $('#fecha_select').hide(600);
                pacientesAtendidos(2, "2023-08-20");
                GenerarInforme(TablaPacientesAtendidos, '#tabla_pacientes_atendidos tbody');
            })

            $('#semana_pasada').click(function() {
                TIPOREPO = 3;
                $('#fecha_select').hide(600);
                pacientesAtendidos(3, "2023-08-20");
                GenerarInforme(TablaPacientesAtendidos, '#tabla_pacientes_atendidos tbody');
            })

            $('#mes_pasado').click(function() {
                TIPOREPO = 4;
                $('#fecha_select').hide(600);
                pacientesAtendidos(4, "2023-08-20");
                GenerarInforme(TablaPacientesAtendidos, '#tabla_pacientes_atendidos tbody');
            })

            $('#fecha_atencion_medica').click(function() {
                TIPOREPO=5;
                $('#fecha_select').show(600);
                pacientesAtendidos(5, $('#fecha_atencion').val());
                GenerarInforme(TablaPacientesAtendidos, '#tabla_pacientes_atendidos tbody');
            })

            $('#fecha_atenciondata').change(function() { 
                pacientesAtendidos(5, $(this).val());
            })

            AnalisisConfirm.change(function() {
                if ($(this).val().trim() === 'si') {
                    Analisis.removeAttr('readonly');
                    Analisis.focus();
                } else {
                    Analisis.val("");
                    Analisis.attr('readonly', 'readonly');
                }
            });

            $('#save_paciente_plan_atencion').click(function() {
                
                if(Gestante){
                    EdadGestacional =$('#edad_gestacional');
                    FechaDeParto = $('#fecha_parto');
                    IndicationGestante = "si";
                }else{
                   EdadGestacional =null;
                   FechaDeParto = null;
                   IndicationGestante = "no";
                }
                if (Antecedente.val().trim().length == 0) {
                    Antecedente.focus();
                } else {
                   if (ExamenFisico.val().trim().length == 0) {
                            ExamenFisico.focus();
                    } else {
                            saveAtencionMedicaPaciente(
                                NumeroExpediente,Antecedente,TiempoEnfermedad, Alergias, Vacunas,Procedimiento,
                                ExamenFisico, Diagnostico,Tratamiento,TiempoTratamiento,
                                TRIAJE_ID, CITA_ID, HORARIO_ID, MotivoConsulta, LaProximaCitaPaciente,
                                Ant_fam_,Diabetes,Hipertension,Transfusiones,CirujiasPrevias,
                                MedicamentosActuales,Otros_,Fecha_de_la_atencion,MontoAPagar,MontoMedicoTotal,MontoClinicaTotal,
                                IndicationGestante,EdadGestacional,FechaDeParto
                            );
                    }
                }
            });

            // pasar enter
            enter('medicamento', 'dosis');
            enter('duracion_', 'cantidad');

            $('#cantidad').keypress(function(evento) {
                if (evento.which == 13) {
                    evento.preventDefault();

                    if ($(this).val().trim().length == 0) {
                        $(this).focus();
                    } else {
                        if (!existeReceta($('#medicamento').val())) {
                            let tr = '';

                            tr += `
                        <tr>
                            <td>` + $('#medicamento').val() + `</td>
                            <td>` + $('#dosis').val() + `</td>
                            <td>` + $('#duracion_').val() + `</td>
                            <td>` + $('#cantidad').val() + `</td>
                            <td><button class="btn btn-rounded btn-outline-danger btn-sm" id="quitar"><i class='bx bx-x'></i></button></td>
                        </tr>
                        `;

                            $('#lista_receta').append(tr);
                            $('#medicamento').val("");
                            $('#dosis').val("");
                            $('#duracion_').val("");
                            $('#cantidad').val("");

                            $('#medicamento').focus();
                            $('#alerta_existe_medicamento_receta').hide(200);
                        } else {
                            $('#alerta_existe_medicamento_receta').show(600);
                            $('#medicamento').select();
                        }
                    }
                }
            });

            $('#lista_receta').on('click', '#quitar', function() {

                let fila = $(this).closest('tr');

                // quitamos de la lista

                fila.remove();
            });

            $('#importe_pagar_cita_medica').keyup(function(){
                 let MontoTotalPagar = $(this).val();

                 let MontoMedico_ = MontoTotalPagar * (MONTOPAGARMEDICO/100);
                 let MontoClinica_ = MontoTotalPagar * (MONTOPAGARCLINICA/100);

                 $('#importe_pagar_monto_medico').val(parseFloat(MontoMedico_).toFixed(2));
                 $('#importe_pagar_monto_clinica').val(parseFloat(MontoClinica_).toFixed(2));
            });
        });

        function existeReceta(data) {
            let bandera = false;

            for (let i = 0; i < tabla_receta.rows.length; i++) {
                if (tabla_receta.rows[i].cells[0].innerHTML === data) {
                    bandera = true;
                }
            }

            return bandera;
        }

        /// guardar atenciónn medica del paciente que sacó la cita

        /*
        self::post("expediente"),self::post("antecedente"),self::post("tiempo_enfermedad"),
      self::post("alergias"), self::post("vacuna"),self::post("procedimiento"),self::post("examen_fisico"),
      self::post("diagnostico_general"),self::post("plan_tratamiento"),self::post("desc_tratamiento"), self::post("triaje"),
      self::post("proxima_cita"),self::post("ant_fam"),self::post("otros"),self::post("fechaatencion"),
      self::post("diabetes"),self::post("hiper_arterial"),self::post("transfusiones"),self::post("cirujias"),
      self::post("medicamentos_actuales")
        */
        function saveAtencionMedicaPaciente(
            NumExpediente,antecedente, tiempo_enfermedad, alergias, vacuna,procedimiento_,
            examen_fisico, diagnostico_general_, desc_tratamiento,tiempo_tratamiento_,
            triaje_, citamedica, horario, observacion_, Proxima_cita_paciente,
            AnteFamiliar,diabetes_,hiper_arterial_,transfusiones_,cirujias_,
            medicamentos_actuales_,otros_,fechaAtencion,montPago,montomedico,montoclinica,
            gestantedata,edad_gestacionaldata,fecha_partodata
        ) {
            $.ajax({
                url: RUTA + "save_atencion_medica_paciente",
                method: "POST",
                async: false,
                data: {
                    token_: TOKEN,
                    expediente:NumExpediente.val(),
                    antecedente: antecedente.val(),
                    tiempo_enfermedad: tiempo_enfermedad.val(),
                    alergias: alergias.val(),
                    vacuna: vacuna.val(),
                    procedimiento:procedimiento_.val(),
                    examen_fisico: examen_fisico.val(),
                    diagnostico_general: diagnostico_general_.val(),
                    desc_tratamiento: desc_tratamiento.val(),
                    tiempo_tratamiento:tiempo_tratamiento_.val(),
                    triaje: triaje_,
                    proxima_cita: Proxima_cita_paciente.val(),
                    ant_fam:AnteFamiliar.val(),
                    otros: otros_.val(),
                    fechaatencion:fechaAtencion.val(),
                    diabetes:diabetes_.val(),
                    hiper_arterial:hiper_arterial_.val(),
                    transfusiones:transfusiones_.val(),
                    cirujias:cirujias_.val(),
                    medicamentos_actuales:medicamentos_actuales_.val(),
                    cita_medica: citamedica,
                    obs: observacion_.val(),
                    horario_id: horario,
                    monto_pago:montPago.val(),
                    monto_medico:montomedico.val(),
                    monto_clinica:montoclinica.val(),
                    gestante:gestantedata,
                    edad_gestacional:IndicationGestante === 'si' ? edad_gestacionaldata.val() : null,
                    fecha_parto:IndicationGestante === 'si' ? fecha_partodata.val() : null
                },
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response === 'ok') {
                        Swal.fire({
                            title: "Mensaje del sistema!",
                            text: "La atención médica del paciente " + $('#paciente').val() +
                                " se ha registrado correctamente",
                            icon: "success"
                        }).then(function() {
                            /// receteamos todo el formulario
                            showPacientesEnAtencionMedica();
                            GenerarFolioHistorialClinico();
                            showDiagnosticosAsignadosDelPaciente();
                            antecedente.val("");
                            tiempo_enfermedad.val("");
                            alergias.val("");
                            procedimiento_.val("");
                            vacuna.val("se");
                            examen_fisico.val("");
                            diagnostico_general_.val("");
                            if(IndicationGestante === 'si'){
                              $('#gestante').prop("checked",false);
                                edad_gestacionaldata.val("");
                                AnteFamiliar.val("");
                                fecha_partodata.val("{{$this->FechaActual('Y-m-d')}}");
                                $('#edadgestacionaldiv').hide(200);
                                $('#fechadivparto').hide(200);
                                Gestante = false;
                            }
                            diabetes_.val("no");
                            hiper_arterial_.val("no");
                            transfusiones_.val("no");
                            cirujias_.val("");
                            medicamentos_actuales_.val("");
                            desc_tratamiento.val("");
                            otros_.val("");
                            montPago.val("");
                            AnteFamiliar.val("");
                            observacion_.val("");
                            tiempo_tratamiento_.val("");
                            $('#form_paciente_atencion_medica').hide(500);
                            TRIAJE_ID = null;
                            CITA_ID = null;
                            HORARIO_ID = null;

                        });
                    }
                }
            });
        }



        /// guardar la receta del paciente
        function saveRecetaMedicaPaciente() {
            $('#lista_receta tr').each(function() {

                let medicamento = $(this).find('td').eq(0).text();
                let Dosis = $(this).find('td').eq(1).text();
                let TiempoToma = $(this).find('td').eq(2).text();
                let Cantidad_ = $(this).find('td').eq(3).text();

                $.ajax({
                    url: RUTA + "save_receta_paciente",
                    method: "POST",
                    data: {
                        token_: TOKEN,
                        triaje_id: TRIAJE_ID,
                        medic: medicamento,
                        dosis: Dosis,
                        tiempo_dosis: TiempoToma,
                        cantidad: Cantidad_
                    },
                    success: function(response) {}
                });

            });
        }
        /// mostrar pacientes que pasan a la atención médica
        function showPacientesEnAtencionMedica() {
            TablaPacientesAtencionMedica = $('#tabla_pacientes_atm').DataTable({
                retrieve: true,
                responsive: true,
                language: SpanishDataTable(),
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "order": [
                    [2, 'asc']
                ], /// enumera indice de las columnas de Datatable
                ajax: {
                    url: RUTA + "pacientes/cola/atencion_medica?token_=" + TOKEN + "&&medico=" + USER,
                    method: "GET",
                    dataSrc: "response"
                },
                columns: [{
                        "data": "paciente_atencion"
                    },
                    {
                        "data": "paciente_atencion",render:function(pacienteatencion){
                            return pacienteatencion.toUpperCase();
                        }
                    },
                    {
                        "data": "hora_de_la_cita",
                        render: function(hora) {
                            return '<span class="badge bg-success text-primary">' + hora + '</span>';
                        },className:"text-center"
                    },
                    {
                        "data": "nombre_esp",
                        render: function(especialida) {
                            return '<b>' + especialida.toUpperCase() + '</b>';
                        },className:"text-center"
                    },
                    {
                        "data": null,
                        render: function() {
                            return '<button class="btn btn-rounded btn-outline-primary btn-sm" id="atencion"><i class="bx bxs-user-check"></i></button>';
                        },className:"text-center"
                    },
                ]

            }).ajax.reload();
            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaPacientesAtencionMedica.on('order.dt search.dt', function() {
                TablaPacientesAtencionMedica.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }
        /// mostrar pacientes atendidos
        function pacientesAtendidos(opcion, fecha) {
            TablaPacientesAtendidos = $('#tabla_pacientes_atendidos').DataTable({
                bDestroy: true,
                responsive: true,
                processing: true,
                language: SpanishDataTable(),
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "order": [
                    [1, 'asc']
                ], /// enumera indice de las columnas de Datatable
                ajax: {
                    url: RUTA + "atencion_medica/pacientes_atendidos/" + opcion + "/" + fecha + "?token_=" + TOKEN,
                    method: "GET",
                    dataSrc: "response",
                },
                columns: [{
                        "data": "documento"
                    },
                    {
                        "data": "documento"
                    },
                    {
                        "data": "paciente_atencion",render:function(pac){
                            return pac.toUpperCase()
                        }
                    },
                    {
                        "data": "fecha_de_la_cita"
                    },
                    {
                        "data": "nombre_esp",render:function(esp){
                            return esp.toUpperCase();
                        }
                    },
                    {
                        "data": "prox_cita"
                    },
                    {
                        "data": "id_atencion_medica"
                    },
                    {"data":"id_paciente"},
                    {"data":"id_medico"},
                    {
                        "data": null,
                  render: function(dta) {

                    if(dta.id_receta_electro != null && dta.id_orden_medico != null){
                            
                            return `<div class="dropdown">
                               <button class="btn btn-outline-primary dropdown-toggle" type="button" id="opciones_atencion" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                   <i class='bx bx-menu'></i>
                               </button>
                               <ul class="dropdown-menu" aria-labelledby="opciones_atencion">
                                    <li><a class="dropdown-item" href="javascript:;"   id="editar_atencion"><b class="text-dark"><i class='bx bx-edit-alt' ></i> Editar<b/></a></li>        
                                   <li><a class="dropdown-item" href='` + RUTA + `receta_medica?v=` + dta.id_atencion_medica + `' target='blank_' id="receta_pdf"><b class="text-warning"> <i
                                           class='bx bxs-file-blank'></i> Ver receta médica<b/></a></li>
                                   <li><a class="dropdown-item" href="javascript:;"  id="laboratorio"><b class="text-danger"><i
                                           class='bx bx-street-view'></i> Ver órden médico<b/></a></li>
                                   <li><a class="dropdown-item" href="javascript:;"   id="informe_medico"><b class="text-info"><i class='bx bxs-file-blank'></i> Crear informe médico<b/></a></li>        
                               </ul>
                           </div>`;
                        }

                    if(dta.id_orden_medico != null){
                        return `<div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="opciones_atencion" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class='bx bx-menu'></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="opciones_atencion">
                                 <li><a class="dropdown-item" href="javascript:;"   id="editar_atencion"><b class="text-dark"><i class='bx bx-edit-alt' ></i> Editar<b/></a></li>        
                                <li><a class="dropdown-item" href="javascript:;" id="realizar_receta"><b class="text-primary"><i class='bx bxs-file-pdf'></i> Generar receta<b></b></a></li>
                                <li><a class="dropdown-item" href='` + RUTA + `receta_medica?v=` + dta.id_atencion_medica + `' target='blank_' id="receta_pdf"><b class="text-warning"> <i
                                        class='bx bxs-file-blank'></i> Ver receta médica<b/></a></li>
                                <li><a class="dropdown-item" href="javascript:;"  id="laboratorio"><b class="text-danger"><i
                                        class='bx bx-street-view'></i> Ver órden médico<b/></a></li>      
                                <li><a class="dropdown-item" href="javascript:;"   id="informe_medico"><b class="text-info"><i class='bx bxs-file-blank'></i> Crear informe médico<b/></a></li>        
                            </ul>
                        </div>`;
                         }

                         if(dta.id_receta_electro != null){
                            return `<div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="opciones_atencion" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class='bx bx-menu'></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="opciones_atencion">
                                 <li><a class="dropdown-item" href="javascript:;"   id="editar_atencion"><b class="text-dark"><i class='bx bx-edit-alt' ></i> Editar<b/></a></li>        
                                <li><a class="dropdown-item" href='` + RUTA + `receta_medica?v=` + dta.id_atencion_medica + `' target='blank_' id="receta_pdf"><b class="text-warning"> <i
                                        class='bx bxs-file-blank'></i> Ver receta médica<b/></a></li>
                                <li><a class="dropdown-item" href="javascript:;"  id="laboratorio"><b class="text-danger"><i
                                        class='bx bx-street-view'></i> Ver órden médico<b/></a></li>
                                <li><a class="dropdown-item" href="javascript:;"   id="ev_pre_operatoria"><b class="text-success"><i
                                        class='bx bx-street-view'></i> Crear órden médico<b/></a></li>        
                                <li><a class="dropdown-item" href="javascript:;"   id="informe_medico"><b class="text-info"><i class='bx bxs-file-blank'></i> Crear informe médico<b/></a></li>        
                            </ul>
                        </div>`;
                         }
                         return `<div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="opciones_atencion" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class='bx bx-menu'></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="opciones_atencion">
                                 <li><a class="dropdown-item" href="javascript:;"   id="editar_atencion"><b class="text-dark"><i class='bx bx-edit-alt' ></i> Editar<b/></a></li>        
                                <li><a class="dropdown-item" href="javascript:;" id="realizar_receta"><b class="text-primary"><i class='bx bxs-file-pdf'></i> Generar receta<b></b></a></li>
                                <li><a class="dropdown-item" href='` + RUTA + `receta_medica?v=` + dta.id_atencion_medica + `' target='blank_' id="receta_pdf"><b class="text-warning"> <i
                                        class='bx bxs-file-blank'></i> Ver receta médica<b/></a></li>
                                <li><a class="dropdown-item" href="javascript:;"  id="laboratorio"><b class="text-danger"><i
                                        class='bx bx-street-view'></i> Ver órden médico<b/></a></li>
                                <li><a class="dropdown-item" href="javascript:;"   id="ev_pre_operatoria"><b class="text-success"><i
                                        class='bx bx-street-view'></i> Crear órden médico<b/></a></li>        
                                <li><a class="dropdown-item" href="javascript:;"   id="informe_medico"><b class="text-info"><i class='bx bxs-file-blank'></i> Crear informe médico<b/></a></li>        
                            </ul>
                        </div>`;
                        
                        }
                    }
                ],
                columnDefs: [{
                    "sClass": "hide_me",
                    target: [6,7,8]
                }],
            });

            /*=========================== ENUMERAR REGISTROS EN DATATABLE =========================*/
            TablaPacientesAtendidos.on('order.dt search.dt', function() {
                TablaPacientesAtendidos.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }

        function atencionMedica(Tabla, Tbody) {
            $(Tbody).on('click', '#atencion', function() {

                /// abrimos el aformulario de atención médica
                BajadaScroll('.modal-body,html', 400);
                $('#form_paciente_atencion_medica').show(800);

                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Data = Tabla.row(fila).data();

                if(Data.genero === '1'){$('#divgestante').hide()}else{$('#divgestante').show();}

                let GeneroPaciente = Data.genero === '1' ? 'MASCULINO' : 'FEMENINO';
                TRIAJE_ID = Data.id_triaje;

                $('#documento').val(Data.documento);
                $('#paciente').val(Data.paciente_atencion);
                $('#importe_pagar_cita_medica').val(Data.monto_pago);

                MONTOPAGARMEDICO = Data.precio_medico;
                MONTOPAGARCLINICA = Data.precio_clinica;
                let MontoMedico = Data.monto_pago*(MONTOPAGARMEDICO/100);
                let MontoClinica = Data.monto_pago*(MONTOPAGARCLINICA/100);
                
                $('#importe_pagar_monto_medico').val(MontoMedico);

                $('#importe_pagar_monto_clinica').val(MontoClinica);

                /// obtenemos la fecha de nacimiento del paciente 1996-11-06
                let FechaNacimiento = Data.fecha_nacimiento;                 

                if (FechaNacimiento != null) {
                    const fechaNacimiento = FechaNacimiento;
                    const fechaActual = new Date().toISOString().split('T')[0];
                    
                    const edad = calcularEdad(fechaNacimiento, fechaActual);
                    $('#edad').val(`${edad.años} años, ${edad.meses} meses y ${edad.días} días`);
                } else {
                    $('#edad').val('Fecha nacimiento no específicado');
                    $('#edad').css({
                        'background-color': 'red',
                        'color': 'white'
                    });
                }

                $('#motivo').val(Data.observacion);
                $('#consultorio_').val(Data.nombre_esp);
                $('#pa').val(Data.presion_arterial == null ? '------------------' : Data.presion_arterial);
                $('#temp').val(Data.temperatura == null ? '------------------' : Data.temperatura);
                $('#fc').val(Data.frecuencia_cardiaca == null ? '------------------' : Data.frecuencia_cardiaca);
                $('#frecart').val(Data.frecuencia_respiratoria == null ? '------------------' : Data
                    .frecuencia_respiratoria);
                $('#so').val(Data.saturacion_oxigeno == null ? '------------------' : Data.saturacion_oxigeno);
                $('#talla').val(Data.talla == null ? '------------------' : Data.talla);
                $('#peso').val(Data.peso == null ? '------------------' : Data.peso);
                $('#imc').val(Data.imc == null ? '------------------' : Data.imc);
                $('#estadoimc').val(Data.estado_imc == null ? '------------------' : Data.estado_imc);
                $('#servicio_del_pacienteview').val(Data.name_servicio != null ? Data.name_servicio : 'Consulta General');
                $('#paciente_genero').val(GeneroPaciente);
                $('#motivo').focus();

                CITA_ID = Data.id_cita_medica;
                HORARIO_ID = Data.id_horario;
            });
        }

        /// generar informe médico del paciente
        function GenerarInforme(Tabla, Tbody) {
            $(Tbody).on('click', '#informe_medico', function() {
                /// fila seleccionada
                let Fila = $(this).parents("tr");

                if (Fila.hasClass("child")) {
                    Fila = Fila.prev();
                }

                let Paciente_Seleccionado = Fila.find('td').eq(2).text();
                let DocumentoPaciente = Fila.find('td').eq(1).text();
                PACIENTE_ATENDIDO_ID = Fila.find('td').eq(6).text();
                $('#paciente_informe').val(Paciente_Seleccionado.toUpperCase());
                $('#doc_paciente_informe').val(DocumentoPaciente);
                if (existeInforme(PACIENTE_ATENDIDO_ID).length > 0) {
                    $('#text_informe').text("Editar informe médico");
                    loading('body', '#4169E1', 'chasingDots')
                    setTimeout(() => {
                        $('#modal_informe_medico').modal("show");
                        Control_Save_Informe = 'update';
                        INFORME_ID = existeInforme(PACIENTE_ATENDIDO_ID)[0].id_informe;
                        $('#detalle_informe').val(existeInforme(PACIENTE_ATENDIDO_ID)[0]
                            .descripcion_medica);
                        $('body').loadingModal('hide');
                        $('body').loadingModal('destroy');
                    }, 1000);
                    return;
                }
                $('#text_informe').text("Generar informe médico")
                $('#modal_informe_medico').modal("show");
                Control_Save_Informe = 'save';
            });
        }
        /// generar datos de la órden del laboratorio en pdf
        function GenerarOrdenLaboratorio(Tabla, Tbody) {
            $(Tbody).on('click', '#laboratorio', function() {
                /// fila seleccionada
                let Fila = $(this).parents("tr");

                if (Fila.hasClass("child")) {
                    Fila = Fila.prev();
                }
                let DataAtencionId = Fila.find('td').eq(6).text();
                /// CONTINUAR LA ORDEN DEL LABORATORIO EN PDF AQUÍ
                window.open("{{ $this->route('paciente/orden_medica/') }}" + DataAtencionId, 'blank_')
            });
        }

        // Modal Para registrar la evaluación pre operatoria de un paciente que a sido atendido
        function OpenModalEvaluacionPreOperatoria(Tabla, Tbody) {

            $(Tbody).on('click', '#ev_pre_operatoria', function() {
                /// fila seleccionada
                
                let Fila = $(this).parents("tr");

                if (Fila.hasClass("child")) {
                    Fila = Fila.prev();
                }
                let PacienteEvaluacion = Fila.find('td').eq(2).text();
                let FechaEvaluacion = Fila.find('td').eq(3).text();

                ATENCION_ID_OPERATORIA = Fila.find('td').eq(6).text();
                PacienteIdSeleccionado = Fila.find("td").eq(7).text();
                
 
               // $('#modal_evaluacion_pre_operatoria').modal("show");
               $('#paciente_lab_update').val(PacienteEvaluacion);
               $('#fecha_atencion_lab_update').val(FechaEvaluacion);
               editarOrdenLaboratorio(ATENCION_ID_OPERATORIA);
            });
        }


        /// verificamos la existencia del informe
        function existeInforme(id) {
            let respuesta = show(RUTA + "informe_medico/" + id + "/verificar_existencia?token_=" + TOKEN);

            return respuesta;

        }

        function actualizarInforme(id) {
            $.ajax({
                url: RUTA + "informe_medico/" + id + "/update",
                method: "POST",
                data: {
                    token_: TOKEN,
                    detalle_informe: $('#detalle_informe').val()
                },
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response === 'ok') {
                        Swal.fire({
                            title: 'Mensaje del sistema!',
                            text: 'Informe médico del paciente ' + $('#paciente_informe').val() +
                                ' se a modificado correctamente',
                            icon: 'success',
                            target: document.getElementById('modal_informe_medico')
                        });
                    } else {
                        Swal.fire({
                            title: 'Mensaje del sistema!',
                            text: 'Error al modificar el informe médico del paciente ' + $(
                                '#paciente_informe').val(),
                            icon: 'success',
                            target: document.getElementById('modal_informe_medico')
                        });
                    }
                }
            });
        }

        function MostrarHistorialPaciente(documento_paciente) {
            TablaHistorialClinico = $('#lista_historial_clinico_paciente').DataTable({
                bDestroy: true,
                language: SpanishDataTable(),
                responsive: true,
                ajax: {
                    url: RUTA + "ver-historial-del-paciente/" + documento_paciente + "?token_=" + TOKEN,
                    method: "GET",
                    dataSrc: "historial",
                },
                columns: [{
                        "data": "id_cita_medica"
                    },
                    {
                        "data": "paciente_"
                    },
                    {
                        "data": "fecha_cita",
                        render: function(fecha) { /// 2023-10-12

                            let Anio = fecha.substr(0, 4);
                            let Mes = fecha.substr(5, 2);
                            let Dia = fecha.substr(8, 2);
                            let fecha_ = Dia + "/" + Mes + "/" + Anio;
                            return fecha_;
                        }
                    },
                    {
                        "data": "nombre_esp",
                        render: function(esp) {
                            return "<b class='badge bg-info'>" + esp + "</b>";
                        }
                    },
                    {
                        "data": "id_atencion_medica",
                        render: function(data) {


                            return "<a href='paciente/historial?v=" + data +
                                "' target='_blank' class='btn btn-danger btn-sm' id='historial_pac'><i class='bx bxs-receipt'></i></a>";
                        }
                    }
                ]
            });
        }

        function  ShowPacientesHistoriasClinicas(){
            LISTA_DE_PACIENTES = $('#lista_pacientes_para_historial_').DataTable({
                responsive: true,
                retrieve: true,
                processing: true,
                language: SpanishDataTable(),
                ajax: {
                    url: RUTA + "ver-pacientes_del_medico?token_=" + TOKEN,
                    method: "GET",
                    dataSrc: "personas",
                },
                columns: [
                    {
                        "data": "name_tipo_doc"
                    },
                    {
                        "data": "documento"
                    },
                    {
                        "data": "paciente_",render:function(pac){
                            return pac.toUpperCase();
                        }
                    },
                    {
                        "data": "genero",render:function(genero){
                            return genero === "1" ? 'MASCULINO' : 'FEMENINO';
                        }
                    },
                    {
                        "data": "paciente_",
                        render: function() {
                            return "<button class='btn btn-info btn-sm' id='select_paciente_his'> <i class='bx bx-right-arrow-alt'></i></button>"
                        },className:"text-center"
                    }
                ]
            }).ajax.reload();
        }
        /// MOSTRAMOS LOS PACIENTES DEL MÉDICO

        function ShowPacientes_() {
            PacientesDelMedico = $('#lista_pacientes_medico_').DataTable({
                responsive: true,
                retrieve: true,
                processing: true,
                language: SpanishDataTable(),
                ajax: {
                    url: RUTA + "ver-pacientes_del_medico?token_=" + TOKEN,
                    method: "GET",
                    dataSrc: "personas",
                },
                columns: [{
                        "data": "documento"
                    },
                    {
                        "data": "paciente_"
                    },
                    {
                        "data": "paciente_",
                        render: function() {
                            return "<button class='btn btn-info btn-sm' id='select_paciente'> <i class='bx bx-right-arrow-alt'></i></button>"
                        }
                    }
                ]
            }).ajax.reload();
        }

        /// seleccionar al paciente y ver su historial clinica
        function SelectPaciente(Tabla, Tbody) {
            $(Tbody).on('click', '#select_paciente', function() {
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Datos = Tabla.row(fila).data();

                let Paciente = Datos.paciente_;
                let DocumentoPaciente = Datos.documento;

                MostrarHistorialPaciente(DocumentoPaciente);
                $('#doc_paciente').attr('disabled', true);
                $('#doc_paciente').val(DocumentoPaciente + " - " + Paciente);
                $('#modal-pacientes_medico').modal('hide');
            });
        }

        /// seleccionar al paciente y ver su historial clinica
        function SelectPacienteTwo(Tabla, Tbody) {
            $(Tbody).on('click', '#select_paciente_his', function() {
                 
                let fila = $(this).parents('tr');

                if (fila.hasClass('child')) {
                    fila = fila.prev();
                }

                let Datos = Tabla.row(fila).data();

                let Paciente = Datos.paciente_;
                let DocumentoPaciente = Datos.documento;

                MostrarHistorialPaciente(DocumentoPaciente);
                $('#doc_paciente').attr('disabled', true);
                $('#pacientedatos').val(DocumentoPaciente + " - " + Paciente);
                $('#modal-pacientes_historial').modal('hide');
            });
        }
 
     /// editar los datos de la órden de laboratorio
     function editarOrdenLaboratorio(id)
     {
        $.ajax({
            url:RUTA+"orden/laboratorio/editar/"+id,
            method:"GET",
            data:{
                token_:TOKEN
            },
            success:function(response){
                response = JSON.parse(response);
               

                if(response.response != 'token-invalidate' && response.response !== 'no-authorized')
                {

                    $('#orden_lab_update').val(response.response.desc_analisis_requerida);
                    
                    $('#modal_evaluacion_pre_operatoria').modal("show");
                    showOrdersAsignadosDelPaciente();
                    GenerarSerieCorrelativoOrdenMedico();
                    $('#orden_lab_update').focus();
                }else{
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Error al editar la órden de laboratorio,posiblemente por un token de seguridad o quizas no está authorizado para realizar esta acción!",
                        icon:"error"
                    })
                }
            }
        })
     }  
     
     /// modificar los datos de la órden de laboratorio
     function updateOrdenLaboratorio_(id)
     {
 
        $.ajax({
            url:RUTA+"orden/laboratorio/update/"+id,
            method:"POST",
            data:{
                token_:TOKEN,
                serieorden:$('#serieorden').val(),
                paciente:PacienteIdSeleccionado
            },
            success:function(response)
            {
                response = JSON.parse(response);

                if(response.response === 'ok')
                {$('#modal_evaluacion_pre_operatoria').modal("hide");
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"La órden médica a sido guardado! 😁",
                        icon:"success",
                        
                    }).then(function(){
                        GenerarSerieCorrelativoOrdenMedico();
                        showOrdersAsignadosDelPaciente();
                        pacientesAtendidos(TIPOREPO, "2023-08-20");
                         
                        
                    });
                }else{
                    Swal.fire({
                        title:"Mensaje del sistema!",
                        text:"Error al guardar la órden médica! 😢😔",
                        icon:"error",
                        target:document.getElementById('modal_evaluacion_pre_operatoria')
                    })
                }
            }
        });
     }
  

    /// enviar paciente
    function sendPacienteViewHistorial(Tabla,Tbody){
        $(Tbody).on('click','#view_paciente',function(){
            let fila = $(this).parents("tr");

            if(fila.hasClass("child")){
                fil = fila.prev();
            }

            let Data = Tabla.row(fila).data();
            let PacienteDataText = (Data.apellidos+" "+Data.nombres).toUpperCase();

            $('#pacientedatos').val(PacienteDataText);
            $('#modal-pacientes_historial').modal("hide");

            MostrarHistorialPaciente(Data.documento);
        });
    }

    /// VER LA LISTA DE ENFERMEDADES
     function showEnfermedadesParaDiagnostico(){
        TablaEnfermedades = $('#lista_enfermedades').DataTable({
            retrieve:true,
            language:SpanishDataTable(),
            ajax:{
                url:RUTA+"enfermedades-habilitadas",
                method:"GET",
                dataSrc:"response",
            },
            columns:[
                {"data":"codigo_enfermedad"},
                {"data":"enfermedad",render:function(enfermeda_){
                    return enfermeda_.toUpperCase();
                }},
                {"data":null,render:function(){
                    return `<button class='btn btn-outline-primary btn-sm' id='asignar_diagnostico'><i class='bx bxs-send'></i></button>`;
                },className:'text-center'}
            ]
        })
      
     }

     /*EMVIAR LA ENFERMEDAD PARA EL DIAGNOSTICO AL PACIENTE*/
     function AsignarDiagnostico(Tabla,Tbody){
        $(Tbody).on('click','#asignar_diagnostico',function(){
            let fila = $(this).parents("tr");

            if(fila.hasClass("child")){
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();
           
            let EnfermedadIdData = Data.id_enfermedad;

            ProcesoAsignarDiagnostico(EnfermedadIdData);
        });
     }


     /** PROCESO PARA ASIGNAR EL DIAGNOSTICO**/
     function ProcesoAsignarDiagnostico(id){
        let formAsignarDiagnostico = new FormData();
        formAsignarDiagnostico.append("token_",TOKEN);
        axios({
            url:RUTA+"asignar-diagnostico-al-paciente/"+id,
            method:"POST",
            data:formAsignarDiagnostico
        }).then(function(response){
            if(response.data.error != undefined){
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!!",
                    text:"ERROR, TOKEN INVALID!!!",
                    icon:"error",
                    target:document.getElementById('modal_view_enfermedades')
                })
            }else{
                if(response.data.response === 'existe'){
                    Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!!",
                    text:"LA ENFERMEDAD QUE DESEAS AÑADIR COMO DIAGNÓSTICO YA LO TIENES AGREGADO EN LA LISTA!!!",
                    icon:"warning",
                    target:document.getElementById('modal_view_enfermedades')
                }).then(function(){
                    showDiagnosticosAsignadosDelPaciente();
                })
             }else{
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!!",
                    text:"DIAGNÓSTICO AGREGADO CORRECTAMENTE!!!",
                    icon:"success",
                    target:document.getElementById('modal_view_enfermedades')
                }).then(function(){
                    showDiagnosticosAsignadosDelPaciente();
                });
             }
            }
        })
     }

     /*MOSTRAR LOS DIAGNOSTICOS QUE SE LE ASIGNARON AL PACIENTE*/
     function showDiagnosticosAsignadosDelPaciente(){
        let tr = '';
        $.ajax({
            url:RUTA+"diagnosticos-asignados-al-paciente",
            method:"GET",
            dataType:"json",
            success:function(response){
                response = Object.values(response.diagnosticos);
                if(response.length > 0){
                    response.forEach(diag => {
                    tr+=`<tr>
                         <td class='text-center'><button class='btn btn-outline-danger btn-sm' id='quitar_diagnostico' onclick="ConfirmEliminadoDiagnostico('`+diag.enfermedad_id+`','`+diag.enfermedad_desc.toUpperCase()+`')">X</button></td>
                         <td class="d-none">`+diag.enfermedad_id+`</td>
                         <td>`+diag.enfermedad_desc.toUpperCase()+`</td>
                         <td>
                            <select class='form-select' id='tipo'>
                              <option value='p' `+(diag.tipo==='p' ? 'selected':'')+`>PRESUNTIVO</option>
                              <option value='r' `+(diag.tipo==='r' ? 'selected':'')+`>REPETITIVO</option>
                              <option value='d' `+(diag.tipo==='d' ? 'selected':'')+`>DEFINITIVO</option>
                            </select>
                            </td>
                        </tr>`;
                });
             }else{
                tr = '<td colspan="3"><span class="text-danger px-2 py-3">No hay diagnósticos agregados.....</span></td>';
             }
             $('#lista_diagnostico_paciente').html(tr);
            }
        })
     }

     /*CONFIRMAR ANTES DE ELIMINAR AL DIAGNOSTICO*/
     function ConfirmEliminadoDiagnostico(id,diagnosticodata){
      Swal.fire({
        title: "Estas seguro de eliminar al diagnóstico "+diagnosticodata+"?",
        text: "Al aceptar se quitará de la lista!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
        }).then((result) => {
        if (result.isConfirmed) {
          QuitarDeLaListaAlDiagnostico(id);
        }
        });
     }

     function QuitarDeLaListaAlDiagnostico(id){
        let formQuitar = new FormData();
        formQuitar.append("token_",TOKEN);
       axios({
         url:RUTA+"quitar-de-lista-diagnostico/paciente/"+id,
         method:"POST",
         data:formQuitar
       }).then(function(response){
         if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error+"!!",
                icon:"error"
            });
         }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:"DIAGNÓSTICO QUITADO DE LA LISTA!!",
                icon:"success"
            }).then(function(){
                showDiagnosticosAsignadosDelPaciente();
            });
         }
         }
       });
     }

     /** CAMBIAR EL TIPO DE ENFERMEDAD---*/
     function changeTipoDiagnosticoPaciente(id,DataTipo){
        $.ajax({
            url:RUTA+"modificar/tipo-diagnostico/paciente/"+id,
            method:"POST",
            data:{
                token_:TOKEN,tipo:DataTipo
            },
            success:function(response){

            }
        })
     }


     /*MOSTRAR LOS EXAMENES*/
     function showExamenesOrden(id){
        TablaListaExamenesOrden = $('#lista_examenes_orden').DataTable({
            language:SpanishDataTable(),
            bDestroy:true,
            autoWidth:true,
            ajax:{
                url:RUTA+"ordenes/disponibles/"+id,
                method:"GET",
                dataSrc:"examenes"
            },
            columns:[
                {"data":"id_examen"},
                {"data":"codigo_orden"},
                {"data":"nombre_examen",render:function(examenname){
                    return examenname.toUpperCase();
                }},
                {"data":"nombre_tipo_examen",render:function(tipoexamen){
                    return tipoexamen.toUpperCase();
                }},
                {"data":"nombre_categoria",render:function(namecategoria){
                    return namecategoria.toUpperCase();
                }},
                {"data":"precio_examen",render:function(precioexamen){
                    return precioexamen;
                }},
                {
                    "data":null,render:function(){
                        return `<button class='btn btn-success' id="send_examen"><i class='bx bxs-send'></i></button>`;
                    }
                }
            ],
            columnDefs:[
            { "sClass": "hide_me", targets: [0] }
        ]
        });
        
     }

     /*ENVIAR EL EXAMEN*/
     function AsignarExamenPaciente(Tabla,Tbody){
        $(Tbody).on('click','#send_examen',function(){
            let fila = $(this).closest("tr");

            if(fila.hasClass("child")){
                fila = fila.prev();
            }

            let Data = Tabla.row(fila).data();

            let ExampleId = fila.find("td").eq(0).text();
             
            ProcesoAsignarOrdenMedicoPaciente(ExampleId);
        });
     }

     /// proceso para asignar examen al paciente
     function ProcesoAsignarOrdenMedicoPaciente(id){
         
        let FormAsignarOrdenPaciente = new FormData();
        FormAsignarOrdenPaciente.append("token_",TOKEN);
        axios({
            url:RUTA+"asignar-orden-medico/paciente/"+id,
            method:"POST",
            data:FormAsignarOrdenPaciente
        }).then(function(response){
            console.log(response.data);
            if(response.data.error != undefined){
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"ORDEN, TOKEN INVALID!!!",
                    icon:"error",
                    target:document.getElementById('modal_view_examenes')
                });
            }else{
                if(response.data.response === 'add'){
                    Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"ORDEN MÉDICO AÑADIDO CORRECTAMENTE!!",
                    icon:"success",
                    target:document.getElementById('modal_view_examenes')
                }).then(function(){
                    showOrdersAsignadosDelPaciente();
                });
              }else{
                Swal.fire({
                    title:"MENSAJE DEL SISTEMA!!",
                    text:"EL EXAMEN QUE DESEAS AÑADIR YA EXISTE!!",
                    icon:"warning",
                    target:document.getElementById('modal_view_examenes')
                })
              }
            }
        });
     }


     /// mostra las orders del paciente 
     function showOrdersAsignadosDelPaciente(){
        let tr = '';
        $.ajax({
            url:RUTA+"orders-del-paciente",
            method:"GET",
            dataType:"json",
            success:function(response){
                response = Object.values(response.orders);
                if(response.length > 0){
                    let importe = 0.00;let TotalAPagar = 0.00;
                    response.forEach(order => {
                        importe = order.precio_examen * order.cantidad;
                        TotalAPagar+=importe;
                    tr+=`<tr>
                         
                          <td>`+order.cantidad+`</td>
                         <td>`+order.codigo_examen+`</td>
                         <td>`+order.examen_desc.toUpperCase()+`</td>
                         <td>`+order.tipo_examen.toUpperCase()+`</td>
                         <td>`+order.categoria_orden.toUpperCase()+`</td>
                         <td><input type="text" class="form-control" value=`+order.precio_examen+` id="precio_examen_medico"></td>
                         <td>`+parseFloat(importe).toFixed(2)+`</td>
                         <td class='text-center'><button class='btn btn-outline-danger btn-sm' id='quitar_orders' onclick="ConfirmEliminadoOrders('`+order.examen_id+`','`+order.examen_desc.toUpperCase()+`')">X</button></td>
                        </tr>`;
                });
                tr+=`<td colspan="6">Total a importe {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</td>`;
                tr+=`<td colspan="1">`+TotalAPagar.toFixed(2)+`</td>`;
             }else{
                tr = '<td colspan="7"><span class="text-danger px-2 py-3">No hay diagnósticos agregados.....</span></td>';
             }
             $('#lista_orders_paciente').html(tr);
            }
        })
     }

     /*CONFIRMAR ANTES DE QUITAR DE LA LISTA*/
      /*CONFIRMAR ANTES DE ELIMINAR AL DIAGNOSTICO*/
      function ConfirmEliminadoOrders(id,orderdata){
      Swal.fire({
        title: "Estas seguro de eliminar la órden "+orderdata+"?",
        text: "Al aceptar se quitará de la lista!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        target:document.getElementById('modal_evaluacion_pre_operatoria')
        }).then((result) => {
        if (result.isConfirmed) {
           QuitarOrden(id);
        }
        });
     }

     /// QUITAR LA ORDEN
     function QuitarOrden(id){
        let formQuitarOrder = new FormData();
        formQuitarOrder.append("token_",TOKEN);
       axios({
         url:RUTA+"order/paciente/quitar/"+id,
         method:"POST",
         data:formQuitarOrder
       }).then(function(response){
         if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error+"!!",
                icon:"error",
                target:document.getElementById('modal_evaluacion_pre_operatoria')
            });
         }else{
            if(response.data.response === 'ok'){
                Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:"DIAGNÓSTICO QUITADO DE LA LISTA!!",
                icon:"success",
                target:document.getElementById('modal_evaluacion_pre_operatoria')
            }).then(function(){
                 showOrdersAsignadosDelPaciente();
            });
         }
         }
       }); 
     }

     /// MOSTRAR LOS TIPOS DE DIAGNOSTICOS EN COMBO
     function showTipoDiagnosticosReporte(){
        let option = '<option selected disabled> ---- Seleccione ---- </option>';
        axios({
            url:RUTA+"mostrar-lista-tipo-diagnosticos",
            method:"GET",
        }).then(function(response){
           if(response.data.diagnosticos.length > 0){
            response.data.diagnosticos.forEach(element => {
                option+=`<option value=`+element.id_tipo_examen+`>`+element.nombre_tipo_examen.toUpperCase()+`</option>`;
            });

            $('#tipo_examen_reporte').html(option);
           }
        });
     }


/// MOSTRAR TODAS LAS CATEGORIAS DISPONIBLES DEPENDIENDO DEL TIPO DE ORDEN
function ShowCategoryDisponiblesTipoParaOrdenMedica(id){
    let option = '<option selected disabled> ---- Seleccione ----</option>';
    axios({
    url:RUTA+"categorias/por-tipo/disponibles/"+id,
    method:"GET",
    }).then(function(response){
    if(response.data.categoriasdata.length > 0){
    response.data.categoriasdata.forEach(element => {
    option+=`<option value=`+element.id_categoria_examen+`>`+element.nombre_categoria.toUpperCase()+`</option>`;
    });
    }
    
    $('#categoria_reporte').html(option);
    });
    }
 /*GENERAR EL NUMERO DE FOLIO PARA EL HISTORIAL CLINICO ELECTRONICO*/
  function GenerarFolioHistorialClinico(){
    axios({
        url:RUTA+"historial-clinico/generate-folio",
        method:"POST",
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error,
                icon:"error"
            });
        }else{
            $('#num_expediente').val(response.data.serie)
        }
    });
  }

  /*GENERAR EL NUMERO DE FOLIO PARA EL HISTORIAL CLINICO ELECTRONICO*/
  function GenerarSerieCorrelativoOrdenMedico(){
    axios({
        url:RUTA+"orden-medico/generate/serie",
        method:"POST",
    }).then(function(response){
        if(response.data.error != undefined){
            Swal.fire({
                title:"MENSAJE DEL SISTEMA!!",
                text:response.data.error,
                icon:"error"
            });
        }else{
            $('#serieorden').val(response.data.serie)
        }
    });
  }


    </script>
@endsection
