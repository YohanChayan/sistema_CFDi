var invoice_id = -1;

filter();

function validatePayment() {
    let errors = 0;

    if($('#date').val() == '') {
        $('#date').addClass('is-invalid');
        $('#error_date').text('Ingresa una fecha');
        errors++;
    }
    else {
        $('#date').removeClass('is-invalid');
        $('#error_date').text('');
    }

    if($('#payment').val() == '') {
        $('#payment').addClass('is-invalid');
        $('#error_payment').text('Ingresa una cantidad');
        errors++;
    }
    else {
        $('#payment').removeClass('is-invalid');
        $('#error_payment').text('');
    }

    if($('#payment_method').val() == '-1') {
        $('#payment_method').addClass('is-invalid');
        $('#error_payment_method').text('Selecciona un método');
        errors++;
    }
    else {
        $('#payment_method').removeClass('is-invalid');
        $('#error_payment_method').text('');
    }

    if($('#receipt').val() == '') {
        $('#receipt').addClass('is-invalid');
        $('#error_receipt').text('Carga tu comprobante');
        errors++;
    }
    else {
        $('#receipt').removeClass('is-invalid');
        $('#error_receipt').text('');
    }

    return errors;
}

function validate() {
    let errors = 0;

    if($('#start_date').val() == '') {
        $('#start_date').addClass('is-invalid');
        $('#error_start_date').text('Ingresa una fecha');
        errors++;
    }
    else {
        $('#start_date').removeClass('is-invalid');
        $('#error_start_date').text('');
    }

    if($('#end_date').val() == '') {
        $('#end_date').addClass('is-invalid');
        $('#error_end_date').text('Ingresa una fecha');
        errors++;
    }
    else {
        $('#end_date').removeClass('is-invalid');
        $('#error_end_date').text('');
    }

    if(errors == 0) {
        if($('#start_date').val() > $('#end_date').val()) {
            $('#start_date').addClass('is-invalid');
            $('#error_start_date').text('La fecha de inicio es inválida');
            errors++;
        }
        else {
            $('#start_date').removeClass('is-invalid');
            $('#error_start_date').text('');
        }
    }

    return errors;
}

function changeOwner() {
    let owner = datalist_id('owner', 'owners_list');
    $.ajax({
        'url': './providersDatalist',
        data: {owner: owner},
        success: function(data) {
            if(owner == -1) {
                $('#provider').val('');
                $('#providers_list').html('');
            }
            else {
                $('#providers_list').html(data);
            }
            filter();
        }
    });
}

function filter() {
    let owner = datalist_id('owner', 'owners_list');
    let provider = datalist_id('provider', 'providers_list');

    if(validate() == 0) {
        $('#my_spinner').css('display', 'inline-block');
        $('#my_invoices_table').html('');
        $.ajax({
            url: './invoicesTable',
            data: {
                owner: owner,
                provider: provider
            },
            success: function(data) {
                $('#my_invoices_table').html(data);
                $('#my_spinner').css('display', 'none');
            },
            error: function(data) {
                Swal.fire(
                    'Error',
                    'Hubo un error, por favor intenta de nuevo o contacta al desarrollador.',
                    'error'
                );
            }
        });
    }
}

function modalPayment(id) {
    $('#prov_id').val(id);

    invoice_id = id;
    $.ajax({
        url: './modalPayment',
        data: {id: id},
        success: function(data) {
            $('#paymentsHistory').html(data);
        }
    });
}

document.querySelector('#formNewPayment').addEventListener('submit', function(e) {
    e.preventDefault();
    if(validatePayment() == 0) {
        $.ajax({
            url: './addPayment',
            type: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data){
                if(data == 1) {
                    cleanPayment();
                    modalPayment(invoice_id);
                    Swal.fire(
                        'Éxito',
                        'Pago registrado correctamente.',
                        'success'
                    );
                }
                else {
                    Swal.fire(
                        'Error',
                        'No es posible agregar una cantidad mayor a la del saldo pendiente.',
                        'error'
                    );
                }
            }
        });
    }
});

function cleanPayment() {
    $('#date').val('');
    $('#payment').val('');
    $('#payment_method').val('-1');
    $('#receipt').val('');
}

function modalFile(id) {
    invoice_id = id;
}

function downloadFile() {
    let option = $('input[name=option]:checked').val();
    if(option != null || option != undefined) {
        window.location.href = './downloadfile/' + invoice_id + '?option=' + option;
    }
    else {
        Swal.fire(
            'Advertencia',
            'Debes de seleccionar una opción.',
            'warning'
        );
    }
}

function resendEmail(id) {
    window.location.href = './resendEmail/' + id;
}

function deleteInvoice(btn) {
    let button_id = btn.id;
    let id = button_id.substring(7);

    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará está factura',
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

function datalist_id(datalist, lista){
    var id = document.getElementById(datalist).value;
    var findId = -1;
    $('#' + lista + '> option').each(function() {
        if ($(this).attr("value") == id) {
            findId = $(this).attr("id");
            return findId;
        }
    });
    return findId;
}