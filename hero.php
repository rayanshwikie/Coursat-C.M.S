<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include 'links.php';
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Hero Carousel</title>
</head>
<body>
    <div class="carousel-container">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/banner1.jpg" class="d-block w-100" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="img/banner2.jpg" class="d-block w-100" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="img/banner3.jpg" class="d-block w-100" alt="Slide 3">
                </div>
            </div>
        </div>
        <div class="overlay-wrapper">
            <div class="overlay animate__animated animate__fadeInUp">
                <h1 class="display-1 logo">COURSAT</h1>
                <br>
                <h1 class="display-3">Learn With no Limits</h1>
                <br>
                <p class="py-1 lead-h">In here you will be able to grow your skills.</p>
                <br>
                <a href="#whyus">
                    <button type="button" class="btn btn-primary btn-lg rounded-5">Why us</button>
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
