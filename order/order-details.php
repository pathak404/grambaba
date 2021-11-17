<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1c1d1f">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&family=Poppins:wght@200;300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <title>Order Details - GRAMBABA</title>
</head>

<body>
    <!-- header -->
    <header class="fixed">
        <div class="container">
            <div class="logoWrapper">
                <!-- <a href="/">GRAMBABA</a> -->
                <a href="/"><img src="../logo-grambaba.svg" alt="grambaba logo"></a>
            </div>
            <nav role="navigation">
                <ul class="navMenuList">
                    <li><a href="/">Home</a></li>
                    <li><a href="/buy-instagram-views/">Buy Instagram Views</a></li>
                    <li><a href="/buy-instagram-likes/">Buy Instagram Likes</a></li>
                    <li><a href="/buy-instagram-reel-views/">Buy Instagram Reel Views</a></li>
                    <li><a href="/buy-instagram-reel-likes/">Buy Instagram Reel Likes</a></li>
                    <li>
                        <button class="theme-btn" onclick="toggleTheme();">
                            <span class="material-icons-outlined theme-btn">light_mode</span>
                        </button>
                    </li>

                </ul>
            </nav>
            <button class="mobileMenu" onclick="this.classList.toggle('opened');this.setAttribute('aria-expanded', this.classList.contains('opened')); mobileMenu();" aria-label="Main Menu">
                <svg width="38" height="38" viewBox="0 0 100 100">
                    <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" />
                    <path class="line line2" d="M 20,50 H 80" />
                    <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" />
                </svg>
            </button>
        </div>
    </header>









    <?php

    require '../vendor/autoload.php';
    require 'services.php';
    require_once('NonceUtil.php');
    define('NONCE_SECRET', 'waitwaitwaitbrocreateorder');

    use Google\Cloud\Firestore\FirestoreClient;
    use Kreait\Firebase\Factory;

    $secret = 'CEwLD5qaYUYQUBJWXIhKewpg';

    //   check if empty
    if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST['paymentid']) || empty($_POST['nonce']) || empty($_POST['orderiddata'])) {
        header("Location: https://grambaba.com");
        die();
    }




    function cleanInput($input, $isINT = false, $max_char = false)
    {
        if ($isINT) {
            $result = preg_replace('/\D/', '', $input);
            return (int)$result;
        } elseif (!$isINT) {
            $search = "/[^-a-zA-Z0-9\\/\\:\\?\\.\\_\\-]+/i";
            $subst = "";
            $result = preg_replace($search, $subst, $input);
            if ($max_char) {
                $result = substr($result, 0, $max_char);
            }
            return $result;
        } else {
            return false;
        }
    }


    $paymethod = "razorpay";
    $payid = cleanInput($_POST['paymentid']);
    $serviceid = cleanInput($_POST['serviceid'], true);
    $quantity = cleanInput($_POST['quantitydata'], true);
    $iglink = cleanInput($_POST['iglinkdata']);
    $price = $_POST['pricedata'];
    $ENCOrderID = cleanInput($_POST['orderiddata']);
    $rzSign = cleanInput($_POST['signature']);
    $nonceDataValue = cleanInput($_POST['nonce']);


    // realtime db auth
    $factory = (new Factory)
        ->withServiceAccount('grambaba-fb-firebase-adminsdk-53qcu-5cffdc1458.json')
        ->withDatabaseUri('https://grambaba-fb-default-rtdb.asia-southeast1.firebasedatabase.app/');


    $auth = $factory->createAuth();
    $realtimeDatabase = $factory->createDatabase();

    $reference = $realtimeDatabase->getReference("/orderReference/{$ENCOrderID}-gb");
    $orderReferenceObj = $reference->getSnapshot()->getValue();
    if ($orderReferenceObj == NULL) {
        header("Location: https://grambaba.com");
        die();
    }

    $fullNonce = "{$orderReferenceObj['salt']},{$orderReferenceObj['time']},{$nonceDataValue}";

    if (NonceUtil::check(NONCE_SECRET, $fullNonce)) {





        $service = $allServices[$serviceid]["name"];
        $trimmedServiceName = trim(explode('-', $service, -1)[0]);
        $priceOnServer = $allServices[$serviceid]["priceUponQuantity"][$quantity];
        $apiname = $allServices[$serviceid]["apiname"];




        $rzorder = $orderReferenceObj['rzorder'];
        $rzstr = $rzorder . "|" . $payid;
        $gen_sign = hash_hmac('sha256', $rzstr, $secret);
        // check price match and signature match
        if ($gen_sign == $rzSign && $price == $priceOnServer) {

            // get order Id
            $reference = $realtimeDatabase->getReference('newOrderID');
            $OrderValueObj = $reference->getSnapshot()->getValue();
            $orderid = $OrderValueObj["ID"];
            // increase and save
            $reference->update(["ID" => $orderid + 1]);

            $db = new FirestoreClient([
                'projectId' => 'grambaba-fb',
            ]);
            $orderRef = $db->collection('orders');
            $docRef = $orderRef->document("order-{$orderid}");


            // $api = new SMARTApi($smart_api_providers[$apiname]);
            // $api_order = $api->order(array('service' => $serviceid, 'link' => $iglink, 'quantity' => $quantity));
            // if ($api_order->order) {
            // $docRef->set([
            //     'paymentmethod' => $paymethod,
            //     'paymentid' => $payid,
            //     'rzorder' => $rzorder,
            //     'iglink' => $iglink,
            //     'service' => $service,
            //     'quantity' => $quantity,
            //     'price' => $price,
            //     'apiname' => $apiname,
            //     'apiorder' => $api_order->order,

            // ]);

            // } else {
            $docRef->set([
                'paymentmethod' => $paymethod,
                'paymentid' => $payid,
                'rzorder' => $rzorder,
                'iglink' => $iglink,
                'service' => $service,
                'quantity' => $quantity,
                'price' => $price,
                'apiname' => "not send",
                'apiorder' => 0,

            ]);
            // }
            // echo "success";
            $realtimeDatabase->getReference("/orderReference/{$ENCOrderID}-gb")->remove();
            onsuccess($orderid, $iglink, $quantity, $trimmedServiceName, $price);
        } else {
            // echo "invalid signature or price.";
            onerror($trimmedServiceName, "Error: Signature not verified :(");
        }
    } else {
        onerror($trimmedServiceName, "Error: Invalid Nonce :(");
    }











    function onsuccess($orderid, $iglink, $quantity, $service, $price)
    {

        echo '
        <section class="orderDetails">
            <div class="container">
    
                <span><svg class="contactResponseSVG" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="contactResponseSVG_circle" cx="26" cy="26" r="25" fill="none" />
                    <path class="contactResponseSVG_check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                </svg></span>
    
                <h2> Order Placed Successfully </h2> 
    
                <table>
                    <tr>
                        <th>Order ID</th>
                        <td>' . $orderid . '</td>
                    </tr>
                    <tr>
                        <th>Service</th>
                        <td>' . $service . '</td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td>' . $quantity . '</td>
                    </tr>
                    <tr>
                        <th>Instagram Link</th>
                        <td><a href="' . $iglink . '" target="_blank">' . $iglink . '</a></td>
                    </tr>
                    <tr>
                        <th>Total Price</th>
                        <td>' . $price . '</td>
                    </tr>
                </table>
                <div class="contactFormResponseBtn">
                <a href="/" class="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>Home</a>
                <a href="/buy-' . str_replace(' ', '-', strtolower($service)) . '/' . '" class="button">Buy Again<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></a>
            </div>
            </div>
        </section>';
    }




    function onerror($service, $message)
    {

        echo '<section class="orderDetails">
        <div class="container">

            <span><svg class="contactResponseErrorSVG" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="contactResponseError_circle" cx="26" cy="26" r="25" fill="none" /><path class="contactResponseSVG_check" fill="none" d="M16 16 36 36 M36 16 16 36" /></svg></span>
            <h2>' . $message . '</h2> 

            <div class="contactFormResponseBtn">
            <a href="/" class="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>Home</a>
            <a href="/buy-' . str_replace(' ', '-', strtolower($service)) . '/' . '" class="button">Buy Again<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></a>
        </div>
        </div>
    </section>';
    }


    ?>
















    <!-- footer -->
    <footer class="footer">
        <div class="topFooter">
            <div class="footerMenuList">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/buy-instagram-views/">Buy Instagram Likes</a></li>
                    <li><a href="/buy-instagram-views/">Buy Instagram Views</a></li>
                    <li><a href="/buy-instagram-reel-views/">Buy Instagram Reel Views</a></li>
                    <li><a href="/buy-instagram-reel-likes/">Buy Instagram Reel Likes</a></li>
                    <li><a href="/privacy-and-refund-policy/">Privacy & Refund Policy</a></li>
                    <li><a href="/terms-and-conditions/">Terms & Conditions</a></li>
                    <li><a href="/contact-us/">Contact Us</a></li>
                    <li><a href="/about-us/">About Us</a></li>
                </ul>
            </div>

            <div class="notice">
                <p>All logos and trademarks of third parties referenced on grambaba.com are the trademarks and logos of their respective owners. All rights belong to their owners. We are not affiliated with Instagram.
                </p>
            </div>
        </div>
        <div class="bottomFooter">
            <p></p>
            <p class="copyright">Made with code and coffee by Abhishek Pathak.</br>&copy; Copyright 2021, All rights
                reserved.</p>
        </div>
    </footer>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        const themeBtnIcon = document.querySelector("button.theme-btn span");

        function setTheme(themeName) {
            localStorage.setItem('theme', themeName);
            document.documentElement.className = themeName;
            if (themeName == "dark_mode")
                themeBtnIcon.innerHTML = "light_mode";
            else
                themeBtnIcon.innerHTML = "dark_mode";
        }


        function toggleTheme() {

            if (localStorage.getItem('theme') === 'dark_mode') {
                setTheme('light_mode');
                themeBtnIcon.innerHTML = "dark_mode";
                changeLogoOnThemeChange("light_mode");
            } else {
                setTheme('dark_mode');
                themeBtnIcon.innerHTML = "light_mode";
                changeLogoOnThemeChange("dark_mode");
            }
        }

        // Immediately invoked function to set the theme on initial load
        (function() {


            if (localStorage.getItem('theme') === 'dark_mode') {
                setTheme('dark_mode');
                themeBtnIcon.innerHTML = "light_mode";
                changeLogoOnThemeChange("dark_mode");
            } else {
                setTheme('light_mode');
                themeBtnIcon.innerHTML = "dark_mode";
                changeLogoOnThemeChange("light_mode");
            }
        })();

        function changeLogoOnThemeChange($theme){
            var logo = document.querySelector(".logoWrapper a img");
            if($theme === "dark_mode"){
                logo.src = "https://grambaba.com/logo-grambaba-white.svg";
            }else{
                logo.src = "https://grambaba.com/logo-grambaba.svg";
            }
        }




        // mobile menu
        function mobileMenu() {
            document.body.classList.toggle("modal-active");
            document.querySelector("header nav").classList.toggle("open-menu");
        }



        (function(window, $) {
            $(function() {
                $('.ripple').on('click', function(event) {
                    event.preventDefault();
                    var $btn = $(this),
                        $div = $('<div/>'),
                        btnOffset = $btn.offset(),
                        xPos = event.pageX - btnOffset.left,
                        yPos = event.pageY - btnOffset.top;

                    $div.addClass('ripple-effect');
                    $div
                        .css({
                            height: $btn.height(),
                            width: $btn.height(),
                            top: yPos - ($div.height() / 2),
                            left: xPos - ($div.width() / 2),
                            background: $btn.data("ripple-color") || "#fff"
                        });
                    $btn.append($div);

                    window.setTimeout(function() {
                        $div.remove();
                    }, 2000);
                });

            });

        })(window, jQuery);
    </script>

</body>

</html>