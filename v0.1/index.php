<?php 
    $conn = new mysqli('localhost', 'root', '', 'familytree');

    if(isset($_POST['RESET'])) {
        if ($conn->query("DELETE FROM family")) {
            echo "ALL RECORDS DELETED "; } else {echo $conn->error; }
        if ($conn->query("ALTER TABLE family AUTO_INCREMENT = 1")) {
            echo "| INDEX SET TO 1 <br><br> "; } else {echo $conn->error; }
    }


    if ($conn->query("SELECT * FROM family WHERE id=1")->num_rows < 1) {
        // Pierwszy rekord
        if(isset($_POST['name']))
        {   
            echo "Dodanie pierwszego rekordu ";

            $name = $_POST['name'];
            $gender = $_POST['gender'];
    
            if ($conn->query("INSERT INTO family VALUES (NULL, '$name', 0, 0 , '$gender')")) {
                echo 'Sukces<br><br>'; } else {echo $conn->error.'<br><br>'; }
        }
        else { echo "Pierwsze uruchomienie"; }
    } 
    else { //kolejny rekord
        if (isset($_POST['addParents'])) {
            echo "addParents clicked <br>";
            if($conn->query("SELECT * FROM family WHERE id=".$_POST['addParents']." AND father=0")->num_rows > 0)
            {
                if ($conn->query("INSERT INTO family VALUES (NULL, 'Tata', 0, 0 , 1),(NULL, 'Mama', 0, 0 , 0)")) {
                    echo 'Sukces<br><br>'; } else { echo $conn->error.'<br><br>';}
    
                if ($conn->query("UPDATE family SET father=".($conn->query("SELECT * FROM family")->num_rows-1).", mother=".($conn->query("SELECT * FROM family")->num_rows)." WHERE id=".$_POST['addParents'])) {
                    echo 'Sukces<br><br>'; } else { echo $conn->error.'<br><br>';}
            }
            
            echo "Rodzice już istnieją <br>";

        } else if (isset($_POST['addChildren'])) {  //najpierw dodajemy żone, potem dziecko
            echo "addChildren clicked <br>";
            
            $plecIstRodzica = ($conn->query("SELECT gender FROM family WHERE id=".$_POST['addChildren'])->fetch_object()->gender ? 1 : 0);

            if (dzieci($_POST['addChildren'])==0) {
                if ($conn->query("INSERT INTO family VALUES (NULL, '".($plecIstRodzica ? 'MamaD' : 'TataD')."', 0, 0 , ".($plecIstRodzica ? 0 : 1).")")) {
                    echo 'Sukces<br><br>'; } else {echo $conn->error.'<br><br>'; }
                    //dodanie dziecka i przypisanie drugiego rodzica z ostatniego rekordu ^
                if ($conn->query("INSERT INTO family VALUES (NULL, 'Dziecko', ".($plecIstRodzica ? $_POST['addChildren'] : $conn->query("SELECT * FROM family")->num_rows).", ".($plecIstRodzica ? $conn->query("SELECT * FROM family")->num_rows : $_POST['addChildren'])." , 1)")) {
                    echo 'Sukces<br><br>'; } else {echo $conn->error.'<br><br>'; }
            } else {    //dodanie dziecka i przypisanie mu drugiego rodzica z funkcji partner
                if ($conn->query("INSERT INTO family VALUES (NULL, 'Dziecko', ".($plecIstRodzica ? $_POST['addChildren'] : partner($_POST['addChildren'])).", ".($plecIstRodzica ? partner($_POST['addChildren']) : $_POST['addChildren'])." , 1)")) {
                    echo 'Sukces<br><br>'; } else {echo $conn->error.'<br><br>'; }
            }
            
        } else if (isset($_POST['save'])) {
            if ($conn->query("UPDATE family SET name='".$_POST['ename'].(isset($_POST['egender']) ? "', gender=".$_POST['egender'] : "'")." WHERE id=".$_POST['save'])) {
                echo 'Sukces<br><br>'; } else {echo $conn->error.'<br><br>'; }
        }
    }

    function dzieci($idOsoby) {
        global $conn;
        $dzieci = $conn->query("SELECT * FROM family WHERE father='$idOsoby' OR mother='$idOsoby'");
        if ($dzieci->num_rows > 0) {
            return $dzieci->fetch_assoc();
        } else { 
            return 0; 
        }
    }

    function partner($idOsoby) {
        global $conn;
        if($conn->query("SELECT * FROM family WHERE father='$idOsoby'")->num_rows > 0) {
            $idPartnera = $conn->query("SELECT mother FROM family WHERE father='$idOsoby'")->fetch_object()->mother;
        } else if ($conn->query("SELECT * FROM family WHERE mother='$idOsoby'")->num_rows > 0) {
            $idPartnera = $conn->query("SELECT father FROM family WHERE mother='$idOsoby'")->fetch_object()->father;
        } else {
            $idPartnera = 0;
        }

        return $idPartnera;
    }
    

    if ($conn->query("SELECT * FROM family WHERE id=1")->num_rows < 1) {
        echo '<form method="post"> <br>
            Dodaj pierwszą osobę <br>
        
            <input type="text" placeholder="Name" name="name"> <br>
            <label> <input type="radio" name="gender" value="1" checked> Male</label>
            <label> <input type="radio" name="gender" value="0"> Female</label> <br>

            <input type="submit">
        </form>';
    } else {
        $family = $conn->query("SELECT * FROM family");

        while($row = $family->fetch_assoc()) {
            if(@$_POST['edit'] == $row['id']) 
            {
                echo $row['id'].' <form method="post"><input type="text" placeholder="Name" name="ename">';
                if(dzieci($_POST['edit'])==0) {
                    echo '<label> <input type="radio" name="egender" value="1" checked> Male</label>
                    <label> <input type="radio" name="egender" value="0"> Female</label>';
                }
                echo '<button type="submit" name="save" value="'.$row['id'].'">Save</button> </form> <br>';
                
            } else {
                $thisfather = ($row['father']==0 ? 'Brak' : $conn->query("SELECT name FROM family WHERE id=".$row['father'])->fetch_object()->name);
                $thismother = ($row['mother']==0 ? 'Brak' : $conn->query("SELECT name FROM family WHERE id=".$row['mother'])->fetch_object()->name);
                echo $row['id'].' '.$row['name'].' '.$thisfather.' '.$thismother.' <form method="post">
                    <button type="submit" name="edit" value="'.$row['id'].'">Edit</button>
                    <button type="submit" name="addParents" value="'.$row['id'].'">Add parents</button>
                    <button type="submit" name="addChildren" value="'.$row['id'].'">Add children</button>
                </form> <br>';
            }
            
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Family Tree</title>
    
<style>
form {
    display: inline;
}

    
</style>
</head>
<body>
<br> <br> <br>
<form method="post"><input type="submit" name="RESET" value="RESET"></form>
<br>
<!-- usuwanie - usuwani są wszysycy potomkowie tej osoby i partnerzy  <br>
dodanie relacji do partnera - zamiast funkcji - niezależność od płci. <br>
<br>
tworzenie drzewa: <br>
mechanika - zaczynanie od pierwszego rekordu - wypozycjonowanie wszystkich osób połączonych z danym rekordem, dodanie ich do listy wypozycjonowanych. <br>
rekurencyjne pozycjonowanie każdek osoby. <br> -->

<br><br>
God left me unfinished
<br><br>
Project suspended for now
<br><br>
<a href="../v0.2">Check v0.2</a>

</body>
</html>