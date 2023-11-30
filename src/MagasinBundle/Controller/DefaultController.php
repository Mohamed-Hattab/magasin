<?php

namespace MagasinBundle\Controller;

use MagasinBundle\Entity\Slideshow;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MagasinBundle\Entity\Categorie;
use MagasinBundle\Entity\Vendeur;
use MagasinBundle\Entity\User;
use MagasinBundle\Entity\Commande;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MagasinBundle:Default:index.html.twig');
    }
    public function adminAction(){
        $emm= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Mail');
        $msg=$emm->findAll();
        if (!isset($_SESSION))
        {
            session_start();
        }
        session_destroy();
        if($_POST){
            session_start();
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Admin');
            $ems=$this->getDoctrine()->getRepository('MagasinBundle\Entity\User');
            $user=$em->findOneBy(array('login'=>$_POST['Pseudo'],'password'=>$_POST['Password']));
            $logi=$ems->findOneBy(array('login'=>$_POST['Pseudo'],'password'=>$_POST['Password']));
            $emk= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article');
            $arts=$emk->findAll();
            $x=0;
            foreach ($arts as $c){
                $x=$x+1;
            }
            $x=($x*100)/1000;
            if($user){
                $_SESSION['login']=$_POST['Pseudo'];
                $_SESSION['type']="admin";
                return $this->adminhomeAction();
                
            }elseif ($logi){
                $_SESSION['login']=$_POST['Pseudo'];
                $_SESSION['type']="logisticien";
                $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article') ;
                $art=$em->findAll();
                return $this->render('MagasinBundle:Default:articleatt.html.twig',array("nb"=>$x,"art"=>$art,"name"=>$_SESSION['login']));
            }
            else{
                return $this->render('MagasinBundle:Default:admin.html.twig',array("error"=>" login Incorrect !"));
            }
        }else{
            return $this->render('MagasinBundle:Default:admin.html.twig');
        }    }
    public function adminhomeAction()
    {
        $emm= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Mail');
        $msg=$emm->findAll();
        $emk= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article');
        $arts=$emk->findAll();
        $emkk= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Vendeur');
        $user=$emkk->findAll();
        $emkc= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Commande');
        $com=$emkc->findAll();

        $x=0;
        $usr=0;
        $artac=0;
        $rev=0;
        $nc=0;
        foreach ($arts as $c){
            $x=$x+1;
            if($c->getType()=="articleacc"){$artac++;}
        }
        foreach ($user as $m){
            $usr++;
        }
        foreach ($com as $j){
            if($j->getEtat()=="commandeliv"){
            $rev=$rev+$j->getPrix();
            }
            $nc++;

        }


        $x=($x*100)/1000;
        if(isset($_SESSION)){
        if($_SESSION['type']=="admin" ){
        return $this->render('MagasinBundle:Default:adminhome.html.twig',array("nc"=>$nc,"rev"=>$rev,"com"=>$com,"users"=>$user,"arts"=>$arts,"usr"=>$usr,"nb"=>$x,"ac"=>$artac,"msg"=>$msg,"name"=>$_SESSION['login']));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
        }
        else{return $this->render('MagasinBundle:Front:error.html.twig');}
    }
    public function mailAction()
    {
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
        $cat=$em->findAll();
        if($_GET){
            $emm= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Mail');
            $msg=$emm->find($_GET['x']);
            return $this->render('MagasinBundle:Default:mail.html.twig',array('message'=>$msg,'cat'=> $cat,"name"=>$_SESSION['login']));

        }
        return $this->render('MagasinBundle:front:error.html.twig');
    }

    public function commandeaccAction(){
        if($_SESSION['type']=="admin"){
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Commande') ;
            $emp=$em->findAll();
            $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article') ;
            $art=$ems->findAll();
            $emss= $this->getDoctrine()->getRepository('MagasinBundle\Entity\User') ;
            $usr=$emss->findAll();
            $emv= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Vendeur') ;
            $vnd=$emv->findAll();
            if($emp){
                return $this->render('MagasinBundle:Default:commandeacc.html.twig',array('vnd'=>$vnd,'usr'=>$usr,'art'=>$art,'emp'=> $emp,"name"=>$_SESSION['login']));
            }else{
                return $this->render('MagasinBundle:Default:commandeacc.html.twig',array("error"=>"Pas de commande accepté!","name"=>$_SESSION['login']));
            }
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }

    public function livcomAction($id)
    {

        if($_SESSION['type']=="admin"){
            $em = $this->getDoctrine()->getManager();
            $employe = $em->getRepository('MagasinBundle\Entity\Commande')->find($id);
            $employe->setEtat("commandeliv");
            $em->flush();
            return $this->render('MagasinBundle:Default:livcom.html.twig',array("name"=>$_SESSION['login']));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }

    public function commandelivAction(){
        if($_SESSION['type']=="admin"){
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Commande') ;
            $emp=$em->findAll();
            $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article') ;
            $art=$ems->findAll();
            $emss= $this->getDoctrine()->getRepository('MagasinBundle\Entity\User') ;
            $usr=$emss->findAll();
            $emv= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Vendeur') ;
            $vnd=$emv->findAll();
            if($emp){
                return $this->render('MagasinBundle:Default:commandeliv.html.twig',array('vnd'=>$vnd,'usr'=>$usr,'art'=>$art,'emp'=> $emp,"name"=>$_SESSION['login']));
            }else{
                return $this->render('MagasinBundle:Default:commandeliv.html.twig',array("error"=>"Pas de commande accepté!","name"=>$_SESSION['login']));
            }
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }


    public function slideshowAction()
    {

        if($_SESSION['type']=="admin"){
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
                            $fileDestination= $this->getParameter('brochures_directory1').$fileNameNew;
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
                $article= new Slideshow();
                $article->setTitre($_POST['titre']);
                $article->setImage($fileNameNew);
                $em->persist($article);
                $em->flush();
                return $this->render('MagasinBundle:Default:slideshow.html.twig',array("name"=>$_SESSION['login'],'ok'=>"Merci! Votre annonce va être vérifiée par nos modérateurs et sera bientôt en ligne.","name"=>$_SESSION['login'],"type"=>$_SESSION['type']));
            }

            return $this->render('MagasinBundle:Default:slideshow.html.twig',array("name"=>$_SESSION['login']));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }

    public function livreurAction()
    {
        if($_SESSION['type']=="admin"){
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Livreur') ;
            $emp=$em->findAll();

            return $this->render('MagasinBundle:Default:livreur.html.twig',array('liv'=>$emp,"name"=>$_SESSION['login']));

        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }

    public function localiserAction($id)
    {

        if($_SESSION['type']=="admin"){
            $em = $this->getDoctrine()->getManager();
            $liv = $em->getRepository('MagasinBundle\Entity\Livreur')->find($id);
            $lat=$liv->getLat();
            $lon=$liv->getLon();
            $prenom=$liv->getPrenom();
            return $this->render('MagasinBundle:Default:localiser.html.twig',array("name"=>$_SESSION['login'],"prenom"=>$prenom,"lat"=>$lat,"lon"=>$lon));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }



}
