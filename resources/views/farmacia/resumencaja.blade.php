@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Resumen de caja')

@section('css')
  <style>
    
    td.hide_me
    {
      display: none;
    }
    #tabla_resumen_caja>thead>tr>th{
      background:  linear-gradient(to bottom, rgba(228,245,252,1) 0%,rgba(191,232,249,1) 50%,rgba(159,216,239,1) 51%,rgba(42,176,237,1) 100%);
    }
  </style>
@endsection

@section('contenido')
<div class="row">
    <div class="col-12">
        <div class="card">
          <div class="card-header" style="background: linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
             <h5 class="text-white letra float-start">Resumen de caja</h5>
          </div>
            <div class="card-body">
               <div class="card-text mt-3">
                <b class="float-start">Total Saldo {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }} :    </b> <span id="totalsaldo_" class="badge bg-primary mx-2"></span>
                 <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped nowrap responsive" id="tabla_resumen_caja" style="width: 100%">
                     <thead>
                        <tr>
                            <th class="py-3 letra">#</th>
                            <th class="py-3 letra">Ver Informe</th>
                            <th class="py-3 letra">Quién aperturó la caja?</th>
                            <th class="py-3 letra">Fecha apertura</th>
                            <th class="py-3 letra">Saldo inicial {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                            <th class="py-3 letra">Fecha de cierre</th>
                            <th class="py-3 letra">Total Gastos {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                             <th class="py-3 letra">Total Préstamo {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                              <th class="py-3 letra">Total Depósito {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                            <th class="py-3 letra">Ingreso Farmacia {{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
 
                            <th class="py-3 letra">Saldo Final{{ count($this->BusinesData()) == 1 ? $this->BusinesData()[0]->simbolo_moneda : 'S/.' }}</th>
                             
                        </tr>
                     </thead>
                     <tbody>
                        @if (isset($DetalleCaja) && count($DetalleCaja) > 0)
                           @php
                               $item = 0;
                           @endphp
                            @foreach ($DetalleCaja as $cajad)
                            @php $item++; @endphp
                                <tr>
                                    <td>{{$item}}</td>
                                    <td>
                                        <a href="{{$this->route("informe-cierre-caja/farmacia/")}}{{$cajad->id_caja}}" target="_blank" class="btn btn-outline-info btn-sm"><i class='bx bxs-file-pdf'></i></a>
                                    </td>
                                    <td>{{strtoupper($cajad->name."[".$cajad->rol."]")}}</td>
                                    <td><span class="badge bg-primary">{{$cajad->fecha_apertura}}</span></td>
                                    <td><span class="badge bg-success">{{$cajad->saldo_inicial}}</span></td>
                                    <td><span class="badge bg-info">{{$cajad->fecha_cierre}}</span></td>
                                    <td><span class="badge bg-danger">{{$cajad->total_gastos}}</span></td>
                                    <td><span class="badge bg-danger">{{$cajad->total_prestamos}}</span></td>
                                    <td><span class="badge bg-primary">{{$cajad->total_depositos}}</span></td>
                                    <td><span class="badge bg-primary">{{$cajad->ingreso_ventas}}</span></td>
                                    <td><span class="badge bg-primary">{{$cajad->saldo_final}}</td>
                                </tr>
                            @endforeach
                        @endif
                     </tbody>
                    </table>
                 </div>
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
  var TablaResumenCaja;
  $(document).ready(function(){
    TablaResumenCaja = $('#tabla_resumen_caja').DataTable({
        language:SpanishDataTable()
    });
  })  
 </script>   
@endsection