@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Gestión de egresos')

@section('css')
    <style>
        #detalle_egresos>thead>tr>th,
        .modal-header {
            background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);
        }
    </style>
@endsection
@section('contenido')
    <div class="row">
        <div class="col-12">
            <div class="card">
          <div class="card-header" style="background:linear-gradient(to bottom, rgba(109,179,242,1) 0%,rgba(84,163,238,1) 50%,rgba(54,144,240,1) 51%,rgba(30,105,222,1) 100%);">
             <h4 class="text-white letra">Registrar Tipo Gasto</h4>
          </div>
                <div class="card-body">
                    @if ($this->ExistSession("error"))
                        <div class="alert alert-danger mt-1">
                            {{$this->getSession("error")}}
                        </div>
                        {{$this->destroySession("error")}}
                    @endif
                     @if ($this->ExistSession("existe"))
                        <div class="alert alert-warning mt-1">
                            {{$this->getSession("existe")}}
                        </div>
                        {{$this->destroySession("existe")}}
                    @endif
                    <div class="card-text mt-3">
                        <form action="{{$this->route("categoria-egreso/save")}}" method="post" id="form_save_categoria_egreso">
                            <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
                            <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="categoria_name" name="categoria_name" autofocus
                                placeholder="Escriba la categóría...">
                            <label for="categoria_name"><b>Nombre categoría <span class="text-danger">*</span></b></label>
                        </div>
                        </form>
                    </div>
 
                    <div class="card-text text-center mb-3 mt-3">
                        <button class="btn_3d" id="save_egreso" form="form_save_categoria_egreso"><b>Guardar <i
                        class='bx bx-save'></i></b></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection