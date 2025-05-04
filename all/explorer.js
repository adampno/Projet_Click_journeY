document.addEventListener('DOMContentLoaded', function() {
    const boutonRecherche = document.getElementById('boutonRecherche');
    const barreRecherche = document.getElementById('barreRecherche');
    
    boutonRecherche.addEventListener('click', function() {
        effectuerRecherche();
    });
    
    barreRecherche.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            effectuerRecherche();
        }
    });
    
    function effectuerRecherche() {
        const motCle = barreRecherche.value.trim();
        const destination = document.getElementById('destinationSelect').value;
        const etapes = document.getElementById('nombreEtapes').value;
        const date = document.getElementById('dateDepart').value;
        const budget = document.getElementById('budgetMax').value;
        
        // Ici vous feriez un appel AJAX à votre backend PHP
        fetchResultats(motCle, destination, etapes, date, budget);
    }
    
    function fetchResultats(motCle, destination, etapes, date, budget) {
        // Exemple avec Fetch API
        fetch('recherche_voyages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                motCle: motCle,
                destination: destination,
                etapes: etapes,
                date: date,
                budget: budget
            })
        })
        .then(response => response.json())
        .then(data => {
            afficherResultats(data);
        });
    }
    
    function afficherResultats(voyages) {
        const container = document.getElementById('resultatsRecherche');
        
        if (voyages.length === 0) {
            container.innerHTML = `
                <div class="aucunResultat">
                    <p>Aucun voyage ne correspond à votre recherche</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        voyages.forEach(voyage => {
            html += `
                <div class="voyageCard">
                    <img src="${voyage.image}" alt="${voyage.titre}" class="voyageImage">
                    <div class="voyageInfos">
                        <h3 class="voyageTitre">${voyage.titre}</h3>
                        <p class="voyageDescription">${voyage.description}</p>
                        <div class="voyageDetails">
                            <span class="voyageDetailItem">
                                <span class="material-symbols-outlined">location_on</span>
                                ${voyage.destination}
                            </span>
                            <span class="voyageDetailItem">
                                <span class="material-symbols-outlined">steps</span>
                                ${voyage.etapes} étapes
                            </span>
                            <span class="voyageDetailItem">
                                <span class="material-symbols-outlined">calendar_month</span>
                                ${voyage.duree} jours
                            </span>
                        </div>
                        <div class="voyagePrix">À partir de ${voyage.prix}€</div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
});



// Initialisation de la carte
var map = L.map('map').setView([20, 0], 2);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Liste des 7 merveilles du monde avec les coordonnées et les vidéos
var wonders = [
    { name: "La Grande Muraille de Chine", lat: 40.4319, lon: 116.5704, video: "videos/grande-muraille.mp4" },
    { name: "Pétra, Jordanie", lat: 30.3285, lon: 35.4444, video: "videos/petra.mp4" },
    { name: "Christ Rédempteur, Brésil", lat: -22.9519, lon: -43.2105, video: "videos/christ-redempteur.mp4" },
    { name: "Machu Picchu, Pérou", lat: -13.1631, lon: -72.5450, video: "videos/machu-picchu.mp4" },
    { name: "Chichen Itza, Mexique", lat: 20.6843, lon: -88.5678, video: "videos/chichen-itza.mp4" },
    { name: "Le Colisée, Italie", lat: 41.8902, lon: 12.4922, video: "videos/colisee.mp4" },
    { name: "Taj Mahal, Inde", lat: 27.1751, lon: 78.0421, video: "videos/taj-mahal.mp4" }
];

// Ajout des marqueurs sur la carte
wonders.forEach(function(wonder) {
    var marker = L.marker([wonder.lat, wonder.lon]).addTo(map)
        .bindTooltip(wonder.name, { permanent: false, direction: 'top', sticky: true });

    // Quand on clique sur un marqueur
    marker.on("click", function() {
        document.getElementById("wonder-title").innerText = wonder.name;

        var videoElement = document.getElementById("wonder-video");
        var videoSource = document.getElementById("video-source");

        // Met à jour la source de la vidéo
        videoSource.src = wonder.video;
        videoElement.style.display = "block";
        
        videoElement.load(); // Recharge la vidéo
        videoElement.play(); // Lance la lecture
    });
});
