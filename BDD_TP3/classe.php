<?php 

function connect(){ 
    $PARAM_hote= "localhost";
    $PARAM_port= 3306;
    $PARAM_nom_bd= "tp3";
    $PARAM_utilisateur= "root";
    $PARAM_mot_passe= "";
    $connexion = new PDO('mysql:host='.$PARAM_hote.';port='.$PARAM_port.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);  
    return $connexion;  
}

function getClasse($nomProf, $prenomProf){
    $connexion = connect();
    $anneeActuelle = date("Y");
    $date = date("d-m");
    if ($date < "01-09"){
       $anneeActuelle = strtotime($anneeActuelle."- 1 years");
    }
    $classe = "SELECT DISTINCT nomEleve, prenomEleve FROM eleve
    JOIN classeeleve on eleve.numEleve = classeeleve.numEleve 
    JOIN classeprofesseur ON classeeleve.numClasse = classeprofesseur.numClasse 
    AND classeeleve.libelleClasse = classeprofesseur.libelleClasse
    JOIN professeur ON classeprofesseur.numProf = professeur.numProf
    WHERE professeur.nomProf = '$nomProf'
    AND professeur.prenomProf = '$prenomProf'
    AND classeeleve.anneeInscription = '$anneeActuelle'";
    $result = $connexion->query($classe);  
    echo "<table>\n";
    echo "<tr>\n";
    echo "<th>prenom</th>\n";
    echo "<th>nom<th/th>\n";
    echo "</tr>\n";

    while($row = $result->fetch()){
        echo "<form action='addNote.php' method='post'>\n";
        echo "<tr>\n";
        echo "<td> " . $row['prenomEleve'] . "</td>\n";
        echo "<td>" . $row['nomEleve'] . "</td>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='prenomEleve' value= '{$row['prenomEleve']}'></input>\n";
        echo "<input type='hidden' name='nomEleve' value= '{$row['nomEleve']}'></input>\n";
        echo "<input type='hidden' id='nomProf' name='nomProf' value='{$nomProf}'>\n";
        echo "<input type='hidden' id='prenomProf' name='prenomProf' value='{$prenomProf}'>\n";
        echo "</td>\n";
        echo "<td>
            <button type='submit' name='ajoutNote' value='ajoutNote'>ajoutNote</button>
            <button type='submit' name='contact' value='contact'>contact</button>
            <button type='submit' name='ancienBulettin' value='ancienBulettin'>ancienBulettin</button>
            <button type='submit' name='editeBulettin' value='editeBulettin'>editeBulettin</button> </td>\n";
        echo "</tr>\n";
        echo "</form>\n";
    }
    echo "</table>\n";
    
    
}

if(isset($_POST["getClasse"])){
    getClasse($_POST['nom'], $_POST['prenom']);
}
