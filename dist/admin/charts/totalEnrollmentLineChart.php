<?php

include '../../config/db.php';


$sql = "
    SELECT 
    sy.school_year,
    COUNT(se.enrollment_id) AS enrollment_count

    FROM 
        student_enrollment se
    JOIN 
        school_year sy ON se.school_year_id = sy.school_year_id

    GROUP BY 
        sy.school_year
    ORDER BY 
        sy.school_year ASC

";
$result = $connection->query($sql);

// Initialize data arrays
$schoolYears = [];
$enrollmentCounts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schoolYears[] = $row['school_year']; // School years for x-axis
        $enrollmentCounts[] = $row['enrollment_count']; // Enrollment counts for y-axis
    }
}

$connection->close();



?>
<div id="linechart"></div>

<script>
    // Pass PHP data to JavaScript
    const schoolYears = <?php echo json_encode($schoolYears); ?>;
    const enrollmentCounts = <?php echo json_encode($enrollmentCounts); ?>;

    // Chart options
    var options = {
        series: [{
            name: "Enrollments",
            data: enrollmentCounts // Enrollment numbers
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        title: {
            text: 'Enrollment Numbers by School Year',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        xaxis: {
            categories: schoolYears, // School years
            title: {
                text: 'School Year'
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#linechart"), options);
    chart.render();
</script>