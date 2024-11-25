<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tailwind + jQuery DataTables</title>

  
 
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
 
 <script src="https://cdn.tailwindcss.com"></script>


  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- DataTables CSS CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS CDN -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <script defer>
    $(document).ready(function () {
        $('#myTable').DataTable({
            "lengthMenu": [10, 25, 50, 75, 100],
            "pageLength": 10,
            "pagingType": "full_numbers",
            
            responsive: true
        });
    });
  </script>
</head>
<body>

  <div class="flex items-center justify-between w-full">
                    <p class="font-bold">All Users <span class="text-gray-400 font-medium ml-1">44</span></p>

                    <div class="flex flex-wrap items-center gap-4 text-sm">

                        <div class="relative mx-auto text-gray-600 ">
                            <input class="border border-gray-100 bg-white py-2 px-3 pr-16 rounded-lg text-sm focus:outline-none"
                            type="search" name="search" placeholder="Search">
                            <button type="submit" class="absolute right-3 top-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>

                            </button>
                        </div>

                        <details class="dropdown dropdown-end">
                          <summary class="border border-gray-100 bg-white py-2 px-3 pr-16 rounded-lg text-sm focus:outline-none">Filters</summary>
                          <ul class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow mt-2">
                            <li><a>Item 1</a></li>
                            <li><a>Item 2</a></li>
                          </ul>
                        </details>

                        <button onclick="my_modal_5.showModal()" class="flex items-center gap-1 font-medium  text-white border border-blue-600 hover:border-blue-700 bg-blue-600 hover:bg-blue-700 rounded-lg px-3 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add user
                        </button>
                      
                                

                    </div>


    </div>


    <div class="flex flex-col mt-7">
      <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
          <div class="border rounded-lg divide-y divide-gray-200">
          
            <div class="overflow-hidden">
              <table class="min-w-full divide-y divide-gray-200">
                <thead >
                  <tr>
                    <th scope="col" class="py-3 px-4 pe-0">
                      <div class="flex items-center h-5">
                        <input id="hs-table-pagination-checkbox-all" type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                        <label for="hs-table-pagination-checkbox-all" class="sr-only">Checkbox</label>
                      </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Age</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <td class="py-3 ps-4">
                      <div class="flex items-center h-5">
                        <input id="hs-table-pagination-checkbox-1" type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                        <label for="hs-table-pagination-checkbox-1" class="sr-only">Checkbox</label>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">John Brown</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">45</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">New York No. 1 Lake Park</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <button  onclick="my_modal_6.showModal()"  type="button" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">Edit</button>
                      <button  onclick="my_modal_7.showModal()" type="button" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-none focus:text-red-800 disabled:opacity-50 disabled:pointer-events-none">Delete</button>
                    </td>
                  </tr>




                </tbody>
              </table>
            </div>



            <div class="py-1 px-4">
              <nav class="flex items-center space-x-1" aria-label="Pagination">
                <button type="button" class="p-2.5 min-w-[40px] inline-flex justify-center items-center gap-x-2 text-sm rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" aria-label="Previous">
                  <span aria-hidden="true">«</span>
                  <span class="sr-only">Previous</span>
                </button>
                <button type="button" class="min-w-[40px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 py-2.5 text-sm rounded-full disabled:opacity-50 disabled:pointer-events-none" aria-current="page">1</button>
                <button type="button" class="min-w-[40px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 py-2.5 text-sm rounded-full disabled:opacity-50 disabled:pointer-events-none">2</button>
                <button type="button" class="min-w-[40px] flex justify-center items-center text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 py-2.5 text-sm rounded-full disabled:opacity-50 disabled:pointer-events-none">3</button>
                <button type="button" class="p-2.5 min-w-[40px] inline-flex justify-center items-center gap-x-2 text-sm rounded-full text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" aria-label="Next">
                  <span class="sr-only">Next</span>
                  <span aria-hidden="true">»</span>
                </button>
              </nav>
            </div>


          </div>
        </div>
      </div>
    </div>


  <!-- <div class="overflow-x-auto ">
    <table id="myTable" class="display min-w-full">
      <thead>
        <tr >
          <th class="px-4 py-2 text-left">ID No.</th>
          <th class="px-4 py-2 text-left">User name</th>
          <th class="px-4 py-2 text-left">Email</th>
          <th class="px-4 py-2 text-left">Access/Role</th>
          <th class="px-4 py-2 text-left">Last active</th>
          <th class="px-4 py-2 text-left">Date Added</th>
          <th class="px-4 py-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="px-4 py-2">1</td>
          <td class="px-4 py-2">Alex</td>
          <td class="px-4 py-2">sample@gmail.com</td>
          <td class="px-4 py-2">Admin</td>
          <td class="px-4 py-2">Mar 4, 2024</td>
          <td class="px-4 py-2">Jan 4, 2024</td>
          <td class="px-4 py-2 flex items-center gap-3">
            <button onclick="my_modal_6.showModal()" class="text-blue-500 hover:text-blue-700">Edit</button>
            <button onclick="my_modal_7.showModal()" class="text-red-500 hover:text-red-700">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div> -->

  <!-- Edit Modal -->

    <dialog id="my_modal_6" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Edit user</h3>
            <form action="" class="py-4 grid grid-cols-2 gap-3">
              
                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">First Name</label>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter First Name" />
                        
                            </div>
                    </div>

                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">Last Name</label>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter Last Name" />
                        
                            </div>
                    </div>

                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">Email</label>
                            <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter email" />
                        
                            </div>
                    </div>


                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">Role</label>
                            <div class="relative flex items-center">
                            <select name="gender" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600">
                                    <option value="" disabled selected>Select role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Teacher">Teacher</option>
                                    <option value="Student">Student</option>
                                  
                                </select>
                        
                            </div>
                    </div>


                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">Current Password</label>
                            <div class="relative flex items-center">
                                <input id="password" name="password" type="password" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter password" />
                                <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                                    <i id="togglePasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                                </button>
                            </div>
                    </div>


                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">New Password</label>
                            <div class="relative flex items-center">
                                <input id="password" name="password" type="password" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter password" />
                                <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                                    <i id="togglePasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                                </button>
                            </div>
                    </div>

                    <div>
                            <label class="text-gray-800 text-sm mb-2 block">Confirm New Password</label>
                            <div class="relative flex items-center">
                                <input id="password" name="password" type="password" required class="w-full text-gray-800 text-sm border border-slate-900/10 px-3 py-2 rounded-md outline-blue-600" placeholder="Enter password" />
                                <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" class="absolute inset-y-0 right-4 flex items-center">
                                    <i id="togglePasswordIcon" class='bx bx-show w-4 h-4 text-gray-400'></i>
                                </button>
                            </div>
                    </div>


                  
            </form>

            

           
            <div class="modal-action">
            <form method="dialog">
              
                <button class="btn">Close</button>
                <button class="btn bg-blue-500 hover:bg-blue-700 text-white border border-blue-500 hover:border-blue-700">Edit User</button>
            </form>
            </div>
        </div>
    </dialog>

    
    <dialog id="my_modal_7" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Delete user?</h3>
            <p class="py-4">Are you sure you want to delete this user?</p>
        
            <div class="modal-action">
            <form method="dialog">
              
                <button class="btn">Close</button>
                <button class="btn bg-red-500 hover:bg-red-700 text-white border border-red-500 hover:border-red-700">Confirm</button>
            </form>
            </div>
        </div>
    </dialog>

</body>
</html>
