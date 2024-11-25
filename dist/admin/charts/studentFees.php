<div id="barchart"></div>

<script>
   var options = {
          series: [{
          name: 'Received Payments',
          data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 90]
        }, {
          name: 'Pending Payments',
          data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 82]
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        colors: ['#3B82F6', '#eff6ff'], // Tailwind CSS 'blue-500' and 'yellow-400' hex values
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
        },
        yaxis: {
          title: {
            text: '$ (thousands)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#barchart"), options);
        chart.render();
</script>
