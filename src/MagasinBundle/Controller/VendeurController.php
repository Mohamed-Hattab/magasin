<?php

namespace MagasinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MagasinBundle\Entity\Vendeur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class VendeurController extends Controller
{



    public function vendeuraccAction(){
        if($_SESSION['type']=="admin"){
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Vendeur') ;
        $emp=$em->findAll();
        if($emp){
            return $this->render('MagasinBundle:Default:vendeuracc.html.twig',array('emp'=> $emp,"name"=>$_SESSION['login']));
        }else{
            return $this->render('MagasinBundle:Default:vendeuracc.html.twig',array("error"=>"Pas de vendeur accepté!","name"=>$_SESSION['login']));
        }
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }

    public function vendeurattAction(){
        if($_SESSION['type']=="admin"){
        $em= $this->getDoctrine()->getRepository('MagasinBundle\Entity\Vendeur') ;
        $emp=$em->findAll();
        if($emp){
            return $this->render('MagasinBundle:Default:vendeuratt.html.twig',array('emp'=> $emp,"name"=>$_SESSION['login']));
        }else{
            return $this->render('MagasinBundle:Default:vendeuratt.html.twig',array("error"=>"Pas de vendeur en attente!","name"=>$_SESSION['login']));
        }
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }




    public function supprimervacAction(Vendeur $employe)
    {
        if($_SESSION['type']=="admin"){

            $em= $this->getDoctrine()->getManager();
            $em->remove($employe);
            $em->flush();

        $nom=$employe->getNom();
        return $this->render('MagasinBundle:Default:supprimervac.html.twig',array("name"=>$_SESSION['login'],"nom"=>$nom));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
        }


    public function supprimervatAction(Vendeur $employe)
    {

        if($_SESSION['type']=="admin"){
        $em= $this->getDoctrine()->getManager();
        $em->remove($employe);
        $em->flush();
        $nom=$employe->getNom();
        return $this->render('MagasinBundle:Default:supprimervat.html.twig',array("name"=>$_SESSION['login'],"nom"=>$nom));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
        }

    public function acceptervatAction($id)
    {

        if($_SESSION['type']=="admin"){
        $em = $this->getDoctrine()->getManager();
        $employe = $em->getRepository('MagasinBundle\Entity\Vendeur')->find($id);
        $employe->setType("vendeuracc");
        $em->flush();
        $nom=$employe->getNom();
        return $this->render('MagasinBundle:Default:vendeuracc.html.twig',array("name"=>$_SESSION['login'],"nom"=>$nom));
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }

    public function venteAction()
    {

        if($_SESSION['type']=="admin"){
            if($_GET){
                $id=$_GET['id'];
                $emss = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Vendeur');
                $nomv=$emss->find($id)->getMagasin();
                $ems = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Categorie');
                $cat = $ems->findAll();
                $em = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Commande');
                $com=$em->findAll();
                $emk = $this->getDoctrine()->getRepository('MagasinBundle\Entity\Article');
                $arts=$emk->findAll();
                $ema = $this->getDoctrine()->getRepository('MagasinBundle\Entity\User');
                $x=0;
                foreach ($com as $array){
                    $idp=$array->getIdproduit();
                    foreach ($arts as $item){
                    if($item->getIdvendeur()==$id and $idp==$item->getId()){
                        $client=$ema->find($array->getIdclient());
                        $art=$emk->find($array->getIdproduit());
                        $t[$x]=array('ids'=>$array->getId(),'type'=>$array->getEtat(),'client'=>$client,'titre'=>$art->getTitre(),'prix'=>$art->getPrix(),'qte'=>$array->getQuantite(),'date'=>$array->getDate());
                        $x=$x+1;
                    }
                    }



                }


            }
            if($x>0){
            return $this->render('MagasinBundle:Default:vente.html.twig',array("nomv"=>$nomv,"x"=>$t,"name"=>$_SESSION['login']));}
            else{
                return $this->render('MagasinBundle:Default:vente.html.twig',array("nomv"=>$nomv,"name"=>$_SESSION['login']));
            }
        }
        else
        {return $this->render('MagasinBundle:Front:error.html.twig');}
    }








}