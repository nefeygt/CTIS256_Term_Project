<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <main class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
        <form action="" method="post" class="flex flex-col space-y-4">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Enter Verification Code:</label>
                <input type="text" name="code" id="code" placeholder="Enter code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <input type="submit" value="Verify" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
        </form>
    </main>
</body>
</html>
