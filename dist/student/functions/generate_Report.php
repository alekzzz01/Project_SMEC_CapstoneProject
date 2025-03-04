<?php 


include '../../../config/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure it's an integer to prevent SQL injection

    // Fetch PDF blob using the unique report_id
    $sql = "SELECT pdf_blob FROM student_grade_reports WHERE report_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($pdf_blob);
    $stmt->fetch();

    if ($pdf_blob) {
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=report.pdf");
        echo $pdf_blob;
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid request.";
}


?>