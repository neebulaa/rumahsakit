<?php 

if(isset($_POST['submit'])){
    var_dump($_POST);
    die();
}

if(isset($_POST['submit1'])){
    var_dump($_POST);
    die();
}

$arr = [
    "a1" => 1,
    "a2" => 2, 
    "a3" => 3, 
    "a4" => 4, 
    "a5" => 5
];

var_dump(array_map(function($d) {echo $d;}, $arr));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="hello">
        <input type="text" name="hello">
        <button type="submit" name="submit">Submit</button>
    </form>

    <br>

    <form action="" method="post">
        <input type="text" name="hello">
        <input type="text" name="hello">
        <button type="submit" name="submit1">Submit</button>
    </form>
</body>
</html>