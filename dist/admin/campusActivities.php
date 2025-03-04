<?php
session_start();

include '../../config/db.php';


// Add Event

if (isset($_POST['createEvent'])) {
    $eventName = $_POST['eventName'];
    $from = $_POST['date_time_from'];
    $to = $_POST['date_time_to'];
    $eventVenue = $_POST['eventVenue'];
    $eventType = $_POST['eventType'];
    $eventDescription = $_POST['eventDescription'];
    $organizerName = $_POST['organizer_name'];

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $bannerImage = null;
    if (isset($_FILES['banner-Image']) && $_FILES['banner-Image']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['banner-Image']['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file type
        if (in_array($fileType, $allowedTypes)) {
            $sanitizedFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "", $fileName);
            $targetFilePath = $targetDir . $sanitizedFileName;

            if (move_uploaded_file($_FILES['banner-Image']['tmp_name'], $targetFilePath)) {
                $bannerImage = $targetFilePath;
            } else {
                die("Failed to upload the file.");
            }
        } else {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }
    }

    $stmt = $connection->prepare("INSERT INTO events (event_name, date_time_from, date_time_to, venue, event_type, description, organizer_name, banner) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $eventName, $from, $to, $eventVenue, $eventType, $eventDescription, $organizerName, $bannerImage);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event created successfully!";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error'] = "Failed to create event. Please try again.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archiveEvent'])) {
    // Retrieve the event ID from the POST data
    $event_id = $_POST['archiveEvent'];

    // Debug: Check if the event ID is being passed correctly
    // echo "Event ID is: " . $event_id;

    // Prepare the SQL query to archive the event
    $sql = "UPDATE events SET is_archived = 1 WHERE event_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $event_id);  // Bind the event ID as an integer parameter

    if ($stmt->execute()) {
        // Set a success message in the session
        $_SESSION['message'] = "Event archived successfully!";
        // $_SESSION['message'] = "Event archived successfully!" . $event_id;
    } else {
        // Set an error message in the session
        $_SESSION['error'] = "Error archiving event: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $connection->close();

    // Redirect back to the events page
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}


// Add announcement

if (isset($_POST['createAnnouncement'])) {
    $announcement = $_POST['title'];
    $announcementMessage = $_POST['announcementMessage'];


    $stmt = $connection->prepare("INSERT INTO notifications (title, message, type, target_role, created_at) VALUES (?, ?, 'system', 'all', NOW())");
    $stmt->bind_param("ss", $announcement, $announcementMessage);
    $stmt->execute();
    $notification_id = $connection->insert_id;

    // send notification to all users
    $sql2 = "INSERT INTO user_notifications (user_id, notification_id, status, sent_at) 
             SELECT user_id, ?, 'sent', NOW() FROM users";
    $stmt = $connection->prepare($sql2);
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();

    if ($stmt->execute()) {
        $_SESSION['message'] = "Announcement created successfully!";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error'] = "Failed to create announcement. Please try again.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }


}





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activities</title>


    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>


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

        <!-- Navbar -->

        <?php include('./components/navbar.php'); ?>


        <div class="p-6 bg-[#fafbfc] h-full">

            <?php if (isset($_SESSION['message'])): ?>
                <div class="rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10 mb-7"><?= $_SESSION['message']; ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10 mb-7"><?= $_SESSION['error']; ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>


            <div class="flex items-center justify-between">


                <div>
                    <h1 class="text-lg font-medium mb-1">Campus Activities</h1>
                </div>

                <div class="breadcrumbs text-sm">
                    <ul>
                        <li><a>Dashboard</a></li>
                        <li><a>Campus Activities</a></li>
                    </ul>
                </div>


            </div>

            <div role="tablist" class="tabs tabs-lifted mt-7">

                <input
                    type="radio"
                    name="my_tabs_2"
                    role="tab"
                    class="tab"
                    checked="checked" 
                    aria-label="Event" />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box">
                    <?php include './tables/eventTable.php' ?>
                </div>


                <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Announcement" />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box">
                    <?php include './tables/announcementsTable.php' ?>
                </div>



                <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Service" />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                    Tab content 3
                </div>
            </div>






        </div>





    </div>

    <!-- Add event -->

    <dialog id="events_Modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Add New Event</h3>

            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <form action="" class="py-4 flex flex-col gap-3" method="POST" enctype="multipart/form-data" id="eventForm">


                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Event Name</label>
                    <div class="relative flex items-center">
                        <input name="eventName" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Event Name" />

                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">From</label>
                        <div class="relative flex items-center">
                            <input name="date_time_from" type="datetime-local" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />

                        </div>
                    </div>

                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">To</label>
                        <div class="relative flex items-center">
                            <input name="date_time_to" type="datetime-local" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />

                        </div>
                    </div>

                </div>


                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Organizer Name</label>
                    <div class="relative flex items-center">
                        <input name="organizer_name" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter organizer name" />

                    </div>
                </div>



                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Venue</label>
                    <div class="relative flex items-center">
                        <input name="eventVenue" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Venue" />

                    </div>
                </div>




                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Type</label>
                    <div class="relative flex items-center">
                        <select name="eventType" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                            <option value="" disabled selected>Select Event Type</option>
                            <option value="Public">Public</option>
                            <option value="Private">Private</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-gray-800 text-sm  mb-6 block">Banner Image</label>
                    <div class="relative flex items-center">
                        <input name="banner-Image" type="file" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" />
                    </div>
                </div>


                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Description</label>
                    <div id="editor"></div>
                    <input type="hidden" name="eventDescription" />
                </div>

                <div class="modal-action">

                    <button type="submit" name="createEvent" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Add Event</button>

                </div>

            </form>

        </div>
    </dialog>


    <!-- Add Announcements -->

    <dialog id="announce_Modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Add New Announcement</h3>

            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <form action="" class="py-4 flex flex-col gap-3" method="POST" enctype="multipart/form-data" id="announcementForm">


                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Announcement</label>
                    <div class="relative flex items-center">
                        <input name="title" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Event Name" />

                    </div>
                </div>




                <div>
                    <label class="text-gray-800 text-sm mb-2 block">Description</label>
                    <div id="announcementEditor"></div>
                    <input type="hidden" name="announcementMessage" />
                </div>

                <div class="modal-action">

                    <button type="submit" name="createAnnouncement" class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Add Event</button>

                </div>

            </form>

        </div>
    </dialog>







</body>

</html>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var quill = new Quill('#editor', {
            theme: 'snow', // Options: 'snow' or 'bubble'
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

        // On form submission, append the editor content to a hidden input
        var form = document.querySelector("#eventForm");
        form.addEventListener("submit", function() {
            var descriptionInput = document.querySelector("input[name='eventDescription']");
            descriptionInput.value = quill.root.innerHTML; // Store editor content
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var quill = new Quill('#announcementEditor', {
            theme: 'snow', // Options: 'snow' or 'bubble'
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

        // On form submission, append the editor content to a hidden input
        var form = document.querySelector("#announcementForm");
        form.addEventListener("submit", function() {
            var descriptionInput = document.querySelector("input[name='announcementMessage']");
            descriptionInput.value = quill.root.innerHTML; // Store editor content
        });
    });
</script>

<script>
    function archiveEvent(eventId) {
        if (confirm("Are you sure you want to archive this event?")) {
            // Create a form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = ''; // Submits to the same file

            // Add the event ID as a hidden input field
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'archiveEvent'; // This name must match with the PHP code
            input.value = eventId; // Set the event ID here
            form.appendChild(input);

            // Append form to the body and submit it
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('#toggleSidebar').on('click', function() {
            $('#sidebar').toggleClass('-translate-x-full');
        });

        $('#closeSidebar').on('click', function() {
            $('#sidebar').addClass('-translate-x-full');
        });



    });
</script>