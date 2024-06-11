import ApexCharts from 'apexcharts';

// fetch data from './online_offline_count.php' and return type is json
// then do apex chart, pie chart

fetch('./online_offline_count.php').then(response => response.json()).then(data => {
    let options = {
        series: data,
        labels: ['Online', 'Offline'],
        chart: {
            type: 'pie',
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    let chart = new ApexCharts(document.getElementById("onlineOfflinePie"), options);
    chart.render();

} );