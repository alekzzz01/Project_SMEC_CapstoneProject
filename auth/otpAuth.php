<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Authentication</title>

    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>
    <html data-theme="light"></html>


</head>
<body class="relative h-screen flex items-center justify-center bg-stone-50">

    <a class="absolute top-8 left-4 px-4 flex items-center gap-2 btn btn-ghost text-blue-500" href="login.php">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>

        Go back to Login
   </a>

    <div class="relative max-w-md mx-auto text-center bg-white px-4 sm:px-8 py-10 rounded-xl shadow">
        <header class="mb-8">
            <h1 class="text-2xl font-bold mb-1">Mobile Phone Verification</h1>
            <p class="text-[15px] text-slate-500">Enter the 4-digit verification code that was sent to your phone number.</p>
        </header>
        <form id="otp-form">
            <div class="flex items-center justify-center gap-3">
                <input
                    type="text"
                    class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    pattern="\d*" maxlength="1" />
                <input
                    type="text"
                    class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    maxlength="1" />
                <input
                    type="text"
                    class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    maxlength="1" />
                <input
                    type="text"
                    class="w-14 h-14 text-center text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded p-4 outline-none focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                    maxlength="1" />
            </div>
            <div class="max-w-[260px] mx-auto mt-4">
                <button type="submit"
                    class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-blue-500 px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-blue-950/10 hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-blue-300 transition-colors duration-150">Verify
                    Account</button>
            </div>
        </form>
        <div class="text-sm text-slate-500 mt-4">Didn't receive code? <a class="font-medium text-blue-500 hover:text-blue-600" href="#0">Resend</a></div>
    </div>

    <div class="absolute bottom-4 px-4 w-full text-xs text-gray-400  flex items-center justify-between">
            <p class="hover:text-black transition-colors">© 2024 JMA Technologies. All Rights Reserved.</p>
            <div class="flex items-center gap-6">
                <a href="" class="hover:text-black transition-colors">Privacy</a>
                <a href="" class="hover:text-black transition-colors">Cookies Policy</a>
            </div>
    </div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('otp-form')
        const inputs = [...form.querySelectorAll('input[type=text]')]
        const submit = form.querySelector('button[type=submit]')

        const handleKeyDown = (e) => {
            if (
                !/^[0-9]{1}$/.test(e.key)
                && e.key !== 'Backspace'
                && e.key !== 'Delete'
                && e.key !== 'Tab'
                && !e.metaKey
            ) {
                e.preventDefault()
            }

            if (e.key === 'Delete' || e.key === 'Backspace') {
                const index = inputs.indexOf(e.target);
                if (index > 0) {
                    inputs[index - 1].value = '';
                    inputs[index - 1].focus();
                }
            }
        }

        const handleInput = (e) => {
            const { target } = e
            const index = inputs.indexOf(target)
            if (target.value) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus()
                } else {
                    submit.focus()
                }
            }
        }

        const handleFocus = (e) => {
            e.target.select()
        }

        const handlePaste = (e) => {
            e.preventDefault()
            const text = e.clipboardData.getData('text')
            if (!new RegExp(`^[0-9]{${inputs.length}}$`).test(text)) {
                return
            }
            const digits = text.split('')
            inputs.forEach((input, index) => input.value = digits[index])
            submit.focus()
        }

        inputs.forEach((input) => {
            input.addEventListener('input', handleInput)
            input.addEventListener('keydown', handleKeyDown)
            input.addEventListener('focus', handleFocus)
            input.addEventListener('paste', handlePaste)
        })
    })                        
</script>
    
</body>
</html>