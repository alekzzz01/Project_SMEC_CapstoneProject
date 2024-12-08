<?php 
session_start();

include '../../config/db.php';



    // Add Event
    if (isset($_POST['createEvent'])) {
        $eventName = $_POST['eventName'];
        $eventSchedule = $_POST['eventSchedule'];
        $eventVenue = $_POST['eventVenue'];
        $eventType = $_POST['eventType'];
        $eventDescription = $_POST['eventDescription'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['banner-Image']['type'], $allowedTypes) && $_FILES['banner-Image']['size'] <= 5 * 1024 * 1024) { // 5MB size limit
            $bannerImage = file_get_contents($_FILES['banner-Image']['tmp_name']);
        } else {
            $_SESSION['error'] = "Invalid file type or size. Only JPEG, PNG, and GIF files are allowed, and size must not exceed 5MB.";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
        

        // Prepare and execute the SQL statement
        $stmt = $connection->prepare("INSERT INTO events (event_name, event_date, venue, event_type, description, banner) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssb", $eventName, $eventSchedule, $eventVenue, $eventType, $eventDescription, $null);
        $stmt->send_long_data(5, $bannerImage);


        if ($stmt->execute()) {
            $_SESSION['message'] = "Event created successfully!";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error'] = "Failed to create event. Please try again.";
        }
        
    }

    // Delete Event from the delete buttonin the event table

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event_id'])) {
        $event_id = intval($_POST['delete_event_id']);
        $stmt = $connection->prepare("DELETE FROM events WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
    
        if ($stmt->execute()) {
            $_SESSION['message'] = "Event deleted successfully!";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error'] = "Failed to delete event.";
        }
    
        $stmt->close();
        $connection->close();
        exit();
    }

    

    // if (isset($_POST['createAnnouncement'])) {

    //     $announcementTitle = $_POST['announcementTitle'];
    //     $announcementDescription = $_POST['announcementDescription'];
    //     $announcementImage = $_POST['announcementImage'];

    //     $sql = "INSERT INTO announcements (announcement_title, announcement_description, announcement_image) VALUES ('$announcementTitle', '$announcementDescription', '$announcementImage')";

    //     if ($conn->query($sql) === TRUE) {
    //         echo "New record created successfully";
    //     } else {
    //         echo "Error: " . $sql . "<br>" . $conn->error;
    //     }

    // }

    // if (isset($_POST['addService'])) {

    //     $serviceName = $_POST['serviceName'];
    //     $serviceDescription = $_POST['serviceDescription'];
    //     $serviceImage = $_POST['serviceImage'];

    //     $sql = "INSERT INTO services (service_name, service_description, service_image) VALUES ('$serviceName', '$serviceDescription', '$serviceImage')";

    //     if ($conn->query($sql) === TRUE) {
    //         echo "New record created successfully";
    //     } else {
    //         echo "Error: " . $sql . "<br>" . $conn->error;
    //     }

    // }







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

     
     <html data-theme="light"></html>
    


</head>
<body class="flex h-screen">

<?php include('./components/sidebar.php'); ?>


<div class="flex flex-col w-full shadow-xl">

<!-- Navbar -->

<?php include('./components/navbar.php'); ?>


    <div class="p-7 bg-gray-50 h-full">

        <?php if (isset($_SESSION['message'])): ?>
                <div class="rounded-md bg-green-50 px-2 py-1 font-medium text-green-600 ring-1 ring-inset ring-green-500/10 mb-7"   ><?= $_SESSION['message']; ?></div>
                <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
                <div class="rounded-md bg-red-50 px-2 py-1 font-medium text-red-600 ring-1 ring-inset ring-red-500/10 mb-7" ><?= $_SESSION['error']; ?></div>
                <?php unset($_SESSION['error']); ?>
        <?php endif; ?>


        <div class="flex items-center justify-end">

            <div class="flex items-center justify-end gap-3">
              
                <button onclick="events_Modal.showModal()" class="text-xs flex items-center gap-1 font-medium  text-white border border-blue-600 hover:border-blue-700 bg-blue-600 hover:bg-blue-700 rounded-lg px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                       CREATE EVENTS
                </button>

                
                <button onclick="announce_Modal.showModal()" class="text-xs flex items-center gap-1 font-medium  text-white border border-blue-600 hover:border-blue-700 bg-blue-600 hover:bg-blue-700 rounded-lg px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                       CREATE ANNOUNCEMENTS
                </button>

                
                <button onclick="service_Modal.showModal()" class="text-xs flex items-center gap-1 font-medium  text-white border border-blue-600 hover:border-blue-700 bg-blue-600 hover:bg-blue-700 rounded-lg px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                       ADD SERVICES
                </button>
                      
            </div>

        </div>

        

        <div class=" p-6 bg-white rounded-md my-7">
        
            <div role="tablist" class="tabs tabs-bordered">

                <input
                    type="radio"
                    name="my_tabs_1"
                    role="tab"
                    class="tab"
                    aria-label="Events"
                    checked="checked" />
                    <div role="tabpanel" class="tab-content pt-7"><?php include './tables/eventTable.php' ?></div>

                    

                        <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Announcements" />
                        <div role="tabpanel" class="tab-content">Tab content 1</div>

                    

                        <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Services" />
                        <div role="tabpanel" class="tab-content">Tab content 3</div>

                    </div>

                    <div>

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

                <form action="" class="py-4 flex flex-col gap-3" method="POST" enctype="multipart/form-data">


                        <div>
                                <label class="text-gray-800 text-sm mb-2 block">Event Name</label>
                                <div class="relative flex items-center">
                                <input name="eventName" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Event Name" />
                            
                                </div>
                        </div>
                
                        <div>
                                <label class="text-gray-800 text-sm mb-2 block">Schedule</label>
                                <div class="relative flex items-center">
                                <input name="eventSchedule" type="datetime-local" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter First Name" />
                            
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
                                <label class="text-gray-800 text-sm font-medium mb-6 block">Banner Image</label>
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

   

    
</body>
</html>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        var quill = new Quill('#editor', {
            theme: 'snow', // Options: 'snow' or 'bubble'
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

        // On form submission, append the editor content to a hidden input
        var form = document.querySelector("form");
        form.addEventListener("submit", function () {
            var descriptionInput = document.querySelector("input[name='eventDescription']");
            descriptionInput.value = quill.root.innerHTML; // Store editor content
        });
    });
</script>

<script>
function deleteEvent(eventId) {
    if (confirm("Are you sure you want to delete this event?")) {
        // Create a form dynamically
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = ''; // Submits to the same file

        // Add the event ID as a hidden input
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_event_id';
        input.value = eventId;
        form.appendChild(input);

        // Append form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    }
}
</script>




