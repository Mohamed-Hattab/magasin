<?php

namespace MagasinBundle\Controller;

use MagasinBundle\Entity\Commande;
use MagasinBundle\Entity\Lignecommande;
use MagasinBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MagasinBundle\Entity\Categorie;
use MagasinBundle\Entity\User;
use MagasinBundle\Entity\Article;
use MagasinBundle\Entity\Vendeur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class FrontController extends Controller
{
    public function indexAction()
    {
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$em->findAll();
        $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article') ;
        $art=$ems->findAll();
        $emsa= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Slideshow') ;
        $sld=$emsa->findAll();
        if(isset($_SESSION['login'])){
            if(isset($_SESSION['type'])){
            return $this->render('MagasinBundle:Front:index.html.twig',array('slide'=>$sld,'art'=>$art,'cat'=> $cat,"name"=>$_SESSION['login'],"id"=>$_SESSION['id'],"type"=>$_SESSION['type']));
        }
        }

        return $this->render('MagasinBundle:Front:index.html.twig',array('slide'=>$sld,'art'=>$art,'cat'=> $cat));
    }


    public function connexionAction()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        session_destroy();
        if($_POST){
            session_start();
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\User');
            $user=$em->findOneBy(array('email'=>$_POST['Email'],'password'=>$_POST['Password']));
            $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Vendeur');
            $vendeur=$ems->findOneBy(array('email'=>$_POST['Email'],'password'=>$_POST['Password']));
            $ema= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
            $cat=$ema->findAll();
            $emsa= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Slideshow') ;
            $sld=$emsa->findAll();
            $emk= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article') ;
            $art=$emk->findAll();
            if($user){
                $id=$user->getId();
                $type="client";
                $login=$user->getLogin();
                $_SESSION['login']=$login;
                $_SESSION['id']=$id;
                $_SESSION['type']=$type;
                return $this->render('MagasinBundle:Front:index.html.twig',array("slide"=>$sld,"art"=>$art,"cat"=>$cat,"name"=>$_SESSION['login'],"id"=>$_SESSION['id'],"type"=>$_SESSION['type']));

            }
            if($vendeur){
                $id=$vendeur->getId();
                $type=$vendeur->getType();
                $login=$vendeur->getMagasin();
                $_SESSION['login']=$login;
                $_SESSION['id']=$id;
                $_SESSION['type']=$type;
                return $this->render('MagasinBundle:Front:index.html.twig',array("slide"=>$sld,"art"=>$art,"cat"=>$cat,"name"=>$_SESSION['login'],"id"=>$_SESSION['id'],"type"=>$_SESSION['type']));
            }
            else{
                return $this->render('MagasinBundle:Front:connexion.html.twig',array("error"=>" login Incorrect !"));
            }
        }
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$em->findAll();
        return $this->render('MagasinBundle:Front:connexion.html.twig',array("cat"=>$cat));

    }



    public function modifierprofileAction($id)
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        $emx = $this->getDoctrine()->getRepository('MagasinBundle\Entity\User');
        $us=$emx->findByid($id);
        $type=$us[0]->getType();
        if ($_POST) {
            $em = $this->getDoctrine()->getManager();
            $profile = $em->getRepository('MagasinBundle\Entity\User')->find($id);
            $profile->setNom($_POST['nom']);
            $profile->setPrenom($_POST['prenom']);
            $profile->setEmail($_POST['email']);
            $profile->setTel($_POST['tel']);
            $profile->setAdresse($_POST['adresse']);
            $profile->setLogin($_POST['login']);
            $profile->setPassword($_POST['password']);
            $profile->setType($type);
            if($_POST['oldpassword']==$us[0]->getPassword()){
                $em->flush();
                return $this->render('MagasinBundle:Front:index.html.twig',array("name"=>$_SESSION['login'],"id"=>$_SESSION['id'],"type"=>$_SESSION['type']));
            }
            else{
                return $this->render('MagasinBundle:Front:modifierprofile.html.twig',array("cat"=>$cat,"name"=>$_SESSION['login'],"id"=>$_SESSION['id'],"type"=>$_SESSION['type'],"us"=>$us,"error"=>"Mot de passe incorrect !"));
            }
        }


        return $this->render('MagasinBundle:Front:modifierprofile.html.twig',array("cat"=>$cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id'],"us"=>$us));

    }


    public function inscriptionAction()
    {
        if($_POST){
            $em=$this->getDoctrine()->getManager();
            $user= new User();
            $user->setNom($_POST['nom']);
            $user->setPrenom($_POST['prenom']);
            $user->setAdresse($_POST['adresse']);
            $user->setTel($_POST['tel']);
            $user->setEmail($_POST['email']);
            $user->setLogin($_POST['login']);
            $user->setPassword($_POST['password']);
            $user->setType("client");
            $ema= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
            $cat=$ema->findAll();
            $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\User') ;
            $us= $ems->findOneBy(array('email'=>$_POST['email']));
            if($us){
                return $this->render('MagasinBundle:Front:inscription.html.twig',array('cat'=> $cat,'error'=>"Cette adresse e-mail est déjà utilisé. Essayez un autre adresse.!"));
            }else{
                $em->persist($user);
                $em->flush();
                return $this->render('MagasinBundle:Front:inscription.html.twig',array('cat'=> $cat,'ok'=>"inscription effectuée, vous pouvez se connecter ."));
            }
        }
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$em->findAll();
        return $this->render('MagasinBundle:Front:inscription.html.twig',array("cat"=>$cat));

    }


    public function ajoutarticleAction()
    {
        if($_POST){
            $file=$_FILES['file'];
//            print_r($file);
            $fileName=$_FILES['file']['name'];
            $fileTmpName=$_FILES['file']['tmp_name'];
            $fileSize=$_FILES['file']['size'];
            $fileError=$_FILES['file']['error'];
            $fileType=$_FILES['file']['type'];

            $fileExt= explode('.', $fileName);
            $fileActualExt=strtolower(end($fileExt));
            $allowed = array('jpg','jpeg','png');
            if(in_array($fileActualExt,$allowed)){
                if($fileError===0){
                    if($fileSize < 1000000){
                        define ('SITE_ROOT', realpath(dirname(__FILE__)));
                        $fileNameNew= uniqid('',true).".".$fileActualExt;
                        $fileDestination= $this->getParameter('brochures_directory').$fileNameNew;
                        move_uploaded_file($fileTmpName,$fileDestination);
                        header("location: index.php?uploadsuccess");
//                        echo "success!";
                    }else{
//                        echo "your file is too big!";
                    }

                }else{
                    echo "There was an error uploading your file!";
                }
            }else{
                echo "You cannot upload files of this type !";
            }
            $em=$this->getDoctrine()->getManager();
            $article= new Article();
            $article->setTitre($_POST['titre']);
            $article->setDescription($_POST['description']);
            $article->setCategorie($_POST['categorie']);
            $article->setQuantite($_POST['quantite']);
            $article->setPrix($_POST['prix']);
            $article->setImage($fileNameNew);
            $article->setIdvendeur($_SESSION['id']);
            $article->setType("articleatt");
            $em->persist($article);
            $em->flush();
            $ema= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
            $cat=$ema->findAll();
            return $this->render('MagasinBundle:Front:ajoutarticle.html.twig',array('cat'=> $cat,'ok'=>"Merci! Votre annonce va être vérifiée par nos modérateurs et sera bientôt en ligne.","name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
        }
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$em->findAll();
        return $this->render('MagasinBundle:Front:ajoutarticle.html.twig',array('cat'=> $cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));

    }
    public function modifartAction($id)
    {
        if ($_POST) {
            $file=$_FILES['file'];
//            print_r($file);
            $fileName=$_FILES['file']['name'];
            $fileTmpName=$_FILES['file']['tmp_name'];
            $fileSize=$_FILES['file']['size'];
            $fileError=$_FILES['file']['error'];
            $fileType=$_FILES['file']['type'];

            $fileExt= explode('.', $fileName);
            $fileActualExt=strtolower(end($fileExt));
            $allowed = array('jpg','jpeg','png');
            if(in_array($fileActualExt,$allowed)){
                if($fileError===0){
                    if($fileSize < 1000000){
                        define ('SITE_ROOT', realpath(dirname(__FILE__)));
                        $fileNameNew= uniqid('',true).".".$fileActualExt;
                        $fileDestination= $this->getParameter('brochures_directory').$fileNameNew;
                        move_uploaded_file($fileTmpName,$fileDestination);
                        header("location: index.php?uploadsuccess");
//                        echo "success!";
                    }else{
//                        echo "your file is too big!";
                    }

                }else{
                    echo "There was an error uploading your file!";
                }
            }else{
                echo "You cannot upload files of this type !";
            }
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('MagasinBundle\Entity\Article')->find($id);
            $article->setTitre($_POST['titre']);
            $article->setCategorie($_POST['categorie']);
            $article->setPrix($_POST['prix']);
            $article->setQuantite($_POST['quantite']);
            $article->setDescription($_POST['description']);
            $article->setImage($fileNameNew);
            $em->flush();

        }

        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article') ;
        $art=$em->findByid($id);
        $ema= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$ema->findAll();
        return $this->render('MagasinBundle:Front:modifart.html.twig',array('ids'=>$id,'art'=>$art,'cat'=> $cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));

    }
    public function devenirvendeurAction()
    {
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$em->findAll();
        return $this->render('MagasinBundle:Front:devenirvendeur.html.twig',array('cat'=> $cat));
    }

    public function inscrivendeurAction()
    {
        if($_POST){
            $em=$this->getDoctrine()->getManager();
            $user= new Vendeur();
            $user->setNom($_POST['nom']);
            $user->setPrenom($_POST['prenom']);
            $user->setAdresse($_POST['adresse']);
            $user->setTel($_POST['tel']);
            $user->setEmail($_POST['email']);
            $user->setMagasin($_POST['magasin']);
            $user->setPassword($_POST['password']);
            $user->setType("vendeuratt");
            $ema= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
            $cat=$ema->findAll();
            $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Vendeur') ;
            $us= $ems->findOneBy(array('email'=>$_POST['email']));
            if($us){
                return $this->render('MagasinBundle:Front:inscrivendeur.html.twig',array('cat'=> $cat,'error'=>"Cette adresse e-mail est déjà utilisé. Essayez un autre adresse.!"));
            }else{
                $em->persist($user);
                $em->flush();
                return $this->render('MagasinBundle:Front:inscrivendeur.html.twig',array('cat'=> $cat,'ok'=>"inscription effectuée, vous pouvez se connecter ."));
            }
        }
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$em->findAll();
        return $this->render('MagasinBundle:Front:inscrivendeur.html.twig',array("cat"=>$cat));
    }

    public function mesarticlesAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        $em = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article');
        $art=$em->findBy(array('idvendeur'=>$_SESSION['id']));
        if($art){
            return $this->render('MagasinBundle:Front:mesarticles.html.twig',array("art"=>$art,"cat"=>$cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
        }
        else{
            return $this->render('MagasinBundle:Front:mesarticles.html.twig',array("error"=>"Pas d'articles !","cat"=>$cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
        }
    }

    public function produitAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();

        if($_GET){
            if(isset($_SESSION['type'])){
                session_destroy();
            }
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article') ;
            $art=$em->findBy(array('categorie'=>$_GET['cat']));
            if(isset($_SESSION['login'])) {
                if ($art) {
                    return $this->render('MagasinBundle:Front:produit.html.twig', array("ctg" => $_GET['cat'], "art" => $art, "cat" => $cat, "name" => $_SESSION['login'], "type" => $_SESSION['type'], "id" => $_SESSION['id']));
                } else {
                    return $this->render('MagasinBundle:Front:produit.html.twig', array("ctg" => $_GET['cat'], "error" => "Aucun produit pour le categorie", "cat" => $cat, "name" => $_SESSION['login'], "type" => $_SESSION['type'], "id" => $_SESSION['id']));
                }
            }else{
                if ($art) {
                    return $this->render('MagasinBundle:Front:produit.html.twig', array("ctg" => $_GET['cat'], "art" => $art, "cat" => $cat));
                } else {
                    return $this->render('MagasinBundle:Front:produit.html.twig', array("ctg" => $_GET['cat'], "error" => "Aucun produit pour le categorie", "cat" => $cat));
                }
            }
        }

    }

    public function errorAction()
    {
        return $this->render('MagasinBundle:Front:error.html.twig');

    }

    public function suppartAction(Article $article)
    {
        $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$ems->findAll();
        $em= $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        $nom=$article->getTitre();
        $id=$article->getId();
        return $this->render('MagasinBundle:Front:suppart.html.twig',array("cat"=>$cat,"type"=>$_SESSION['type'],"id"=>$id,"name"=>$_SESSION['login'],"nom"=>$nom));

    }

    public function commandeAction()
    {
        if(isset($_SESSION['type'])){
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
            $cat=$em->findAll();
            $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\User') ;
            $user=$ems->findByid($_SESSION{'id'});
            if(isset($_POST["w3ls_item_1"])){
                $arts[0]=array('titre'=>$_POST["w3ls_item_1"],'prix'=>$_POST["amount_1"],'qte'=>$_POST["quantity_1"]);
            }
            if(isset($_POST["w3ls_item_2"])){
                $arts[1]=array('titre'=>$_POST["w3ls_item_2"],'prix'=>$_POST["amount_2"],'qte'=>$_POST["quantity_2"]);
            }
            if(isset($_POST["w3ls_item_3"])){
                $arts[2]=array('titre'=>$_POST["w3ls_item_3"],'prix'=>$_POST["amount_3"],'qte'=>$_POST["quantity_3"]);
            }
            if(isset($_POST["w3ls_item_4"])){
                $arts[3]=array('titre'=>$_POST["w3ls_item_4"],'prix'=>$_POST["amount_4"],'qte'=>$_POST["quantity_4"]);
            }
            if(isset($_POST["w3ls_item_5"])){
                $arts[4]=array('titre'=>$_POST["w3ls_item_5"],'prix'=>$_POST["amount_5"],'qte'=>$_POST["quantity_5"]);
            }
            if(isset($_POST["w3ls_item_6"])){
                $arts[5]=array('titre'=>$_POST["w3ls_item_6"],'prix'=>$_POST["amount_6"],'qte'=>$_POST["quantity_6"]);
            }
            if(isset($_POST["w3ls_item_7"])){
                $arts[6]=array('titre'=>$_POST["w3ls_item_7"],'prix'=>$_POST["amount_7"],'qte'=>$_POST["quantity_7"]);
            }
            $_SESSION['arts']=$arts;
            if($_SESSION['type'] == "client"  ){
                return $this->render('MagasinBundle:Front:commande.html.twig',array('arts'=>$arts,'user'=>$user,'cat'=> $cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
            }
            else{
                return $this->render('MagasinBundle:Front:error1.html.twig');
            }

        }
        else
        {        return $this->render('MagasinBundle:Front:connexion.html.twig');
        }
    }

    public function passercommandeAction()
    {
        $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$ems->findAll();
        $em=$this->getDoctrine()->getManager();
        $arts=$_SESSION['arts'];
        $c="";
        foreach ($arts as $array) {
            $commande= new Commande();
            $commande->setIdclient($_SESSION['id']);
            $commande->setDate(date('d/m/Y'));
            $commande->setEtat("commandeatt");
            $titre="";
            $prix=0;
            $qte=0;
            foreach ($array as $key => $value) {
                $c= $key;
                if($key=="titre"){
                    $titre=$value;
                }
                if($key=="prix"){
                    $prix=$value;
                }
                if($key=="qte"){
                    $qte=$value;
                }

            }
            $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article') ;
            $cat=$ems->findOneBy(['titre'=>$titre]);
            $commande->setIdproduit($cat->getId());
            $commande->setPrix($prix);
            $commande->setQuantite($qte);
            $em->persist($commande);
            $em->flush();
            $id=$commande->getId();
        }
        return $this->render('MagasinBundle:Front:passercommande.html.twig',array("x"=>$c,"cat"=>$cat,"type"=>$_SESSION['type'],"id"=>$id,"name"=>$_SESSION['login']));

    }

    public function venteattAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        $em = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Commande');
        $com=$em->findAll();
        $emk = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article');
        $art=$emk->findAll();
        $id=$_SESSION["id"];
        $ema = $this->getDoctrine()->getRepository('MagasinBundle\Entity\User');
        $x=0;
        if($com) {
            foreach ($com as $array) {
                $idp = $array->getIdproduit();
                $item = $emk->find($idp);
                $idv = $item->getIdvendeur();
                if ($id == $idv) {
                    $client = $ema->find($array->getIdclient());
                    $art = $item;
                    $x = $x + 1;
                    $t[$x] = array('ids' => $array->getId(), 'type' => $array->getEtat(), 'client' => $client, 'titre' => $art->getTitre(), 'prix' => $art->getPrix(), 'qte' => $array->getQuantite(), 'date' => $array->getDate());

                }

            }
            return $this->render('MagasinBundle:Front:venteatt.html.twig',array('x'=>$x,"t"=>$t,"cat"=>$cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
        }
        else{
            return $this->render('MagasinBundle:Front:venteatt.html.twig',array("name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));

        }




    }

    public function venteaccAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        $em = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Commande');
        $com=$em->findAll();
        $emk = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article');
        $art=$emk->findAll();
        $id=$_SESSION["id"];
        $ema = $this->getDoctrine()->getRepository('MagasinBundle\Entity\User');
        $x=0;
        if($com){
        foreach ($com as $array){
            $idp=$array->getIdproduit();
            $item=$emk->find($idp);
            $idv=$item->getIdvendeur();
            if($id==$idv){
                $client=$ema->find($array->getIdclient());
                $art=$item;
                $x=$x+1;
                $t[$x]=array('ids'=>$array->getId(),'type'=>$array->getEtat(),'client'=>$client,'titre'=>$art->getTitre(),'prix'=>$art->getPrix(),'qte'=>$array->getQuantite(),'date'=>$array->getDate());

            }

        }
            return $this->render('MagasinBundle:Front:venteacc.html.twig',array("t"=>$t,"cat"=>$cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
        }
        else{
            return $this->render('MagasinBundle:Front:venteacc.html.twig',array("name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
        }
    }

    public function ventelivAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        $em = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Commande');
        $com=$em->findAll();
        $emk = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article');
        $art=$emk->findAll();
        $id=$_SESSION["id"];
        $ema = $this->getDoctrine()->getRepository('MagasinBundle\Entity\User');
        $x=0;
        if($com){
        foreach ($com as $array){
            $idp=$array->getIdproduit();
            $item=$emk->find($idp);
            $idv=$item->getIdvendeur();
            if($id==$idv){
                $client=$ema->find($array->getIdclient());
                $art=$item;
                $x=$x+1;
                $t[$x]=array('ids'=>$array->getId(),'type'=>$array->getEtat(),'client'=>$client,'titre'=>$art->getTitre(),'prix'=>$art->getPrix(),'qte'=>$array->getQuantite(),'date'=>$array->getDate());

            }

        }
        return $this->render('MagasinBundle:Front:venteliv.html.twig',array("t"=>$t,"cat"=>$cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
        }
        else{
            return $this->render('MagasinBundle:Front:venteliv.html.twig',array("cat"=>$cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
        }
    }


    public function acceptervntAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        if($_GET){
            $id=$_GET['id'];
            $em = $this->getDoctrine()->getManager();
            $employe = $em->getRepository('MagasinBundle\Entity\Commande')->find($id);
            $employe->setEtat("commandeacc");
            $em->flush();
            return $this->render('MagasinBundle:Front:acceptervnt.html.twig',array('cat'=>$cat));

        }




        return $this->render('MagasinBundle:Front:error.html.twig');
    }




    public function supvntAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        if($_GET){
            $id=$_GET['id'];
            $em = $this->getDoctrine()->getManager();
            $employe = $em->getRepository('MagasinBundle\Entity\Commande')->find($id);
            $employe->setEtat("commandesupp");
            $em->flush();
            return $this->render('MagasinBundle:Front:supvnt.html.twig',array('cat'=>$cat));

        }




        return $this->render('MagasinBundle:Front:error.html.twig');
    }


    public function livrervntAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        if($_GET){
            $id=$_GET['id'];
            $em = $this->getDoctrine()->getManager();
            $employe = $em->getRepository('MagasinBundle\Entity\Commande')->find($id);
            $employe->setEtat("commandeliv");
            $em->flush();
            return $this->render('MagasinBundle:Front:livrervent.html.twig',array('cat'=>$cat));

        }




        return $this->render('MagasinBundle:Front:error.html.twig');
    }

    public function mescommandesAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        $em = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Commande');
        $com=$em->findAll();
        $emk = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article');
        $id=$_SESSION["id"];
        $ema = $this->getDoctrine()->getRepository('MagasinBundle\Entity\User');
        $x=0;
        foreach ($com as $array){
                if($array->getIdclient()==$id){
                    $client=$ema->find($array->getIdclient());
                    $art=$emk->find($array->getIdproduit());
                    $t[$x]=array('ids'=>$array->getId(),'type'=>$array->getEtat(),'client'=>$client,'titre'=>$art->getTitre(),'prix'=>$art->getPrix(),'qte'=>$array->getQuantite(),'date'=>$array->getDate());
                    $x=$x+1;
                }



        }




        return $this->render('MagasinBundle:Front:mescommandes.html.twig',array("t"=>$t,"cat"=>$cat,"name"=>$_SESSION['login'],"type"=>$_SESSION['type'],"id"=>$_SESSION['id']));
    }

    public function contactusAction()
    {
        if($_POST){
            $em=$this->getDoctrine()->getManager();
            $mail= new Mail();
            $mail->setNom($_POST['nom']);
            $mail->setEmail($_POST['email']);
            $mail->setTel($_POST['tel']);
            $mail->setMessage($_POST['message']);
            $mail->setDate(date('d/m/Y'));
            $em->persist($mail);
            $em->flush();
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
            $cat=$em->findAll();
            return $this->render('MagasinBundle:Front:contactus.html.twig',array('cat'=> $cat,'ok'=>"ok"));
        }
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$em->findAll();
        return $this->render('MagasinBundle:Front:contactus.html.twig',array('cat'=> $cat));
    }

    public function uploadAction()
    {
        if($_POST){
            $file=$_FILES['file'];
            print_r($file);
            $fileName=$_FILES['file']['name'];
            $fileTmpName=$_FILES['file']['tmp_name'];
            $fileSize=$_FILES['file']['size'];
            $fileError=$_FILES['file']['error'];
            $fileType=$_FILES['file']['type'];

            $fileExt= explode('.', $fileName);
            $fileActualExt=strtolower(end($fileExt));
            $allowed = array('jpg','jpeg','png');
            if(in_array($fileActualExt,$allowed)){
                if($fileError===0){
                    if($fileSize < 1000000){
                        define ('SITE_ROOT', realpath(dirname(__FILE__)));
                        $fileNameNew= uniqid('',true).".".$fileActualExt;
                        $fileDestination= '/'.$fileNameNew;
                        move_uploaded_file($fileTmpName,SITE_ROOT.$fileDestination);
                        header("location: index.php?uploadsuccess");
                        echo "success!";
                    }else{
                        echo "your file is too big!";
                    }

                }else{
                    echo "There was an error uploading your file!";
                }
            }else{
                echo "You cannot upload files of this type !";
            }
            return $this->render('MagasinBundle:Front:import.html.twig');
        }
        return $this->render('MagasinBundle:Front:import.html.twig');
    }



    public function viewAction()
    {
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        if($_GET){
            $id=$_GET['id'];
            $em = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article');
            $art = $em->find($id);
            $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Vendeur');
            $vend = $ems->find($art->getIdVendeur());
            $ven = $vend->getMagasin();
            return $this->render('MagasinBundle:Front:view.html.twig',array('cat'=>$cat,'art'=>$art,'ven'=>$ven));

        }




        return $this->render('MagasinBundle:Front:error.html.twig');
    }











}



