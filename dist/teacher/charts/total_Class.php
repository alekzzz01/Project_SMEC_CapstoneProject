<div id="chart"></div>


<style>
  #chart {
    max-width: 100%;
    height: auto;
  }
</style>

<script>

var options = {
          series: [
          {
            name: 'Actual',
            data: [
             
          
              {
                x: '2017',
                y: 7332,
                goals: [
                  {
                    name: 'Expected',
                    value: 8700,
                    strokeHeight: 5,
                    strokeColor: '#775DD0'
                  }
                ]
              },
              {
                x: '2018',
                y: 6553,
                goals: [
                  {
                    name: 'Expected',
                    value: 7300,
                    strokeHeight: 2,
                    strokeDashArray: 2,
                    strokeColor: '#775DD0'
                  }
                ]
              }
            ]
          }
        ],
          chart: {
          height: "auto",
          type: 'bar',
          toolbar: {
            show: false
         }
          
        },
        plotOptions: {
          bar: {
            columnWidth: '10%'
          }
        },
        colors: ['#00E396'],
        dataLabels: {
          enabled: false
        },
        legend: {
          show: true,
          showForSingleSeries: true,
          customLegendItems: ['Actual', 'Expected'],
          markers: {
            fillColors: ['#00E396', '#775DD0']
          }
        }, 

        yaxis: {
            show: false,
            showAlways: false,
            
        },

        grid: {
        show: false 
        }
        
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      


</script>