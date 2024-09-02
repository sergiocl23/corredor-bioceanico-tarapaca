function EnviarCorreoContato(){
    var contacto_nombre = $('#contacto_nombre').val();
    var contacto_email = $('#contacto_email').val();
    var contacto_telefono = $('#contacto_telefono').val();
    var contacto_asunto = $('#contacto_asunto').val();
    var contacto_mensaje = $('#contacto_mensaje').val();

    var data = {
        accion: 'enviar_correo_contacto',
        var1: contacto_nombre,
        var2: contacto_email,
        var3: contacto_telefono,
        var4: contacto_asunto,
        var5: contacto_mensaje,
    };
    showFormLoader(true)
    $.post('../negocios/correoContacto.php', data, function(response) {
        if(response['status'] == 'error_formulario'){
            showFormLoader(false);
            Swal.fire({
                title: "Incorrecto!",
                icon: "error",
                html: response['message'],
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: "rgba(54, 169, 225, 1)",
            });
            return;
        }
        showFormLoader(false);
        Swal.fire({
            title: "Correcto!",
            icon: "success",
            html: response['message'],
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: "rgba(54, 169, 225, 1)",
        });
        $('#contacto_nombre').val('');
        $('#contacto_email').val('');
        $('#contacto_telefono').val('');
        $('#contacto_asunto').val('');
        $('#contacto_mensaje').val('');
    }, 'json')
    
}

function showFormLoader(show){
    if(show){
        $('.container-contact-us-form').addClass('on-blur');
        $('#loading').removeClass('d-none');
        return;
    }
    $('.container-contact-us-form').removeClass('on-blur');
    $('#loading').addClass('d-none');
    return;

}