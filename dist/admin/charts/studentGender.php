<?php
include '../../config/db.php';

// SQL query to get enrollment data by grade level and gender
$sql = "
    SELECT
        sec.grade_level,
        s.gender, 
        COUNT(CASE WHEN se.status = 'Enrolled' THEN 1 END) AS enrollment_status
    FROM
        student_enrollment se
    JOIN 
        students s ON se.student_id = s.student_id
    JOIN 
        sections sec ON se.section = sec.section_id
    WHERE
        se.status = 'Enrolled'
    GROUP BY 
        sec.grade_level, s.gender
    ORDER BY 
        sec.grade_level, s.gender;
";

$result = $connection->query($sql);

// Prepare data for the chart
$grades = [];
$maleCounts = [];
$femaleCounts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $grade = $row['grade_level'];
        $gender = $row['gender'];
        $count = $row['enrollment_status'];

        if (!in_array($grade, $grades)) {
            $grades[] = $grade;
        }

        if ($gender === 'Male') {
            $maleCounts[$grade] = $count;
        } elseif ($gender === 'Female') {
            $femaleCounts[$grade] = $count;
        }
    }
}

// Ensure all grades have counts (fill missing data with 0)
foreach ($grades as $grade) {
    $maleCounts[$grade] = $maleCounts[$grade] ?? 0;
    $femaleCounts[$grade] = $femaleCounts[$grade] ?? 0;
}

// Pass data to JavaScript
$gradesJSON = json_encode($grades);
$maleCountsJSON = json_encode(array_values($maleCounts));
$femaleCountsJSON = json_encode(array_values($femaleCounts));

?>

<div id="barchart"></div>

<script>

   var gradeLevels = <?php echo $gradesJSON; ?>;
   var maleCounts = <?php echo $maleCountsJSON; ?>;
   var femaleCounts = <?php echo $femaleCountsJSON; ?>;



   var options = {
          series: [{
          name: 'Male',
          data: maleCounts
        }, {
          name: 'Female',
          data: femaleCounts
        }],
          chart: {
          type: 'bar',
          height: 350
        },
        colors: ['#3b82f6', '#38bdf8'], // Tailwind CSS 'blue-500' and 'yellow-400' hex values
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
          categories: gradeLevels,
          title: {
                    text: 'Grade Levels'
                }
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
              return val + " students";
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#barchart"), options);
        chart.render();
</script>
