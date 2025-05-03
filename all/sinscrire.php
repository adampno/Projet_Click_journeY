<?php
session_start(); // Active la gestion des sessions
$estConnecte = isset($_SESSION['user']);
$estAdmin = $estConnecte && ($_SESSION['user']['role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wander7-Formulaire d'Inscription</title>
    <link rel="icon" href="assets/Logo_Wander7_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style/sinscrire.css"/>
</head>
<body>

    <header>
        <img class="logo" src="assets/LogoWander7.png" alt="logo">
        <nav>
            <ul class="nav_links">
            <li><a href="index.php">Accueil</a></li>
              <li><a href="aproposdenous.php">À propos de nous</a></li>
              <li><a href="explorer.php">Explorer</a></li>
              <li><a href="monprofil.php">Mon profil</a></li>
              <li><a href="admin.php">Admin</a></li>
              <li><a href="seconnecter.php">Se connecter</a></li>
            </ul>
        </nav>
    </header>

    <main class="signup-main-container">
        <div class="signup-inner-container">
            <h2>Inscription</h2>

            <form>
                <!-- Nom -->
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>

                <!-- Prénom -->
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required>

                <!-- Sexe -->
                <label for="sexe">Sexe :</label>
                <select id="sexe" name="sexe" required>
                    <option value="">-- Sélectionnez --</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                    <option value="non_precise">Préfère ne pas préciser</option>
                </select>

                <!-- Numéro de téléphone -->
                <label for="telephone">Numéro de téléphone :</label>
                <input type="tel" id="telephone" name="telephone" pattern="[0-9]{10}" maxlength="10" placeholder="Ex: 0601020304" required>


<!-- Domicile (Régions de France) -->
<label for="domicile">Domicile :</label>
<select id="domicile" name="domicile" required>
    <option value="">-- Sélectionnez une région --</option>
    <option value="auvergne-rhone-alpes">Auvergne-Rhône-Alpes</option>
    <option value="bourgogne-franche-comte">Bourgogne-Franche-Comté</option>
    <option value="bretagne">Bretagne</option>
    <option value="centre-val-de-loire">Centre-Val de Loire</option>
    <option value="corse">Corse</option>
    <option value="grand-est">Grand Est</option>
    <option value="hauts-de-france">Hauts-de-France</option>
    <option value="ile-de-france">Île-de-France</option>
    <option value="normandie">Normandie</option>
    <option value="nouvelle-aquitaine">Nouvelle-Aquitaine</option>
    <option value="occitanie">Occitanie</option>
    <option value="pays-de-la-loire">Pays de la Loire</option>
    <option value="provence-alpes-cote-d-azur">Provence-Alpes-Côte d'Azur</option>
    <option value="outre-mer">Outre-Mer</option>
</select>
<p class="info-text">Votre domicile nous permet de vous proposer des vols au départ des aéroports les plus proches de chez vous.</p>


                <!-- Adresse email -->
                <label for="email">Adresse mail :</label>
                <input type="email" id="email" name="email" required>

                <!-- Mot de passe -->
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" 
                    pattern="(?=.*[A-Z])(?=.*\d).{8,}" 
                    title="Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule et un chiffre." 
                    required>
                <p class="info-text">Votre mot de passe doit contenir au moins 8 caractères, une lettre majuscule et un chiffre.</p>

                
                <div class="checkbox-wrapper">
                    <label class="checkbox-container">
                        <input type="checkbox" id="accept-terms" required>
                        <span class="checkmark"></span>
                        En soumettant ce formulaire, j'accepte que mes informations soient utilisées exclusivement dans le cadre de ma demande et de la relation commerciale éthique et personnalisée qui pourrait en découler si je le souhaite.
                    </label>
                </div>
                
                <div class="checkbox-wrapper">
                    <label class="checkbox-container">
                        <input type="checkbox" id="accept-newsletter">
                        <span class="checkmark"></span>
                        J'accepte que mon mail soit utilisé à des fins publicitaires (newsletter, offres exclusives, etc.).
                    </label>
                </div>
                


            <!-- Bouton d'inscription -->
            <button type="submit" class="signup-button">S'inscrire</button>
        </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Wander7. Tous droits réservés.</p>
    </footer>

</body>
</html>
