filter();

function filter() {
    let value = $('#filter').val();

    if(value != 'TO') {
        $('#div_start_date').show();
        $('#div_end_date').show();

        const date = new Date();
        let year = date.getFullYear().toString();
        let month = date.getMonth() + 1;
        const lastDay = new Date(year, month, 0).getDate().toString();

        month = (month < 10) ? ('0' + month) : month.toString();

        $('#start_date').val(year + '-' + month + '-01');
        $('#end_date').val(year + '-' + month + '-' + lastDay);
    }
    else {
        $('#div_start_date').hide();
        $('#div_end_date').hide();
    }

    $.ajax({
        url: './myInvoicesTable',
        data: {filter: value},
        success: function(data) {
            $('#my_invoices_table').html(data);
        }
    });
}