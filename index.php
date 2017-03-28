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
        foreach($value as $value) {
                echo $value['label'];
            foreach($value as $value) {
            }
        }
    }
}
?>



</body>
</html>