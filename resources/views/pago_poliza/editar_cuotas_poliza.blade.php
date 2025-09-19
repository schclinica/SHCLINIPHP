@extends($this->Layouts("dashboard"))

@section("title_dashboard","Editar cuota")

@section('contenido')
<div class="mx-3">
  <div class="row">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center justify-content-between">
        <h4 class="mb-0">Editar cuota</h4>
      </div>

      <form action="{{URL_BASE}}poliza/plan-pagos/{{isset($cuotaData)?$cuotaData[0]->id_cuota:''}}/update/{{isset($cuotaData)?$cuotaData[0]->prima_pago_id:''}}" method="post">
        <input type="hidden" value="{{$this->Csrf_Token()}}" name="token_">
        <div class="card-body">
          

          </div>
        
          <div class="row">
            <label for="" class="col-md-2 col-form-label"><b>Secuencia(*)</b></label>
            <div class="col-md-10">
              <input type="text" class="form-control mb-3" name="secuencia" id="secuencia"
                placeholder="Secuencia...."  
                value="{{isset($cuotaData)?$cuotaData[0]->secuencia:''}}" autofocus>
            </div>

            <label for="" class="col-md-2 col-form-label"><b>Num.Cupón(*)</b></label>
            <div class="col-md-10">
              <input type="text" class="form-control mb-3" name="num_cupon" id="num_cupon"
                placeholder="Numero cupón...." value="{{isset($cuotaData)?$cuotaData[0]->num_cupon:''}}" autofocus>
            </div>

            <label for="" class="col-md-2 col-form-label"><b>Fecha vencimiento(*)</b></label>
            <div class="col-md-10">
              <input type="date" class="form-control mb-3" name="fechav" id="fechav"
              value="{{isset($cuotaData)?$cuotaData[0]->fecha_vencimiento:''}}" autofocus>
            </div>

            <label for="" class="col-md-2 col-form-label"><b>Importe(*)</b></label>
            <div class="col-md-10">
              <input type="text" class="form-control mb-3" name="importe" id="importe"
                placeholder="Importe...." value="{{isset($cuotaData)?$cuotaData[0]->importe:''}}" autofocus>
            </div>
            <label for="" class="col-md-2 col-form-label"><b>Fecha de pago(*)</b></label>
            <div class="col-md-10">
              <input type="date" class="form-control mb-3" name="fecha_pago" id="fecha_pago"
              value="{{isset($cuotaData)?$cuotaData[0]->fecha_pago:''}}" autofocus>
            </div>

            <label for="" class="col-md-2 col-form-label"><b>Factura(*)</b></label>
            <div class="col-md-10">
              <input type="text" class="form-control mb-3" name="factura" id="factura"
                placeholder="Factura...." value="{{isset($cuotaData)?$cuotaData[0]->factura:''}}" autofocus>
            </div>

            <label for="" class="col-md-2 col-form-label"><b>Observación(*)</b></label>
            <div class="col-md-10">
              <input type="text" class="form-control mb-3" name="observacion" id="observacion"
                placeholder="Observación...." value="{{isset($cuotaData)?$cuotaData[0]->observacion:''}}" autofocus>
            </div>
          </div>
        </div>

        <div class="row justify-content-center mb-4 mx-3">
          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-xl-0 mlg-0 m-md-0 m-1">
            <button class="btn_success_person" name="save" style="width: 100%"><b>Guardar <i class='bx bxs-save'></i></b></button>
          </div>

          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12 m-xl-0 mlg-0 m-md-0 m-1">
            <button class="btn btn-danger rounded form-control" name="cancelar"><b>Cancelar </b><i
                class='bx bx-window-close'></i></button>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection