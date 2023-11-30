<?php

namespace MagasinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MagasinBundle\Entity\Categorie;


class CategorieController extends Controller
{

    /*  ----------Categorie--------------------------  */



    public function ajoutcatAction(){
        if($_SESSION['type']=="admin"){
        if($_POST){
            $em=$this->getDoctrine()->getManager();
            $Categorie= new Categorie();
            $Categorie->setNom($_POST['nom']);
            if($_POST['parent']==0){
                $Categorie->setIdparent(0);
            }else{
                $Categorie->setIdparent($_POST['parent']);
            }
            $em->persist($Categorie);
            $em->flush();
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
            $cat=$em->findAll();
            if($cat){
                return $this->render('MagasinBundle:Default:ajoutcat.html.twig',array('cat'=> $cat,"name"=>$_SESSION['login']));
            }else{
                return $this->render('MagasinBundle:Default:ajoutcat.html.twig',array("error"=>"Pas de categories!","name"=>$_SESSION['login']));
            }
        }else{
            $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie') ;
            $cat=$em->findAll();
            if($cat){
                return $this->render('MagasinBundle:Default:ajoutcat.html.twig',array('cat'=> $cat,"name"=>$_SESSION['login']));
            }else{
                return $this->render('MagasinBundle:Default:ajoutcat.html.twig',array("error"=>"Pas de categories!","name"=>$_SESSION['login']));
            }
        }
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}


    }


    public function modifiercatAction($id)
    {
        if($_SESSION['type']=="admin"){
        $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
        $cat = $ems->findAll();
        $categorie=$ems->findByid($id);
        if ($_POST) {
            $em = $this->getDoctrine()->getManager();
            $Categorie = $em->getRepository('MagasinBundle\Entity\Categorie')->find($id);
            $Categorie->setNom($_POST['nom']);
            $Categorie->setIdparent($_POST['parent']);
            $em->flush();
            return $this->render('MagasinBundle:Default:ajoutcat.html.twig',array("id"=>$id,"name"=>$_SESSION['login'],"cat"=>$cat,"ok"=>"Votre catégorie a été modifié !"));
        }
        return $this->render('MagasinBundle:Default:modifiercat.html.twig',array("id"=>$id,"name"=>$_SESSION['login'],"cat"=>$cat,"categorie"=>$categorie));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }


    public function supprimercatAction(Categorie $categorie)
    {

        if($_SESSION['type']=="admin"){
        $nom=$categorie->getNom();
        $id=$categorie->getId();
        $em= $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return $this->render('MagasinBundle:Default:supprimercat.html.twig',array("id"=>$id,"name"=>$_SESSION['login'],"nom"=>$nom));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
        }

}
