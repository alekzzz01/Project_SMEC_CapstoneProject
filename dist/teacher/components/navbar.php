<div class="bg-teal-800 px-4 py-2">
    <div class=" flex items-center justify-between">

        <a class="flex items-center gap-4 text-white " href="./">
            <img src="./../../assets/images/smeclogo.png" alt="" class="w-10 h-10 object-cover">
            <p class="text-2xl font-medium tracking-tighter hidden lg:block">Sta. Marta Educational Center Inc.</p>
        </a>


        <div class="flex items-center ">
            
            <!-- Initial Items Menu -->
            <ul class="menu menu-horizontal px-1 font-medium hidden lg:flex text-white ">
            <li><a href="class.php">Class</a></li>
            <li><a href="schedules.php">Schedules</a></li>
     
            </ul>

            <!-- Small Screen Menu -->
            <div>
                <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    class="inline-block h-5 w-5 stroke-current text-white">
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                </div>
                <ul
                    tabindex="0"
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    <li><a href="class.php">Class</a></li>
                    <li><a href="schedules.php">Schedules</a></li>
                </ul>
                </div>

            </div>

            <!-- Profile Button -->

            <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-9 rounded-full">
                <img
                    alt="Tailwind CSS Navbar component"
                    src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                </div>
            </div>
            <ul
                tabindex="0"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-5 w-52 p-4 shadow">
                <li>
                <a class="justify-between" href="profile.php">
                    Profile
                    <span class="badge">New</span>
                </a>
                </li>           
                <li><a href="./../../auth/logout.php">Logout</a></li>
            </ul>
            </div>

            
        
        </div>



    </div>









</div>




