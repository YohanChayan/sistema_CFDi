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

function filter() {
    let owner = $('#owner').val();
    let start_date = $('#start_date').val();
    let end_date = $('#end_date').val();

    if(validate() == 0) {
        $.ajax({
            url: './invoicesTable',
            data: {
                owner: owner,
                start_date: start_date,
                end_date: end_date
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
        $.ajax({
            url: './addPayment',
            data: {
                id: invoice_id,
                date: date,
                amount: amount,
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