<?php

namespace PNC\PatrimoineBatiBundle\Services;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Commons\Exceptions\DataObjectException;
use Commons\Exceptions\CascadeException;

use PNC\PatrimoineBatiBundle\Entity\InfoSite;
use PNC\PatrimoineBatiBundle\Entity\SiteFichiers;
use PNC\PatrimoineBatiBundle\Entity\SiteMurGrosOeuvre;


class PbSitesService{

    // doctrine
    private $db;

    public function __construct($db, $parentServ, $es, $pg, $fs){
        $this->db = $db;
        $this->parentService = $parentServ;
        $this->entityService = $es;
        $this->pagination = $pg;
        $this->fileService = $fs;
        $this->schema = '../src/PNC/PatrimoineBatiBundle/Resources/config/doctrine/InfoSite.orm.yml';
    }

    public function getFilteredList($request){
        $schema = array(
            'id'=>null,
            'bsNom'=>null,
            'bsDate'=>'date',
            'typeLieu'=>null,
            'nomObservateur'=>null,
            'bsCode'=>null,
            'bsTypeId'=>null,
            'pbDesTypeAttributNational'=>null,
            'typeDenominationLocal'=>null,
            'geom'=>null,
            'commune'=>null
        );

        $entity = 'PNCPatrimoineBatiBundle:SiteView';

        $res = $this->pagination->filter_request($entity, $request);

        $infos = $res['filtered'];

        $out = array();

        //definition de la structure de données sous form GeoJson
        $geoLabelConf = array(
            'label'=>'<h4><a href="#/patrimoinebati/site/%s">%s<a></h4>',
            'refs'=>array('id', 'bsNom')
        );

        foreach($infos as $info){
            $out[] = $this->entityService->getGeoJsonFeature(
                $this->entityService->normalize($info, $schema),
                $geoLabelConf,
                'geom');
        }

        return array('total'=>$res['total'], 'filteredCount'=>$res['filteredCount'], 'filtered'=>$out);
    }

    public function getOne($id){
        $info = $this->entityService->getOne('PNCPatrimoineBatiBundle:InfoSite', array('fk_bs_id'=>$id));

        if(!$info){
            throw new NotFoundHttpException();
        }
        $schema = '../src/PNC/PatrimoineBatiBundle/Resources/config/doctrine/InfoSite.orm.yml';

        $data = $this->entityService->normalize($info, $schema);

        //Récupération des informations de baseSite
        $baseSite = $this->entityService->getOne('PNCBaseAppBundle:Site', array('id'=>$id));

        if(!$info){
            throw new NotFoundHttpException();
        }
        $schema = '../src/PNC/BaseAppBundle/Resources/config/doctrine/Site.orm.yml';

        $dataBaseSite = $this->entityService->normalize($baseSite, $schema);

        //Fusion du détail du site avec Base Site
        unset($dataBaseSite['id']);
        $data = array_merge($data, $dataBaseSite);


        $data['siteFichiers'] = $this->fileService->getFichiers('patrimoinebati/site', $id);

        $murGrosOeuvre = $this->entityService->getAll('PNCPatrimoineBatiBundle:SiteMurGrosOeuvre', array('site_id'=>$id));
        $data['pbDesMurGrosoeuvre'] = array();
        foreach($murGrosOeuvre as $murGrosOeuvre){
            $data['pbDesMurGrosoeuvre'][] = $murGrosOeuvre->getThesaurusId();
        }

        return $data;
    }

    public function create($data){
        $manager = $this->db->getManager();
        $manager->getConnection()->beginTransaction();
        $errors = array();
        $site = null;

        $infoSite = new InfoSite();
        try{
            $site = $this->parentService->create($this->db, $data);
        }
        catch(DataObjectException $e){
            $errors = $e->getErrors();
        }
        try{
            $this->entityService->hydrate($infoSite, $this->schema, $data);
        }
        catch(DataObjectException $e){
            $errors = array_merge($errors, $e->getErrors());
            $manager->getConnection()->rollback();
        }
        if($errors){
            throw new DataObjectException($errors);
        }
        $infoSite->setParentSite($site);
        $manager->persist($infoSite);
        $manager->flush();

        $this->fileService->record_files($site->getId(), $data['siteFichiers'], $manager);

        $manager->getConnection()->commit();


        $this->_record_murGrosOeuvre($site->getId(), $data);

        return array('id'=>$site->getId());
    }

    public function update($id, $data){
        $repo = $this->db->getRepository('PNCPatrimoineBatiBundle:InfoSite');
        $infoSite = $repo->findOneBy(array('fk_bs_id'=>$id));
        if(!$infoSite){
            return null;
        }
        $manager = $this->db->getManager();
        $manager->getConnection()->beginTransaction();
        $site = $infoSite->getParentSite();
        $errors = array();
        try{
            $site = $this->parentService->update($this->db, $site, $data);
        }
        catch(DataObjectException $e){
            $errors = $e->getErrors();
        }

        $this->fileService->record_files($site->getId(), $data['siteFichiers'], $manager);
        try{
            $this->entityService->hydrate($infoSite, $this->schema, $data);
            $manager->flush();
            $manager->getConnection()->commit();
        }
        catch(DataObjectException $e){
            $errors = array_merge($errors, $e->getErrors());
            $manager->getConnection()->rollback();
            throw new DataObjectException($errors);
        }

        $this->_record_murGrosOeuvre($site->getId(), $data);

        return array('id'=>$site->getId());

    }


    public function remove($id, $cascade=false){
        $repo = $this->db->getRepository('PNCPatrimoineBatiBundle:InfoSite');
        $infoSite = $repo->findOneBy(array('fk_bs_id'=>$id));
        if(!$infoSite){
            return false;
        }
        $site = $infoSite->getParentSite();

        $manager = $this->db->getManager();
        $manager->remove($infoSite);
        $manager->flush();

        $this->fileService->delete_all('patrimoinebati/site', $id);

        $this->parentService->remove($this->db, $site);
        return true;
    }

    private function _record_murGrosOeuvre($site_id, $data){
        $this->_delete_murGrosOeuvre($site_id);

        $manager = $this->db->getManager();

        foreach($data['pbDesMurGrosoeuvre'] as $grosoeuvre_id){
          if ($grosoeuvre_id) {
            $grosoeuvre = new SiteMurGrosOeuvre();
            $grosoeuvre->setSiteId($site_id);
            $grosoeuvre->setThesaurusId($grosoeuvre_id);
            $manager->persist($grosoeuvre);
            $manager->flush();
          }
        }
    }

    private function _delete_murGrosOeuvre($site_id){
        $manager = $this->db->getManager();

        // suppression des liens existants
        $delete = $manager->getConnection()->prepare('DELETE FROM patrimoinebati.rel_pbsite_thesaurus_murgrosoeuvre WHERE site_id=:siteid');
        $delete->bindValue('siteid', $site_id);
        $delete->execute();
    }
}
