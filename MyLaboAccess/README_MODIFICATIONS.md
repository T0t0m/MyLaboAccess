### Modifications apporté dans ce prototypes :

## L'introduction :

    Ce que je propose dans ce prototype, différente page
    à ajouter pour le site internet de ce projet LABO.
    Ceci à était réaliser avec une base de données sur
    phpMyAdmin directement lier à laragon.
    Et les autres pages qui accompagneront la page 
    principale ont toutes érait réalisés en PHP.
    Celui ci n'est pas encore achevé, et d'autres 
    améliorations. Mais en attendant, voici se qui
    est proposé à travers cette base de données.


## La base de données :

    J'ai essayer de le faire avec l'un des moyens que 
    je maitrise le mieux, c'est à dire Laragon.
    le fichier "db.php" est le pricipal permettant 
    de faire le lien avec cette de données locale.
    il a suffit ensuite de faire un "riquire_once" sur 
    chacune des autres pages concernées pour pouvoir
    ainsi qu'elle y soit connectées et que les 
    modifications soit apportés.

    Cette base de données a était créer avec la 
    commande suivante :

    ```
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(50) NOT NULL,
        prenom VARCHAR(70) NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(100) NOT NULL,    
        role ENUM("user", "admin") NOT NULL
    )
    ```

    De plus, chaque mot de passe taper lorsque on créer 
    un compte, passe automatiquement en haché dans la 
    base de données pour éviter toute tentative de piratage.


    Ensuite voici les commandes qui m'ont aidé à créer
    les tables des différents matériels :

    Appareils :
    ```
    CREATE TABLE appareils (
    id_appareil INT AUTO_INCREMENT PRIMARY KEY,
    type_appareil ENUM('ecran','routeur','switch','serveur','cable','wifi') NOT NULL,
    nom VARCHAR(100) NOT NULL,
    marque VARCHAR(50),
    modele VARCHAR(50),
    numero_serie VARCHAR(100),
    date_achat DATE,
    statut ENUM('en_service','stock','panne','rebut') DEFAULT 'en_service',
    localisation VARCHAR(100),
    commentaire TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ```

    ecrans 
    ```
    CREATE TABLE ecrans (
    id_ecran INT AUTO_INCREMENT PRIMARY KEY,
    id_appareil INT NOT NULL,
    taille_pouces DECIMAL(4,1),
    resolution VARCHAR(20),
    type_dalle ENUM('IPS','TN','VA'),
    connectiques VARCHAR(100),
    incurve BOOLEAN DEFAULT 0,
    taux_rafraichissement INT,
    FOREIGN KEY (id_appareil) REFERENCES appareils(id_appareil) ON DELETE CASCADE
    );
    ```

    routeurs
    ```
    CREATE TABLE routeurs (
    id_routeur INT AUTO_INCREMENT PRIMARY KEY,
    id_appareil INT NOT NULL,
    nb_ports INT,
    debit_max VARCHAR(20),
    wifi BOOLEAN DEFAULT 0,
    norme_wifi VARCHAR(20),
    vpn BOOLEAN DEFAULT 0,
    firewall BOOLEAN DEFAULT 0,
    FOREIGN KEY (id_appareil) REFERENCES appareils(id_appareil) ON DELETE CASCADE
    );
    ```

    switches
    ```
    CREATE TABLE switches (
    id_switch INT AUTO_INCREMENT PRIMARY KEY,
    id_appareil INT NOT NULL,
    nb_ports INT,
    type_switch ENUM('manageable','non-manageable'),
    vitesse_ports VARCHAR(20),
    poe BOOLEAN DEFAULT 0,
    couche ENUM('L2','L3'),
    FOREIGN KEY (id_appareil) REFERENCES appareils(id_appareil) ON DELETE CASCADE
    );
    ```

    serveurs
    ```
    CREATE TABLE serveurs (
    id_serveur INT AUTO_INCREMENT PRIMARY KEY,
    id_appareil INT NOT NULL,
    cpu VARCHAR(100),
    ram_go INT,
    stockage_go INT,
    type_stockage ENUM('HDD','SSD','NVMe'),
    os VARCHAR(50),
    virtualisation BOOLEAN DEFAULT 0,
    FOREIGN KEY (id_appareil) REFERENCES appareils(id_appareil) ON DELETE CASCADE
    );
    ```

    cables_reseau
    ```
    CREATE TABLE cables_reseau (
    id_cable INT AUTO_INCREMENT PRIMARY KEY,
    id_appareil INT NOT NULL,
    type_cable ENUM('RJ45','Fibre'),
    categorie ENUM('Cat5e','Cat6','Cat6a','Cat7'),
    longueur_m DECIMAL(5,2),
    couleur VARCHAR(20),
    usage VARCHAR(50),
    FOREIGN KEY (id_appareil) REFERENCES appareils(id_appareil) ON DELETE CASCADE
    );
    ```

    points_acces_wifi
    ```
    CREATE TABLE points_acces_wifi (
    id_wifi INT AUTO_INCREMENT PRIMARY KEY,
    id_appareil INT NOT NULL,
    norme_wifi VARCHAR(20),
    frequence VARCHAR(20),
    debit_max VARCHAR(20),
    poe BOOLEAN DEFAULT 0,
    nb_clients_max INT,
    FOREIGN KEY (id_appareil) REFERENCES appareils(id_appareil) ON DELETE CASCADE
    );
    ```

    tous ces appareils pourront être visibles dans le
    fichier "dashboard.php".

# comment changer la base de données / table ?

    Vous alleé dans "db.php" dans le dossier database.
    Ensuite pour la base de données, vous modifier 
    "dbname=projettutore2" par "dbname=NomDeLaDatabase"
    dans la ligne 2.
    Et pour la table, vous devez modifier dans la ligne 9
    le "users" "SELECT * FROM users" et le remplacer par
    le nom de votre table.


