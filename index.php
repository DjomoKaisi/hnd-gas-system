<?php
require_once "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QuickGas | Delivery</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  
  <link rel="stylesheet" href="assets/style.css" />

  <style>
    /* Animations CSS Supplémentaires */
    .hero-image img {
        animation: float 6s ease-in-out infinite;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    .step-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .step-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-color: #27ae60;
    }

    .brand-card:hover img {
        transform: scale(1.1) rotate(5deg);
        transition: 0.3s;
    }

   

    .btn:active { transform: scale(0.95); }

    /* Nettoyage de la map */
    .map-container {
        margin-top: 50px;
        filter: grayscale(100%);
        transition: 0.5s;
    }
    .map-container:hover { filter: grayscale(0%); }
  </style>
</head>
<body>

  <header class="site-header">
    <div class="container header-inner">
      <div class="brand" data-aos="fade-right">
        <div class="brand-mark"></div>
        <h2 class="brand-name">QuickGas</h2>
      </div>

      <div data-aos="fade-left">
        <a href="register.php" class="btn btn-login">Register</a>
      </div>
    </div>
  </header>

 <section class="hero-slider-section">
  <div class="slider-container">
    
    <div class="slide active" style="background-image: url('car.jpg');">
      <div class="slide-overlay"></div>
      <div class="container slide-content">
        <h1 class="slide-title">Le gaz livré à <span style="color: #2ecc71;">votre porte</span></h1>
        <p class="slide-text">Rapide, fiable et pratique. Commandez votre bouteille en quelques clics.</p>
        <div class="slide-buttons">
            <a href="customer/dashboard.php" class="btn btn-primary">Commander</a>
        </div>
      </div>
    </div>

    <div class="slide" style="background-image: url('kitchen.jpg');"> <div class="slide-overlay"></div>
      <div class="container slide-content">
        <h1 class="slide-title">La sécurité <span style="color: #f1c40f;">avant tout</span></h1>
        <p class="slide-text">Des fournisseurs certifiés et des livreurs formés pour votre tranquillité d'esprit.</p>
        <div class="slide-buttons">
            <a href="about.php" class="btn btn-secondary" style="background: white; color: #333;">En savoir plus</a>
        </div>
      </div>
    </div>

    <div class="slide" style="background-image: url('restaurant.jpg');"> <div class="slide-overlay"></div>
      <div class="container slide-content">
        <h1 class="slide-title">Solutions pour <span style="color: #3498db;">Professionnels</span></h1>
        <p class="slide-text">Restaurants, hôtels, industries : nous gérons vos approvisionnements de A à Z.</p>
        <div class="slide-buttons">
            <a href="contact.php" class="btn btn-primary">Nous contacter</a>
        </div>
      </div>
    </div>

    <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
    <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
    
    <div class="slider-dots">
      <span class="dot active" onclick="currentSlide(0)"></span>
      <span class="dot" onclick="currentSlide(1)"></span>
      <span class="dot" onclick="currentSlide(2)"></span>
    </div>
  </div>
</section>

  <section class="how-it-works">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <h2>How it works</h2>
        <p>Three simple steps to get your gas</p>
      </div>

      <div class="steps-grid">
        <div class="step-card" data-aos="fade-up" data-aos-delay="100">
          <div class="icon-circle" style="background: #e8f5e9; font-size: 2rem;">🛒</div>
          <h3>1. Order</h3>
          <p>Place your order online through our easy-to-use platform.</p>
        </div>

        <div class="step-card" data-aos="fade-up" data-aos-delay="300">
          <div class="icon-circle" style="background: #fff3e0; font-size: 2rem;">👤</div>
          <h3>2. We assign</h3>
          <p>We link you to the nearest verified supplier and delivery agent.</p>
        </div>

        <div class="step-card" data-aos="fade-up" data-aos-delay="500">
          <div class="icon-circle" style="background: #e3f2fd; font-size: 2rem;">🚚</div>
          <h3>3. Delivered</h3>
          <p>Get your gas cylinder delivered right to your doorstep.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="brands-section" style="background: #f9f9f9; padding: 60px 0;">
    <div class="container">
      <h2 class="brands-title" data-aos="fade-right">Popular Brands</h2>

      <div class="brands-grid">
        <div class="brand-card" data-aos="flip-left" data-aos-delay="100">
          <div class="brand-logo">
            <img src="total.jpg" alt="Total" />
          </div>
          <span>Total</span>
        </div>

        <div class="brand-card" data-aos="flip-left" data-aos-delay="200">
          <div class="brand-logo">
            <img src="bocom.jpg" alt="Bocom" />
          </div>
          <span>Bocom</span>
        </div>

        <div class="brand-card" data-aos="flip-left" data-aos-delay="300">
          <div class="brand-logo">
            <img src="tradex.jpg" alt="Tradex" />
          </div>
          <span>Tradex</span>
        </div>
      </div>
    </div>
  </section>

  <section class="map-container" data-aos="fade-up">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15919.297441584282!2d9.711!3d4.05!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNMKwMDMnMDAuMCJOIDnCsDQyJzM5LjYiRQ!5e0!3m2!1sfr!2scm!4v1647874561234!5m2!1sfr!2scm" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
  </section>

  <footer class="site-footer">
    <div class="container footer-inner">
      <div class="footer-left">
        <div class="footer-brand">
          <div class="brand-mark"></div>
          <h3>GASORDER</h3>
        </div>
        <p>Fueling your convenience since 2026</p>
      </div>
      <div class="footer-copy">
        <p>© 2026 QuickGas Inc. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000, // Durée de l'animation (1s)
      once: true,     // L'animation ne se joue qu'une seule fois
      easing: 'ease-out-back'
    });
  </script>
  <script>
  let slideIndex = 0;
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  let sliderInterval;

  function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));

    slideIndex = index;
    
    // Boucler si on dépasse les limites
    if (slideIndex >= slides.length) slideIndex = 0;
    if (slideIndex < 0) slideIndex = slides.length - 1;

    slides[slideIndex].classList.add('active');
    dots[slideIndex].classList.add('active');
  }

  function nextSlide() {
    showSlide(slideIndex + 1);
  }

  function prevSlide() {
    showSlide(slideIndex - 1);
  }

  function currentSlide(index) {
    showSlide(index);
    resetInterval(); // Réinitialise le timer si l'utilisateur clique
  }

  function startInterval() {
    sliderInterval = setInterval(nextSlide, 5000); // Change toutes les 5 secondes
  }

  function resetInterval() {
    clearInterval(sliderInterval);
    startInterval();
  }

  // Écouteurs d'événements pour les boutons
  document.querySelector('.next-btn').addEventListener('click', () => {
    nextSlide();
    resetInterval();
  });
  
  document.querySelector('.prev-btn').addEventListener('click', () => {
    prevSlide();
    resetInterval();
  });

  // Démarrer le slider
  startInterval();
</script>
</body>
</html>