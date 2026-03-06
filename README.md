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