changeProvider();

function changeOwner() {
    let owner = datalist_id('owner', 'owners_list');
    $.ajax({
        'url': './providersDatalist',
        data: {owner: owner},
        success: function(data) {
            if(owner == -1) {
                $('#provider').val('');
                $('#providers_list').html('');
                changeProvider();
            }
            else {
                $('#providers_list').html(data);
            }
        }
    });
}

function changeProvider() {
    let owner = datalist_id('owner', 'owners_list');
    let provider = datalist_id('provider', 'providers_list');

    $.ajax({
        'url': './pendingPaymentsTable',
        data: {
            owner: owner,
            provider: provider
        },
        success: function(data) {
            if(provider == -1) {
                changeAlert('warning');
            }
            else {
                changeAlert('success');
            }
            $('#table_pending_payments').html(data);
        }
    });
}

function changeAlert(type) {
    if(type == 'warning') {
        $('#div_alert').removeClass('alert-success');
        $('#div_alert').addClass('alert-warning');
        $('#icon_alert').removeClass('fa-check');
        $('#icon_alert').addClass('fa-exclamation-circle');
        $('#text_alert').text('Ingrese el RFC de su empresa y del proveedor');
    }
    else {
        $('#div_alert').removeClass('alert-warning');
        $('#div_alert').addClass('alert-success');
        $('#icon_alert').removeClass('fa-exclamation-circle');
        $('#icon_alert').addClass('fa-check');
        $('#text_alert').text('Facturas filtradas por empresa y proveedor');
    }
}

function payment() {
    let payments = JSON.parse($('#paymentsFiltered').val());
    let sum = 0;

    for(let i = 0; i < payments.length; i++) {
        if($('#payment_' + payments[i]['id']).val() != '') {
            sum += parseFloat($('#payment_' + payments[i]['id']).val());
        }
    }

    $('#total').text('$' + sum.toFixed(2));
}

function saveAll() {
    let payments = JSON.parse($('#paymentsFiltered').val());
    let pendingPayments = [];
    let emptyInput = false;
    let higherPayment = false;
    let cont = 0;
    let errores = 0;

    if($('#date').val() == '') {
        $('#date').addClass('is-invalid');
        $('#error_date').text('La fecha es inválida');
        errores++;
    }
    if($('#filePayment').val() == '') {
        $('#filePayment').addClass('is-invalid');
        $('#error_filePayment').text('Es necesario subir un archivo');
        errores++;
    }
    if(errores > 0) {
        Swal.fire(
            'Advertencia',
            'Es necesario llenar los campos obligatorios',
            'warning'
        );
    }
    else {
        $('#date').removeClass('is-invalid');
        $('#filePayment').removeClass('is-invalid');
        $('#error_date').text('');
        $('#error_filePayment').text('');

        for(let i = 0; i < payments.length; i++) {
            let id = payments[i]['id'];
            // let date = $('#date_' + id).val();
            let payment_method = $('#payment_method_' + id).val();
            let payment = $('#payment_' + id).val();

            let pendingMoney = payments[i]['total'];
            for(let j = 0; j < payments[i]['payments'].length; j++) {
                pendingMoney -= payments[i]['payments'][j]['payment'];
            }

            if(payment == '') {
                emptyInput = true;
                cont++;
            }
            else if(pendingMoney != 0 && payment > pendingMoney) {
                higherPayment = true;
                break;
            }
            else {
                pendingPayments.push({
                    'invoice_id': id,
                    'payment_method': payment_method,
                    'payment': payment
                });
            }
        }

        if(cont == payments.length) {
            Swal.fire(
                'Error',
                'Debes ingresar los datos de al menos una factura que quieras guardar.',
                'error'
            );
        }
        else if(higherPayment) {
            Swal.fire(
                'Error',
                'No es posible ingresar una cantidad mayor a la que queda en el saldo.',
                'error'
            );
        }
        else if(emptyInput) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Solo de guardarán los registros que tengan una fecha y un monto de pago asignados.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if(result.isConfirmed) {
                    $('#pendingPayments').val(JSON.stringify(pendingPayments));
                    $('#paymentsForm').submit();
                }
            });
        }
        else {
            $('#pendingPayments').val(JSON.stringify(pendingPayments));
            $('#paymentsForm').submit();
        }
    }
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


