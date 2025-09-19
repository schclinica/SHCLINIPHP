@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Dashboard')

@section('css')
    <style>
        #tabla_listado_pacientes_cita>thead>tr>th {
            background-color: #E6E6FA;
        }
    </style>
@endsection

@section('contenido')
 
    @if ($this->profile()->rol === 'Médico')
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card text-white" style="background-color: rgb(13, 112, 212)">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/medico.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">PACIENTES ATENDIDOS</span>
                        <h3 class="card-title mb-2 text-white text-center">
                            {{ $Pacientes_Atendidos_Medico->pacientes_atendidos }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card text-white" style="background-color: #FFD700">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/paciente.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-primary"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center text-dark">PACIENTES ANULADOS</span>
                        <h3 class="card-title mb-2 text-white text-center">
                            {{ $Pacientes_Anulados_Medico->pacientes_atendidos }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card text-white" style="background-color: rgb(37, 143, 16)">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">MONTO RECAUDADO</span>
                        <h3 class="card-title mb-2 text-white text-center">
                            {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}
                            {{ $MontoRecaudadoMedicoHoy[0]->total_ingreso == null ? '0.00' : $MontoRecaudadoMedicoHoy[0]->total_ingreso }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card text-white" style="background-color: rgb(26, 188, 123)">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (MENSUAL)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">MONTO RECAUDADO</span>
                        <h3 class="card-title mb-2 text-white text-center">
                            {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} {{ $MontoRecaudadoMedicoMensual[0]->total_ingreso == null ? '0.00' : $MontoRecaudadoMedicoMensual[0]->total_ingreso }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card text-white" style="background-color: rgb(6, 109, 235)">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (AÑO {{$this->FechaActual("Y")}})</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">MONTO RECAUDADO</span>
                        <h3 class="card-title mb-2 text-white text-center">
                            {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} {{ $MontoRecaudadoMedicoAnio[0]->total_ingreso == null ? '0.00' : $MontoRecaudadoMedicoAnio[0]->total_ingreso }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-12 my-1">
                <div class="card text-white">
                    <div class="card-body">
                        <div class="card-text">
                            <div id="reporte_pacientes_atendidos_mes_medico_sistema"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif
    @if ($this->profile()->rol === 'Admisión' || $this->profile()->rol === 'Enfermera-Triaje')
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-danger text-white">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/citas.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">CITAS MÉDICAS ANULADOS</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $CitasMedicasAnuladosHoy->cantidad }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-success text-white">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">CITAS MÉDICAS PAGADOS</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $Citas_Sin_Concluir_Hoy->cantidad }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card text-white" style="background-color: crimson">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/notificacion.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">CITAS MÉDICAS PENDIENTES</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $Citas_Medicas_Pendientes->cantidad }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card text-white" style="background-color: rgb(20, 220, 217)">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/pacientes_examinados.ico') }}"
                                    alt="chart success" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">PACIENTES EXAMINADOS</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $Pacientes_Examinados->cantidad }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-12 my-1">
                <div class="card text-white" style="background-color: rgb(20, 60, 220)">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/medico.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">PACIENTES ATENDIDOS</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $PacientesAtendidosHoy->cantidad }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'admin_general')
        <div class="row mb-2">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-primary text-white">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/paciente.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">PACIENTES ATENDIDOS</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $PacientesAtendidosHoy->cantidad }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-danger text-white">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/citas.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center">CITAS ANULADOS</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $CitasMedicasAnuladosHoy->cantidad }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/paciente.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center text-white">PACIENTES</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $PacientesExistentes->cantidad_paciente }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/medico.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center text-white">MÉDICOS EXISTENTES</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $MedicosExistentes->cantidad_medico }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-warning">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/notificacion.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center text-white">CITAS SIN CONCLUIR</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $Citas_Sin_Concluir_Hoy->cantidad }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-info">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/admin_user_man_22187.ico') }}"
                                    alt="chart success" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center text-white">USUARIOS ACTIVOS</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $User_Active->cantidad_user }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4  col-12 my-1">
                <div class="card" style="background-color: rgb(227, 116, 19)">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/admin_user_man_22187.ico') }}"
                                    alt="chart success" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center text-white">USUARIOS INACTIVOS</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $User_Inactive->cantidad_user }}</h3>
                    </div>
                </div>
            </div>

            {{-- -REPORTES ESTADISTICOS-- --}}
            <div class="col-md-6 col-12 mt-2">
                <div class="card">
                    <div class="card-body">

                        <div class="card-text">
                            <div id="reporte_anual"></div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            <div id="reporte_mes"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            <div id="reporte_medico_cantidad_citas"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            <div id="reporte_estado_citas_por_mes"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            <div id="reporte_estado_citas_por_diario"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- - PARTE PRINCIPAL DEL PACIENTE - --}}
    @if ($this->profile()->rol === 'Paciente')
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/cita_.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center text-white">Citas registrados</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $TotalDeCitasDelPacientes->cantidad_citas }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-info">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/cita_.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center text-white">Citas concluidos</span>
                        <h3 class="card-title mb-2 text-white text-center">{{ $CitasConcluidosPaciente->cantidad_citas }}
                        </h3>
                    </div>
                </div>

            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 my-1">
                <div class="card bg bg-danger">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ $this->asset('img/icons/unicons/citas.ico') }}" alt="chart success"
                                    class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-white"> Total</i>
                                </button>

                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1 text-center text-white">Citas no concluidos</span>
                        <h3 class="card-title mb-2 text-white text-center">
                            {{ $CitasNoConcluidosPaciente->cantidad_citas }}</h3>
                    </div>
                </div>

            </div>
        </div>
    @endif
    <div class="row mt-3 d-none">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card" style="background:#F8F8FF">
                <div class="card-body">
                    <div class="cars-text p-2">
                        <h5>Citas por mes</h5>
                    </div>
                    <div class="card-text">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

   @if ($this->profile()->rol === 'Director' || $this->profile()->rol === 'Admisión' || $this->profile()->rol === 'admin_general')
   <div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card-text">
                    <h5>Seguimiento de pacientes (Hoy)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped nowrap responsive"
                        id="tabla_listado_pacientes_cita" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Paciente</th>
                                <th>Médico</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

   @endif

   @if ($this->profile()->rol === 'Farmacia' || $this->profile()->rol === 'admin_farmacia')
    <div class="row mt-1">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mt-xl-0 mt-lg-0 mt-1">
            <div class="card text-white" style="background-color: #87CEFA">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                                class="rounded" />
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded text-white"> (Hoy)</i>
                            </button>
            
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1 text-center">Total recaudado</span>
                    <h3 class="card-title mb-2 text-white text-center">
                        {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}  {{isset($repoFarmaciaVentaDia[0]->total_ventas_dia) ?$repoFarmaciaVentaDia[0]->total_ventas_dia : "0.00"}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12  mt-xl-0 mt-lg-0 mt-1">
            <div class="card text-white" style="background-color: #00FA9A">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                                class="rounded" />
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded text-white"> (Mes)</i>
                            </button>
            
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1 text-center">Total recaudado</span>
                    <h3 class="card-title mb-2 text-white text-center">
                        {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} {{isset($repoFarmaciaVentaMes[0]->total_ventas_mes) ?$repoFarmaciaVentaMes[0]->total_ventas_mes : "0.00"}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4  col-12  mt-xl-0 mt-lg-0 mt-1">
            <div class="card text-white" style="background-color: #FFA500">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ $this->asset('img/icons/unicons/money.ico') }}" alt="chart success"
                                class="rounded" />
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded text-white"> (Año)</i>
                            </button>
            
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1 text-center">Total recaudado</span>
                    <h3 class="card-title mb-2 text-white text-center">
                        {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} {{isset($repoFarmaciaVentaAnio[0]->total_ventas_mes) ?$repoFarmaciaVentaAnio[0]->total_ventas_mes : "0.00"}}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-5 col-md-6 col-12 mt-2">
            <div class="card">
                <div class="card-body">

                    <div class="card-text">
                        <div id="reporte_ventas_mes"></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-7 col-lg-7 col-md-6 col-12 mt-2">
            <div class="card">
                <div class="card-body">

                    <div class="card-text">
                        <div id="reporte_ventas_anio"></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8 col-12 mt-2">
            <div class="card">
                <div class="card-body">

                    <div class="card-text">
                        <div id="reporte_cantidad_mes"></div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-12 mt-2">
            <div class="card">
                <div class="card-body">

                    <div class="card-text">
                        <div id="reporte_cantidad_anio"></div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-body">

                    <div class="card-text">
                       <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="reporte_ventas_producto_all" checked>
                                <label class="form-check-label" for="reporte_ventas_producto_all">En general</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="reporte_ventas_producto_hoy">
                                <label class="form-check-label" for="reporte_ventas_producto_hoy">Hoy</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="reporte_ventas_producto_mes" id="">
                                <label class="form-check-label" for="reporte_ventas_producto_mes">Mes</label>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3  col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="reporte_ventas_producto_anio">
                                <label class="form-check-label" for="reporte_ventas_producto_anio">Año {{$this->FechaActual("Y")}}</label>
                            </div>
                        </div>
                       </div>
                        <div id="reporte_ventas_producto"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
   @endif
