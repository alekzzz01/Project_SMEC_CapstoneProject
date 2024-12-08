<?php

include '../../config/db.php';

$sql = "
  SELECT role from users
";

$result = $connection->query($sql);

$student = [];
$teacher = [];
$admin = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $role = $row['role'];

        if ($role === 'student') {
            $student[] = $role;
        } elseif ($role === 'teacher') {
            $teacher[] = $role;
        } elseif ($role === 'admin') {
            $admin[] = $role;
        }

       
    }


  }


  $studentCount = count($student);
  $teacherCount = count($teacher);
  $adminCount = count($admin);



?>



<div id="donutchart"></div>

<script>


      var studentCount = <?php echo $studentCount; ?>;
      var teacherCount = <?php echo $teacherCount; ?>;
      var adminCount = <?php echo $adminCount; ?>;
          
      var options = {
          series: [studentCount, teacherCount, adminCount],
          chart: {
          type: 'donut',
        },
        labels: ['Student', 'Teacher' , 'Employees'],
        colors: ['#3B82F6', '#38bdf8' , '#67e8f9'], 
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