<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Device Login</title>
    <style>
        .bg-gray-100 {
            background-color: #f7fafc;
        }
        .bg-blue-600 {
            background-color: #2563eb;
        }
        .text-blue-600 {
            color: #2563eb;
        }
        .text-white {
            color: #fff;
        }
        .text-gray-700 {
            color: #4a5568;
        }
        .font-bold {
            font-weight: 700;
        }
        .font-semibold {
            font-weight: 600;
        }
        .text-center {
            text-align: center;
        }
        .text-3xl {
            font-size: 1.875rem;
        }
        .text-4xl {
            font-size: 2.25rem;
        }
        .p-4 {
            padding: 1rem;
        }
        .p-6 {
            padding: 1.5rem;
        }
        .p-8 {
            padding: 2rem;
        }
        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .px-8 {
            padding-left: 2rem;
            padding-right: 2rem;
        }
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        .mb-6 {
            margin-bottom: 1.5rem;
        }
        .mb-8 {
            margin-bottom: 2rem;
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .overflow-hidden {
            overflow: hidden;
        }
        .inline-flex {
            display: inline-flex;
        }
        .justify-center {
            justify-content: center;
        }
        .whitespace-nowrap {
            white-space: nowrap;
        }
        .bg-blue-500 {
            background-color: #2563eb;
        }
        .bg-blue-600 {
            background-color: #3b82f6;
        }
        .text-sm {
            font-size: 0.875rem;
        }
        .bg-white {
            background-color: #fff;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto my-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 py-6 px-8">
            <h1 class="text-3xl font-bold text-white text-center">STA. MARTA EDUCATIONAL CENTER INC.</h1>
        </div>
        <div class="p-8">
            <p class="text-gray-700 mb-6">Hello, <?php echo htmlspecialchars($name); ?>!</p>
            <p class="text-gray-700 mb-6">A new device has been used to log into your account. If this was you, please note the following details of the login:</p>
            <div class="bg-gray-100 rounded-lg p-4 mb-6">
                <p class="text-4xl font-bold text-center text-blue-600">Location: <?php echo htmlspecialchars($location); ?></p>
                <p class="text-4xl font-bold text-center text-blue-600">Date: <?php echo htmlspecialchars($date); ?></p>
            </div>
            <p class="text-gray-700 mb-6">If you didn't request this login, please secure your account immediately.</p>
            <p class="text-gray-700">Thank you for using our service!</p>
        </div>
        <div class="bg-gray-100 py-4 px-8">
            <p class="text-sm text-gray-600 text-center">&copy; 2024 Sta. Marta Educational Center Inc. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
