<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>


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



</head>

<body class="flex min-h-screen">

    <?php include('./components/sidebar.php'); ?>

    <div class="flex flex-col w-full">


        <?php include('./components/navbar.php'); ?>

        <div class="p-6 bg-[#f2f5f8] h-full">


            <h1 class="text-lg font-medium mb-1">Backup & Restore</h1>


            <div class="bg-white shadow rounded-md px-4 py-4 sm:px-6 mt-7 space-y-6">
              
                        <div class="space-y-4">
                            <p class="text-sm text-base-content/70">Select a table to backup </p>

                            <div class=" p-4 border border-dashed space-y-4">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" checked="checked" class="checkbox checkbox-xs" />
                                    <label for="" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Table_db1
                                    </label>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" checked="checked" class="checkbox checkbox-xs" />
                                    <label for="" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Table_db1
                                    </label>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" checked="checked" class="checkbox checkbox-xs" />
                                    <label for="" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Table_db1
                                    </label>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" checked="checked" class="checkbox checkbox-xs" />
                                    <label for="" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Table_db1
                                    </label>
                                </div>

                            </div>

                        </div>

                            <button class="px-4 py-2 w-full rounded-lg font-medium bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Create Backup</button>

                            <div class="border border-b border-gray-100"></div>

                            <p class="font-medium">Recent Backups</p>

                        <div class="hello">
                            <ul class="divide-y divide-gray-200 border border-dashed">
                                <li>
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-indigo-600 truncate">Backup ID: {backup.id}</p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <Button class="px-4 py-2 w-full rounded-lg font-medium border border-gray-300">
                                                    Restore
                                                </Button>
                                            </div>
                                        </div>

                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex flex-wrap gap-2">

                                                <div class="badge badge-ghost">table name</div>

                                            </div>
                                        </div>

                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    Date: 02/11/2025, 8:35:59 PM
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">Size: 234MB</div>
                                        </div>

                                    </div>
                                </li>

                                <li>
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-indigo-600 truncate">Backup ID: {backup.id}</p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <Button class="px-4 py-2 w-full rounded-lg font-medium border border-gray-300">
                                                    Restore
                                                </Button>
                                            </div>
                                        </div>

                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex flex-wrap gap-2">

                                                <div class="badge badge-ghost">table name</div>

                                            </div>
                                        </div>

                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    Date: 02/11/2025, 8:35:59 PM
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">Size: 234MB</div>
                                        </div>

                                    </div>
                                </li>

                            </ul>
                        </div>


                    </div>

           

        </div>

    </div>
</body>

</html>