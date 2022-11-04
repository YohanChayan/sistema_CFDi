filter();

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
        $('#my_spinner').css('display', 'inline-block');
        $('#my_invoices_table').html('');
        $.ajax({
            url: './invoicesTable',
            data: {
                owner: owner,
                start_date: start_date,
                end_date: end_date
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

function generatePDF() {
    let owner = $("#owner").val();
    let start_date = $("#start_date").val();
    let end_date = $("#end_date").val();
    window.open('./invoicesPDFReport?owner=' + owner + '&start_date=' + start_date + '&end_date=' + end_date, '_blank');
}