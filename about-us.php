<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1c1d1f">
    <link rel="stylesheet" href="/assets/other/other-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&family=Poppins:wght@200;300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
        rel="stylesheet">
    <title>About Us - GRAMBABA</title>
</head>

<body>
    <!-- header -->
    <header class="fixed">
        <div class="container">
            <div class="logoWrapper">
                <!-- <a href="/">GRAMBABA</a> -->
                <a href="/"><img src="logo-grambaba.svg" alt="grambaba logo"></a>
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
            <button class="mobileMenu"
                onclick="this.classList.toggle('opened');this.setAttribute('aria-expanded', this.classList.contains('opened')); mobileMenu();"
                aria-label="Main Menu">
                <svg width="38" height="38" viewBox="0 0 100 100">
                    <path class="line line1"
                        d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058" />
                    <path class="line line2" d="M 20,50 H 80" />
                    <path class="line line3"
                        d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942" />
                </svg>
            </button>
        </div>
    </header>




    <section class="about-us">
        <span>
            <h1>GRAMBABA</h1>
        </span>
        <div class="container">
            <h2><strong>How I started?</strong></h2>
            <p>In 2018 my friends wanted to gain followers on Instagram and they give their confidential account data to
                a site to gain likes, views, and as well as followers. But their account is used by many sites to follow
                and like other posts, also Instagram freeze their account for some time.

                In the same year, I searched how social media marketing works and I started selling SMM services on
                Instagram by direct message to users.

                After pursuing B.Tech I don’t have enough time to continue this job. So I started making a
                site(GRAMBABA) where people reach more users and grow their “appearance” without giving Instagram
                account passwords.
            </p>
        </div>
    </section>















    <!-- footer -->
    <footer class="footer">
        <div class="topFooter">
            <div class="footerMenuList">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/buy-instagram-likes/">Buy Instagram Likes</a></li>
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
                <p>All logos and trademarks of third parties referenced on grambaba.com are the trademarks and logos of
                    their respective owners. All rights belong to their owners. We are not affiliated with Instagram.
                </p>
            </div>
        </div>
        <div class="bottomFooter">
            <p class="copyright">Made with code and coffee by Abhishek Pathak.</br>&copy; Copyright 2021, All rights
                reserved.</p>
        </div>
    </footer>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
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
        (function () {


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



        (function (window, $) {
            $(function () {
                $('.ripple').on('click', function (event) {
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

                    window.setTimeout(function () {
                        $div.remove();
                    }, 2000);
                });

            });

        })(window, jQuery);

    </script>
</body>

</html>