@extends('layouts.master')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Inicio</title>
@section('Content')
    <div class="container">
        <h1>Panel Principal</h1>
        <br>
        <div class="carousel">
            <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="card">
                            <div class="profile-header">                                        
                                    <img src="{{asset('SGEA/public/assets/img/lgoTec.png')}}" alt="">                                        
                                    <h2 class="name">Eventos Disponibles</h2>
                            </div>
                            <div class="profile-body">
                                <p class="descripcion">Estos son los eventos disponibles</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="card">
                            <div class="profile-header">                                        
                                        <img src="{{asset('SGEA/public/assets/img/lgoTec.png')}}" alt="">                                        
                                        <h2 class="name">Articulos Disponibles</h2>
                                </div>
                                <div class="profile-body">
                                    <p class="descripcion">Estos son los articulos disponibles</p>
                                </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="card">
                        <div class="profile-header">                                        
                                    <img src="{{asset('SGEA/public/assets/img/lgoTec.png')}}" alt="">                                        
                                    <h2 class="name">Autores Disponibles</h2>
                            </div>
                            <div class="profile-body">
                                <p class="descripcion">Estos son los autores que han publicado articulos</p>
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
@endsection

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

