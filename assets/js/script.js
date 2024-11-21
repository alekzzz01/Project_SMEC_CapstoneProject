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
