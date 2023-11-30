<?php

namespace MagasinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MagasinBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class EmployeController extends Controller
{



    public function employeAction(){
        if($_SESSION['type']=="admin"){
        if($_POST){
            $em=$this->getDoctrine()->getManager();
            $employe= new User();
            $employe->setNom($_POST['nom']);
            $employe->setPrenom($_POST['prenom']);
            $employe->setAdresse($_POST['adresse']);
            $employe->setTel($_POST['tel']);
            $employe->setEmail($_POST['email']);
            $employe->setLogin($_POST['login']);
            $employe->setPassword($_POST['password']);
            $employe->setType("logisticien");
            $em->persist($employe);
            $em->flush();
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\User') ;
            $emp=$em->findAll();
            if($emp){
                return $this->render('MagasinBundle:Default:employe.html.twig',array('emp'=> $emp,"name"=>$_SESSION['login']));
            }else{
                return $this->render('MagasinBundle:Default:employe.html.twig',array("error"=>"Pas d'employés!","name"=>$_SESSION['login']));
            }
        }else{
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\User') ;
            $emp=$em->findAll();
            if($emp){
                return $this->render('MagasinBundle:Default:employe.html.twig',array('emp'=> $emp,"name"=>$_SESSION['login']));
            }else{
                return $this->render('MagasinBundle:Default:employe.html.twig',array("error"=>"Pas d'employés!","name"=>$_SESSION['login']));
            }
        }
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }



    public function modifierempAction($id)
    {

        if($_SESSION['type']=="admin"){
        if ($_POST) {
            $em = $this->getDoctrine()->getManager();
            $ems= $this->getDoctrine()->getRepository('MagasinBundle\Entity\User') ;
            $emp=$ems->findAll();
            $employe = $em->getRepository('MagasinBundle\Entity\User')->find($id);
            $employe->setNom($_POST['nom']);
            $employe->setPrenom($_POST['prenom']);
            $employe->setAdresse($_POST['adresse']);
            $employe->setTel($_POST['tel']);
            $employe->setEmail($_POST['email']);
            $employe->setLogin($_POST['login']);
            $employe->setPassword($_POST['password']);
            $em->flush();
            return $this->render('MagasinBundle:Default:employe.html.twig',array('emp'=>$emp,"id"=>$id,"name"=>$_SESSION['login']));
        }

        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\User');
        $employe=$ems->findByid($id);
        $nom=$employe[0]->getNom();
        $prenom=$employe[0]->getPrenom();
        $adresse=$employe[0]->getAdresse();
        $tel=$employe[0]->getTel();
        $email=$employe[0]->getEmail();
        $login=$employe[0]->getLogin();
        $password=$employe[0]->getPassword();
        $type=$employe[0]->getType();
        return $this->render('MagasinBundle:Default:modifieremp.html.twig',array("id"=>$id,"name"=>$_SESSION['login'],"nom"=>$nom,"prenom"=>$prenom,"adresse"=>$adresse,"tel"=>$tel,"email"=>$email,"login"=>$login,"password"=>$password,"type"=>$type));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }


    public function supprimerempAction(User $employe)
    {

        if($_SESSION['type']=="admin"){
            $em= $this->getDoctrine()->getManager();
            $em->remove($employe);
            $em->flush();
        $nom=$employe->getNom();
        return $this->render('MagasinBundle:Default:supprimeremp.html.twig',array("name"=>$_SESSION['login'],"nom"=>$nom));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
        }









}