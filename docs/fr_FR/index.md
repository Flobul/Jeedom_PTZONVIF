# Changelog


# Présentation

Ce plugin permet de scanner le réseau à la recherche de caméras compatibles avec le protocole universel ONVIF, puis de créer les équipements correspondant ainsi que les commandes PTZ associées.
Il permet également d'itentifier les liens rtsp, http, et snapshot (si disponible).

# Configuration du plugin

Après téléchargement du plugin, il vous suffit juste d’activer celui-ci, et de lancer l'installation des dépendances.

## Dépendances

Cette partie permet de valider et d’installer les dépendances requises au bon fonctionnement du plugin Zwave (aussi bien en local qu’en déporté, ici en local) ![configuration02](../images/configuration02.png)

-   Un Statut **OK** confirme que les dépendances sont satisfaites.
-   Si le statut est **NOK**, il faudra réinstaller les dépendances à l’aide du bouton ![configuration03](../images/configuration03.png)


> **Important**
>
> La mise à jour des dépendances est normalement à effectuer seulement si le Statut est **NOK**, mais il est toutefois possible, pour régler certains problèmes, d’être appelé à refaire l’installation des dépendances.


# Création des équipements

Les équipements peuvent être créé manuellement, mais il est préférable de passer par le bouton découverte.

## Bouton découverte

Ce bouton permet d'afficher une fenêtre, puis après quelques secondes, d'afficher la liste des caméras compatible ONVIF trouvée sur le réseau.
Il suffit ensuite de cliquer sur l'icone "créer" afin de créer l'équipement voulu.
A la fermeture de la fenêtre, l'écran se rafraichis, et le nouvel équipement apparait, avec l'ensemble de ses commandes créées.

# modification équipement

Suite à la création de l'équipement, il est impératif, si vous n'ètes pas passé par le bouton découverte,  de renseigner à minima l'adresse IP, et si possible le user et le mdp.

## Bouton analyse

Ce bouton permet d'analyser en détail la caméra afin de compléter les champs correspondant.

# utilisation

une fois l'ensemble des commandes créées, vous pouvez les utiliser directement sur le dashbord ou sur un design, ou créer un lien vers ces commandes dans le plugin caméra.


