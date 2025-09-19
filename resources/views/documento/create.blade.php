@extends($this->Layouts("dashboard"))

@section("title_dashboard","crear tipo documento")

@section('contenido')
<div class="mx-3">
  <div class="row">
    <div class="card">
      <div class="card-header" style="background: #2b618d">
        <h4 class="text-white letra">Crear Tipo Documento</h4>
      </div>

      <form action="{{$this-> route('save-tipo-documento')}}" method="post">
        <input type="hidden" value="{{$this->Csrf_Token()}}" name="token_">
        <div class="card-body">
          @if ($this->ExistSession("mensaje"))
          <div class="m-2">

             @if ($this->getSession("mensaje")== 'error')
             <div class="alert alert-danger">
              <b>Complete el campo de nombre de tipo de documento</b>
              </div>
              @else 
              <div class="alert alert-warning">
                <b>Ya existe este tipo de documento</b>
               </div>
             
             @endif
              {{--- DESTRUIMOS LA SESSION----}}
              @php
              $this->destroySession("mensaje")
              @endphp

          </div>
          @endif
            <div class="form-floating mb-2">
              <input type="text" class="form-control" name="name-tipo-doc" id="name-tipo-doc"
                placeholder="Tipo documento" value="{{$this->old("name-tipo-doc")}}" autofocus>
                <label for="name-tipo_doc">Nombre Tipo Documento</label>
            </div>
        </div>

        <div class="row justify-content-center mb-4 mx-3">
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-5 col-12 m-xl-0 mlg-0 m-md-0 m-1">
            <button class="btn btn-outline-success rounded form-control" name="save" ><b>Guardar <i class='bx bxs-save'></i></b></button>
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