<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Choir;
use AppBundle\Entity\Chorister;
use AppBundle\Entity\Song;
use AppBundle\Entity\Plan;
use AppBundle\Entity\Rehearsal;
use AppBundle\Entity\Mark;

class EntityController extends Controller
{

    /**
     * @Route("/entity/save", name="save_entity")
     */
    public function saveAction(Request $request)
    {
      $user = $this->user();
      $entity = $request->request->get('entity');
      $return = $request->request->get('return');
      $fields = $request->request->get('fields');
      $values = $request->request->get('values');
      $choir_id = $request->request->get('choir');

      if($entity != 'Choir' && $entity != 'Mark'){
        $choir = $this->em()->getRepository('AppBundle:Choir')->find($choir_id);
      }
      

      $fields_with_values = array_combine($fields, $values);

      switch ($entity) {
        case 'Choir':
          $this_entity = new Choir();
          break;
        case 'Chorister':
          $this_entity = new Chorister();
          break;
        case 'Song':
          $this_entity = new Song();
          break;
        case 'Rehearsal':
          $this_entity = new Rehearsal();
          $rehearsal_date = $fields_with_values['day'];

          $choir = $this->em()->getRepository('AppBundle:Choir')->find($choir);

          $starttime = substr($fields_with_values['starttime'], 0, -3);
          $endtime = substr($fields_with_values['endtime'], 0, -3);

          $prepared_starttime = \DateTime::createFromFormat('Y-m-d H:i:s', $rehearsal_date." $starttime:00");
          $prepared_endtime = \DateTime::createFromFormat('Y-m-d H:i:s',  $rehearsal_date." $endtime:00");
          $prepared_date = \DateTime::createFromFormat('Y-m-d',  $rehearsal_date);

          $this_entity->setStarttime($prepared_starttime);
          $this_entity->setEndtime($prepared_endtime);
          $this_entity->setDay($prepared_date);
          $this_entity->setChoir($choir);
          $this->save($this_entity);
          break;
          goto end;
        case 'Plan':
          $this_entity = new Plan();

          $choir = $this->em()->getRepository('AppBundle:Choir')->find($choir);
          $song = $this->em()->getRepository('AppBundle:Song')->find($fields_with_values['song']);
          $rehearsal = $this->em()->getRepository('AppBundle:Rehearsal')->find($fields_with_values['rehearsal']);

          $starttime = substr($fields_with_values['commence'], 0, -3);
          $endtime = substr($fields_with_values['conclude'], 0, -3);

          $prepared_starttime = \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d")." $starttime:00");
          $prepared_endtime = \DateTime::createFromFormat('Y-m-d H:i:s',  date("Y-m-d")." $endtime:00");

          $this_entity->setCommence($prepared_starttime);
          $this_entity->setConclude($prepared_endtime);
          $this_entity->setRehearsal($rehearsal);
          $this_entity->setSong($song);
          $this_entity->setTodo($fields_with_values['todo']);
          $this_entity->setChoir($choir);
          $this->save($this_entity);
          goto end;
          break;
        case 'Mark':
          $chorister = $this->em()->getRepository('AppBundle:Chorister')->find($fields_with_values['chorister']);
          $rehearsal = $this->em()->getRepository('AppBundle:Rehearsal')->find($fields_with_values['rehearsal']);

          $this_marking_exists = $this->em()->getRepository('AppBundle:Mark')
            ->findOneBy(
               array('rehearsal' => $rehearsal, 'chorister' => $chorister),
               array('id' => 'DESC')
            );
          if($this_marking_exists){
            $this_entity = $this_marking_exists;
            $out_time = \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"));
          } else {
            $this_entity = new Mark();
            $in_time = \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"));
            $out_time = $rehearsal->getEndtime();
            $this_entity->setTimein($in_time);
          }

          
          $this_entity->setChorister($chorister);
          $this_entity->setRehearsal($rehearsal);
          $this_entity->setTimeout($out_time);
          $this->save($this_entity);
          goto end;

          break;
      }

      if($entity == "Choir"){
        $this_entity->setUser($this->user());
      } elseif ($entity == "Mark"){
        // do nothing
      } else {
        $this_entity->setChoir($choir);
      }
      
      foreach ($fields_with_values as $key => $value) {
        if (strpos($key, '_id') == false) {
          $str = "set$key";
          $this_entity->$str($value);
        }
      }
      if($entity != 'Rehearsal'){
        $this->save($this_entity);
      }

      end:

      $to_return = $this_entity->$return();

      return new JsonResponse($to_return);

    }

