var invoice_id = -1;

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