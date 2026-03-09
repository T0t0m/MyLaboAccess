# Structure du code "main.dart" (dans le dossier lib) <br>
📌 1. void main() { runApp(const MyApp()); }

    C'est le point d'entrée de l'application Flutter.
    Il exécute la classe MyApp, qui est un StatelessWidget.

📌 2. Classe MyApp

    Définit l’application avec MaterialApp.
    Configure un thème personnalisé :
        Couleurs (primaryColor blanc, boutons en rouge).
        Styles de texte (Roboto, tailles et couleurs définies).
        Style des ElevatedButton (boutons arrondis, rouge avec texte blanc).
    Définit la page d’accueil MyHomePage.

🔹 Écran principal (MyHomePage)

C'est un StatefulWidget, donc il peut gérer l'état de la liste des équipements.
🔸 Attributs

    Une liste equipments contenant plusieurs objets Equipment, chacun ayant un nom et une quantité.

🔸 build (Interface utilisateur)

Utilise un Scaffold avec :

    AppBar : Barre d’application avec le titre MyLaboAccess.
    Body (corps de l'application) :
        Deux ElevatedButton pour afficher des instructions (AlertDialog) sur :
            Comment emprunter du matériel.
            Comment rendre du matériel.
        Une liste (ListView.builder) affichant les équipements avec :
            Une icône spécifique pour chaque type (_getIconForEquipment).
            Le nom de l'équipement.
            La quantité affichée dans un badge rouge.
    FloatingActionButton (Boutons flottants) :
        Un bouton pour aller à la page Emprunter du matériel.
        Un bouton pour aller à la page Rendre du matériel.

🔹 Autres pages
📌 1. BorrowEquipmentPage (Emprunter du matériel)

    Une page affichant un message Page pour emprunter du matériel.

📌 2. ReturnEquipmentPage (Rendre du matériel)

    Une page affichant un message Page pour rendre du matériel.

🔹 Classe Equipment

Modélise un équipement avec :

class Equipment {
final String name;
final int quantity;

Equipment(this.name, this.quantity);
}

Chaque équipement a :

    Un nom (ex. "Écrans").
    Une quantité (ex. 15).

🔹 Fonction _getIconForEquipment

Cette fonction renvoie une icône adaptée à chaque type d’équipement :

    Icons.monitor pour les écrans.
    Icons.router pour les routeurs.
    Icons.device_hub pour les switches.
    Icons.storage pour les serveurs.
    Icons.cable pour les câbles réseau.
    Icons.wifi pour les points d'accès WiFi.
    Icons.devices_other (par défaut si l'équipement n'est pas reconnu).

🛠 Résumé

Cette application permet : ✅ D'afficher une liste d'équipements avec leurs quantités.
✅ D'expliquer comment emprunter et rendre du matériel via des boîtes de dialogue.
✅ D'ouvrir deux nouvelles pages pour emprunter et rendre du matériel.<br>

# Pour lancer l'application
- Sur android studio aller dans le device manager (situé à droite de l'écran)
s'il n'y est pas cliquer sur "Tools" (dans le menu déroulant en haut à gauche)
- puis "create virtual device"
- choisir un smartphone (pour cet exemple j'ai pris un google pixel 9)
- Recommandé : API 30 (Android 11) ou supérieur
Si aucune version n'est disponible, cliquer sur "Download"
- demarrer le smartphone virtuel dans Device Manager, clique sur l'icône de lecture ▶ à côté du téléphone puis lancer le main.dart<br>

## Problèmes fréquents et solutions

❌ AVD Manager ne s’ouvre pas ?
✔ Active Intel HAXM dans les paramètres du BIOS (VT-x)

❌ L'émulateur est trop lent ?
✔ Active "Use Host GPU" dans les options avancées de l'AVD

❌ Erreur "No device found" ?
✔ Vérifie avec la commande :

```bash
flutter doctor
```

✔ Installe les SDK Android manquants depuis Android Studio

# Pour télécharger l'apk si vous avez un android

- ouvrir le terminal et android studio
- execute la commade
```
  flutter build apk --release
```
- Une fois le build terminé, le fichier APK se trouve dans :
```
/build/app/outputs/flutter-apk/app-release.apk
```

## Backend auth & frontend role-based access

🔧 The app persists an auth token and (optionally) the user's role when the API returns them on login.

- Expected login response (Laravel):

    {
        "token": "...",
        "user": {"id": 1, "email": "user@example.com", "role": "admin"}
    }

- Frontend behavior:
    - `AuthService.login()` stores the token and the `role` (if present) in SharedPreferences.
    - `AuthService.getRole()` and `AuthService.isAdmin()` are available for role-based UI decisions.
    - `AuthService.authHeaders()` returns headers including Authorization: Bearer <token> for secured endpoints.

This allows the backend to remain unconnected for now — as soon as the API returns the `role` in the login response the app will store it and admin-only UI will automatically be shown to admin users.
