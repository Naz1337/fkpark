import ApexCharts from 'apexcharts';

// fetch data from 'parking_count.php' and return type as json
// Create Pie chart from the data 

fetch('./parking_count.php').then(response => response.json()).then(data => {
    let options = {
        series: data,
        labels: ['Available Parking Spaces', 'Not Available Parking Spaces'],
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

    let chart = new ApexCharts(document.getElementById("parkingPie"), options);
    chart.render();

});