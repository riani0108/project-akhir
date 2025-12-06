<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{$title}}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fugaz+One&family=Poppins:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset('front-end/lib/animate/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('front-end/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('front-end/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{asset('front-end/css/style.css')}}" rel="stylesheet">

    <!-- Link ke Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg px-5 py-3 fixed-navbar">
        <a href="{{route('home.index')}}" class="navbar-brand d-flex align-items-center">
            <h1 class="display-5 mb-0 logo-text">P2PWeb</h1>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler custom-toggler" type="button" onclick="toggleNav()">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Menu -->
        <div class="navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link {{ request()->routeIs('home.index') ? 'active' : '' }}">Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('about')}}" class="nav-link {{ request()->routeIs('about.index') ? 'active' : '' }}">About</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('hitung')}}" class="nav-link {{ request()->routeIs('hitung.index') ? 'active' : '' }}">Hitung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/contact')}}">Contact</a>
                </li>
            </ul>
        </div>
    </nav>



    <!-- konten halaman home -->
    @if($title ==='Home')
    @yield('banner')
    @yield('about')
    @endif

    <!-- konten halaman lain -->
    @if($title === 'Hitung')
    @yield('hitung')
    @endif


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('front-end/lib/wow/wow.min.js')}}"></script>
    <script src="{{asset('front-end/lib/owlcarousel/owl.carousel.min.js')}}"></script>
    <script>
        function toggleNav() {
            document.getElementById("navbarMenu").classList.toggle("show");
        }
    </script>




    <!-- Template Javascript -->
    <script src="{{asset('front-end/js/main.js')}}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const navLinks = document.querySelectorAll(".navbar-expand-lg .nav-link");

            // Ambil active link dari localStorage
            const activeLink = localStorage.getItem("activeNavLink");

            // Jika ada active tersimpan → tandai
            if (activeLink) {
                navLinks.forEach(link => {
                    if (link.href === activeLink) {
                        link.classList.add("active");
                    }
                });
            }

            // Tambahkan event klik
            navLinks.forEach(link => {
                link.addEventListener("click", function() {

                    // Hapus active dari semua
                    navLinks.forEach(l => l.classList.remove("active"));

                    // Tambahkan active pada yang diklik
                    this.classList.add("active");

                    // Simpan ke localStorage
                    localStorage.setItem("activeNavLink", this.href);
                });
            });
        });
    </script>


</body>

</html>