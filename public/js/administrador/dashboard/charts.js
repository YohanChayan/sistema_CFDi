function load_top_providers(data){
    const providers_vs_invoices = data;
    const arrayLabel_SingleBar = [];
    const arrayData_SingleBar = [];

    let i = 0;
    for( p of providers_vs_invoices){
        arrayLabel_SingleBar[i] = p['nombre_proveedor'];
        arrayData_SingleBar[i] = p['facturas'];
        i++;
    }

    // Single Bar Chart
    var ctx4 = $("#bar-chart").get(0).getContext("2d");
    var myChart4 = new Chart(ctx4, {
        type: "bar",
        data: {
            labels: ["#1", "#2", "#3", "#4", "#5"],
            // labels: arrayLabel_SingleBar,
            datasets: [{
                label: "Facturas",
                backgroundColor: [
                    "rgba(0, 156, 255, .7)",
                    "rgba(0, 156, 255, .6)",
                    "rgba(0, 156, 255, .5)",
                    "rgba(0, 156, 255, .4)",
                    "rgba(0, 156, 255, .3)"
                ],
                data: arrayData_SingleBar
            }]
        },
        options: {
            responsive: true,
            plugins:{
                tooltip:{
                    callbacks:{
                        title: function(context){
                            return ` ${arrayLabel_SingleBar[context[0].dataIndex]} `;
                        }
                    }
                }
            }
        }
    });

}

function load_invoices_months(data){

    const arrayMonthsLabel = [
    'Enero',
    'Febrero',
    'Marzo',
    'Abril',
    'Mayo',
    'Junio',
    'Julio',
    'Agosto',
    'Septiembre',
    'Octubre',
    'Noviembre',
    'Diciembre'];

    const invoices_vs_months = data

    // Single Line Chart
    var ctx3 = $("#line-chart").get(0).getContext("2d");
    var myChart3 = new Chart(ctx3, {
        type: "line",
        data: {
            // labels: [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150],
            labels: arrayMonthsLabel,
            datasets: [{
                label: "Facturas",
                fill: false,
                backgroundColor: "rgba(0, 156, 255, .3)",
                // data: [7, 8, 8, 9, 9, 9, 10, 11, 14, 14, 11]
                data: invoices_vs_months
            }]
        },
        options: {
            responsive: true
        },
        scales:{
            y:{
                beginAtZero: true
            }
        }
    });

}
