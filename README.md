# LegendaryFiesta

Un simple depôt pour regrouper des fonctions et mecaniques de PHP.  
Je vais me permettre de parler francais parceque c'est la langue dans laquelle j'explique le mieux

## Table (WIP)

Ce projet propose une table CLI en PHP.  
Cependant, celui ci est en développement.  
Petit exemple ici :
```PHP
<?php

// (...)

$data_from_table_A = [
    ['ID_1' => 'A', 'ID_2' => 'A', 'METRIC_A' => 1, 'METRIC_B' => 1, 'METRIC_C' => 1],
    ['ID_1' => 'A', 'ID_2' => 'B', 'METRIC_A' => 2, 'METRIC_B' => 2, 'METRIC_C' => 2],
    ['ID_1' => 'B', 'ID_2' => 'A', 'METRIC_A' => 3, 'METRIC_B' => 3, 'METRIC_C' => 3],
    ['ID_1' => 'B', 'ID_2' => 'B', 'METRIC_A' => 4, 'METRIC_B' => 4, 'METRIC_C' => 4],
];

$table = new Table();
$table->setText('title', 'Keys');

foreach ($data_from_table_A as $data) {
    $key = $data['ID_1'] . '_' . $data['ID_2'];

    $table->addData($key, $data);
}

echo $table->export() . PHP_EOL;
```
Output:
```
=====================================
Keys | METRIC_A | METRIC_B | METRIC_C
=====================================
A_A  |        1 |        1 |        1
A_B  |        2 |        2 |        2
B_A  |        3 |        3 |        3
B_B  |        4 |        4 |        4
=====================================
```

## Change log
### 08/04/2022


Beaucoup de changement.
- changement à propos des tests
- changement à propos d'une nouvelle section dédié aux tables

Une grosse partie du code precedent, de développement et de recherche se trouve dans le dossier `TrashCode`.  
C'est du code qui n'a pas ue le temps d'etre commité.  
Je pense faire la version 1.0.0 quand le système de table sera terminé

### Tests

J'ai créé le moyen d'intégrer des tests PHP Unit dans le projet.  
J'ai donc créé des tests sur ce que j'ai deja codé.  
Bien que ces tests soient inutiles, je les ai quand meme gardé dans `TrashCode`.

### Tables

Nouvelle partie de code sur des tables en CLI.  
Je suis en train de faire une la partie des sections.  
Il manque encore touts les tests et 2 grosse partie du code.  
Je ne suis pas encore sur de tout garder, mais tout a l'air sur la bonne voie.
