function validate() {
    let errors = 0;

    if($('#product').val() == '') {
        $('#product').addClass('is-invalid');
        $('#error_product').text('Ingrese el nombre de un producto');
        errors++;
    }
    else {
        $('#product').removeClass('is-invalid');
        $('#error_product').text('');
    }

    if($('#budget').val() == '') {
        $('#budget').addClass('is-invalid');
        $('#error_budget').text('Ingrese un presupuesto');
        errors++;
    }
    else {
        $('#budget').removeClass('is-invalid');
        $('#error_budget').text('');
    }

    if($('#filter').val() == -1) {
        $('#filter').addClass('is-invalid');
        $('#error_filter').text('Seleccione una opción');
        errors++;
    }
    else {
        $('#filter').removeClass('is-invalid');
        $('#error_filter').text('');
    }

    if($('#location').val() == -1) {
        $('#location').addClass('is-invalid');
        $('#error_location').text('Seleccione una opción');
        errors++;
    }
    else {
        $('#location').removeClass('is-invalid');
        $('#error_location').text('');
    }

    return errors;
}

function quote() {
    if(validate() == 0) {
        let product = $('#product').val();
        let budget = $('#budget').val();
        let filter = $('#filter').val();
        let location = $('#location').val();

        $.ajax({
            url: './infer',
            data: {
                product: product,
                budget: budget,
                filter: filter,
                location: location,
            },
            success: function(data) {
                $('#quote_result').html(data);
            }
        });
    }
}