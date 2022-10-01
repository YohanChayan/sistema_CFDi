function validate() {
    let errors = 0;

    if($('#rfc').val() == '') {
        $('#rfc').addClass('is-invalid');
        $('#error_rfc').text('Ingresa un rfc válido');
        errors++;
    }
    else {
        $('#rfc').removeClass('is-invalid');
        $('#error_rfc').text('');
    }

    if($('#rfc').val().length < 12 || $('#rfc').val().length > 13) {
        $('#rfc').addClass('is-invalid');
        $('#error_rfc').text('Ingresa un rfc válido');
        errors++;
    }
    else {
        $('#rfc').removeClass('is-invalid');
        $('#error_rfc').text('');
    }

    if($('#name').val() == '') {
        $('#name').addClass('is-invalid');
        $('#error_name').text('Ingresa un nombre');
        errors++;
    }
    else {
        $('#name').removeClass('is-invalid');
        $('#error_name').text('');
    }

    return errors;
}

function registerOwner(btn) {
    if(validate() == 0) {
        btn.disabled = true;
        $('#createOwnerForm').submit();
    }
}

function deleteOwner(btn) {
    let btn_id = btn.id;
    let id = btn_id.substring(7);
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará esta empresa',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if(result.isConfirmed) {
            window.location.href = './delete/' + id;
        }
    })
}