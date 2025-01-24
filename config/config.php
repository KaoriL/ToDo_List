<?php

function getConnexion(){
    try{
        $dsn = 'mysql:host=localhost;dbname=todo_list;charset=utf8';
        $user ='root';
        $password ='root';

        return new PDO($dsn, $user, $password,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }catch(Exception $e){
        die('Erreur :'.$e->getMessage());
    }
}

?>
