<?php 
    require('./library.php');

    // SALVAR ACCESS TOKEN
    $accessToken = $_GET['accessTokenQueue'];

    checkAccessToken('61780945263ad50009f2e990', $accessToken);

    echo "<h1>A PÁGINA REAL OFICIAL</h1>";
?>