
<div id="donutchart"></div>

<script>
          
          var options = {
          series: [44, 55],
          chart: {
          type: 'donut',
        },
        labels: ['Male', 'Female'],
        colors: ['#3B82F6', '#facc15'], 
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

        var chart = new ApexCharts(document.querySelector("#donutchart"), options);
        chart.render();
      
</script>