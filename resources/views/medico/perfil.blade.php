@extends($this->Layouts("dashboard"))

@section("title_dashboard","Médicos")

@section('css')
 
@endsection
 
@section("contenido")
 <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-6  col-12 text-center">
               @if(isset($medico))  <img src="{{getFoto($medico->foto)}}" alt="Responsive image" class="img-fluid  rounded"
               style="height: 340px;border-radius: 50%"> @endif
            </div>

            <div class="col-xl-5 col-lg-5 col-md-6 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-3">
                <div class="card-text"><b>Dr.</b> {{strtoupper($medico->nombres)}} {{strtoupper($medico->apellidos)}} </div>
                 
                <div class="card-text mt-2"><b> <i class='bx bx-phone-call text-primary'></i> </b>  {{$medico->celular_num}}  </div>
                <div class="card-text mt-1"><b>Universidad: </b> {{is_null($medico->universidad_graduado)?'No espeficia':strtoupper($medico->universidad_graduado)}}</div>
                <br>
                <b class="mt-1">Bibliografía</b>
                <div class="card-text mt-2">
                    <p>
                        {{!is_null($medico->experiencia)?$medico->experiencia:'No especifica....'}}
                    </p>
                </div>
                
          
            </div>
        </div>
    </div>
 </div>
@endsection