    /**
     * @Route("/edit", name="edit_entity")
     */
    public function editAction(Request $request)
    {
      $user = $this->user();
      $entity = $request->request->get('entity');
      $id = $request->request->get('id');
      $fields = $request->request->get('fields');
      $values = $request->request->get('values');

      $fields_with_values = array_combine($fields, $values);
      $this_entity = $this->find($entity, $id);

      if($entity == 'Rehearsal'){
          $starttime = substr($fields_with_values['starttime'], 0, -3);
          $endtime = substr($fields_with_values['endtime'], 0, -3);
          $rehearsal_date = $fields_with_values['day'];

          $prepared_starttime = \DateTime::createFromFormat('Y-m-d H:i:s', $rehearsal_date." $starttime:00");
          $prepared_endtime = \DateTime::createFromFormat('Y-m-d H:i:s',  $rehearsal_date." $endtime:00");
          $prepared_date = \DateTime::createFromFormat('Y-m-d',  $rehearsal_date);

          $fields_with_values['starttime'] = $prepared_starttime;
          $fields_with_values['endtime'] = $prepared_endtime;
          $fields_with_values['day'] = $prepared_date;

      } else if ($entity == 'Plan'){
          $starttime = substr($fields_with_values['commence'], 0, -3);
          $endtime = substr($fields_with_values['conclude'], 0, -3);
          $song = $this->em()->getRepository('AppBundle:Song')->find($fields_with_values['song']);
          $rehearsal = $this->em()->getRepository('AppBundle:Rehearsal')->find($fields_with_values['rehearsal']);

          $prepared_starttime = \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d")." $starttime:00");
          $prepared_endtime = \DateTime::createFromFormat('Y-m-d H:i:s',  date("Y-m-d")." $endtime:00");
          
          $fields_with_values['commence'] = $prepared_starttime;
          $fields_with_values['conclude'] = $prepared_endtime;
          $fields_with_values['song'] = $song;
          $fields_with_values['rehearsal'] = $rehearsal;

      } else if ($entity == 'Mark'){
          $starttime = substr($fields_with_values['timein'], 0, -3);
          $endtime = substr($fields_with_values['timeout'], 0, -3);
          $rehearsal = $this_entity->getRehearsal();
          $date = $rehearsal->getDay()->format('Y-m-d');

          $prepared_starttime = \DateTime::createFromFormat('Y-m-d H:i:s', $date." $starttime:00");
          $prepared_endtime = \DateTime::createFromFormat('Y-m-d H:i:s',  $date." $endtime:00");
          
          $fields_with_values['timein'] = $prepared_starttime;
          $fields_with_values['timeout'] = $prepared_endtime;

      }

      // $this_entity->setUser($this->user());
      foreach ($fields_with_values as $key => $value) {
        if (strpos($key, '_id') == false) {
          $str = "set$key";
          $this_entity->$str($value);
        }
      }
      $this->save($this_entity);
      return new JsonResponse("true");

    }

    /**
     * @Route("/delete", name="delete_entity")
     */
    public function deleteAction(Request $request)
    {
      $choir_id = $request->request->get('choir');
      $choir = $this->em()->getRepository('AppBundle:Choir')->find($choir_id);
      $user = $this->user();
      $entity = $request->request->get('entity');
      $id = $request->request->get('id');

      $this_entity = $this->find($entity, $id);
      if($entity == "Choir"){
        $url = $this->generateUrl('choir_index');
      } else {
        $url = $this->generateUrl('choir_show', ['id' => $choir_id]);
      }

      $this->delete($this_entity);

      return new JsonResponse($url);

    }

    /**
     * @Route("/menu", name="load_first_menu")
     */
    public function menuAction(Request $request)
    {
      $user = $this->user();

      $choirs = $this->em()->getRepository('AppBundle:Choir')
        ->findBy(
            array('user' => $user),
            array('id' => 'ASC')
        );

      $choirs_array = [];
      foreach ($choirs as $key => $choir) {
        $choirs_array[] = [$choir->getTitle(), $choir->getId()];
      }

      return new JsonResponse($choirs_array);

    }

    /**
     * @Route("/entity/verses", name="get_verses")
     */
    public function versesAction(Request $request)
    {
      $user = $this->user();
      $song_id = $request->request->get('song_id');
      $song = $this->em()->getRepository('AppBundle:Song')->find($song_id);

      $verses = $song->getVerses();
      $verses_array = [];
      for ($v=1; $v <= $verses; $v++) { 
        $verses_array[] = $v;
      }
      if($song->getChorus() == true ){
        $verses_array[] = "Chorus";
      }

      return new JsonResponse($verses_array);

    }

      private function em(){
          $em = $this->getDoctrine()->getManager();
          return $em;
      }

      private function find($entity, $id){
          $entity = $this->em()->getRepository("AppBundle:$entity")->find($id);
          return $entity;
      }

      private function save($entity){
          $this->em()->persist($entity);
          $this->em()->flush();
          return true;
      }

      private function delete($entity){
          $this->em()->remove($entity);
          $this->em()->flush();
          return true;
      }

      private function user(){
          $user = $this->container->get('security.token_storage')->getToken()->getUser();
          return $user;
      }
}
