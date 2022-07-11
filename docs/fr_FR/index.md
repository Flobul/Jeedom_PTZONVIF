# Présentation

Ce plugin permet de scanner le réseau à la recherche de caméras compatibles avec le protocole universel ONVIF, puis de créer les équipements correspondant ainsi que les commandes PTZ associées.
Il permet également d'identifier les liens rtsp, http, et snapshot (si disponible).

# Configuration du plugin

Après téléchargement du plugin, il vous suffit juste d’activer celui-ci. Aucune installation de dépendance n'est nécéssaire.
Le champ "Timeout découverte (sec)" peut recevoir une valeur de 1 à 15, il est utilisé pour la découverte des caméras sur le réseau.
Par défaut il est de 5s, mais peut être augmenté si certaines caméras ont du mal à répondre.

# Création des équipements

Les équipements peuvent être créé manuellement, mais il est préférable de passer par le bouton découverte.

## Bouton découverte

Ce bouton permet d'afficher une fenêtre, puis après quelques secondes, d'afficher la liste des caméras compatible ONVIF trouvée sur le réseau.
Si certaines caméras n'aparraissent pas, vous pouvez essayer d'augmenter la valeur "Timeout découverte (sec)" dans la partie configuration du plugin.
Dans la deuxième partie du tableau, seront listés les caméras diponibles dans le plugin Camera. Vous pouvez choisir d'associer la création de l'équipement avec une caméra déjà existante. dans ce cas, lors de la création, l'objet parent, le username et le password du plugin caméra seront récupérés.
Il suffit ensuite de cliquer sur l'icone "créer" afin de créer l'équipement voulu.
A la fermeture de la fenêtre, l'écran se rafraichis, et le nouvel équipement apparait, avec l'ensemble de ses commandes créées.

# modification équipement

Suite à la création de l'équipement, il est impératif, si vous n'ètes pas passé par le bouton découverte,  de renseigner à minima l'adresse IP, et si possible le user et le mdp.

## Bouton analyse

Ce bouton permet d'analyser en détail la caméra afin de compléter les champs correspondant.
Cette analyse est obligatoire pour faire fonctionner les commandes.
Une fenêtre apparait, et après quelques secondes, l'ensemble des paramètres ONVIF devraient être détectés.
A la fermeture de la fenêtre, la page équipement se recharge avec l'emble des champs mis à jour.

# utilisation

une fois l'ensemble des commandes créées, vous pouvez les utiliser directement sur le dashbord ou sur un design, ou créer un lien vers ces commandes dans le plugin caméra.

## Commandes Pan/Tilt/Zoom

La vitesse de déplacement de la caméra peut être réglée par les champs Vitesse et délais de chaque axe.

### Vitesse
Vitesse de déplacement, valeur comprise entre 0 et 1 (0pas de déplacement, 1 vitesse max).

### Délais
Temps en ms avant que la commande stop soit envoyée à la caméra.

### cas particulier du zoom
Le zoom numérique ne fonctionne que sur très peu de caméra en mode ONVIF. Le zoom optique devrait normalement fonctionner

## Commandes presets

### Go preset
Les  boutons P0 à P5 servent à envoyer une demande de position prédéterminée à la caméra.
Si des positions ont été trouvés lors de l'analyse, les bouton devrait être visibles

### Set preset
Les  boutons S0 à S5 servent à sauvegarder une position qui sera associée aux bouton respectifs P0 à P5

## Commande reboot
Permet de redémarrer la caméra.

# Compatibilité / débuggage

Ce plugin s'appuis sur le protocole. Bien que très répendu, toutes les caméras ne sont pas forcément compatible avec ce protocole, et dans certain cas de manière incomplète.
Le logiciel Onvif Device Manager https://sourceforge.net/projects/onvifdm/ permet de testet de la même manière votre caméra ONVIF. Si ODM ne la reconnait pas ou ne peut agir sur certaines fonction, le plugin ne le pourra pas non plus.
Il est important lors de la partie découverte/analyse qu'aucun client ONVIF ne soit connecté à la caméra (ODM, page WEB...) afin que le dialogue ne soit pas perturbé.
En cas de problème, ne pas hésiter à poster les log (en mode debug).
Lors de l'analyse de l'équipement, les différents profiles ONVIF de la caméra sont sauvegardés dans le dossier /ressources du plugin, et peuvent donc être analysé en détail (ou envoyé par MP).
