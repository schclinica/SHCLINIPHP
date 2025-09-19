<main id="main">

    <!-- ======= Why Us Section ======= -->
    <section id="why-us" class="why-us">
        <div class="container">

            <div class="row">
                <div class="col-lg-4   align-items-stretch">
                    <div class="content">
                        <h4>
                            <b>
                               Nuestros valores
                            </b>
                        </h4>
                        <p>
                            @if (isset($this->BusinesData()[0]->quienes_son) and !empty($this->BusinesData()[0]->quienes_son))
                              {{$this->BusinesData()[0]->quienes_son}}  
                              @else 
                              Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa commodi aut sunt officiis, nam esse quod distinctio similique, quo obcaecati est, at molestiae quam. Quibusdam quasi culpa consequuntur magni fuga.Maxime tempora molestiae voluptates pariatur rem voluptatibus odio ea, labore inventore rerum dolor ut nemo dolores dicta! Dolor, iste rerum nam facere nisi ipsum? Autem eligendi quidem reprehenderit sunt unde.
                            @endif
                        </p>
                       
                    </div>
                </div>
                <div class="col-lg-8 d-flex align-items-stretch">
                    <div class="icon-boxes d-flex flex-column justify-content-center">
                        <div class="row">
                            <div class="col-xl-6 d-flex align-items-stretch">
                                <div class="icon-box mt-4 mt-xl-0">
                                    <i class="bx bx-receipt"></i>
                                    <h4>Misión</h4>
                                    <p>
                                        @if (isset($this->BusinesData()[0]->mision) and !empty($this->BusinesData()[0]->mision))
                                        {{$this->BusinesData()[0]->mision}}  
                                        @else 
                                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa commodi aut sunt officiis, nam esse quod distinctio similique, quo obcaecati est, at molestiae quam. Quibusdam quasi culpa consequuntur magni fuga.Maxime tempora molestiae voluptates pariatur rem voluptatibus odio ea, labore inventore rerum dolor ut nemo dolores dicta! Dolor, iste rerum nam facere nisi ipsum? Autem eligendi quidem reprehenderit sunt unde.
                                        @endif    
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-6 d-flex align-items-stretch">
                                <div class="icon-box mt-4 mt-xl-0">
                                    <i class="bx bx-cube-alt"></i>
                                    <h4>Visión</h4>
                                    <p>
                                        @if (isset($this->BusinesData()[0]->vision) and !empty($this->BusinesData()[0]->vision))
                                        {{$this->BusinesData()[0]->vision}}  
                                        @else 
                                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa commodi aut sunt officiis, nam esse quod distinctio similique, quo obcaecati est, at molestiae quam. Quibusdam quasi culpa consequuntur magni fuga.Maxime tempora molestiae voluptates pariatur rem voluptatibus odio ea, labore inventore rerum dolor ut nemo dolores dicta! Dolor, iste rerum nam facere nisi ipsum? Autem eligendi quidem reprehenderit sunt unde.
                                        @endif
                                    </p>
                                </div>
                            </div>                                                        
                        </div>
                    </div><!-- End .content-->
                </div>
            </div>

        </div>
    </section><!-- End Why Us Section -->

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container-fluid">

            <div class="row">
                <div
                    class="col-xl-5 col-lg-6 video-box d-flex justify-content-center align-items-stretch position-relative">
                    <a href="{{$this->BusinesData()[0]->video_url != null ? $this->BusinesData()[0]->video_url:'https://youtu.be/xR2_TjhE3GE?si=psfu-QgI_uPcve6C'}}" class="glightbox play-btn mb-4"></a>
                </div>

                <div
                    class="col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5">
                    <h3>Especialidades</h3>
                    <p>Los servicios que ofrecemos en nuestra clínica son:</p>
                 
                    @if (isset($dataEspecialidades) and count($dataEspecialidades) > 0)
                      <div class="row">
                      @foreach ($dataEspecialidades as $esp)
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                        <a href="">
                        <div class="icon-box">
                            <div class="icon"><i class='bx bx-street-view'></i></div>
                            <h5 class="title my-4">  {{$esp->nombre_esp}} </h5>
                          </div>
                        </a>
                      </div>
                      @endforeach
                      </div>
                      @else 
                      <div class="alert alert-danger">
                        <b>No hay especialidades aún....</b>
                      </div>
                    @endif
                </div>
            </div>

        </div>
    </section><!-- End About Section -->

    <!-- ======= Counts Section ======= -->
    <section id="counts" class="counts">
        <div class="container">

            <div class="row">

                <div class="col-lg-3 col-md-6">
                    <div class="count-box">
                        <i class="fas fa-user-md"></i>
                        <span data-purecounter-start="0" data-purecounter-end="{{isset($MedicosExistentes->cantidad_medico) ? $MedicosExistentes->cantidad_medico:0}}" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Doctores</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mt-5 mt-md-0">
                    <div class="count-box">
                        <i class="far fa-hospital"></i>
                        <span data-purecounter-start="0" data-purecounter-end="{{isset($CantidadDeEspecialidades->cantidad_esp) ? $CantidadDeEspecialidades->cantidad_esp:0}}" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Especialidades</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
                    <div class="count-box">
                        <i class="fa-regular fa-user"></i>
                        <span data-purecounter-start="0" data-purecounter-end="{{isset($PacientesExistentes->cantidad_paciente)?$PacientesExistentes->cantidad_paciente:0}}" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Pacientes</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
                    <div class="count-box">
                        <i class="fa-solid fa-notes-medical"></i>
                        <span data-purecounter-start="0" data-purecounter-end="{{isset($CantidadServicios->cantidad_serv) ?$CantidadServicios->cantidad_serv:0}}" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Servicios</p>
                    </div>
                </div>

            </div>

        </div>
    </section><!-- End Counts Section -->

  
    <!-- ======= Appointment Section ======= -->
    <section id="appointment" class="appointment section-bg">
        <div class="container">

            <div class="section-title">
                <h2>Haga una cita</h2>
                <p>Complete los datos que le solicitan para enviar los datos de la cita.</p>
            </div>

            <form action="" method="post" role="form" class="php-email-form" id="form_save_notificaciones">
                <input type="hidden" name="_token" value="{{$this->Csrf_Token()}}">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for=""><b>Seleccione la sede <span class="text-danger">*</span></b></label>
                        <select name="sede" id="sede" class="form-select">
                            <option disabled selected>--- Seleccione ---</option>
                            @foreach ($sedes as $lugar)
                                <option value="{{$lugar->id_sede}}">{{strtoupper($lugar->namesede)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="tipodoc" class="form-label"><b>Tipo documento <span class="text-danger">*</span></b></label>
                        <select name="tipodoc" id="tipodoc" class="form-select">
                            @if (isset($DatosTipoDoc))
                                @foreach ($DatosTipoDoc as $doc)
                                    <option value="{{$doc->id_tipo_doc}}">{{$doc->name_tipo_doc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="documento" class="form-label"><b>Número documento <span class="text-danger">*</span></b></label>
                        <input type="text" name="documento" id="documento" class="form-control" placeholder="Num. documento">
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Apellidos y nombres completos" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                        <div class="validate"></div>
                    </div>
                    <div class="col-md-4 form-group mt-3 mt-md-0">
                        <input type="email" class="form-control" name="email" id="email"
                            placeholder="Correo electrónico" data-rule="email" data-msg="Please enter a valid email">
                        <div class="validate"></div>
                    </div>
                    <div class="col-md-4 form-group mt-3 mt-md-0">
                        <input type="tel" class="form-control" name="phone" id="phone"
                            placeholder="Número de celular| teléfono | whatsApp" data-rule="minlen:4" data-msg="Please enter at least 4 chars">
                        <div class="validate"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group mt-3">
                        <input type="date" name="fecha_solicitud" id="fecha_solicitud" class="form-control datepicker" id="date"
                            placeholder="Appointment Date" value="{{$this->FechaActual("Y-m-d")}}"
                            min="{{$this->FechaActual("Y-m-d")}}">
                        <div class="validate"></div>
                    </div>
                    <div class="col-md-4 form-group mt-3">
                        <select name="especialidad" id="especialidad" class="form-select">
                            <option value="" disabled selected> --- seleccione especialidad --- </option>
                            @if (isset($dataEspecialidades))
                                @foreach ($dataEspecialidades as $espdata)
                                    <option value="{{$espdata->id_especialidad}}">{{strtoupper($espdata->nombre_esp)}}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="validate"></div>
                    </div>
                    <div class="col-md-4 form-group mt-3">
                        <select name="doctor" id="doctor" class="form-select">
                          
                        </select>
                        <div class="validate"></div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <textarea class="form-control" name="message" rows="5" placeholder="Message (Optional)"></textarea>
                    <div class="validate"></div>
                </div>
                <div class="mb-3">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Your appointment request has been sent successfully. Thank you!</div>
                </div>
                <div class="text-center"><button type="submit" class="btn_info_tw" id="save_solicitud">Registrar solicitud <i class='bx bx-calendar-check'></i></button></div>
            </form>

        </div>
    </section><!-- End Appointment Section -->

 
    <!-- ======= Doctors Section ======= -->
    <section id="doctors" class="doctors">
        <div class="container">

            <div class="section-title">
                <h2>Doctores</h2>
                <p>Contamos con los mejores profesionales en distintas especiliadades para cuidar de ti y tu familia. ¡Agenda una cita hoy mismo!</p>
            </div>

            <div class="row">
                @if (isset($medicos))
                    @foreach ($medicos as $med)
                    <div class="col-lg-6 my-1">
                        <div class="member d-flex align-items-start">
                            @if ($med->foto != null)
                                @php
                                    $foto = $this->asset("foto/".$med->foto);
                                @endphp
                                @else 
                                @php
                                    $foto = $this->asset("img/avatars/anonimo_4.jpg");
                                @endphp
                            @endif

                            <div  ><img src="{{$foto}}"  
                                    alt="" style="width: 100px;height: 100px;border-radius: 50%"></div>
                            <div class="member-info">
                                <h4>{{$med->doctor}} </h4>
                                <span>{{$med->especialidadesdata}}</span>
                                <p><i class='bx bxs-phone-call'></i> {{$med->celular_num == null ? 'XXX XXX XXX' :$med->celular_num}}</p>
                                 <button href="#" class="btn_success_person mt-1 col-10">Sacar una cita <i class='bx bx-calendar-plus'></i></button>
                                 <button href="#" class="btn_blue mt-1 col-10">Ver perfíl <i class='bx bxs-user-badge'></i></button>
                                <div class="social">
                                    <a href=""><i class="ri-twitter-fill"></i></a>
                                    <a href=""><i class="ri-facebook-fill"></i></a>
                                    <a href=""><i class="ri-instagram-fill"></i></a>
                                    <a href=""> <i class="ri-linkedin-box-fill"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                 @else 
                 <div class="col-12">
                    <div class="alert alert-danger">
                        No hay médicos para mostrar por el momento...
                    </div>
                 </div>
                @endif

            </div>

        </div>
    </section><!-- End Doctors Section -->
 
    <!-- ======= Testimonials Section ======= -->
   <section id="testimonials" class="testimonials">
        <div class="container">
            <div class="section-title"><h2>Testimonios</h2></div>
            <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">

                   @if (isset($testimonios))
                       @foreach ($testimonios as $tes)
                     @if ($tes->foto != null)
                       @php
                           $foto = $this->asset("foto/".$tes->foto);
                       @endphp
                       @else 
                       @php
                           $foto = $this->asset("img/avatars/anonimo_4.jpg");
                       @endphp
                      @endif
                       <div class="swiper-slide">
                        <div class="testimonial-wrap">
                            <div class="testimonial-item">
                                <img src="{{$foto}}" class="testimonial-img"
                                    alt="">
                                <h3>{{$tes->personadata}}</h3>
                                <h4>Paciente</h4>
                                <p>
                                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                                     {{$tes->descripcion_testimonio}}
                                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                                </p>
                            </div>
                        </div>
                    </div><!-- End testimonial item -->
                       @endforeach
                       @else 
                       <div class="row">
                         <div class="col-12">
                            <div class="alert-warning">no existe testimonios publicados por el momento...</div>
                         </div>
                       </div>
                   @endif
 
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>
    </section><!-- End Testimonials Section -->
 
    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
        <div class="container">

            <div class="section-title">
                <h2>Contactos</h2>
                <p>En caso que desees comunicarte con nosotros, ahí te dejamos nuestro correo, teléfono, también si por si deseas
                   nos puedes escribir vía correo llenando el formulario😁😎.</p>
            </div>
        </div>

        <div>
            <iframe src="{{isset($this->BusinesData()[0]->mapa_url)?$this->BusinesData()[0]->mapa_url:'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62413.82717488811!2d-77.10267040026268!3d-12.121442909702553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8137c30393f%3A0x5268cb2b1c4b162b!2sMiraflores!5e0!3m2!1ses-419!2spe!4v1711330512363!5m2!1ses-419!2spe'}}" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="container">
            <div class="row mt-5">

                <div class="col-lg-4">
                    <div class="info">
                        <div class="address">
                            <i class="bi bi-geo-alt"></i>
                            <h4>Dirección </h4>
                            <p> {{isset($this->BusinesData()[0]->direccion) ? $this->BusinesData()[0]->direccion:'Dirección de la clínica'}}</p>
                        </div>

                        <div class="email">
                            <i class="bi bi-envelope"></i>
                            <h4>Email:</h4>
                            <p> {{isset($this->BusinesData()[0]->contacto) ? $this->BusinesData()[0]->contacto:'soporteclinicademo@tecnologysoft.com'}}</p>
                        </div>

                        <div class="phone">
                            <i class="bi bi-phone"></i>
                            <h4>Call:</h4>
                            <p>{{isset($this->BusinesData()[0]->wasap) ? $this->BusinesData()[0]->wasap:'+51 XXX XXX XXX'}}</p>
                        </div>

                    </div>

                </div>

                <div class="col-lg-8 mt-5 mt-lg-0" id="div_contacto">

                    <form action="{{$this->route("contact/clinica")}}" method="post"  class="php-email-form" id="form_contacto">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="text" name="name" class="form-control" id="name_contacto"
                                    placeholder="Nombres completos" required>
                            </div>
                            <div class="col-md-6 form-group mt-3 mt-md-0">
                                <input type="email" class="form-control" name="email" id="email_contacto"
                                    placeholder="Escriba su email" required>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" class="form-control" name="subject" id="subject"
                                placeholder="Indicar un asunto" required>
                        </div>
                        <div class="form-group mt-3">
                            <textarea class="form-control" name="message" id="message_contacto" rows="5" placeholder="Escriba su mensaje" required></textarea>
                        </div>
                        <div class="mt-2" style="display: none" id="mensaje_contacto_success">
                            <div class="alert alert-success">
                                <b>Mensaje enviado correctamente!. 
                                    El equipo de  @if (isset($this->BusinesData()[0]->nombre_empresa))
                                    {{isset($this->BusinesData()[0]->nombre_empresa) ? $this->BusinesData()[0]->nombre_empresa:'Tu clínica online'}}  
                                  @else  
                                   Tu clínica online 
                                  @endif

                                  le responderá muy pronto!.Gracias por escribirnos.😁😎🎉🎉
                                </b>
                            </div>
                        </div>

                        <div class="mt-2" style="display: none" id="mensaje_contacto_error">
                            <div class="alert alert-danger">
                                <b> 
                                    Error al enviar mensaje, comuniquese con el equipo @if (isset($this->BusinesData()[0]->nombre_empresa))
                                    {{isset($this->BusinesData()[0]->nombre_empresa) ? $this->BusinesData()[0]->nombre_empresa:'Tu clínica online'}}  
                                  @else  
                                   Tu clínica online 
                                  @endif

                                  a traves de whatsApp o número de teléfono, Gracias!!.
                                </b>
                            </div>
                        </div>
                        
                        <div class="text-center"><button type="submit" id="envioemail" >Send Message</button></div>
                    </form>

                </div>

            </div>

        </div>
    </section><!-- End Contact Section -->

</main><!-- End #main -->
 
