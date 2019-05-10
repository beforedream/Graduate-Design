$(document).ready(function () {
    /**
     * Line Chart
     */

    var time = eval($('#time').val());
    var amount = eval($('#amount').val());
    var lineChart = $('#line-chart');
    while(time.length < 12){
        time.push('暂无数据');
    }
    while(amount.length < 12){
        amount.push(0);
    }

    if (lineChart.length > 0) {
        if(time.length > 0){
            new Chart(lineChart, {
                type: 'line',
                data: {
                    labels: time.slice(-12),
                    datasets: [{
                        label: 'salary amount ￥',
                        data: amount.slice(-12),
                        backgroundColor: 'rgba(66, 165, 245, 0.5)',
                        borderColor: '#2196F3',
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    }
});
