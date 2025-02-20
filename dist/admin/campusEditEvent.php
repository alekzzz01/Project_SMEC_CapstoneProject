<?php
session_start();
include '../../config/db.php';

// Check if event_id is provided in the GET request
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // SQL query to fetch event details
    $sql = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the event exists
    if ($result->num_rows > 0) {
        // Fetch the event data
        $event = $result->fetch_assoc();
    } else {
        echo json_encode(["error" => "Event not found"]);
        exit();
    }
} else {
    echo json_encode(["error" => "Event ID not provided"]);
    exit();
}

// Check if the 'editEvent' button is pressed
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editEvent'])) {
    // Get form data
    $event_id = $_POST['event_id'];
    $event_name = $_POST['eventName'];
    $date_time_from = $_POST['date_time_from'];
    $date_time_to = $_POST['date_time_to'];
    $event_venue = $_POST['eventVenue'];
    $event_type = $_POST['eventType'];
    $event_description = $_POST['eventDescription'];
    $organizer_name = $_POST['organizer_name'];

    // Handle banner image upload
    $bannerImage = $event['banner']; // Retain the existing banner by default
    if (isset($_FILES['banner-Image']) && $_FILES['banner-Image']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['banner-Image']['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file type
        if (in_array($fileType, $allowedTypes)) {
            $sanitizedFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.\-]/", "", $fileName);
            $targetDir = "uploads/";
            $targetFilePath = $targetDir . $sanitizedFileName;

            // Ensure the upload directory exists
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['banner-Image']['tmp_name'], $targetFilePath)) {
                $bannerImage = $targetFilePath;
            } else {
                $_SESSION['error'] = "Failed to upload the file.";
                header("Location: " . $_SERVER['PHP_SELF'] . "?event_id=" . $event_id);
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            header("Location: " . $_SERVER['PHP_SELF'] . "?event_id=" . $event_id);
            exit();
        }
    }

    // SQL query to update the event
    $sql = "UPDATE events SET 
                event_name = ?, 
                date_time_from = ?,
                date_time_to = ?,
                venue = ?, 
                event_type = ?, 
                description = ?,
                organizer_name = ?,
                banner = ?
            WHERE event_id = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssssssi", $event_name, $date_time_from, $date_time_to, $event_venue, $event_type, $event_description, $organizer_name, $bannerImage, $event_id);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['message'] = "Event updated successfully!";
        header("Location: " . $_SERVER['PHP_SELF'] . "?event_id=" . $event_id);
        exit();
    } else {
        $_SESSION['error'] = "Error updating event: " . $stmt->error;
        header("Location: " . $_SERVER['PHP_SELF'] . "?event_id=" . $event_id);
        exit();
    }

    // Close the statement and connection
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


    <html data-theme="light">

    </html>

</head>

<body class="flex min-h-screen">


    <?php include('./components/sidebar.php'); ?>


    <div class="flex flex-col w-full">

        <?php include('./components/navbar.php'); ?>

        <div class="p-5 bg-[#fafbfc] h-full">

            <?php if (isset($_SESSION['message'])): ?>
                <div class="rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10  mb-7"><?= $_SESSION['message']; ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10  mb-7"><?= $_SESSION['error']; ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>




            <div class="flex items-center justify-between">


                <h1 class="text-lg font-medium mb-1">Edit Event</h1>

                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a href="campusActivities.php">Event</a></li>
                        <li>Edit</li>
                    </ul>
                </div>

            </div>

            <form action="" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-4 my-7" enctype="multipart/form-data">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">


                <div class="bg-white rounded-md border border-gray-200 p-5 space-y-4">

                    <p class="font-medium">Event Information</p>

                    <div>
                        <label class="text-sm mb-2 block text-base-content/70">Event Name</label>
                        <input name="eventName" type="text" value="<?php echo $event['event_name'] ?>" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Event Name" />
                    </div>


                    <div>
                        <label class="text-sm mb-2 block text-base-content/70">Organizer Name</label>
                        <div class="relative flex items-center">
                            <input name="organizer_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter organizer name" value="<?php echo $event['organizer_name'] ?>" />

                        </div>
                    </div>


                    <div>
                        <label class="text-sm mb-2 block text-base-content/70">Description</label>
                        <div id="editorEdit"></div>
                        <input type="hidden" name="eventDescription" value="<?php echo htmlspecialchars($event['description']); ?>" />
                    </div>




                </div>


                <div class="bg-white rounded-md border border-gray-200 p-5 space-y-4">

                    <p class="font-medium">Date and Place</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm mb-2 block text-base-content/70">From</label>
                            <div class="relative flex items-center">
                                <input name="date_time_from" type="datetime-local" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" value="<?php echo $event['date_time_from'] ?>" />

                            </div>
                        </div>

                        <div>
                            <label class="text-sm mb-2 block text-base-content/70">To</label>
                            <div class="relative flex items-center">
                                <input name="date_time_to" type="datetime-local" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" value="<?php echo $event['date_time_to'] ?>" />

                            </div>
                        </div>

                        <div>
                            <label class="text-sm mb-2 block text-base-content/70">Venue</label>
                            <input name="eventVenue" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Venue" value="<?php echo $event['venue'] ?>" />
                        </div>


                        <div>
                            <label class="text-sm mb-2 block text-base-content/70">Type</label>
                            <select name="eventType" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                <option value="Public" <?php echo $event['event_type'] === 'Public' ? 'selected' : ''; ?>>Public</option>
                                <option value="Private" <?php echo $event['event_type'] === 'Private' ? 'selected' : ''; ?>>Private</option>
                            </select>
                        </div>

                    </div>



                </div>


                <div class="bg-white rounded-md border border-gray-200 p-5 col-span-1 lg:col-span-2">
                    <label class="text-gray-800 font-medium mb-4 block">Banner Image</label>
                    <!-- Show current banner image -->

                    <?php
                    // Check if there is a banner image
                    if ($event['banner']) {
                        // If a banner image exists, display it
                        echo '<img src="' . $event['banner'] . '" alt="Event Banner" class="w-full h-96 object-cover rounded-md mb-6">';
                    } else {
                        // If there is no banner, display a message
                        echo '<p class="mb-6">No banner available for this event.</p>';
                    }
                    ?>

                    <input name="banner-Image" type="file" class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                </div>



                <div class="modal-action col-span-2">
                    <a href="campusActivities.php" class="btn bg-gray-500 hover:bg-gray-700 text-white border border-gray-500 hover:border-gray-700">Cancel</a>
                    <button type="submit" name="editEvent" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Update Event</button>
                </div>



            </form>





        </div>





    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var quill = new Quill('#editorEdit', {
                theme: 'snow',
                placeholder: 'Write your description here...',
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        ['link', 'image', 'code-block'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                    ],
                },
            });

            // Set the editor content to the event description
            var descriptionInput = document.querySelector("input[name='eventDescription']");
            quill.root.innerHTML = descriptionInput.value; // Set the content from the hidden input

            // On form submission, append the editor content to a hidden input
            var form = document.querySelector("form");
            form.addEventListener("submit", function() {
                descriptionInput.value = quill.root.innerHTML; // Store editor content
            });
        });
    </script>

</body>

</html>