<?php
session_start();
include '../../config/db.php';

// Initialize variables for event data
$event_name = $event_schedule = $event_venue = $event_type = $event_description = $banner_image = '';

// Check if event_id is provided in the GET request
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // SQL query to fetch event details
    $sql = "SELECT event_id, event_name, event_date, venue, event_type, description, banner FROM events WHERE event_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the event exists
    if ($result->num_rows > 0) {
        // Fetch the event data
        $event = $result->fetch_assoc();
        $event_name = $event['event_name'];
        $event_schedule = $event['event_date']; // Assuming event_date is in 'Y-m-d H:i:s' format
        $event_venue = $event['venue'];
        $event_type = $event['event_type'];
        $event_description = $event['description'];
        $banner_image = $event['banner']; // Assuming banner is stored as a binary
    } else {
        echo json_encode(["error" => "Event not found"]);
    }

} else {
    echo json_encode(["error" => "Event ID not provided"]);
}

// Check if the 'editEvent' button is pressed
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editEvent'])) {
    // Get form data
    $event_id = $_POST['event_id'];
    $event_name = $_POST['eventName'];
    $event_schedule = $_POST['eventSchedule'];
    $event_venue = $_POST['eventVenue'];
    $event_type = $_POST['eventType'];
    $event_description = $_POST['eventDescription'];

    // Handle banner image upload (optional)
    if (isset($_FILES['banner-Image']) && $_FILES['banner-Image']['error'] == UPLOAD_ERR_OK) {
        // Check file type and size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['banner-Image']['tmp_name']);
        $file_size = $_FILES['banner-Image']['size'];

        if (in_array($file_type, $allowed_types) && $file_size <= 5 * 1024 * 1024) { // 5MB limit
            $banner_image = file_get_contents($_FILES['banner-Image']['tmp_name']);
        } else {
            $_SESSION['error'] = "Invalid file type or size. Only JPEG, PNG, and GIF files are allowed, and size must not exceed 5MB.";
            header('Location: ' . $_SERVER['PHP_SELF'] . "?event_id=" . $event_id);
            exit();
        }

        // SQL query to update the event, including the banner
        $sql = "UPDATE events SET 
                    event_name = ?, 
                    event_date = ?, 
                    venue = ?, 
                    event_type = ?, 
                    description = ?, 
                    banner = ? 
                WHERE event_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssssi", $event_name, $event_schedule, $event_venue, $event_type, $event_description, $banner_image, $event_id);
    } else {
        // No new file uploaded, retain existing banner
        $sql = "UPDATE events SET 
                    event_name = ?, 
                    event_date = ?, 
                    venue = ?, 
                    event_type = ?, 
                    description = ? 
                WHERE event_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssssi", $event_name, $event_schedule, $event_venue, $event_type, $event_description, $event_id);
    }

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['message'] = "Event updated successfully!";
        header("Location: " . $_SERVER['PHP_SELF'] . "?event_id=" . $event_id);
        exit();
    } else {
        $_SESSION['error'] = "Error updating event: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.css" rel="stylesheet">


    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    
    <html data-theme="light"></html>

</head>
<body class="flex">
    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">

    <?php include('./components/navbar.php'); ?>

            <div class="p-7 bg-gray-50 h-full">

                <?php if (isset($_SESSION['message'])): ?>
                <div class="rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10  mb-7"><?= $_SESSION['message']; ?></div>
                <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10  mb-7" ><?= $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                  
                <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="campusActivities.php">Campus Activities</a></li>
                    <li>Event</li>
                </ul>
                </div>

                <div class=" p-6 bg-white rounded-md mt-7">
                    <h3 class="text-lg font-bold">Edit Event</h3>

                    <form action="" method="POST" class="py-4 flex flex-col gap-3" enctype="multipart/form-data">
                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-6 block">Banner Image</label>
                             <!-- Show current banner image -->
                             <?php
                            // Check if there is a banner image
                            if ($banner_image) {
                                // If a banner image exists, display it
                                echo '<img src="data:image/jpeg;base64,'.base64_encode($banner_image).'" alt="Event Banner" class="w-full h-60 object-cover rounded-md mb-6 ">';
                            } else {
                                // If there is no banner, display a message
                                echo '<p class="mb-6">No banner available for this event.</p>';
                            }
                            ?>
                            <input name="banner-Image" type="file" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Event Name</label>
                            <input name="eventName" type="text" value="<?php echo $event_name; ?>" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Event Name" />
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Schedule</label>
                            <input name="eventSchedule" type="datetime-local" value="<?php echo date('Y-m-d\TH:i', strtotime($event_schedule)); ?>" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Venue</label>
                            <input name="eventVenue" type="text" value="<?php echo $event_venue; ?>" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Venue" />
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Type</label>
                            <select name="eventType" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                <option value="Public" <?php echo $event_type == 'Public' ? 'selected' : ''; ?>>Public</option>
                                <option value="Private" <?php echo $event_type == 'Private' ? 'selected' : ''; ?>>Private</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium mb-2 block">Description</label>
                            <div id="editorEdit"></div>
                            <input type="hidden" name="eventDescription" value="<?php echo htmlspecialchars($event_description); ?>" />
                        </div>

                        <div class="modal-action">
                            <button type="submit" name="editEvent" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Update Event</button>
                        </div>
                    </form>

                </div>

        
            </div>





</div>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        var quill = new Quill('#editorEdit', {
            theme: 'snow',
            placeholder: 'Write your description here...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    ['link', 'image', 'code-block'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                ],
            },
        });

        // Set the editor content to the event description
        var descriptionInput = document.querySelector("input[name='eventDescription']");
        quill.root.innerHTML = descriptionInput.value; // Set the content from the hidden input

        // On form submission, append the editor content to a hidden input
        var form = document.querySelector("form");
        form.addEventListener("submit", function () {
            descriptionInput.value = quill.root.innerHTML; // Store editor content
        });
    });
</script>

</body>
</html>