## Les pages PHP dans le dossier NewPages/ :

# admin.php :

    C'est la page qui permettra la gestion des comptes.
    Uniquement accessible pour les users qui ont le role 
    d'admin. mais il pourront avoir accés à tous les autres
    données des compte. Ils pourront également modifier des
    données dessus ou supprimer des comptes.

    Pour tenter des manoeuvres, vous pourrez essayer le 
    deuxième protocoles que vous verrez dans la partie
    "Comment lancer se prototype".


# login.php :

    C'est la page d’authentification du site. L’utilisateur 
    doit y entrer son email et son mot de passe. Le système 
    vérifie les informations via la base de données et utilise
    password_verify() pour comparer le mot de passe sécurisé (hashé).
    En cas de connexion réussie :
    si c’est un admin → redirection vers admin.php
    sinon, vers la page par défaut du projet Flutter ex: 
    http://localhost:61976/. En cas d’erreur : un message s’affiche 
    indiquant que les identifiants sont incorrects.


# registers :

    Elle permet aux utilisateurs de créer un nouveau compte. Le 
    formulaire demande un email / un mot de passe / 
    un rôle (user ou admin). Le mot de passe est 
    automatiquement sécurisé grâce à password_hash() avant 
    d’être enregistré dans la base. Une fois l'inscription 
    validée, l'utilisateur peut ensuite se connecter via 
    login.php.  
    EN REVANCHE : il impossible d'accéder au mot de passe, peut
    importe les users / admin pour des raisons de sécurités.


# edit_users.php :

    C'est la page php permettant de modifier les informations de 
    n'importe quel user, si nous sommes connectés en tant qu'admin
    sur le site internet. Accessible depuis la page Admain, si on 
    a cliquer sur le button "éditer" de la ligne d'un certain 
    utilisateur.
    Si nous y sommes, nous pouvons ensuite rentrer toutes les 
    informations que nous souhaitons remplacer à la place des 
    informations initiales. Et le tout sera ensuite modifier
    dans la Database.
    EN REVANCHE : il impossible de modifier le mot de passe, peut
    importe les users / admin pour des raisons de sécurités.

# delete_users.php :

    Alors contrairement aux autres, ce n'est pas une page, mais
    simplement un fichier qui nous permet de supprimer les 
    utilisateurs une fois que nous faisons appel à lui.
    Pour cela, il suffit simplement de cliquer sur le bouton
    "supprimer" à coté de l'utilisateur que nous voulons
    supprimer. Si il est supprimer, cela va l'effacer de
    la base de données, et donc, la ligne de cet utilisateur
    va donc disparaitre du tableau.


## comment lancer se prototype ?

# Ce qui est nécessaire !

    Il faut que le dossier de ce projet dans le dossier :
    C:\laragon\www
    C'est ce qui permettre la connexion principale avec 
    la base de données sur phpMyAdmin.


# différentes manoeuvres :

#pour les users
    Si vous voulez tentez de vous voulez tenter la manoeuvre
    je créer un compte / je me connecte / je vérifie le 
    matériel, voici comment vous devez faire :

    -démarrer le server (avec Chrome) avec la commande :
    
    ```
    flutter run -d chrome 
    ```

    -Prennez l'url du site internet en question, ensuite,
    dirrigez vous dans le fichier NewPages/login.php,
    à la ligne 16, vous devriez voir :
    
    ```
    header('Location: http://localhost:61976/');
    ```

    et remplacer l'url par celle du nouveau site internet.

    -ensuite, grâce à Laragon qui doit être démaré, vous 
    n'avez qu'à taper dans la recherche d'url :

    ```
    localhost/Nom_Du_Dossier/login.php
    ```

    Attention ! "Nom_Du_Dossier" doit être remplacer
    par le nom du dossier se trouvant dans le dossier
    www/ dans Laragon. S'il y en a plusieurs, taper les 
    tous ou copier le chemin du fichier login.php

# pour les admins

    Si vous voulez tentez de vous voulez tenter la manoeuvre
    je créer un compte admin / je me connecte / je vérifie la 
    base de données, voici comment vous devez faire :
    
    -Vous pouvez créer un nouveau compte pour cela mais
    pensez à mettre admin comme role !

    -ensuite, grâce à Laragon qui doit être démaré, vous 
    n'avez qu'à taper dans la recherche d'url :

    ```
    localhost/Nom_Du_Dossier/login.php
    ```
    Et vous vous connecté avec le compte admin.

    Attention ! "Nom_Du_Dossier" doit être remplacer
    par le nom du dossier se trouvant dans le dossier
    www/ dans Laragon. S'il y en a plusieurs, taper les 
    tous ou copier le chemin du fichier login.php

    Ensuite vous avez accés à toute la database.
    Et vous pouvez avoir en tant qu'admin, 2 deux 
    principale nouvelles options :

# supprimer le compte :

    Pour cela, il suffit tout simplement de cliquer 
    sur le bouttons "supprimer" sur la ligne de votre
    choix, et l'utilisateur se trouvant sur cette ligne,
    ainsi que toute ces données seront supprimés.


# modifier les données d'un compte :

    Pour cela vous devez cliquer sur le bouton 
    "éditer", et cela vous ammènera dans la page
    permettant la modification des données, et le tout, 
    simplement en remplaçant les données de notre choix,
    sauf bien évidemment le mot de passe, pour certaines 
    raison de confidentialité pour l'ulisateur.




### Si vous avez un problème ou une question :

    Vous pouvez m'envoyer un mail sur mon adresse mail
    éducative :"mathis.flan@edu.igensia.com".
    Je vous répondrai aussi vite que possible.