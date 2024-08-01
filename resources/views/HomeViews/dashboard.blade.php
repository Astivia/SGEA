@extends('layouts.master')
    <title>Inicio</title>
    <link rel="stylesheet" href="./css/style-home.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
</head>
@section('Content')
            
            
            <div class="container">
                <h1>Panel Principal</h1>
                <br>
                <div class="carousel">
                    <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="card">
                                    <div class="infoCard">
                                        <div class="header-img">
                                            <img src="{{asset('SGEA/public/assets/img/lgoTec.png')}}" alt="">
                                        </div>
                                        <h1>Eventos Disponibles</h1>

                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card">
                                <div class="infoCard">
                                <div class="header-img">
                                            <img src="{{asset('SGEA/public/assets/img/lgoTec.png')}}" alt="">
                                        </div>
                                        <h1>Articulos Disponibles</h1>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card">
                                <div class="infoCard">
                                <div class="header-img">
                                            <img src="{{asset('SGEA/public/assets/img/lgoTec.png')}}" alt="">
                                        </div>
                                        <h1>Talleres Disponibles</h1>
                                        
                                    </div>
                                </div>
                            </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                
            </div>
        
   <script>


document.addEventListener("DOMContentLoaded", function () {
    let currentIndex = 0;
    const items = document.querySelectorAll(".carousel-item");
    const totalItems = items.length;

    function updateCarousel() {
        const inner = document.querySelector(".carousel-inner");
        inner.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateCarousel();
    }

    document.querySelector(".carousel-control-next").addEventListener("click", () => {
        clearInterval(autoSlide);
        showNext();
        autoSlide = setInterval(showNext, 2000);
    });

    document.querySelector(".carousel-control-prev").addEventListener("click", () => {
        clearInterval(autoSlide);
        showPrev();
        autoSlide = setInterval(showNext, 2000);
    });

    let autoSlide = setInterval(showNext, 2000);
});
   </script>
@endsection

