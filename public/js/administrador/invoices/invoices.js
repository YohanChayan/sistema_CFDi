var invoice_id = -1;

filter();

function validatePayment() {
    let errors = 0;

    if($('#date').val() == '') {
        $('#date').addClass('is-invalid');
        errors++;
    }
    else {
        $('#date').removeClass('is-invalid');
    }

    if($('#payment').val() == '') {
        $('#payment').addClass('is-invalid');
        errors++;
    }
    else {
        $('#payment').removeClass('is-invalid');
    }

    if($('#payment_method').val() == '-1') {
        $('#payment_method').addClass('is-invalid');
        errors++;
    }
    else {
        $('#payment_method').removeClass('is-invalid');
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
        $.ajax({
            url: './invoicesTable',
            data: {
                owner: owner,
                provider: provider
            },
            success: function(data) {
                $('#my_invoices_table').html(data);
            }
        });
    }
}

function modalPayment(id) {
    invoice_id = id;
    $.ajax({
        url: './modalPayment',
        data: {id: id},
        success: function(data) {
            $('#paymentsHistory').html(data);
        }
    });
}

$('#addPaymentBtn').on('click', addPayment);

function addPayment() {
    if(validatePayment() == 0) {
        let date = $('#date').val();
        let amount = $('#payment').val();
        let payment_method = $('#payment_method').val();
        $.ajax({
            url: './addPayment',
            data: {
                id: invoice_id,
                date: date,
                amount: amount,
                payment_method: payment_method,
            },
            success: function(data) {
                if(data == 1) {
                    cleanPayment();
                    modalPayment(invoice_id);
                    Swal.fire('Éxito', 'Pago registrado correctamente.', 'success');
                }
                else {
                    Swal.fire('Error', 'No es posible agregar una cantidad mayor a la del saldo pendiente.', 'error');
                }
            }
        });
    }
}

function cleanPayment() {
    $('#date').val('');
    $('#payment').val('');
    $('#payment_method').val('-1');
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