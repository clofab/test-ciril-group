# Installation

Pour installer le projet dans le répertoire de vos projets web utilisez la commande suivante :

    git clone https://github.com/clofab/test-ciril-group.git

# Configuration

Les simulations sont configurables depuis le fichier *config.txt* qui comprend les paramètres suivants :
|Paramètres|Définition|
|--|--|
| [dimensions] | Dimensions de la grille de simulation qu'on renseigne avec *[x,y]* où x est la hauteur de la grille et y sa largeur |
| [probabilite] | Nombre compris entre 1 et 100 qui indique la probabilté d'une case de la grille de prendre feu  |
| [departs] | Ensemble de points de départs de feu au début de la simulation renseignés sous la forme *[x,y]* : x étant la ligne du point (valeur située entre 0 et hauteur de la grille - 1) et y étant la colonne du point (valeur située entre 0 et largeur de la grille - 1)|


# Simulations
Le déroulement des simulations sont enregistrés dans le dossier *simulations* avec la nomenclature suivante :

 

> *[identifiant de la simulation]*-*[etape]*.txt

Chaque fichier indique les zones de feu actives sur l'étape en cours ainsi que les zones déjà brulées auparavant.