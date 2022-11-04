filter();

function filter() {
    let value = $('#filter').val();

    $('#my_spinner').css('display', 'inline-block');
    $('#my_invoices_table').html('');
    $.ajax({
        url: './myInvoicesTable',
        data: {filter: value},
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

function modalDetails(btn) {
    let btn_id = btn.id;
    let id = btn_id.substring(8);

    $.ajax({
        url: './modalDetails',
        data: {
            id: id,
        },
        success: function(data) {
            $('#invoiceDetailsModal').modal('show');
            $('#invoiceDetailsModalContent').html(data);
        }
    });
}