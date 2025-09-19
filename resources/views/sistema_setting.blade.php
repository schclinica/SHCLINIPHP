@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Colors-System')

@section('css')
    <style>

    </style>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{$this->route("config/sistema/color/menu")}}" method="post" id="form_config_sistema">
                        <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
                        <div class="card-text mb-3"><b>Colores del menú</b></div>

                        <div class="card-text">
                           <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <b>Seleccione color de fondo</b>
                                    <input type="color" name="color_fondo" class="form-control form-control-color" value="{{!$this->ExistSession("color_fondo")? "#F8F8FF":$this->getSession("color_fondo")}}" list="colors">
                                </div>
                                <datalist id="colors"> 
                                    <option value="#ff0000"></option> 
                                    <option value="#00ff00"></option> 
                                    <option value="#0000ff"></option> 
                                    <option value="#660000"></option> 
                                    <option value="#006600"></option> 
                                    <option value="#000066"></option> 
                                    <option value="#cc0000"></option> 
                                    <option value="#00cc00"></option> 
                                    <option value="#0034af"></option> 
                                </datalist> 
                                
                            </div>
                           </div>
                            <div class="form-group mb-2 mt-2">
                                <b>Seleccione color de texto</b>
                                <input type="color" name="color_texto" class="form-control form-control-color" value="{{$this->getSession("color_texto")}}" list="colors_texto">
                            </div>
                            <datalist id="colors_texto"> 
                                <option value="#ff0000"></option> 
                                <option value="#00ff00"></option> 
                                <option value="#0000ff"></option> 
                                <option value="#660000"></option> 
                                <option value="#006600"></option> 
                                <option value="#000066"></option> 
                                <option value="#cc0000"></option> 
                                <option value="#00cc00"></option> 
                                <option value="#0034af"></option> 
                            </datalist> 

                            <div class="text-center mt-3">
                                <button class="btn_blue" id="save_config_sistema">Guardar cambios <i class="fas fa-save"></i></button>
                                <button class="btn btn-outline-danger" id="cancel_config_sistema">Cancelar cambios <i class="fas fa-cancel"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
