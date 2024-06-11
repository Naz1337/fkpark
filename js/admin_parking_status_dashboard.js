import ApexCharts from 'apexcharts';

// fetch data from 'parking_count.php' and return type as json
// Create Pie chart from the data 

fetch('./parking_status_count.php').then(response => response.json()).then(data => {
    let bar = {
        series: data,
        chart: {
            height: 350,
            type: 'bar',
            events: {
                click: function (chart, w, e) {
                    // console.log(chart, w, e)
                }
            }
        },
        colors: colors,
        plotOptions: {
            bar: {
                columnWidth: '45%',
                distributed: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        xaxis: {
            categories: [
                'Open',
                'Closed',
                ['Under', 'Maintenance'],
            ],
            labels: {
                style: {
                    colors: colors,
                    fontSize: '12px'
                }
            }
        }
    };

    let barchart = new ApexCharts(document.getElementById("parkingStatus"), bar);
    barchart.render();

});