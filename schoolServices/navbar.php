<div id="navbar" class="px-4 py-2 fixed w-full top-0 left-0 z-10 transition duration-300">
    <div class=" flex items-center justify-between">

        <a class="flex items-center gap-4 text-white" href="../">
            <img src="../assets/images/smeclogo.png" alt="" class="w-10 h-10 object-cover bg-white rounded-full">
            <p class="text-2xl font-medium tracking-tighter hidden lg:block">Sta. Marta Educational Center Inc.</p>
        </a>


        <div class="flex items-center ">
            
            <!-- Initial Items Menu -->
            <ul class="menu menu-horizontal px-1 font-medium hidden lg:flex text-white">
          
            <li><a href="../aboutus.php">ABOUT US</a></li>
            <li><a href="../programs.php">PROGRAMS</a></li>
            <li><a href="../newsAndevents.php">NEWS & EVENTS</a></li>
            <li><a href="./">ADMISSIONS</a></li>
            <li><a href="../portal/">PORTALS</a></li>
            <li><a href="../auth/login.php">LOGIN</a></li>

            </ul>

            <!-- Small Screen Menu -->
            <div>
                <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden text-white">
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    class="inline-block h-5 w-5 stroke-current">
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
                                
                    <li><a href="../aboutus.php">ABOUT US</a></li>
                    <li><a href="../programs.php">PROGRAMS</a></li>
                    <li><a href="../newsAndevents.php">NEWS & EVENTS</a></li>
                    <li><a href="./">ADMISSIONS</a></li>
                    <li><a href="../portal/">PORTALS</a></li>
                    <li><a href="../auth/login.php">LOGIN</a></li>
                    </ul>
                </div>

            </div>

        </div>



    </div>









</div>


<script>
    document.addEventListener("scroll", function () {
    const navbar = document.getElementById("navbar");
    if (window.scrollY > 50) {
        navbar.classList.add("bg-blue-800");
    } else {
        navbar.classList.remove("bg-blue-800");
    }
    });

</script>




