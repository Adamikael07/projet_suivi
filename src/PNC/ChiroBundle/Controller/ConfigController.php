<?php

namespace PNC\ChiroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class ConfigController extends Controller{
    //path : chiro/breadcrumb
    public function breadcrumbAction(Request $req){
        $view = $req->get('view');
        $id = $req->get('id');

        $manager = $this->getDoctrine()->getConnection();

        $out = array();
        switch($view){
            case 'biometrie':
                $req = $manager->prepare('SELECT id, obs_tx_id FROM chiro.chiro_biometrie WHERE id=:id');
                $req->bindValue('id', $id);
                $req->execute();
                $res = $req->fetchAll();
                if(!isset($res[0])){
                    return new JsonResponse(array('id'=>null), 404);
                }
                $res = $res[0];
                $out[] = array('id'=>$res['id'], 'label'=>'Biometrie n°'.$id, 'link'=>'#/chiro/biometrie/'.$id);
                $id = $res['obs_tx_id'];
            case 'taxons': 
            case 'taxon':
                $req = $manager->prepare('SELECT id, nom_complet as label, obs_id FROM chiro.chiro_observation_taxon WHERE id=:id');
                $req->bindValue('id', $id);
                $req->execute();
                $res = $req->fetchAll();
                if(!isset($res[0])){
                    return new JsonResponse(array('id'=>null), 404);
                }
                $res = $res[0];
                $out[] = array('id'=>$res['id'], 'label'=>$res['label'], 'link'=>'#/chiro/taxons/'.$id);
                $id = $res['obs_id'];
            case 'validation':
                if($view == 'validation'){
                    return new JsonResponse(array(array('id'=>null, 'label'=>'Validation', 'link'=>'#/chiro/validation')));
                }
            case 'inventaire':
            case 'observation':
                $req = $manager->prepare('SELECT id, obs_date as label, site_id FROM pnc.base_observation WHERE id=:id');
                $req->bindValue('id', $id);
                $req->execute();
                $res = $req->fetchAll();
                if(!isset($res[0])){
                    return new JsonResponse(array(array('id'=>null, 'label'=>'Inventaire', 'link'=>'#/chiro/inventaire')));
                }
                $res = $res[0];
                if($res['site_id']==null){
                    $out[] = array('id'=>$res['id'], 'label'=>implode('/', array_reverse(explode('-', $res['label']))), 'link'=>'#/chiro/inventaire/'.$id);
                    $out[] = array('id'=>null, 'label'=>'Inventaire', 'link'=>'#/chiro/inventaire');
                    return new JsonResponse(array_reverse($out));
                }
                $out[] = array('id'=>$res['id'], 'label'=>implode('/', array_reverse(explode('-', $res['label']))), 'link'=>'#/chiro/observation/'.$id);
                $id = $res['site_id'];
            case 'site':
                $req = $manager->prepare('SELECT id, site_nom as label FROM pnc.base_site WHERE id=:id');
                $req->bindValue('id', $id);
                $req->execute();
                $res = $req->fetchAll();
                if(!isset($res[0])){
                    return new JsonResponse(array(array('id'=>null, 'label'=>'Sites', 'link'=>'#/chiro/site')));
                }
                $res = $res[0];
                $out[] = array('id'=>$res['id'], 'label'=>$res['label'], 'link'=>'#/chiro/site/'.$id);
                $out[] = array('id'=>null, 'label'=>'Sites', 'link'=>'#/chiro/site');
        }
        return new JsonResponse(array_reverse($out));
    }
}
