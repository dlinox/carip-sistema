var ctx = $('#salesChart');

let myChart1;

let paletaColores = [
    'rgba(154,238,219,.5)',
    'rgba(118,136,260,.5)',
    'rgba(205,242,229,.5)',
    'rgba(206,230,240,.5)',
    'rgba(150,205,235,.5)',
    
]

let salesChartData = {};
let dataChart = [];
let labelChart = [];
var salesChartOptions = {
    // Boolean - If we should show the scale at all
    showScale: true,
    // Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines: false,
    // String - Colour of the grid lines
    scaleGridLineColor: 'rgba(0,200,0,.05)',
    // Number - Width of the grid lines
    scaleGridLineWidth: 1,
    // Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    // Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,
    // Boolean - Whether the line is curved between points
    bezierCurve: true,
    // Number - Tension of the bezier curve between points
    bezierCurveTension: 0.3,
    // Boolean - Whether to show a dot for each point
    pointDot: false,
    // Number - Radius of each point dot in pixels
    pointDotRadius: 4,
    // Number - Pixel width of point dot stroke
    pointDotStrokeWidth: 1,
    // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius: 20,
    // Boolean - Whether to show a stroke for datasets
    datasetStroke: true,
    // Number - Pixel width of dataset stroke
    datasetStrokeWidth: 2,
    // Boolean - Whether to fill the dataset with a color
    datasetFill: true,
    // String - A legend template
    legendTemplate: '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: true,
    // Boolean - whether to make the chart responsive to window resizing
    responsive: true
};

$(document).ready(function () {
    GetDataChart();
})

function GetDataChart() {
    if (myChart1) {
        myChart1.destroy();
    }

    $.ajax({
        dataType: "json",
        method: "GET",
        url: baseurl + "Contabilidad/getVentasAnio",
        success: function (resp) {
            console.log(resp.data);
            let _labels = [];
            Object.keys(resp.data).forEach( (key, i) => {
                let _data = [];
                _labels = []
                 resp.data[key].forEach(val => {
                    _data.push(val.total_mes);
                    _labels.push(val.mes_nombre);
                });
                dataChart.push({
                    fill: true,
                    label: key,
                    backgroundColor: paletaColores[i],
                    data: _data
                })

            });
            labelChart = _labels;
            salesChartData = {
                labels: labelChart,
                datasets: dataChart
            }
        },
        complete: () => {
            myChart1 = new Chart(ctx, {
                type: 'line',
                data: salesChartData,
                options: salesChartOptions
            });
        }
    });
}
