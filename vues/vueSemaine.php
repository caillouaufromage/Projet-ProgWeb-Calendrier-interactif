<?php

    $vueCalendrier = 'vueSemaine';
    // Affichage du tableau calendrier
    echo '<table>';

    // les jours de la semaine
    echo '<thead><tr><th></th>';
    foreach ($jours_semaine as $jour) {
        echo '<th id=jours colspan=3 #87CEEB >' . $jour . '</th>';
    }
    echo '</tr></thead>';

    // les groupes
    echo '<tr><td id=groupe bgcolor = "#FFA07A"></td>';
    foreach($jours_semaine as $jour) {
        foreach($groupes as $groupe){
            echo '<td id=groupe bgcolor = "#FFA07A">' . $groupe . '</td>';
        }
    }
    echo '</tr>';

    // les horaires
    echo '<tbody>';

    foreach($horaires as $horaire){
        echo '<tr><td id=creneau>' . $horaire . '</td>';
        foreach($jours_semaine as $jour){

            for($groupe=0; $groupe<=3; $groupe++){
                $slot = $calendrier[$jour][$groupe][$horaire];
                $cellule = ''; //on stocke le contenu de la cellule
        
                if(is_array($slot)){
                    if($slot[8] == true){
                        if($groupe==0){ //s'il s'agit d'un amphi (tous les groupes):
                            $cellule = $slot[0] . '<br>' . $slot[4] . '<br>' . $slot[8];
                            //echo '<td id=creneau colspan=3 rowspan='.$slot[1].'>'. $cellule . '</td>';
                            echo '<td id=creneau rowspan="'. $slot[1] .'" colspan=3 bgcolor="'. $slot[2].'">'. $cellule . '</td>';
                            break;
                        } else { // s'il s'agit d'un td (seulement un groupe)    
                            $cellule = 'cours';
                            //echo '<td rowspan='.$slot[1].'id=creneau>'. $cellule . '</td>';
                            echo '<td id=creneau rowspan="'. $slot[1] .'" bgcolor="'. $slot[2].'">'. $cellule . '</td>';
                        }
                    } else if($groupe==0){ //si ce n'est pas la premiere heure de cours, alors on affiche rien
                        break;
                    }

                } else {
                    //if($slot[8]=='0') echo '<td id=creneau></td>';
                    if($groupe != 0) echo '<td id=creneau></td>';
                }

            }
        }
    }


    echo '</tbody>';
    echo '</table>';
?>
