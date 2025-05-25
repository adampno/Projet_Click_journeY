const images = [
    {
      src: 'assets/grandemuraille.jpg',
      text: "Une gigantesque fortification longue de plus de 20 000 km, construite pour protéger la Chine des invasions. C'est l'un des symboles les plus emblématiques du pays."
    },
    {
      src: 'assets/petra.jpg',
      text: "Une ancienne cité taillée dans la roche rose au cœur du désert jordanien. Elle témoigne du génie architectural des Nabatéens."
    },
    {
      src: 'assets/christcar.jpeg',
      text: "La célèbre statue du Christ dominant Rio de Janeiro du haut du mont Corcovado. Symbole de paix et de foi au cœur du Brésil."
    },
    {
      src: 'assets/machupicchucar.jpg',
      text: "Une ancienne cité inca perchée dans les montagnes des Andes. Mystérieuse et majestueuse, elle est un chef-d'œuvre d'ingénierie et d'architecture."
    },
    {
      src: 'assets/chichen_itza.jpg',
      text: "Une impressionnante cité maya au Yucatán, célèbre pour sa pyramide de Kukulcán et son héritage astronomique et culturel."
    },
    {
      src: 'assets/colisee.jpg',
      text: "Ancien amphithéâtre romain situé à Rome. Ce monument historique témoigne de la grandeur de l'Empire romain et de ses spectacles."
    },
    {
      src: 'assets/tajmahalcar.jpg',
      text: "Un magnifique mausolée blanc construit par amour, mêlant architecture moghole, persane et indienne. Il est le symbole romantique de l'Inde."
    }
  ];
  
  let currentIndex = 0;
  let intervalId; // Pour contrôler l'intervalle automatique
  
  function updateCarousel() {
    const imageElement = document.getElementById('carousel-image');
    const textElement = document.getElementById('carousel-text');
  
    // Effet de fondu
    imageElement.style.opacity = 0;
    textElement.style.opacity = 0;
  
    setTimeout(() => {
      imageElement.src = images[currentIndex].src;
      textElement.textContent = images[currentIndex].text;
  
      imageElement.style.opacity = 1;
      textElement.style.opacity = 1;
    }, 300);
  }
  
  function goToSlide(index) {
    currentIndex = (index + images.length) % images.length;
    updateCarousel();
    resetTimer();
  }
  
  function prevSlide() {
    goToSlide(currentIndex - 1);
  }
  
  function nextSlide() {
    goToSlide(currentIndex + 1);
  }
  
  function startAutoSlide() {
    intervalId = setInterval(() => {
      nextSlide();
    }, 5000);
  }
  
  function resetTimer() {
    clearInterval(intervalId);
    startAutoSlide();
  }
  
  window.onload = () => {
    updateCarousel();
    startAutoSlide();
  };
  