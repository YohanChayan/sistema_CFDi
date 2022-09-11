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
    let start_date = $('#start_date').val();
    let end_date = $('#end_date').val();

    if(validate() == 0) {
        $.ajax({
            url: './myPaymentsTable',
            data: {
                start_date: start_date,
                end_date: end_date
            },
            success: function(data) {
                $('#my_payments_table').html(data);
            }
        });
    }
}

function paymentPreview(id) {
    $.ajax({
        url: './preview',
        data: {id: id},
        success: function(response){
            if(response == 'No'){
                document.querySelector('#imgPreviewContainer').innerHTML = '';
                const p = document.createElement('p');
                p.classList.add('text-center', 'text-danger');
                p.innerText = 'No existe el comprobante de pago.';
                document.querySelector('#imgPreviewContainer').appendChild(p);
                $('#paymentPreview').modal('toggle');
            }
            else {
                document.querySelector('#imgPreviewContainer').innerHTML = '';
                const img = document.createElement('img');
                img.setAttribute('width', '100%');
                img.setAttribute('height', '500px');
                img.setAttribute('src', response);
                img.classList.add('p-2');
                document.querySelector('#imgPreviewContainer').append(img);
                $('#paymentPreview').modal('toggle');
            }
        }
    })
}
