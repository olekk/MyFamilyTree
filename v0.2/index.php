<!-- 
todo: edycja ind wpływa na relacje m - son,father,husband; f - daughter,mother,wife
porzucam. 
 -->
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Family tree</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

 <?php
    $conn = new mysqli('localhost', 'root', '', 'familytree2');
    $conn->set_charset("utf8");
    if($conn->query("SELECT * FROM individuals")->num_rows < 1) {               //jeśli nie ma rekordów dodajemy pierwszą osobę
        $conn->query("ALTER TABLE individuals AUTO_INCREMENT = 1;");
        $conn->query("ALTER TABLE relationships AUTO_INCREMENT = 1;");
        $conn->query("INSERT INTO individuals VALUES (NULL, 'Imie','Nazwisko','m','".date("Y-m-d")."')");
    }

    function addParents($childId) {

        //echo 'addParents '.$childId;
        global $conn;
        $childRole = ($conn->query("SELECT gender_mf FROM individuals WHERE ind_id=".$childId)->fetch_assoc()['gender_mf']=='m' ? 'Son': 'Daughter');

        if($conn->query("SELECT * FROM relationships WHERE ind1_id=".$childId." AND ind1_role='".$childRole."'")->num_rows==0) {
            $conn->query("INSERT INTO individuals VALUES (NULL, 'Tata','Nazwisko','m','".date("Y-m-d")."')");
    
            $fatherId = $conn->query("SELECT MAX(ind_id) FROM `individuals`")->fetch_assoc()['MAX(ind_id)'];
            
            $conn->query("INSERT INTO relationships VALUES (NULL, ".$childId.",".$fatherId.",'".$childRole."','Father')");

            addSpouse($fatherId);
    
        } else if($conn->query("SELECT * FROM relationships WHERE ind1_id=".$childId." AND ind1_role='".$childRole."'")->num_rows==1){    //jeśli istnieje tylko jeden rodzic - dodajemy drugiego
            $existingParentId = $conn->query("SELECT * FROM relationships WHERE ind1_id=".$childId." AND ind1_role='".$childRole."'")->fetch_assoc()['ind2_id'];
            addSpouse($existingParentId);
        } else {
            echo 'Parents already exist. ';

        }
        
    }

    function addSpouse($existingSpouseId) {
        //echo 'addSpouse '.$existingSpouseId;
        $newSpouseId = NULL;
        global $conn;
        $existingSpouseGender = $conn->query("SELECT gender_mf FROM individuals WHERE ind_id=".$existingSpouseId)->fetch_assoc()['gender_mf'];
        $marriage = $conn->query("SELECT ind1_id, ind2_id FROM relationships WHERE (ind1_id=".$existingSpouseId." AND ind1_role='".($existingSpouseGender=='m' ? 'Husband' : 'Wife' )."') OR (ind2_id=".$existingSpouseId." AND ind2_role='".($existingSpouseGender=='m' ? 'Husband' : 'Wife' )."')")->fetch_assoc();
        
        if($marriage==NULL) {

            $conn->query("INSERT INTO individuals VALUES (NULL, '".($existingSpouseGender=='m' ? 'Żona' : 'Mąż' )."', 'Nazwisko', '".($existingSpouseGender=='m' ? 'f' : 'm')."','".date("Y-m-d")."')");
            $newSpouseId = $conn->query("SELECT MAX(ind_id) FROM `individuals`")->fetch_assoc()['MAX(ind_id)'];

            $conn->query("INSERT INTO relationships VALUES (NULL, ".$existingSpouseId.",".$newSpouseId.",".($existingSpouseGender=='m' ? "'Husband', 'Wife'" : "'Wife', 'Husband'" ).")");
        } else {
            echo 'Spouse already exist.';
            $newSpouseId = ($marriage['ind1_id']!=$existingSpouseId ? $marriage['ind1_id'] : $marriage['ind2_id']);
        }

        $children = $conn->query("SELECT ind1_id, ind1_role FROM relationships WHERE ind2_id=".$existingSpouseId." AND ind2_role=".($existingSpouseGender=='m' ? "'Father'" : "'Mother'" ));

        while($child = $children->fetch_assoc()) {
            //var_dump($child);
            if( !$conn->query("SELECT EXISTS(SELECT * FROM relationships WHERE ind1_id=".$child['ind1_id']." AND ind2_id=".$newSpouseId." AND ind1_role='".$child['ind1_role']."' AND ind2_role=".($existingSpouseGender=='f' ? "'Father'" : "'Mother'" ).")")->fetch_row()[0]) {
                $conn->query("INSERT INTO relationships VALUES (NULL, ".$child['ind1_id'].", ".$newSpouseId.", '".$child['ind1_role']."', ".($existingSpouseGender=='f' ? "'Father'" : "'Mother'" ).")");
            }
        }

        return $newSpouseId;
    }

    if(isset($_POST['addParents'])) {
        addParents($_POST['addParents']);
    }
    else if(isset($_POST['addSpouse'])) {
        addSpouse($_POST['addSpouse']);
    }
    else if(isset($_POST['addChild'])) {
        //$_POST['addChild'] - id rodzica
        echo 'addChild '.$_POST['addChild']." ";
        $conn->query("INSERT INTO individuals VALUES (NULL, 'Dziecko', 'Nazwisko', 'm','".date("Y-m-d")."')");
        $childId = $conn->query("SELECT MAX(ind_id) FROM `individuals`")->fetch_assoc()['MAX(ind_id)'];
        
        $existingParentRole = ($conn->query("SELECT gender_mf FROM individuals WHERE ind_id=".$_POST['addChild'])->fetch_assoc()['gender_mf']=='m' ? 'Father' : 'Mother' );
        $conn->query("INSERT INTO relationships VALUES (NULL,".$childId.", ".$_POST['addChild'].",'Son','".$existingParentRole."')");

        $spouseId = addSpouse($_POST['addChild']);
        // $conn->query("INSERT INTO relationships VALUES (NULL, '".$childId."', '".$spouseId."', 'Son', '".($existingParentRole=='Father' ? 'Mother' : 'Father')."')");
       
    }
    else if(isset($_POST['save'])) {

        echo 'save '.$_POST['save'];
        $conn->query("UPDATE individuals SET first_name='".$_POST['first_name']."', last_name='".$_POST['last_name']."', gender_mf='".$_POST['gender']."', birthdate='".$_POST['birthdate']."' WHERE ind_id=".$_POST['save']);

    }
    else if(isset($_POST['delete'])) {

        echo 'delete '.$_POST['delete'];
        $conn->query("DELETE FROM relationships WHERE ind1_id=".$_POST['delete']." OR ind2_id=".$_POST['delete']);
        $conn->query("DELETE FROM individuals WHERE ind_id=".$_POST['delete']);

    }



    $individuals = $conn->query("SELECT * FROM individuals");
    echo '<br><table>';                                                             //wyświetlenie wszystkich osób i przycisków obook
    while($row = $individuals->fetch_assoc()) {
        echo "<tr>";
        if(@$_POST['edit']==$row['ind_id']) {                                   //reakcja na edit
            echo '<form method="post"><td>'.$row['ind_id']."</td><td>";
            echo '<input type"text" value="'.$row['first_name'].'" name="first_name"></td><td>';
            echo '<input type"text" value="'.$row['last_name'].'" name="last_name"></td><td>';
            echo '<label> <input type="radio" name="gender" value="m" checked> Male</label>
            <label> <input type="radio" name="gender" value="f"> Female</label></td><td>';
            echo '<input type"text" value="'.$row['birthdate'].'" name="birthdate"></td><td>';
            echo '<button type="submit" name="save" value="'.$_POST['edit'].'">Save</button></td></form>';
        } else {                                                                   //zwykłe wyswietlenie rekordu
            echo "<td>".$row['ind_id']."</td><td>";
            echo $row['first_name']."</td><td>";
            echo $row['last_name']."</td><td>";
            echo $row['gender_mf']."</td><td>";
            echo $row['birthdate']."</td>";        

            echo '<td><form method="post"><button type="submit" name="addParents" value="'.$row['ind_id'].'">Add Parents</button>
            <button type="submit" name="addSpouse" value="'.$row['ind_id'].'">Add Spouse</button>
            <button type="submit" name="addChild" value="'.$row['ind_id'].'">Add Child</button>
            <button type="submit" name="edit" value="'.$row['ind_id'].'">Edit</button>
            <button type="submit" name="delete" value="'.$row['ind_id'].'">Delete</button></form></td></tr>';
        }
    }
    echo "</table><br><br>";

    $relationships = $conn->query("SELECT * FROM relationships");
    echo '<table>';                                                             //wyświetlenie wszystkich osób i przycisków obook
    while($row = $relationships->fetch_assoc()) {
        echo "<tr>";                                                                   //zwykłe wyswietlenie rekordu
        foreach($row as $i => $value) {
            echo '<td>'.$value.'</td>';
        }
        echo '</tr>';
    }
    echo "</table>";

 ?>
<br><br>
<button onclick="window.location = window.location.href;">Reload</button>
     
</body>
</html>
