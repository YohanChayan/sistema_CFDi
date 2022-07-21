var rfc = '';

$("#xml_input").change(function() {
    let file = document.getElementById("xml_input").files[0];
    let reader = new FileReader();
    let exp = /<cfdi:emisor(.*)>/;
    let exp2 = /rfc="(.*?)"/;
    
    reader.readAsText(file);
    reader.onloadend = function() {
        let xmlData = $(reader.result);
        let data = '';

        for(let i = 0; i < xmlData.length; i++) {
            if(typeof xmlData[i]['outerHTML'] !== 'undefined') {
                data = xmlData[i]['outerHTML'];
            }
        }

        let result = data.match(exp);
        let result2 = result[0].match(exp2);
        rfc = result2[1];
        console.log(rfc);
    };
});

function cancelCreateInvoiceData() {
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
                if(data == 1) {
                    $('#formulario').submit();
                }
                else {
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