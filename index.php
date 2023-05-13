<?php
include "./utils/utils.php";
include "./utils/ssl.php";
include "./utils/portScanner.php";
include "./utils/notion.php";

$url = formatURL($_POST["url"] ?? "");

if (isset($_POST['ssl'])) {
    $ssl = getSSLCertificate($url);
} else if (isset($_POST['ports'])) {
    $ports = fastPortScanner($url);
}



if (isset($_POST['schedule'])) {

    // Example usage
    $databaseId = "ecef73c7f191419c91a7277efde6b88f";
    $apiKey = "";
    $domain = $_POST["link"];
    $expiration = $_POST["expiration"];
    $email = $_POST["email"];

    $result = insertDataToNotion($databaseId, $apiKey, $domain, $expiration, $email);

    $email_success = "تم إضافة الشهادة بنجاح";
    header("Location: ./");
}




?>


<!DOCTYPE html>
<html>

<head>
    <title>أداة فحص شهادة SSL</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSL/Port Scanning Tool</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./script.js"></script>
</head>

<body dir="trl">

    <div class="w-full z-50 backdrop-blur-md  bg-gray-300/75 sticky top-0 shadow overflow-hidden">
        <div class="max-w-2xl mx-auto flex justify-between items-center px-2 sm:px-0 ">

            <div class="flex items-center space-x-4 py-2">
                <a class="px-2 py-1 rounded-md hover:bg-red-300 block text-lg text-red-500" href="#">Home</a>
                <a class="px-2 py-1 rounded-md hover:bg-red-300 block text-lg text-red-500" href="#tool">the tool</a>
                <a class="px-2 py-1 rounded-md hover:bg-red-300 text-lg hidden sm:block text-red-500" href="#about">about tool</a>
            </div>


            <span>Your Name</span>

        </div>

    </div>


    <div class="min-h-screen flex flex-col justify-center items-stretch w-full overflow-hidden px-2 sm:px-0">
        <div class="max-w-4xl mx-auto rounded-lg bg-white shadow-lg my-8 sm:hover:scale-110 transform duration-300 ">
            <div class="max-w-2xl mx-auto  p-4 text-center">
                <h1 class="text-3xl py-8 text-red-500">Welcome to this Tool</h1>

                <p class="text-lg leading-8">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates tempora nisi, alias reprehenderit eveniet delectus accusamus debitis ex vitae cum architecto accusantium iure, non impedit nulla assumenda laudantium doloremque laboriosam.
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates tempora nisi, alias reprehenderit eveniet delectus accusamus debitis ex vitae cum architecto accusantium iure, non impedit nulla assumenda laudantium doloremque laboriosam.
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates tempora nisi, alias reprehenderit eveniet delectus accusamus debitis ex vitae cum architecto accusantium iure, non impedit nulla assumenda laudantium doloremque laboriosam.
                </p>
            </div>
        </div>
    </div>





    <div id="tool" class="h-24"></div>
    <div class="container max-w-2xl mx-auto mt-4 overflow-hidden">
        <form   id="check-form" method="POST" action="./#tool">
            <label for="url-input">أدخل رابط الموقع</label>
            <input type="text" id="url-input" name="url" required>
            <div class="flex items-center justify-center space-x-7">
                <input class="bg-red-500" type="submit" name="ssl" value="ssl فحص">
                <input id="tool" class="bg-red-500" type="submit" name="ports" value="فحص ports">
            </div>
        </form>


        <?php if (isset($ports)) { ?>
            <div class="mt-8">
                <h1 class="text-xl leading-loose">Open ports for <span class="font-mono"><?= $url ?></span></h1>
                <table id=" resultTable" class="table-auto w-full mb-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Port</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody id="resultRows" class="odd:bg-gray-100">

                        <?php foreach ($ports as $port => $status) { ?>
                            <tr class="odd:bg-gray-100">
                                <td class="px-4 py-2"><?php echo $port ?></td>

                                <?php if ($status == "open") { ?>
                                    <td class="px-4   "><span class="py-2 px-2 before:transform before:-skew-y-6 text-white  relative before:z-0 before:absolute before:inset-0 before:content-[''] before:bg-red-500"><span class="relative z-30"><?php echo $status ?></span></span></td>
                                <?php } else { ?>
                                    <td class="px-4 py-4"><?= $status ?></td>
                                <?php } ?>
                            </tr>

                        <?php } ?>
                        <!-- Add more result rows here -->
                    </tbody>
                </table>
            </div>

        <?php } ?>



        <?php if (isset($ssl)) { ?>
            <div class="mt-8">
                <h1 class="text-xl leading-loose">SSL certificate expiration date for <span class="font-mono"><?= $url ?></span></h1>

                <div class="flex items-center w-full space-x-12 mt-4">
                    <img id="ssl-cert" class="w-24" src="./icon.png" alt="SSL certificate">

                    <div class="text-left">
                        <h1 id="ssl-expiration" class="text-lg leading-loose">site name: <b class="font-mono"><?= $ssl["domain"] ?></b></h1>
                        <h1 id="ssl-expiration" class="text-lg leading-loose">from: <b class="font-mono"><?= $ssl["issuer"] ?></b></h1>
                        <h1 id="ssl-expiration" class="text-lg leading-loose">expires at <b class="font-mono"><?= $ssl["expires"] ?></b></h1>
                    </div>



                </div>

                <form method="POST" class="mt-4 border-t-2 border-dashed border-gray-400 pt-4">
                    <h1 class="text-xl leading-loose">Reminder</h1>
                    <p class="text">get reminded for when the SSL</p>
                    <div class="space-y-2 flex  flex-col sm:flex-row sm:space-y-0 sm:space-x-2">
                        <input type="hidden" name="link" value="<?= $url ?>" class="hidden">
                        <input type="hidden" name="expiration" value="<?= $ssl["reminder"] ?>" class="hidden">
                        <input type="email" id="emailReminder" name="email" placeholder="Email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <input value="<?= $ssl["reminder"] ?>" type="date" id="emailReminder" placeholder="Date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <button type="submit" name="schedule" class="mt-4 text-neutral-900 border-2 border-black rounded-md font-bold py-2 px-4 ">
                        Schedule Now
                    </button>
                    <div class="flex items-center space-x-2 mt-4">
                        <div class="flex-1 border-b border-gray-500">

                        </div>
                        <span>OR</span>
                        <div class="flex-1 border-b border-gray-500">

                        </div>
                    </div>

                    <a target="_blank" href="<?= makeGoogleCalenderLink('Expiring SSL for ' . $url, $ssl["reminder"]) ?>" id="googleCalendarButton" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Add to Google Calendar
                    </a>
                </form>

            </div>
        <?php } ?>



    </div>

    <div class="min-h-screen flex flex-col justify-center  w-full px-2 sm:px-0 overflow-hidden">
        <div id="about" class="max-w-4xl mx-auto rounded-lg bg-white shadow-lg my-8 sm:hover:scale-110 transform duration-300 ">
            <div class="max-w-2xl mx-auto  p-4 text-center">
                <h1 class="text-3xl py-8 text-red-500">Welcome to this Tool</h1>

                <p class="text-lg leading-8">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates tempora nisi, alias reprehenderit eveniet delectus accusamus debitis ex vitae cum architecto accusantium iure, non impedit nulla assumenda laudantium doloremque laboriosam.
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates tempora nisi, alias reprehenderit eveniet delectus accusamus debitis ex vitae cum architecto accusantium iure, non impedit nulla assumenda laudantium doloremque laboriosam.
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates tempora nisi, alias reprehenderit eveniet delectus accusamus debitis ex vitae cum architecto accusantium iure, non impedit nulla assumenda laudantium doloremque laboriosam.
                </p>
            </div>
        </div>
    </div>


</body>

</html>