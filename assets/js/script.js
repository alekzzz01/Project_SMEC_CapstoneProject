tailwind.config = {
    theme: {
      extend: {
        colors: {
            secondaryText: '#7F7F7F'
        }
      }
    }
    
  }




// function for toggle password

function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("bx-show");
                toggleIcon.classList.add("bx-hide");
             
             
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("bx-hide");
                toggleIcon.classList.add("bx-show");
              
              
            }
}

// for sidebar  

$(document).ready(function () {
  console.log("Document is ready."); // Debug: Check if the document is fully loaded.

  $('#toggleSidebar').on('click', function () {
    console.log("Toggle Sidebar button clicked."); // Debug: Log button click.
    $('#sidebar').toggleClass('-translate-x-full');
    console.log("Sidebar classes:", $('#sidebar').attr('class')); // Debug: Log sidebar classes.
  });

  $('#closeSidebar').on('click', function () {
    console.log("Close Sidebar button clicked."); // Debug: Log button click.
    $('#sidebar').addClass('-translate-x-full');
    console.log("Sidebar classes:", $('#sidebar').attr('class')); // Debug: Log sidebar classes.
  });
});
