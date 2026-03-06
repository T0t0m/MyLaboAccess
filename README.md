# 🚀 MyLaboAccess - Guide d'Installation Complet

Bienvenue dans le projet **MyLaboAccess** ! Ce document décrit étape par étape comment configurer l'environnement de développement local pour faire tourner l'application.

Ce projet utilise une architecture classique en 3 tiers :
1. **Front-end** : Application Flutter (Web / Mobile).
2. **Back-end** : API en PHP.
3. **Base de données** : MySQL.

## 🛠️ Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre machine (Windows) :
* [Git](https://git-scm.com/) (pour cloner le projet)
* [Visual Studio Code](https://code.visualstudio.com/) (l'éditeur recommandé)
* [Laragon](https://laragon.org/download/) (pour le serveur local Apache/PHP et MySQL)

## 📦 Étape 1 : Installation et configuration de Flutter

Si Flutter n'est pas encore installé sur votre machine, la méthode la plus simple est d'utiliser VS Code :

1. Ouvrez **VS Code** et installez l'extension officielle **Flutter**.
2. Ouvrez la palette de commandes (`Ctrl+Shift+P`), tapez `Flutter: New Project` (Nouveau projet).
3. VS Code vous proposera de télécharger le SDK Flutter. Choisissez un dossier (ex: `C:\src\`) et laissez faire.
4. **Important :** À la fin, cliquez sur la notification **"Ajouter le SDK au PATH"**.
5. Redémarrez VS Code.

Une fois Flutter installé, ouvrez le dossier du projet `MyLaboAccess` dans VS Code, ouvrez un terminal et téléchargez les dépendances :

```powershell
flutter pub get
```

## ⚙️ Étape 2 : Préparation du Back-end (Laragon)

L'application Flutter a besoin de communiquer avec une API PHP locale.

1. Dans les fichiers de ce projet, trouvez le dossier `server-samples/mylabo_api`.
2. **Copiez** le dossier `mylabo_api` complet.
3. **Collez-le** dans le répertoire web de Laragon, par défaut : `C:\laragon\www\`.
*(Vous devriez avoir `C:\laragon\www\mylabo_api\register.php`, etc.)*
4. Lancez **Laragon** et cliquez sur **Start All** (Tout démarrer).

## 🗄️ Étape 3 : Création de la base de données

1. Depuis Laragon, cliquez sur le bouton **Database** (ou allez sur `http://localhost/phpmyadmin`).
2. Ouvrez un nouvel onglet de requête SQL.
3. Copiez, collez et exécutez le code SQL suivant pour créer la base `mylaboipi` et ses tables :

```SQL
CREATE DATABASE IF NOT EXISTS `mylaboipi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mylaboipi`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(200) NOT NULL,
  `email` VARCHAR(200),
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `reports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `contenu` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);
```

## 🔗 Étape 4 : Configuration de l'Application Flutter

Il faut maintenant indiquer à l'application où se trouve l'API.

1. Dans VS Code, ouvrez le fichier `lib/config.dart`.
2. Vérifiez que la variable `apiBaseUrl` pointe bien vers votre serveur local Laragon :

```Dart
// Pour une exécution sur le navigateur web (Chrome) avec Laragon :
const String apiBaseUrl = '[http://127.0.0.1/mylabo_api](http://127.0.0.1/mylabo_api)';

// Note : Si vous utilisez un émulateur Android plus tard, il faudra utiliser :
// const String apiBaseUrl = '[http://10.0.2.2/mylabo_api](http://10.0.2.2/mylabo_api)';
```

## ▶️ Étape 5 : Lancement et Tests

Tout est prêt ! Assurez-vous que Laragon est en cours d'exécution, puis lancez l'application depuis le terminal de VS Code :

```PowerShell
flutter run -d chrome
```

Une fenêtre Google Chrome va s'ouvrir avec l'application.

**Test rapide** : Essayez de créer un compte via l'interface d'inscription de l'application. Si le compte se crée, la liaison entre Flutter, PHP et MySQL fonctionne parfaitement ! 🎉