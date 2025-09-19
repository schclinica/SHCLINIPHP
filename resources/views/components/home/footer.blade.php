<a  href="https://wa.me/{{isset($this->BusinesData()[0]->wasap) ? str_replace(" ","",str_replace("+","",$this->BusinesData()[0]->wasap)): '51980724244'}}?text=¡{{isset($this->BusinesData()[0]->message_wasap) ? $this->BusinesData()[0]->message_wasap:'¡Hola,empresa de software!'}}!" class="whatsapp" target="_blank"> <i class="fab fa-whatsapp"></i></a>
<!-- ======= Footer ======= -->
<footer id="footer" style="background-color: black">
    <div class="container d-md-flex py-4">

        <div class="me-md-auto text-center text-md-start">
            <div class="copyright text-white">
                &copy; Copyright <strong><span>
                        @if (isset($this->BusinesData()[0]->nombre_empresa))
                            {{ isset($this->BusinesData()[0]->nombre_empresa) ? $this->BusinesData()[0]->nombre_empresa : 'Tu clínica online' }}
                        @else
                            Tu clínica online
                        @endif
                        </a>
                    </span></strong>. Todos los derechos reservados
            </div>
             
        </div>
        <div class="social-links text-center text-md-right pt-3 pt-md-0">
          @if (isset($redesSocialesClinica) && count($redesSocialesClinica) > 0)
            @foreach ($redesSocialesClinica as $red)
            <a href="{{$red->link_red_social}}" class="twitter" target="_blank"><i class="{{$red->icono}}"></i></a>
            @endforeach
            @endif
 
        </div>
    </div>
</footer><!-- End Footer -->
