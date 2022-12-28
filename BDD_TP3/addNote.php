<?php 
include("classe.php");

function addNote($nomEleve, $prenomEleve, $note, $matiere, $prenomProf, $nomProf){
    $connexion = connect();
    $anneeActuelle = date("Y");
    $date = date("d-m");
    if ($date < "01-09"){
       $anneeActuelle = strtotime($anneeActuelle."- 1 years");
    }
    $note = "INSERT INTO elevenote(numEleve, codeMatiere, jours, note) 
    VALUES(
        (SELECT numEleve FROM eleve
        WHERE nomEleve = '$nomEleve'
        AND prenomEleve = '$prenomEleve'),
        (SELECT codeMatiere FROM matiere 
        WHERE matiere.libelle = '$matiere'),
        now(),
        '$note'
    );";

    $result = $connexion->query($note);
    echo "note ajouté";
    echo "
    <form action='classe.php' method='post'>
    <input type='hidden' id='nom' name='nom' value='{$nomProf}'><br>
    <input type='hidden' id='prenom' name='prenom' value='{$prenomProf}'><br><br>
    <button type='submit' name='getClasse'>Retour</button>
    </form>\n";
}
function getContact($nomEleve, $prenomEleve, $prenomProf, $nomProf){
    $connexion = connect();
    $contact = "SELECT nomParent, prenomParent, telParent FROM parent
    JOIN eleve ON eleve.numMere = parent.numParent 
    OR eleve.numPere = parent.numParent
    WHERE nomEleve = '$nomEleve'
    AND prenomEleve = '$prenomEleve'";

    $result = $connexion->query($contact);
    echo "<form action='classe.php' method='post'>\n";
    echo "<table>\n";
    echo "<tr>\n";
    echo "<th>prenom</th>\n";
    echo "<th>nom</th>\n";
    echo "<th>numéro</th>\n";
    echo "</tr>\n";

    while($row = $result->fetch()){
        echo "<tr>\n";
        echo "<td> " . $row['prenomParent'] . "</td>\n";
        echo "<td>" . $row['nomParent'] . "</td>\n";
        echo "<td>" . $row['telParent'] . "</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "<br>\n";
    echo "<input type='hidden' id='nom' name='nom' value='{$nomProf}'>\n";
    echo "<input type='hidden' id='prenom' name='prenom' value='{$prenomProf}'>\n";
    echo "<button type='submit' name='getClasse'>Retour</button>\n";
    echo "</form>\n";
    
}

function getBulettin($nomEleve, $prenomEleve, $prenomProf, $nomProf){
    $connexion = connect();
    $getBulletin = "SELECT * from bulletin
    WHERE bulletin.numEleve = (select numEleve from eleve where nomEleve='$nomEleve' and prenomEleve='$prenomEleve');";
    $result = $connexion->query($getBulletin);
    echo "$prenomProf $nomProf";
    echo "<form action='classe.php' method='post'>\n";

    while($row = $result->fetch()){
        echo "<table>\n";
        echo "<tr>\n";
        echo "<td>\n bulletin du : ". $row['semestre'] . " semestre de l'année scolaire : " . $row['anneeScolaire'] . "</td>\n";
        echo "<tr>\n";
        echo "<th>matiere</th>\n";
        echo "<th>note</th>\n";
        echo "<th>appreciation</th>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td> Francais </td>\n";
        echo "<td> " . $row['moyenneFrancais'] . "</td>\n";
        echo "<td>" . $row['appreciationFrancais'] . "</td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td> Mathématique </td>\n";
        echo "<td> " . $row['moyenneMath'] . "</td>\n";
        echo "<td>" . $row['appreciationMath'] . "</td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td> Générale </td>\n";
        echo "<td> " . $row['moyenneGenerale'] . "</td>\n";
        echo "<td>" . $row['appreciationGenerale'] . "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "<br>\n";
    }

    echo "<input type='hidden' id='nom' name='nom' value='{$nomProf}'>\n";
    echo "<input type='hidden' id='prenom' name='prenom' value='{$prenomProf}'>\n";
    echo "<button type='submit' name='getClasse'>Retour</button>\n";
    echo "</form>\n";
    
}

function addBulletin($nomEleve, $prenomEleve, $semestre, $annee, $fr, $math, $gen, $nomProf, $prenomProf){
    $connexion = connect();
    $addBulletin = "INSERT INTO bulletin(anneeScolaire, semestre, appreciationFrancais, appreciationMath, appreciationGenerale, numEleve)
    VALUES ('$annee','$semestre','$fr', '$math', '$gen', (select numEleve from eleve where nomEleve='$nomEleve' and prenomEleve='$prenomEleve'))";
    $result = $connexion->query($addBulletin);
    echo "le bulletin à été correctement ajouté";
    echo "
    <form action='classe.php' method='post'>
    <input type='hidden' id='nom' name='nom' value='{$nomProf}'><br>
    <input type='hidden' id='prenom' name='prenom' value='{$prenomProf}'><br><br>
    <button type='submit' name='getClasse'>Retour</button>
    </form>\n";
}

function editBulletin(){
    echo "<form action='addNote.php' method='post'>\n";
    echo " 
    <label for='matiere'>Appréciation Français : </label><br>
    <input type='text' id='fr' name='fr'><br>\n";
    echo " 
    <label for='matiere'>Appréciation Math : </label><br>
    <input type='text' id='math' name='math'><br>\n";
    echo " 
    <label for='matiere'>Appréciation Générale : </label><br>
    <input type='text' id='gen' name='gen'><br>\n";
    echo "<input type='hidden' name='prenomEleve' value= '{$_POST['prenomEleve']}'></input>\n";
    echo "<input type='hidden' name='nomEleve' value= '{$_POST['nomEleve']}'></input>\n";
    echo "<input type='hidden' id='nomProf' name='nomProf' value='{$_POST['nomProf']}'>\n";
    echo "<input type='hidden' id='prenomProf' name='prenomProf' value='{$_POST['prenomProf']}'>\n";
    echo "<button type='submit' name='nouveauBulletin' value='nouveauBulletin'>Ajouter</button>\n";
    echo "</form>\n";
}


if(isset($_POST["ajoutNote"])){
    echo "<form action='addNote.php' method='post'>\n";
    echo " 
    <label for='note'>Note à ajouter :</label><br>
    <input type='text' id='note' name='note'><br>\n";
    echo " 
    <label for='matiere'>Matiere dans laquel ajouté la note : <br> (francais ou math)</label><br>
    <input type='text' id='matiere' name='matiere'><br>\n";
    echo "<input type='hidden' name='prenomEleve' value= '{$_POST['prenomEleve']}'></input>\n";
    echo "<input type='hidden' name='nomEleve' value= '{$_POST['nomEleve']}'></input>\n";
    echo "<input type='hidden' id='nomProf' name='nomProf' value='{$_POST['nomProf']}'>\n";
    echo "<input type='hidden' id='prenomProf' name='prenomProf' value='{$_POST['prenomProf']}'>\n";
    echo "<button type='submit' name='nouvelleNote' value='nouvelleNote'>Ajouter</button>\n";
    echo "</form>\n";

}

else if(isset($_POST["contact"])){
    getContact($_POST['nomEleve'], $_POST['prenomEleve'], $_POST['prenomProf'], $_POST['nomProf']);
    
}

else if(isset($_POST["ancienBulettin"])){
    
    getBulettin($_POST['nomEleve'], $_POST['prenomEleve'],$_POST['prenomProf'], $_POST['nomProf'] );

}

else if(isset($_POST["editeBulettin"])){
    
    editBulletin($_POST['nomEleve'], $_POST['prenomEleve'], $_POST['prenomProf'], $_POST['nomProf']);

}

else if(isset($_POST['nouvelleNote'])){
    if($_POST['note'] >= 0 && $_POST['note'] < 21){
        if($_POST['matiere'] == "francais" || $_POST['matiere'] == "math"){
            addNote($_POST['nomEleve'], $_POST['prenomEleve'], $_POST['note'], $_POST['matiere'], $_POST['prenomProf'], $_POST['nomProf']);
        }else{
            echo "la matiere ne peut être que francais ou math";
        } 
    }else{
        echo "La note doit être comprise entre 0 et 20";
    }
}

else if (isset($_POST['nouveauBulletin'])){
    $annee = date("Y");
    $date = date("d-m");
    $semestre;
    if ($date < "01-09"){
        $semestre = 2;
        $annee = strtotime($annee."- 1 years");
    }else{
        $semestre = 1;
    }
    
    addBulletin($_POST['nomEleve'], $_POST['prenomEleve'], $semestre, $annee, $_POST['fr'], $_POST['math'], $_POST['gen'], $_POST['nomProf'], $_POST['prenomProf']);
}

?>
