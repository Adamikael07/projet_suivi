<?php

namespace PNC\ChiroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ConfigController extends Controller{
    public function getSiteFormAction(){

        /*
         * récupération du vocabulaire type lieu
         */
        $norm = $this->get('normalizer');
        $repo = $this->getDoctrine()->getRepository('PNCBaseAppBundle:Thesaurus');
        $types = $repo->findBy(array('id_type'=>7));
        $typesLieu = array();
        foreach($types as $tl){
            if($tl->getFkParent() != 0){
                $typesLieu[] = $norm->normalize($tl, array());
            }
        }


        /*
         * description du formulaire
         */
        $out = array(
            'formSite'=>array(
                array(
                    'name'=>'id',
                    'label'=>'id',
                    'type'=>'num',
                    'help'=>'',
                    'options'=>array('hidden'=>true)
                ),
                array(
                    'name'=>'siteNom',
                    'label'=>'Nom',
                    'type'=>'string',
                    'help'=>'Nom du site',
                    'options'=>array('minLength'=>5, 'maxLength'=>250)
                ),
                array(
                    'name'=>'siteCode',
                    'label'=>'Code site',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array('maxLength'=>25, 'minLength'=>0)
                ),
                array(
                    'name'=>'observateurId',
                    'label'=>'Observateur',
                    'type'=>'xhr',
                    'help'=>'',
                    'options'=>array('url'=>'/observateurs'),
                ),
                array(
                    'name'=>'siteDate',
                    'label'=>'Date créa.',
                    'type'=>'date',
                    'help'=>"Date d'ajout du site à la base de données",
                    'options'=>array(),
                ),
                array(
                    'name'=>'siteDescription',
                    'label'=>'Description',
                    'type'=>'text',
                    'help'=>'',
                    'options'=>array('maxLength'=>1000, 'minLength'=>0)
                ),
                array(
                    'name'=>'siteAmenagement',
                    'label'=>'Amenagement',
                    'type'=>'text',
                    'help'=>'Amenagement du site',
                    'options'=>array('maxLength'=>1000, 'minLength'=>0)
                ),
                array(
                    'name'=>'siteFrequentation',
                    'label'=>'Fréquentation',
                    'type'=>'text',
                    'help'=>'Fréquentation du site',
                    'options'=>array('maxLength'=>1000, 'minLength'=>0)
                ),
                array(
                    'name'=>'siteMenace',
                    'label'=>'Menaces',
                    'type'=>'text',
                    'help'=>'Menaces pesant sur le site',
                    'options'=>array('maxLength'=>1000, 'minLength'=>0)
                ),
                array(
                    'name'=>'typeId',
                    'label'=>'Type',
                    'type'=>'select',
                    'help'=>'Type de lieu',
                    'options'=>array('choices'=>$typesLieu)
                ),
                array(
                    'name'=>'contactNom',
                    'label'=>'Nom du contact',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array('maxLength'=>25, 'minLength'=>0)
                ),
                array(
                    'name'=>'contactPrenom',
                    'label'=>'Prénom du contact',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array('maxLength'=>25, 'minLength'=>0)
                ),
                array(
                    'name'=>'contactAdresse',
                    'label'=>'Adresse du contact',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array('maxLength'=>150, 'minLength'=>0)
                ),
                array(
                    'name'=>'contactCodePostal',
                    'label'=>'Code postal',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array('maxLength'=>5, 'minLength'=>0)
                ),
                array(
                    'name'=>'contactVille',
                    'label'=>'Ville',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array('maxLength'=>100, 'minLength'=>0)
                ),
                array(
                    'name'=>'contactTelephone',
                    'label'=>'Telephone',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array('maxLength'=>15, 'minLength'=>0)
                ),
                array(
                    'name'=>'contactPortable',
                    'label'=>'Portable',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array('maxLength'=>15, 'minLength'=>0)
                ),
                array(
                    'name'=>'contactCommentaire',
                    'label'=>'Commentaires contact',
                    'type'=>'text',
                    'help'=>"Insultes & commentaires désobligeants",
                    'options'=>array('maxLength'=>1000, 'minLength'=>0)
                ),
            ),
            'listSite'=>array(
                array(
                    'name'=>'id',
                    'label'=>'ID',
                    'filter'=>array('id'=>'text'),
                    'options'=>array('visible'=>true)
                ),
                array(
                    'name'=>'siteNom',
                    'label'=>'Nom',
                    'filter'=>array('siteNom'=>'text'),
                    'options'=>array('visible'=>true)
                ),
                array(
                    'name'=>'siteCode',
                    'label'=>'Code site',
                    'filter'=>array('siteCode'=>'text'),
                    'options'=>array()
                ),
                array(
                    'name'=>'nomObservateur',
                    'label'=>'Observateur',
                    'filter'=>array('nomObservateur'=>'text'),
                    'options'=>array(),
                ),
                array(
                    'name'=>'siteDate',
                    'label'=>'Date créa.',
                    'filter'=>array('siteDate'=>'text'),
                    'options'=>array()
                ),
                array(
                    'name'=>'dernObs',
                    'label'=>'Dernière observation',
                    'filter'=>array('dernObs'=>'text'),
                    'options'=>array()
                ),
                array(
                    'name'=>'typeLieu',
                    'label'=>'Type',
                    'filter'=>array('typeLieu'=>'text'),
                    'options'=>array('visible'=>true)
                ),
            ),
            'detailSite'=>array(
                array(
                    'name'=>'id',
                    'label'=>'id',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array('hidden'=>true)
                ),
                array(
                    'name'=>'siteNom',
                    'label'=>'Nom',
                    'type'=>'string',
                    'help'=>'Nom du site',
                    'options'=>array()
                ),
                array(
                    'name'=>'siteCode',
                    'label'=>'Code site',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array()
                ),
                array(
                    'name'=>'nomObservateur',
                    'label'=>'Observateur',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array(),
                ),
                array(
                    'name'=>'siteDate',
                    'label'=>'Date créa.',
                    'type'=>'date',
                    'help'=>"Date d'ajout du site à la base de données",
                    'options'=>array(),
                ),
                array(
                    'name'=>'dernObs',
                    'label'=>'Dernière observation',
                    'help'=>"Date de la dernière observation effectuée sur ce site",
                    'type'=>'date',
                    'options'=>array()
                ),
                array(
                    'name'=>'siteDescription',
                    'label'=>'Description',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array()
                ),
                array(
                    'name'=>'siteAmenagement',
                    'label'=>'Amenagement',
                    'type'=>'string',
                    'help'=>'Amenagement du site',
                    'options'=>array()
                ),
                array(
                    'name'=>'siteFrequentation',
                    'label'=>'Fréquentation',
                    'type'=>'string',
                    'help'=>'Fréquentation du site',
                    'options'=>array()
                ),
                array(
                    'name'=>'siteMenace',
                    'label'=>'Menaces',
                    'type'=>'string',
                    'help'=>'Menaces pesant sur le site',
                    'options'=>array()
                ),
                array(
                    'name'=>'typeId',
                    'label'=>'Type',
                    'type'=>'string',
                    'help'=>'Type de lieu',
                    'options'=>array()
                ),
                array(
                    'name'=>'contactNom',
                    'label'=>'Nom du contact',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array()
                ),
                array(
                    'name'=>'contactPrenom',
                    'label'=>'Prénom du contact',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array()
                ),
                array(
                    'name'=>'contactAdresse',
                    'label'=>'Adresse du contact',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array()
                ),
                array(
                    'name'=>'contactCodePostal',
                    'label'=>'Code postal',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array()
                ),
                array(
                    'name'=>'contactVille',
                    'label'=>'Ville',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array()
                ),
                array(
                    'name'=>'contactTelephone',
                    'label'=>'Telephone',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array()
                ),
                array(
                    'name'=>'contactPortable',
                    'label'=>'Portable',
                    'type'=>'string',
                    'help'=>'',
                    'options'=>array()
                ),
                array(
                    'name'=>'contactCommentaire',
                    'label'=>'Commentaires contact',
                    'type'=>'string',
                    'help'=>"Insultes & commentaires désobligeants",
                    'options'=>array()
                ),
            ),
        );
        return new JsonResponse($out);
    }
}