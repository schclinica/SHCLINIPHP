<a  href="https://wa.me/{{isset($this->BusinesData()[0]->wasap) ? str_replace(" ","",str_replace("+","",$this->BusinesData()[0]->wasap)): '51980724244'}}?text=¡{{isset($this->BusinesData()[0]->message_wasap) ? $this->BusinesData()[0]->message_wasap:'¡Hola,empresa de software!'}}!" class="whatsapp" target="_blank"> <i class="fab fa-whatsapp"></i></a>
<!-- Footer --> 
 <footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
      <div class="mb-2 mb-md-0">
        ©
        <script>
          document.write(new Date().getFullYear());
        </script>
        <?php
phpinfo();
?>
         Bienvenido al sistema 
        <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">
          @if (isset($this->BusinesData()[0]->nombre_empresa))
           <span class="text-primary"> {{isset($this->BusinesData()[0]->nombre_empresa) ? $this->BusinesData()[0]->nombre_empresa:'Tu clínica online'}}  </span>
          @else  
           Tu clínica online 
          @endif</a>
      </div>
      <div>
         
      </div>
    </div>
  </footer>