@extends($this->Layouts('dashboard'))

@section('title_dashboard', 'Dashboard')

@section('css')
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ==== HEADER ==== */
        .dashboard-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            border-radius: 1rem;
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
            text-align: center;
        }
        .dashboard-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: .3rem;
        }
        .dashboard-header p {
            font-size: 1rem;
            opacity: .9;
        }

        /* ==== CARDS ==== */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            cursor: pointer;
        }
        .card-body {
            padding: 1.5rem 1.2rem;
        }

        /* ==== ICONOS ==== */
        .avatar img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            background: #fff;
            border-radius: 12px;
            padding: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* ==== TEXTOS ==== */
        .fw-semibold {
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-weight: 600;
        }
        h3.card-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }
        .blockquote h3 {
            font-size: 1.4rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 0.5rem;
        }
        .blockquote-footer {
            font-size: 1.05rem;
            color: #6c757d;
        }
        .letra.h5 {
            font-size: 1.2rem;
            font-weight: 600;
        }

        /* ==== RESPONSIVE ==== */
        @media (max-width: 768px) {
            .dashboard-header h2 {
                font-size: 1.5rem;
            }
            .dashboard-header p {
                font-size: 0.9rem;
            }
            .card-body {
                padding: 1.2rem 1rem;
            }
            h3.card-title {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection

@section('contenido')
<div class="row justify-content-center">

    <!-- Header Bienvenida -->
    <div class="col-12">
        <div class="dashboard-header">
            <h2>👋 Bienvenido, {{$this->profile()->name}}</h2>
            <p>Nos alegra verte nuevamente en el sistema</p>
        </div>
    </div>

    <!-- Bloques de bienvenida -->
    <div class="col-xl-6 col-12">
        <figure>
            <blockquote class="blockquote mb-4">
                <h3>Te damos la bienvenida nuevamente</h3>
            </blockquote>
            <figcaption class="blockquote-footer">
                <b class="text-primary letra h5">[ {{$this->profile()->name}} ] !!</b>
            </figcaption>
        </figure>
    </div>
    <div class="col-xl-6 col-12">
        <figure>
            <blockquote class="blockquote mb-4">
                <h3>Sucursal</h3>
            </blockquote>
            <figcaption class="blockquote-footer">
                <b class="text-success letra h5">[ {{($this->profile()->rol!="admin_general") ? $this->profile()->namesede." (:" : "TODAS LAS SUCURSALES"}} ] </b>
            </figcaption>
        </figure>
    </div>

    <!-- Aquí sigue exactamente tu mismo contenido de cards -->
    <div class="col-12">
        {{-- === Cards por rol (Director / Admin / Admisión / Médico / Enfermera-Triaje) === --}}
        {!! $slot !!}
    </div>
</div>
@endsection

@section('js')
 <script>
    var PROFILE = "{{$this->profile()->rol}}";
    var RUTA = "{{URL_BASE}}";
    $(document).ready(function(){
        $('.cardpac').click(function(){
            (PROFILE === 'Director' || PROFILE === 'Admisión' || PROFILE === 'admin_general') ?  location.href = RUTA+"paciente" : '';
        });
        $('.cardmed').click(function(){
            (PROFILE === 'Director' || PROFILE === 'admin_general') ? location.href = RUTA+"medicos" : '';
        });
        $('.cardusu').click(function(){
            (PROFILE === 'Director' || PROFILE === 'admin_general') ? location.href = RUTA+"user_gestion" : '';
        });
        $('.cardhistorias').click(function(){
            (PROFILE === 'Admisión' || PROFILE === 'Enfermera-Triaje' )? location.href = RUTA+"ver-historial-clinico" : '';
        });
        $('.pacientestriaje').click(function(){
            (PROFILE === 'Enfermera-Triaje' )? location.href = RUTA+"triaje/pacientes" : '';
        });
        $('.pacientesexaminados').click(function(){
            (PROFILE === 'Enfermera-Triaje' )? location.href = RUTA+"triaje/pacientes" : '';
        });
    });
 </script>
@endsection

