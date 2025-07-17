<?php
// PREMIER OPERATION initialisation de la connexion à la base de donnée
// on crée une variable connexion qui une instantiation de la clasee PDO, en lui passant différents argument dont le SGBD et l'hote, le nom de la db ainsi que username et password 
$connexion = new PDO('mysql:host=127.0.0.1; dbname=userstuto1', 'root', '');

if ($connexion){
echo "la connection ";
}

// on verifie que l'utilisateur à appuyer sur le bouton valider tout en s'assurant que les inputs ayant les id nom,postnom,email et password ne sont pas vide
// le données contenu dans ces inputs seronts stockés dans des variables, on utilise à chaque la fonction htmlspecialchars pour s'assurer que c'est uniquement du texte pure qui sera envoyer et non pas de commander (sql,..);
// Pour ce qui est du mot de passe on va le traiter différemment en utilisant un chiffrement sha1 pour eviter que les password soit stockés en clair dans la db


if (isset($_POST['valider'])) {
    if (!empty(isset( $_POST['nom'])) AND  !empty( $_POST['postnom'])  AND !empty( $_POST['email'])  AND !empty( $_POST['password'])) 
        {
          $nomU = htmlspecialchars($_POST['nom']);
          $postnomU = htmlspecialchars($_POST['postnom']);
          $emailU = htmlspecialchars($_POST['email']);
          $mdpU = sha1($_POST['password']);

          // on effectu un test pour verifier que le mot de passe contient plus de 7 caractères

          if (strlen($_POST['password']) < 7 ){
            $message = "Utilsez un password AYANT PLUS DE  7 caracteres";
            
          }

          else if (strlen($nomU) > 20 || strlen($postnomU) > 20) {
            $message = "votre nom ou post-nom est trop long";
        }

        // une fois qu'on a verifier le mot de passe ainsique que la length du nom et du prenom on peut inserer les données dans la db
        // avant de pouvoir inséere des nouvelles données dans notre on doit s'assurer que l'email forunit n'est pas deja dans notre db, cela garantit que les mails seront UNIQUE


        else {

            //MAIL CHECKOUT
            
            $testmail = $connexion->prepare("SELECT * FROM user_information  WHERE email = ?");
            $testmail->execute(array( $emailU));
            $controlMail = $testmail->rowCount();

            if ( $controlMail == 0) {
                $insertion = $connexion->prepare("INSERT INTO user_information(nom,postnom,email,password) values (?,?,?,?)");
                $insertion->execute(array( $nomU,$postnomU,$emailU,$mdpU)) ;
                $messageSuccess = "votre compte à été créer vous pouvez vous connecter";
            }else {
                $message="cette email est deja utiliser, modifiez et réesayez ";
            }
        }
    }

    else {
        $message = "veuillew remplir tous les champs";
    }

}


?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Connexion et inscription </title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/bootstrap.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/all.min.css'>
   
</head>
<body class="bg-light">
    <div class="container ">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top">
                <h2 class="text-center"> Inscription</h2>
                <p class="text-center text-muted lead"> Simple et Rapide </p>

                <form  method="POST" action=""  >
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-user">
                            </i> 
                        </span>
                        <input type="text" name="nom" class="form-control" placeholder="Nom ">
                    </div>
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-user">
                            </i> 
                        </span>
                        <input type="text" name="postnom" class="form-control" placeholder="Prénom ">
                    </div>
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-envelope">
                            </i> 
                        </span>
                        <input type="text"  name="email" class="form-control" placeholder="Email ">
                    </div>
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-lock">
                            </i> 
                        </span>
                        <input type="text" name = "password"class="form-control" placeholder="Mot de passe ">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name= "valider" class="btn btn-success">S’inscrire</button>
                        <p class="text-center text-muted mt-3">
                             <i style="color:red">

                             <?php

                             if (isset($message)) {
                                echo $message.  "<br>";
                             }
                            
                             ?>

                             </i>

                             <i style="color:green">
                            <?php 
                                if (isset($messageSuccess)){
                                echo $messageSuccess."<br>";
                             }
                                
                             ?>


                             </i>





                            En cliquant sur S’inscrire, vous acceptez nos <a href="#">  Conditions générales</a>, notre <a href=""> Politique de confidentialité </a> et notre <a href="#">  Politique d’utilisation</a> des cookies. 
                        </p>
                        <p class="text-center">
                             Avez vous déjà un compte ?<a href="connexion.html"> Connexion </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
</body>
</html> 
<script src='js/bootstrap.js'></script>