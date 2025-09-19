class gestionUser {
  constructor(
    documento,
    apellidos,
    nombres,
    genero,
    direccion,
    fecha_nac,
    tipo_doc,
    distrito,
    usuarioName,
    usuarioEmail,
    userPass,
    usuario_rol,
    url_,
    sede_
  ) {
    this.Documento = documento;
    this.Apellidos = apellidos;
    this.Nombres = nombres;
    this.Genero = genero;
    this.Direccion = direccion;
    this.FechaNacimiento = fecha_nac;
    this.TipoDoc = tipo_doc;
    this.Distrito = distrito;
    this.UsuarioName = usuarioName;
    this.UsuarioEmail = usuarioEmail;
    this.UsuarioPassword = userPass;
    this.Rol = usuario_rol;
    this.Url_APP = url_;
    this.Sede = sede_;
  }

  save() {
 
    let dataForm = new FormData();
    dataForm.append("documento", this.Documento.val());
    dataForm.append("name", this.UsuarioName.val());
    dataForm.append("email", this.UsuarioEmail.val());
    dataForm.append("password", this.UsuarioPassword.val());
    dataForm.append("rol", this.Rol.val());
    dataForm.append("apellidos", this.Apellidos.val());
    dataForm.append("nombres", this.Nombres.val());
    dataForm.append("genero", this.Genero.val());
    dataForm.append("direccion", this.Direccion.val());
    dataForm.append("fecha_nac", this.FechaNacimiento.val());
    dataForm.append("tipo_doc", this.TipoDoc.val());
    dataForm.append("distrito", this.Distrito.val());
    dataForm.append("sede",this.Sede.val());
    dataForm.append("token_", TOKEN);
   
    axios({
      method: "POST",
      url: this.Url_APP + "user_gestion/save",
      data: dataForm,
    }).then(function (response) {
      let respuesta = response.data.response;

      if(respuesta instanceof Object)
      {
        let li = '';
        $('#alerta').show(250);

        respuesta.forEach(error => {
            li+= `
            <li>`+error+`</li>
            `;
        });

        $('.ul').html(li)

      }
      else{
        $('#alerta').hide();

          Swal.fire({
            title: "Mensaje del sistema",
            text: "Usuario registrado sin problemas",
            icon: "success",
            target: document.getElementById("modal_user"),
          }).then(function () {
            $("#form_users_create")[0].reset();
            $('#distrito').empty();
            $('#provincia').empty();
            $('#departamento').prop("selectedIndex",0);
            showUsers();
          });
      }
    });
   
  }

 
}
