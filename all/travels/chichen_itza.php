<?php if ($estConnecte): ?>
<section class="video-header">
    <video autoplay muted loop class="background-video">
        <source src="videos/chichen_itza.mp4" type="video/mp4">
        Votre navigateur ne supporte pas la lecture de vidéos.
    <video>
<div class="video-overlay">
    <h1>Chichén Itzá (Mexique)</h1>
</div>
<s/ection>




<section class="passenger-form">
    <h2>Participants au voyage</h2>
    <form id="travelersForm" method="post">
        <label for="adults">Nombre d'adultes :</label>
        <input type="number" id="adults" name="adults" min="1" value="1" required>
        <label for="children">Nombre d'enfants :</label>
        <input type="number" id="children" name="children" min="0" value="0" required>
        <div id="childrenAges"></div>

    <button type="submit">Mettre à jour</button>
</form>


<section class="flight-info">
    <h2>Vol aller</h2>
    <p>Vol direct au départ de Paris (CDG) à 8:15 (Heure locale)</p>
    <p>Arrivée à l'aéroport de Mérida (MID) à 15:55 (Heure locale)</p>
    <p>Attention : Durée du vol : 16h10min</p>

    <label for="departure-date">Date de départ :</label>
    <input type="date" id="departure-date" name="departure_date" required>
    <p>Durée : 6 jours (fixe)</p>
    <p>Prix par personne : 458€ (gratuit pour les 0-3 ans)</p>
</section>


<section class="hotel-option">
<h2>Sélectionnea votre hôtel (situés dans la ville de Pisté)</h2>

<div class="hotel-option">
    <input type="radio" id="hotel-alba" name="hotel" value="alba">
    <label for="hotel-alba">
        <h3>Hôtel Alba **</h3>
        <ul>
            <li>Transfert aéroport : oui</li>
            <li>Piscines : 2 (extérieures)| Jacuzzi : non | Spa : non </li>
            <li>Services disponibles : chaises longues et parasols de plage<li>
            <li>Pension : Petit-déjeuner inclus | Restaurant (payant) | Bar (payant)</li>
            <li>Wifi gratuit | TV communes | Climatisation : non</li>
            <li>Aire de pique-nique | Jardin | Terasse sur le toit</li>
            <li>Laverie : non</li>
            <li>Accessibilité PMR : non (pas d'ascenseur)
            <li>Prix par chambre double (1 ou 2 pers.) : 309€</li>
</ul>
</label>
</div>

<div class="hotel-option">
    <input type="radio" id="hotel-puerta" name="hotel" value="puerta">
    <label for="hotel-puerta">
        <h3>Hôtel Puerta ***</h3>
        <ul>
            <li>Transfert aéroport : oui</li>
            <li>Piscines : 2 (intérieure et extérieure)| Jacuzzi : non | Spa : oui </li>
            <li>Services disponibles : chaises longues et parasols de plage<li>
            <li>Pension : Petit-déjeuner et déjeuner inclus | Restaurant | Bar </li>
            <li>Wifi gratuit | TV communes et chambres | Climatisation : oui</li>
            <li>Aire de pique-nique | Jardin | Salon commun | Terasses</li>
            <li>Laverie : oui</li>
            <li>Accessibilité PMR : oui</li>
            <li>Prix par chambre double (1 ou 2 pers.) : 493€</li>
</ul>
</label>
</div>

<div class="hotel-option">
    <input type="radio" id="hotel-maya" name="hotel" value="maya">
    <label for="hotel-maya">
        <h3>Hôtel Maya *****</h3>
        <ul>
            <li>Transfert aéroport : oui</li>
            <li>Piscines : 3 (2 extérieures 1 intérieure) | Jacuzzi : oui | Spa : oui | Pool bar </li>
            <li>Services disponibles : chaises longues et parasols de plage<li>
            <li>Pension : Tous repas inclus </li>
            <li>Restaurant avec vue sur la cité antique</li>
            <li>Wifi gratuit | TV communes et chambres | Climatisation : oui</li>
            <li>Balcon privé | Baignoire/douche | Sèche-cheveux</li>
            <li>Billard | Piano </li>
            <li>Aire de pique-nique | Salons communs | Jardins | Terasses
            <li>Laverie : oui</li>
            <li>Accessibilité PMR : oui 
            <li>Prix par chambre double (1 ou 2 pers.) : 594€</li>
</ul>
</label>
</div>
</section>


<section class="total-price">
    <h2>Prix total =</h2>
    <p id="price-display">0€</p>
</section>

<?php else: ?>
    <p style="color: red; font-weight: bold;">
        Vous devez être connecté pour personnaliser ce voyage.
    </p>
<?php endif; ?>

<script>
document.getElementById('children').addEventListener('input', function() {
  const count = parseInt(this.value);
  const container = document.getElementById('childrenAges');
  container.innerHTML = '';
  for (let i = 1; i <= count; i++) {
    const label = document.createElement('label');
    label.textContent = `Âge de l'enfant ${i} :`;
    const input = document.createElement('input');
    input.type = 'number';
    input.name = `child_age_${i}`;
    input.min = 0;
    input.max = 17;
    input.required = true;
    container.appendChild(label);
    container.appendChild(input);
  }
});

function calculateTotal() {
  const adults = parseInt(document.getElementById('adults').value);
  const children = parseInt(document.getElementById('children').value);
  const ages = [...document.querySelectorAll('#childrenAges input')].map(input => parseInt(input.value) || 0);

  const hotelPrices = {
    alba: 309,
    puerta: 493,
    maya: 594
  };

  const hotel = document.querySelector('input[name="hotel"]:checked');
  if (!hotel) return;

  let payingChildren = ages.filter(age => age > 3).length;
  let freeChildren = ages.length - payingChildren;

  const flightPrice = 458;
  const peoplePayingFlight = adults + payingChildren;
  const totalFlight = flightPrice * peoplePayingFlight;

  const totalPeople = adults + payingChildren;
  const rooms = Math.ceil(totalPeople / 2);
  const totalHotel = hotelPrices[hotel.value] * rooms;

  const total = totalFlight + totalHotel;

  document.getElementById('price-display').textContent = `${total}€`;
}

document.getElementById('travelersForm').addEventListener('change', calculateTotal);
document.querySelectorAll('input[name="hotel"]').forEach(input => {
  input.addEventListener('change', calculateTotal);
});
</script>
