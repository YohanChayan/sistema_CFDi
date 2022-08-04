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