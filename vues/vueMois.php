<?php

$liste_semaines = array('SEMAINE1', 'SEMAINE2', 'SEMAINE3', 'SEMAINE4');

//Tableau matin-aprem
$moment_journee = array('MATIN', 'APRES-MIDI');

// Affichage du tableau calendrier
echo '<table>';

// les semaines du mois
echo '<thead><tr><th></th>';
foreach ($liste_semaines as $semaine) {
    echo '<th id=jours colspan=15 #87CEEB >' . $semaine . '</th>';
}
echo '</tr></thead>';

// les groupes
echo '<tr><td id=groupe bgcolor = "#FFA07A"></td>';
foreach ($liste_semaines as $semaine) {
    foreach ($groupes as $groupe) {
        echo '<td id=groupe colspan=5 bgcolor = "#FFA07A">' . $groupe . '</td>';
    }
}
echo '</tr>';
// semaine actuelle = $week-start

// les demis journées
echo '<tbody>';


//for ($i = 0; $i < 2; $i++) { // *2
foreach($horaires as $horaire){
 /*    if($horaire == '8:00') echo '<tr><td id=creneau" >MATIN</td>';
    else if ($horaire == '12:15') echo '<tr><td id=creneau" >APRES-MIDI</td>';
    else ; */
    echo '<tr>';
    echo '<td id=groupe >=></td>';
    foreach ($liste_semaines as $liste) { // *4
        foreach ($jours_semaine as $jour) { // *5
            for ($groupe = 0; $groupe <= 3; $groupe++) { // *4
                $slot = $calendrier[$jour][$groupe][$horaire];
                $vert = "#096a09";
                $blanc = "#FFFFFF";
                $bleu = "#5159cf";
                
                if (is_array($slot)) {
                    echo 'yes ';
                        if ($groupe == 0) {
                            echo 'oui - ';
                            echo '<td id=creneau bgcolor='. $bleu .'></td>';
                        } else {
                            echo 'non - ';
                            echo '<td id=creneau bgcolor=' . $vert . '></td>';
                        }
                        break;
                    
                } //else
                    //echo '<td id=creneau bgcolor=' . $blanc . '></td>';
            }

        }
    }
    echo '</tr>';

}


/* foreach($horaires as $horaire){
if($horaire == '8:00') echo '<tr><td id=creneau" >MATIN</td>';
if($horaire == '12:15') echo '<tr><td id=creneau" >APRES-MIDI</td>';
foreach($jours_semaine as $jour){
$vert = "#096a09";
$blanc = "#FFFFFF";
for($groupe = 0; $groupe <= 3; $groupe++){
$slot = $calendrier[$jour][$groupe][$horaire];
$bgColor = $vert;
// si il y a au moins un cours, la case est $vert, $blanc sinon
if(is_array($slot)) {
echo '<td id="creneau" bgcolor="' . $vert .'"></td>';
break;
} else {
echo '<td id="creneau" bgcolor="' . $blanc .'"></td>';
}
echo 'caca';
}
}
echo '</tr>';
} 
*/

echo '</tbody>';
echo '</table>';


?>