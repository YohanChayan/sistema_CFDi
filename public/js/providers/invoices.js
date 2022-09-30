filter();

function filter() {
    let value = $('#filter').val();

    $.ajax({
        url: './myInvoicesTable',
        data: {filter: value},
        success: function(data) {
            $('#my_invoices_table').html(data);
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