
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Success!</title>

    
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/@heroicons/react@2.0.16/dist/outline/index.js" type="module"></script>
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/script.js"></script>
    <html data-theme="light"></html>

</head>
<body class="relative h-screen flex items-center justify-center bg-stone-50">


    <div
        class="w-full max-w-2xl p-12 mx-4 text-center transition-all transform bg-white shadow-lg rounded-xl hover:shadow-xl">
        <!-- Success Icon -->
        <div class="flex items-center justify-center w-24 h-24 mx-auto mb-8 bg-blue-100 rounded-full">
            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Main Content -->
        <h1 class="mb-6 text-4xl font-extrabold text-blue-600">
            Form Submitted Successfully!
        </h1>

        <p class="mb-8 text-xl text-gray-700">
            Your admission form has been submitted.
        </p>

        <div class="p-6 mb-8 rounded-lg bg-amber-50">
            <p class="text-lg font-medium text-amber-700">
            You will receive an email with your  <span class="font-bold">student number</span> once your admission is approved by the admin.

                
            </p>
        </div>

        <!-- Contact Information -->
        <div class="pt-8 mt-8 border-t border-gray-100">
            <p class="text-lg text-gray-700">
                Have questions? Contact us at:
            </p>
            <a href="mailto:admin@eliteai.tools"
                class="inline-block mt-2 text-xl font-medium text-blue-600 transition-colors duration-200 hover:text-blue-800">
                smecdolmarpasig@gmail.com
            </a>
        </div>

        <!-- Back to Home Button -->
        <div class="mt-12">
            <a href="index.php"
                class="inline-block px-8 py-4 text-lg font-semibold text-white transition-colors duration-200 bg-blue-500 rounded-lg hover:bg-blue-600">
                Return to Dashboard
            </a>
        </div>
    </div>

    
</body>
</html>