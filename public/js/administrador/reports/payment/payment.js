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
        $.ajax({
            url: './paymentsTable',
            data: {
                owner: owner,
                start_date: start_date,
                end_date: end_date
            },
            success: function(data) {
                $('#my_payments_table').html(data);
            }
        });
    }
}

function generatePDF() {
    let owner = $("#owner").val();
    let start_date = $("#start_date").val();
    let end_date = $("#end_date").val();
    window.open('./paymentsPDFReport?owner=' + owner + '&start_date=' + start_date + '&end_date=' + end_date, '_blank');
}