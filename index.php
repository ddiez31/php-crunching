<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PHP Crunching</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

</head>
<body>
<h2>Dictionnaire</h2>
<em>Combien de mots contient ce dictionnaire ?</em>
<?php
$string = file_get_contents("dictionnaire.txt", FILE_USE_INCLUDE_PATH);
$dico = explode("\n", $string);
echo count($dico).' mots<hr>';
?>

<em>Combien de mots font exactement 15 caractères ?</em>
<?php
echo count(array_filter($dico, function($mot) {
    return (strlen($mot) >= 15);
})).' mots';
echo '<hr>';
?>

<em>Combien de mots contiennent la lettre « w » ?</em>
<?php
$result = 0;
foreach($dico as $value) {
    $count = substr_count($value, 'w');
    if($count == 1) {
        $result += 1;
    }
}
echo $result.' mots';
echo '<hr>';
?>

<em>Combien de mots finissent par la lettre « q » </em>
<?php
$result = 0;
foreach($dico as $value) {
    $count = substr($value, -1);
    if($count == 'q') {
        $result += 1;
    }
}
echo $result.' mots';
echo '<hr>';
?>

<h2>Films</h2>
<em>Afficher le top10 des films</em>

<?php
$string = file_get_contents("films.json", FILE_USE_INCLUDE_PATH);
$brut = json_decode($string, true);
$top = $brut["feed"]["entry"];
foreach($top as $key => $value) {
    if($key > 0 && $key <= 10) {
        echo '<br>'.$key.' <br>';
        echo $value['im:name']['label'];         
    }
}
echo '<hr>';
?>

<em>Quel est le classement du film « Gravity » ?</em>
<?php
foreach($top as $key => $value) {
    if($value['im:name']['label'] == 'Gravity') {
        echo '<br>'.$key.' <br>';
    }
}
echo '<hr>';
?>

<em>Quel est le réalisateur du film « The LEGO Movie » ?</em>
<?php
foreach($top as $key => $value) {
    if($value['im:name']['label'] == 'The LEGO Movie') {
        echo '<br>'.$value['im:artist']['label'].' <br>';
    }
}
echo '<hr>';
?>

<em>Combien de films sont sortis avant 2000 ?</em>
<?php
echo count(array_filter($top, function($annee) {
    return ($annee['im:releaseDate']['label'] < date(DATE_ATOM, mktime(0, 0, 0, 1, 1, 2000)));
})).' films';
echo '<hr>';
?>

<em>Quel est le film le plus récent ? Le plus vieux ?</em>
<?php
$topsort = $top;
usort($topsort, function($a,$b){
    return ($a["im:releaseDate"]['label'] < $b["im:releaseDate"]['label']) ? -1 : 1;
});
echo '<br>Plus ancien: '.$topsort[0]['im:name']['label'].'<br>';
echo 'Plus récent: '.$topsort[count($topsort)-1]['im:name']['label'];
echo '<hr>';
?>

<em>Quelle est la catégorie de films la plus représentée ?</em>
<?php
$categorieCount = [];
foreach($top as $value) {
    $categorieCount[$value['category']['attributes']['label']] += 1;
}
echo array_search(max($categorieCount), $categorieCount);
echo '<hr>';
?>

<em>Quel est le réalisateur le plus présent dans le top100 ?</em>
<?php
$realisateurCount = [];
foreach($top as $value) {
    $realisateurCount[$value['im:artist']['label']] += 1;
}
echo array_search(max($realisateurCount), $realisateurCount);  
echo '<hr>';
?>

<em>Combien cela coûterait-il d'acheter le top10 sur iTunes ? de le louer ?</em>
<?php
$rentalTotal = 0;
$priceTotal = 0;
for ($i = 1; $i <= 10; $i++) {
    $rentalTotal += substr($top[$i]['im:rentalPrice']['label'], 1);
    $priceTotal += substr($top[$i]['im:price']['label'], 1);
}
echo '<br>Tarif location = '.$rentalTotal.'$<br>';
echo 'Tarif achat = '.$priceTotal.'$';
echo '<hr>';
?>

<em>Quel est le mois ayant vu le plus de sorties au cinéma ?</em>
<?php
$monthCount = [];
foreach ($top as $key => $value) {
    $monthCount[explode(' ', $value['im:releaseDate']['attributes']['label'])[0]] += 1;
    }
$monthCountArray = array_keys($monthCount, max($monthCount));
foreach ($monthCountArray as $value) {
    echo '<br>'.$value;
};
echo '<hr>';
?>

<em>Quels sont les 10 meilleurs films à voir en ayant un budget limité ?</em>
<?php
$tabPrice = [];
$tabClass = [];
$tabTitle = [];
foreach ($top as $key => $value) {
    $tabPrice[] = substr($value['im:price']['label'], 1);
    $tabClass[] = $key;
    $tabTitle[] = $value['im:name']['label'];
}
array_multisort($tabPrice, SORT_ASC, $tabClass, SORT_NUMERIC, $tabTitle);
for($i = 0; $i < 10; $i++) {
    echo '<br>n°: '.$tabClass[$i].' - '.$tabTitle[$i].': $'.$tabPrice[$i];
}
echo '<hr>';
?>

</body>
</html>