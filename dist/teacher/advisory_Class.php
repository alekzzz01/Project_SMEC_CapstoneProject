<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisory Class</title>



    <link rel="stylesheet" href="../../assets/css/styles.css">

    <script src="../../assets/js/script.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

    <html data-theme="light">

    </html>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">

    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>

</head>

<body class="min-h-screen bg-[#f2f5f8]">

    <?php include './components/navbar.php' ?>

    <div class="max-w-7xl mx-auto py-14 px-4 lg:px-12 h-full ">



        <div class="breadcrumbs text-sm mb-3.5">
            <ul>
                <li><a href="./">Home</a></li>
                <li>Advisory Class</li>
            </ul>
        </div>


        <div class="rounded bg-teal-100 p-4 mb-7 space-y-2 shadow">
            <h2 class="text-teal-900 font-semibold text-xl">Advisory Class</h2>
            <h1 class="font-extrabold text-4xl text-teal-900">Grade 9 - Lapiz</h1>
            <p class="text-teal-800 text-sm italic">Adviser: Ms. Victoria O. Panganiban</p>
        </div>




        <div class="flex flex-col">
            <div class="overflow-hidden p-4 rounded border border-gray-200 bg-white">
                <table id="example" class="min-w-full divide-y divide-gray-200">
                    <thead class="border border-gray-300  text-sm">

                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Grade</th>
                            <th>Section</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Mark</td>
                            <td>Grade - 1</td>
                            <td>Section - A</td>
                            <td>
                                <a href="view_Student.php" type='submit' name='approve' class='text-teal-700 text-sm hover:underline'>[View]</a>

                            </td>

                        </tr>
                    </tbody>





                </table>


            </div>

        </div>



    </div>




</body>

</html>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            searching: true, // Enables the search box
            paging: true, // Enables pagination
            ordering: true, // Enables column sorting
            info: true // Displays table information (e.g., "Showing 1 to 10 of 50 entries")
        });
    });
</script>