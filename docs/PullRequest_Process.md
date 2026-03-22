# Procédure de Pull Request (PR)

Ce document décrit la marche à suivre pour soumettre vos modifications de code via une Pull Request.

## 📋 Prérequis

Avant d'ouvrir une Pull Request, assurez-vous d'avoir :

1. Créé une branche de travail spécifique pour vos modifications.

2. Commité et poussé (`push`) vos modifications sur le dépôt distant.

3. Vérifié que votre code compile et que vos tests locaux passent.

## 🚀 Étape 1 : Création de la Pull Request

> [!IMPORTANT]
> Une pull request doit être soumise lorsque vous estimez que le travail sur votre branche est terminé.

1. Allez sur la page principale du dépôt sur GitHub.

2. Allez dans l'onglet **"Pull requests"** et cliquez sur **"New pull request"**.

3. Sélectionnez la **branche de base** (`develop`) et la **branche de comparaison** (votre branche de travail).

4. Cliquez sur **"Create pull request"**.

## 📝 Étape 2 : Rédaction et Configuration

1. **Titre descriptif** : Utilisez le nom du ticket présent sur le kanban de Teams.

2. **Assignations (Reviewers & Assignees)** :

	- Dans le menu de droite, assignez-vous la PR (**Assignees**).

	- Demandez une revue en ajoutant **T0t0m** dans **Reviewers**.

3. Cliquez sur le bouton vert "Create pull request" pour finaliser la soumission.

## 🔍 Étape 3 : Suivi et retours

Le but de cette étape est de voir les changements demandés par le relecteur.

1. **Consulter les commentaires** : Rendez-vous sur la page de votre Pull Request (dans l'onglet "Pull requests" de GitHub) pour lire les retours laissés par le relecteur.

2. **Appliquer les changements** : Si des modifications sont demandées, ne créez pas une nouvelle PR. Faites simplement vos modifications en local, commitez, et faites un `push` sur votre branche actuelle. La PR se mettra à jour automatiquement avec vos nouveaux commits.

3. **Notifier le relecteur** : Une fois que toutes les modifications demandées ont été réalisées et poussées, **laissez un commentaire directement sur la page de la Pull Request** pour avertir le relecteur qu'il peut faire une nouvelle passe de relecture.

> [!WARNING]
> **Validation et Fusion** : **⚠️ Ce n'est jamais à vous de valider ou de fusionner votre propre Pull Request.** C'est toujours le relecteur qui, s'il estime le travail terminé et qu'il n'y a plus de modifications à réaliser, se chargera de fusionner le code et de fermer la Pull Request.