@endsection 

@section('js')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{ URL_BASE }}public/js/control.js"></script>
    <script>
        var URL = "{{ URL_BASE }}";
        var TOKEN = "{{ $this->Csrf_Token() }}";
        var PROFILE_ = "{{ $this->profile()->rol }}";
        var TablaPacientesSeguimiento;
        /// esperamos a que carguen todos los datos
        loading('#all', '#4169E1', 'chasingDots');
        setTimeout(() => {
            $('#all').loadingModal('hide');
            $('#all').loadingModal('destroy');
        }, 2000);
        $(document).ready(function() {

            if (PROFILE_ === 'Director' || PROFILE_ === 'admin_general') {
                showGraficoBarraCitasAnio();
                showGraficoBarraCitasMes();
                showCitasPorMedico_Por_Mes();
                GraficoCitasPorEstadoMensual();
                GraficoCitasPorEstadoDiario();
                mostrarPacientesSeguimiento();
                showGraficoBarraVentasMes()
                           
            } else {
                if (PROFILE_ === 'Médico') {
                    GraficaMedicoPacientesAtendidosPorMes();
                } else {
                    if (PROFILE_ === 'Admisión') {
                        mostrarPacientesSeguimiento();
                    }else{
                        if(PROFILE_ === 'Farmacia' || PROFILE_ === 'Director' || PROFILE_ === 'admin_farmacia')
                        {
                            showGraficoBarraVentasMes()
                            showGraficoBarraVentasAnio();
                            showGraficoBarraCantidadVentasPorMes();
                            showGraficoBarraCantidadVentasPorAnio();
                            showGraficoBarraCantidadVentasPorProducto('todos');
                             
                        }
                    }
                }
            }

            $('#reporte_ventas_producto_all').click(function(){
               if($(this).is(":checked"))
               {
                $('#reporte_ventas_producto_hoy').prop("checked",false);
                $('#reporte_ventas_producto_mes').prop("checked",false);
                $('#reporte_ventas_producto_anio').prop("checked",false);
                showGraficoBarraCantidadVentasPorProducto('todos');
               }
            })  

            
            $('#reporte_ventas_producto_mes').click(function(){
               if($(this).is(":checked"))
               {
                $('#reporte_ventas_producto_all').prop("checked",false);
                $('#reporte_ventas_producto_hoy').prop("checked",false);
                $('#reporte_ventas_producto_anio').prop("checked",false);
                showGraficoBarraCantidadVentasPorProducto('mes');
               }
            }); 
            $('#reporte_ventas_producto_anio').click(function(){
               if($(this).is(":checked"))
               {
                $('#reporte_ventas_producto_all').prop("checked",false);
                $('#reporte_ventas_producto_hoy').prop("checked",false);
                $('#reporte_ventas_producto_mes').prop("checked",false);
                showGraficoBarraCantidadVentasPorProducto('anio');
               }
            });
            $('#reporte_ventas_producto_hoy').click(function(){
               if($(this).is(":checked"))
               {
                $('#reporte_ventas_producto_all').prop("checked",false);
                $('#reporte_ventas_producto_anio').prop("checked",false);
                $('#reporte_ventas_producto_mes').prop("checked",false);
                showGraficoBarraCantidadVentasPorProducto('dia');
               }
            }); 
        });

        $(window).resize(function() {
            if (PROFILE_ === 'Director' || PROFILE_ === 'admin_general') {
                showGraficoBarraCitasAnio();
                showGraficoBarraCitasMes();
                showCitasPorMedico_Por_Mes();
                GraficoCitasPorEstadoMensual();
                GraficoCitasPorEstadoDiario();
            } else {
                if (PROFILE_ === 'Médico') {
                    GraficaMedicoPacientesAtendidosPorMes();
                }else{
                        if(PROFILE_ === 'Farmacia' || PROFILE_ === 'Director' || PROFILE_ === 'admin_farmacia'  )
                        {
                            showGraficoBarraVentasMes();
                            showGraficoBarraVentasAnio();
                            showGraficoBarraCantidadVentasPorMes();
                            showGraficoBarraCantidadVentasPorAnio();
                            
                            showGraficoBarraCantidadVentasPorProducto('todos');
                        }
                    }
            }

        });

        function mostrarPacientesSeguimiento() {
            TablaPacientesSeguimiento = $('#tabla_listado_pacientes_cita').DataTable({
                language: SpanishDataTable(),
                retrieve: true,
                responsive: true,
                processing: true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                ajax: {
                    url: URL + "pacientes/seguimiento/cia_medica",
                    method: "GET",
                    dataSrc: "response"
                },
                columns: [{
                        "data": "pacientedata"
                    },
                    {
                        "data": "pacientedata",
                        render: function(paciente) {
                            return paciente.toUpperCase()
                        }
                    },
                    {
                        "data": "medicodata",
                        render: function(medico) {
                            return medico.toUpperCase()
                        }
                    },
                    {
                        "data":"estadodata",render:function(estadodata){
                            if(estadodata === 'pagado')
                            {
                                return '<span class="badge bg-warning">En atención médica</span>'
                            }
                            else{
                                if(estadodata === 'anulado')
                                {
                                    return  '<span class="badge bg-danger">Cita anulado</span>';
                                }
                                return  '<span class="badge bg-success">Cita finalizado</span>';
                            }
                        }
                    },
                    {
                        "data": "fecha_cita",
                        render: function(fechacita) {

                            let fecha = fechacita.split("-");

                            let AnioNac = fecha[0],
                                MesNac = fecha[1],
                                DiaNac = fecha[2];

                            return DiaNac + "/" + MesNac + "/" + AnioNac;
                        }
                    },
                    {
                        "data": "hora_cita"
                    },
                ]
            });

            TablaPacientesSeguimiento.on('order.dt search.dt', function() {
                TablaPacientesSeguimiento.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }
        /*OBTENER LA DATA DE LAS CITAS MEDICAS POR MES EN GRAFICO DE BARRAS*/
        function showGraficoBarraCitasAnio() {
            let Data1 = [];
            $.ajax({
                url: URL + "reporte_estadistico/anual?token_=" + TOKEN,
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);

                    response.reporte.forEach(cita => {
                        Data1.push(['' + cita.anio + '', parseInt(cita.cantidad)]);
                    });
                    GenerateGraficoEstadistico(Data1, 'reporte_anual', 'Citas médicas finalizados por año');
                }
            })
        }

        /*OBTENER LA DATA DE LAS CITAS MEDICAS POR MES EN GRAFICO DE BARRAS*/
        function showGraficoBarraCitasMes() {
            let Data1 = [];
            $.ajax({
                url: URL + "reporte_estadistico/mes?token_=" + TOKEN,
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);

                    response.reporte.forEach(cita => {
                        Data1.push(['' + cita.mes + '', parseInt(cita.cantidad)]);
                    });
                    GenerateGraficoEstadistico(Data1, 'reporte_mes', 'Citas médicas finalizados por mes');
                }
            })
        }

         /*OBTENER LA CANTIDAD DE VENTAS POR MES EN GRAFICO DE BARRAS*/
        


        function showGraficoBarraCantidadVentasPorMes() {
             
            let Data = [
                ['Element', 'Meses del año', {
                    role: 'style',
                }]
            ];
            $.ajax({
                url: URL + "app/farmacia/reporteventas/graficas/estadisticos/mes/anual/cantidad_mes",
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);
                   
                    if (response.response.length > 0) {
                        response.response.forEach(ventas => {
                        Data.push(['""' + ventas.mes + '',parseInt(ventas.cantidad_venta),GenerateRgb()]);
                    });
                    } else {
                        Data.push(["", parseInt(0), GenerateRgb()]);
                    }
                    
                    GraficoBarraChart(Data, '# de ventas por mes', 'Cantidad',
                        'reporte_cantidad_mes');
                   // GenerateGraficoEstadistico(Data1, 'reporte_cantidad_mes', '# de ventas por mes');
                }
            })
            
        }

        

        function showGraficoBarraCantidadVentasPorAnio() {
            let Data1 = [];
            $.ajax({
                url: URL + "app/farmacia/reporteventas/graficas/estadisticos/mes/anual/cantidad_anio",
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);

                    response.response.forEach(ventas => {
                        Data1.push(['' + ventas.anio + '',parseInt(ventas.cantidad_venta)]);
                    })
                    GenerateGraficoEstadistico(Data1, 'reporte_cantidad_anio', '# de ventas por año');
                }
            })
        }

        /** Reporte de cantidad de ventas realizadas a cada producto **/ 
        function showGraficoBarraCantidadVentasPorProducto(tipo) {
            let Data1 = [];
            $.ajax({
                url: URL + "app/farmacia/reporteventas/graficas/estadisticos/cantidad_ventas/producto/"+tipo,
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);
 
                    response.response.forEach(ventas => {
                        Data1.push(['' + ventas.producto + '',parseInt(ventas.cantidad_ventas)]);
                    })
                    GenerateGraficoEstadistico(Data1, 'reporte_ventas_producto', '# de ventas por producto');
                }
            })
        }

         function showGraficoBarraCantidadVentasPorMes() {
            let Data = [
                ['Element', 'Meses del año', {
                    role: 'style',
                }]
            ];
            $.ajax({
                url: URL + "app/farmacia/reporteventas/graficas/estadisticos/mes/anual/cantidad_mes",
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.response.length > 0) {
                        response.response.forEach(ventas => {
                        Data.push([""+ ventas.mes + "", parseInt(ventas.cantidad_venta),GenerateRgb()]);
                    });
                    } else {
                        Data.push(['', 0, GenerateRgb()]);
                    }
                    
                    GraficoBarraChart(Data, '# de ventas por mes', 'Cantidad',
                        'reporte_cantidad_mes');
                   // GenerateGraficoEstadistico(Data1, 'reporte_cantidad_mes', '# de ventas por mes');
                }
            })
            
        }

          
        function showGraficoBarraVentasMes() {
            let Data1 = [];
            $.ajax({
                url: URL + "app/farmacia/reporteventas/graficas/estadisticos/mes/anual/mes",
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);

                    response.response.forEach(ventas => {
                        Data1.push(['' + ventas.mes + '', parseFloat(ventas.total_venta)]);
                    });
                    GenerateGraficoEstadistico(Data1, 'reporte_ventas_mes', 'Monto recaudado por mes');
                }
            })
        }
        

        function showGraficoBarraVentasAnio() {
            let Data1 = [];
            $.ajax({
                url: URL + "app/farmacia/reporteventas/graficas/estadisticos/mes/anual/anio",
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);

                    response.response.forEach(ventas => {
                        Data1.push(['' + ventas.anio + '', parseFloat(ventas.total_venta)]);
                    });
                    GenerateGraficoEstadistico(Data1, 'reporte_ventas_anio', 'Total recaudado por año');
                }
            })
        }

        /// generar gráfico estadístico
        function GenerateGraficoEstadistico(DataGrafico = [], div, Title, En3D = true, Resposiva = true) {

            // Load the Visualization API and the corechart package.
            google.charts.load('current', {
                'packages': ['corechart']
            });

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawChart);

            // Callback that creates and populates a data table,
            // instantiates the pie chart, passes in the data and
            // draws it.
            function drawChart() {

                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Topping');
                data.addColumn('number', 'Slices');
                data.addRows(
                    DataGrafico
                );

                // Set chart options
                var options = {
                    'title': Title,
                    'responsive': Resposiva,
                    is3D: true,
                   
                };

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.PieChart(document.getElementById(div));
                chart.draw(data, options);
            }
        }

        function showCitasPorMedico_Por_Mes() {
            let Data1 = [
                ['Element', 'Cantidad', {
                    role: 'style',
                }]
            ];
            $.ajax({
                url: URL + "reporte_estadistico/medico?token_=" + TOKEN,
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.reporte.length > 0) {
                        response.reporte.forEach(cita => {
                            Data1.push([cita.medico, parseInt(cita.cantidad), GenerateRgb()]);
                        });
                    } else {
                        Data1.push(["", 0, GenerateRgb()]);
                    }
                    GraficoBarraChart(Data1, '# de citas finalizados por médico de cada mes', 'Médicos',
                        'reporte_medico_cantidad_citas')
                }
            })

        }

        /** Mostramos el reporte de pacientes atendidos por cada mes*/
        function GraficoCitasPorEstadoMensual() {
            let Data = [
                ['Element', 'Estados de la cita', {
                    role: 'style',
                }]
            ];
            $.ajax({
                url: "reporte_estadistico/citas_por_estado_mensual?token_=" + TOKEN,
                method: "GET",
                success: function(response_mes_estado_cita) {
                    response_mes_estado_cita = JSON.parse(response_mes_estado_cita);
                    if (response_mes_estado_cita.reporte.length > 0) {
                        response_mes_estado_cita.reporte.forEach(repo => {
                            Data.push(['citas ' + repo.estado + 's', parseInt(repo.cantidad), GenerateRgb()]);
                        });
                    } else {
                        Data.push(['', 0, GenerateRgb()]);
                    }

                    //GenerateGraficoEstadistico(Data, 'reporte_estado_citas_por_mes', 'Citas médicas por estado por mes');
                    GraficoBarraChart(Data, '# de citas por estado de cada mes', 'Estados de la cita',
                        'reporte_estado_citas_por_mes');
                }

            })
        }

        /** Mostramos el reporte de pacientes atendidos por cada día*/
        function GraficoCitasPorEstadoDiario() {
            let Data = [
                ['Element', 'Estados de la cita', {
                    role: 'style',
                }]
            ];
            $.ajax({
                url: "reporte_estadistico/diarios?token_=" + TOKEN,
                method: "GET",
                success: function(response_mes_estado_cita) {
                    response_mes_estado_cita = JSON.parse(response_mes_estado_cita);
                    if (response_mes_estado_cita.reporte.length > 0) {
                        response_mes_estado_cita.reporte.forEach(repo => {
                            Data.push(['citas ' + repo.estado + 's', repo.cantidad, GenerateRgb()]);
                        });
                    } else {
                        Data.push(['', 0, GenerateRgb()]);
                    }

                    //GenerateGraficoEstadistico(Data, 'reporte_estado_citas_por_mes', 'Citas médicas por estado por mes');
                    GraficoBarraChart(Data, '# de citas por estado (Hoy)', 'Estados de la cita',
                        'reporte_estado_citas_por_diario');
                }

            })
        }

        /*Plnatilla reporte gráfico estadistico tipo barra*/
        function GraficoBarraChart(Data1 = [], TitleOptions, TitleOptions_2, IdDiv) {
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawMultSeries);

            function drawMultSeries() {
                var data = new google.visualization.arrayToDataTable(Data1);

                var options = {
                    title: TitleOptions,

                    vAxis: {
                        title: TitleOptions_2
                    },
                    bar: {
                        groupWidth: "40%"
                    },

                };

                var chart = new google.visualization.ColumnChart(
                    document.getElementById(IdDiv));

                chart.draw(data, options);
            }
        }
        /** Generamos código rgb aleatorios*/
        function GenerateCodeAleatorio(numero) {
            return (Math.random() * numero).toFixed(0);
        }

        /** Generamos el rgb**/
        function GenerateRgb() {
            let Code = "(" + GenerateCodeAleatorio(255) + "," + GenerateCodeAleatorio(255) + "," + GenerateCodeAleatorio(
                255) + ")";
            return 'rgb' + Code;
        }


        /** Para la visualización del médico*/
        function GraficaMedicoPacientesAtendidosPorMes() {
            let Data_Doc = [
                ['Element', 'Meses de año {{ $this->FechaActual('Y') }}', {
                    role: 'style',
                }]
            ];
            $.ajax({
                url: URL + "grafica_pacientes_atendidos?token_=" + TOKEN,
                method: "GET",
                success: function(response) {
                    response = JSON.parse(response);

                    if (response.response.length > 0) {
                        response.response.forEach(data_ => {
                            Data_Doc.push([""+data_.mes+"",parseInt(data_.cantidad),GenerateRgb()
                            ]);
                        });

                    } else {
                        Data_Doc.push(['', 0, GenerateRgb()]);
                    }
                    //GenerateGraficoEstadistico(Data_Doc, 'reporte_pacientes_atendidos_mes_medico_sistema', 'Citas médicas por estado por mes');
                    GraficoBarraChart(Data_Doc,
                        '# de pacientes atendidos por mes ( {{ $this->FechaActual("Y") }} )', 'cantidad',
                        'reporte_pacientes_atendidos_mes_medico_sistema');
                }
            })
        }
    </script>
@endsection
