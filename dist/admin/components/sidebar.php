<?php
include '../../config/db.php';

$sql = "SELECT * FROM customization_table WHERE theme_id = 1";
$stmt = $connection->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$customization = $result->fetch_assoc();



// For getting the current page name
$current_page = basename($_SERVER['PHP_SELF']);

?>

<div id="sidebar" class="bg-white border-r border-gray-200 w-full md:w-80 lg:w-96 h-screen fixed md:relative lg:relative transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 z-50">
    <div class="flex justify-between items-center p-6 h-20">
        <div>
            <a class="flex items-center gap-2" href="./">
                <?php
                if (isset($customization['school_logo']) && !empty($customization['school_logo'])) {
                    echo '<img src="' . htmlspecialchars('./' . $customization['school_logo'], ENT_QUOTES, 'UTF-8') . '" class="w-10 h-10 object-cover bg-white rounded-full">';
                } else {
                    echo '<img src="../../assets/images/defaultLogo.png" alt="Default Logo" class="w-10 h-10 object-cover bg-white rounded-full">';
                }

                if (isset($customization['school_name']) && !empty($customization['school_name'])) {
                    echo '<p class="font-bold">' . htmlspecialchars($customization['school_name'], ENT_QUOTES, 'UTF-8') . '</p>';
                } else {
                    echo '<p class="text-2xl font-bold tracking-tight hidden lg:block">LUMIX</p>';
                }
                ?>
            </a>
        </div>

        <button id="closeSidebar" class="btn btn-ghost block md:hidden lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" class="size-5">
                <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <ul class="menu text-base p-4">

        <li>
            <h2 class="menu-title">GENERAL</h2>
            <ul>
                <li>
                    <a class="p-2 <?php echo ($current_page == 'index.php') ? 'bg-[#eef0f2]' : ''; ?>" href="../admin/">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                </li>



                <li>
                    <a class="p-2 <?php echo ($current_page == 'users.php') ? 'bg-[#eef0f2]' : ''; ?>" href="users.php">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                        User Management
                    </a>
                </li>



                <li>
                    <a class="p-2 <?php echo ($current_page == 'campusActivities.php') ? 'bg-[#eef0f2]' : ''; ?>" href="campusActivities.php">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>

                        Campus Activities
                    </a>
                </li>

            </ul>

        </li>

        <li>
            <h2 class="menu-title">DATABASE</h2>
            <ul>



                <li>
                    <a class="p-2 <?php echo ($current_page == 'analyticsPage.php') ? 'bg-[#eef0f2]' : ''; ?>" href="analyticsPage.php">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>

                        Analytics
                    </a>
                </li>

                <li>
                    <details closed>
                        <summary class="pl-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                            </svg>

                            Reports
                        </summary>
                        <ul>
                            <li><a class="<?php echo ($current_page == 'reportsEnrolledPerYear.php') ? 'bg-[#eef0f2]' : ''; ?>" href="reportsEnrolledPerYear.php">School Year / Semester</a></li>
                            <li><a class="<?php echo ($current_page == 'reportsEnrolledSection.php') ? 'bg-[#eef0f2]' : ''; ?>" href="reportsEnrolledSection.php">School Year / Section</a></li>
                            <li><a class="<?php echo ($current_page == 'reportsEnrolledPerType.php') ? 'bg-[#eef0f2]' : ''; ?>" href="reportsEnrolledPerType.php">School Year / Type</a></li>

                        </ul>
                    </details>
                </li>





                <li>
                    <details open>
                        <summary class="pl-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                            </svg>
                            School Management
                        </summary>
                        <ul>
                            <li><a class="<?php echo ($current_page == 'admin_admission_approval.php') ? 'bg-[#eef0f2]' : ''; ?>" href="admin_admission_approval.php">Admissions</a></li>
                            <li><a class="<?php echo ($current_page == 'admin_enrollment_approval.php') ? 'bg-[#eef0f2]' : ''; ?>" href="admin_enrollment_approval.php">Enrollment</a></li>
                            <!-- <li><a>Student List</a></li>
                            <li><a>Teacher List</a></li>
                            <li><a>Fees</a></li> -->

                        </ul>
                    </details>
                </li>


                <li>
                    <details open>
                        <summary class="pl-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v8.25A2.25 2.25 0 0 0 6 16.5h2.25m8.25-8.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-7.5A2.25 2.25 0 0 1 8.25 18v-1.5m8.25-8.25h-6a2.25 2.25 0 0 0-2.25 2.25v6" />
                            </svg>
                            Class Management
                        </summary>
                        <ul>
                            <li><a class="<?php echo ($current_page == 'class_term.php') ? 'bg-[#eef0f2]' : ''; ?>" href="class_term.php">Term</a></li>
                            <li><a class="<?php echo ($current_page == 'class_section.php') ? 'bg-[#eef0f2]' : ''; ?>" href="class_section.php">Section</a></li>
                            <li><a class="<?php echo ($current_page == 'class_subject.php') ? 'bg-[#eef0f2]' : ''; ?>" href="class_subject.php">Subject</a></li>


                        </ul>
                    </details>
                </li>




            </ul>

        </li>

        <li>
            <h2 class="menu-title">SETTINGS</h2>
            <ul>

                <li>
                    <a class="p-2 <?php echo ($current_page == 'audit_logs.php') ? 'bg-[#eef0f2]' : ''; ?>" href="audit_logs.php">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75h4.875a2.625 2.625 0 0 1 0 5.25H12M8.25 9.75 10.5 7.5M8.25 9.75 10.5 12m9-7.243V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185Z" />
                        </svg>

                        Audit Logs
                    </a>
                </li>


                <li>
                    <a class="p-2 <?php echo ($current_page == 'theme_customization.php') ? 'bg-[#eef0f2]' : ''; ?>" href="theme_customization.php">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 0 1-.657.643 48.39 48.39 0 0 1-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 0 1-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 0 0-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 0 1-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 0 0 .657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 0 0 5.427-.63 48.05 48.05 0 0 0 .582-4.717.532.532 0 0 0-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 0 0 .658-.663 48.422 48.422 0 0 0-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 0 1-.61-.58v0Z" />
                        </svg>

                        Preferences
                    </a>
                </li>

                <li>
                    <a class="p-2 <?php echo ($current_page == 'maintenance.php') ? 'bg-[#eef0f2]' : ''; ?>" href="maintenance.php">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                        </svg>


                        Maintenance
                    </a>
                </li>


            </ul>

        </li>











    </ul>






</div>


<script src="../../assets/js/script.js"></script>