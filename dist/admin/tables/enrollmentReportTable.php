<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TailwindCSS Datatable with Export Buttons</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <!-- Buttons Extensions -->
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
</head>
<body class="bg-gray-100 py-10">

    <div class="container mx-auto">

        <div class="bg-white rounded shadow-md p-4">
            <table id="example" class="min-w-full text-sm text-gray-700 border-collapse border border-gray-300">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left border border-gray-300">Name</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-100">
                        <td class="py-3 px-4 border border-gray-300">Tiger Nixon</td>
                       
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="py-3 px-4 border border-gray-300">Garrett Winters</td>
                   
                    </tr>
            
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                dom: '<"flex justify-between items-center mb-4"Bf>rt<"flex justify-between items-center mt-4"ip>',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        className: 'bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600',
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        className: 'bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600',
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600',
                    }
                ],
                responsive: true,
                pageLength: 5,
                language: {
                    paginate: {
                        next: 'Next »',
                        previous: '« Previous'
                    }
                }
            });

            // Apply additional Tailwind styles to elements
            $('div.dataTables_filter input').addClass('rounded border border-gray-300 py-1 px-2');
            $('div.dataTables_filter label').addClass('flex items-center gap-2 text-gray-700');
            $('div.dataTables_length select').addClass('rounded border border-gray-300 py-1 px-2');
            
        });
    </script>
</body>
</html>
