var rfc = '';
var nombre = '';

$("#xml_input").change(function() {
    let file = document.getElementById("xml_input").files[0];
    let reader = new FileReader();
    let exp_emisor = /<cfdi:emisor(.*)>/;
    let exp_rfc = /rfc="(.*?)"/;
    let exp_nombre = /nombre="(.*?)"/;
    
    reader.readAsText(file);
    reader.onloadend = function() {
        let xmlData = $(reader.result);
        let data = '';

        for(let i = 0; i < xmlData.length; i++) {
            if(typeof xmlData[i]['outerHTML'] !== 'undefined') {
                data = xmlData[i]['outerHTML'];
            }
        }

        let result = data.match(exp_emisor);
        let result_rfc = result[0].match(exp_rfc);
        let result_nombre = result[0].match(exp_nombre);
        rfc = result_rfc[1];
        nombre = result_nombre[1];

        console.log(rfc);
        console.log(nombre);
    };
});

//Registrar factura
function registerCreateInvoiceData() {
    //Obtener los nombres de los archivos
    let pdf_file = $('#pdf_input').val();
    let xml_file = $('#xml_input').val();

    //Obtener la extensión de los archivos
    let pdf_extension = pdf_file.substring(pdf_file.lastIndexOf('.')).toLowerCase();
    let xml_extension = xml_file.substring(xml_file.lastIndexOf('.')).toLowerCase();

    if(pdf_extension == '.pdf' && xml_extension == '.xml') {
        $.ajax({
            url: '/invoice/validateProvider',
            data: {rfc: rfc},
            success: function(data) {
                if(data == 1) {   //Enviar formulario
                    $('#formulario').submit();
                }
                else {   //Abrir modal para crear un nuevo proveedor
                    let myModal = new bootstrap.Modal(document.getElementById('registerModal'));
                    myModal.show();
                }
            }
        });
    }
    else {
        Swal.fire('Advertencia', 'Es necesario que cargues los formatos señalados en el formulario.', 'warning');
    }
}

//Cancelar datos capturados cuando se abre el modal
function cancelDataNewProvider() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se borrarán los datos capturados en esta ventana.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Regresar',
        confirmButtonText: 'Aceptar'
    }).then((result) => {
        if(result.isConfirmed) {
            let myModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
            myModal.hide();

            Swal.fire(
                '¡Operación cancelada!',
                'No se ha realizado ninguna acción.',
                'success'
            );

            $('#password').val('');
            $('#confirm_password').val('');

            $('#pdf_input').val('');
            $('#xml_input').val('');
            $('#other_input').val('');
        }
    });
}

//Registrar nuevo proveedor
function registerDataNewProvider() {
    let password = $('#password').val();

    if(validateDataNewProvider() == 0) {
        $.ajax({
            url: '/invoice/createNewProvider',
            data: {
                rfc: rfc,
                nombre: nombre,
                password: password,
            },
            success: function(data) {
                $('#formulario').submit();
            }
        });
    }
}

//Validar las contraseñas del modal
function validateDataNewProvider() {
    let errors = 0;

    if($('input[name=password]').val() == '') {
        $('#password').addClass('is-invalid');
        $('#error-password').text('Ingresa una contraseña');
        errors++;
    }
    else {
        $('#password').removeClass('is-invalid');
        $('#error-password').text('');
    }

    if($('input[name=confirm_password]').val() == '') {
        $('#confirm_password').addClass('is-invalid');
        $('#error-confirm-password').text('Ingresa una contraseña');
        errors++;
    }
    else {
        $('#confirm_password').removeClass('is-invalid');
        $('#error-confirm-password').text('');
    }

    if(errors == 0) {
        if($('input[name=password]').val() != $('input[name=confirm_password]').val()) {
            $('#password').addClass('is-invalid');
            $('#error-password').text('Las contraseñas no coinciden');
            $('#confirm_password').addClass('is-invalid');
            $('#error-confirm-password').text('Las contraseñas no coinciden');
            errors++;
        }
        else {
            $('#password').removeClass('is-invalid');
            $('#error-password').text('');
            $('#confirm_password').removeClass('is-invalid');
            $('#error-confirm-password').text('');
        }
    }

    return errors;
}