@extends($this->Layouts("dashboard"))

@section("title_dashboard","Médicos")

@section('css')
 
@endsection
 
@section("contenido")
<div class="row">
    
  @if (isset($medicos))
      @if (count($medicos)>0)
      @foreach ($medicos as $medico)
      <div class="col-md-6 col-12 mb-2">
          <div class="card" style="background: #f6f6f6">
             <form action="agendar_cita" method="get">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4">
                            <div class="card-text text-center">
                                <img src="{{getFoto($medico->foto)}}" alt=""  class="rounded-circle" style="width: 95px;height: 95px;">
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8">
                            <input type="hidden" value="{{$medico->id_especialidad}}" name="espe_id">
                            <input type="hidden" value="{{$medico->id_medico}}" name="medico_id">
                            <input type="hidden" value="{{$medico->id_medico_esp}}" name="id_medesp_">
                            <input type="hidden" value="{{strtoupper($medico->nombres)}} {{strtoupper($medico->apellidos)}}" name="medico">
                            <div class="card-text"><b>Dr.</b> {{strtoupper($medico->nombres)}} {{strtoupper($medico->apellidos)}} </div>
                            <div class="card-text mt-2"><b> <i class='bx bx-phone-call text-primary'></i> </b>  {{$medico->celular_num}}  </div>
                            <div class="card-text mt-2"> {{strtoupper($medico->nombre_esp)}}</div>
                        </div>
                    </div>
      
                    <div class="text-center mt-3">
                        <a href="{{$this->route("medico_perfil/".$medico->id_medico)}}" class="btn btn-rounded  btn-outline-success m-xl-0 m-lg-0 m-md-0 m-sm-0 m-1 " >Ver perfíl <i class='bx bxs-user-detail'></i></a>
                        <button class="btn_blue m-xl-0 m-lg-0 m-md-0 m-sm-0 m-1">Agendar cita <i class='bx bxs-calendar'></i></button>
                    </div>
                </div>
                    
             </form>
             </div>
         </a>
      </div>
    @endforeach
    @else
    <div class="col">
      <div class="alert alert-danger">
          No hay médicos para mostrar... 😥
        </div>
    </div>
      @endif
  @endif
</div>
@endsection