<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chorister;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Chorister controller.
 *
 * @Route("chorister")
 */
class ChoristerController extends Controller
{
    /**
     * Lists all chorister entities.
     *
     * @Route("/", name="chorister_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $voice = $request->query->get('v');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        $choristers = $em->getRepository('AppBundle:Chorister')
            ->findBy(
                array('choir' => $choir, 'voice' => $voice),
                array('id' => 'ASC')
            );

        return $this->render('chorister/index.html.twig', array(
            'choir' => $choir,
            'choristers' => $choristers,
            'voice' => $voice,
        ));
    }

    /**
     * Lists all voices entities.
     *
     * @Route("/voices", name="chorister_voices")
     * @Method("GET")
     */
    public function voicesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);

        $choristers = $em->getRepository('AppBundle:Chorister')
            ->findBy(
                array('choir' => $choir),
                array('id' => 'ASC')
            );


        $voices = [];
        foreach ($choristers as $key => $chorister) {
            $voices[$chorister->getVoice()] = $chorister->getVoice();
        }
        return $this->render('chorister/voices.html.twig', array(
            'choir' => $choir,
            'voices' => $voices,
        ));
    }

    /**
     * new chorister page.
     *
     * @Route("/new", name="chorister_new")
     * @Method("GET")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $voice = null !== $request->query->get('v') ? $request->query->get('v') : '' ;
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('chorister/new.html.twig', ['choir' => $choir, 'voice' => $voice]);
    }

    /**
     * edit chorister page.
     *
     * @Route("/edit/{id}", name="chorister_edit")
     * @Method("GET")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $chorister = $em->getRepository('AppBundle:Chorister')->find($id);
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('chorister/edit.html.twig', ['choir' => $choir, 'chorister' => $chorister]);
    }

    /**
     * Finds and displays a chorister entity.
     *
     * @Route("/{id}", name="chorister_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Chorister $chorister)
    {
        $em = $this->getDoctrine()->getManager();

        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('chorister/show.html.twig', array(
            'chorister' => $chorister,
            'choir' => $choir,
        ));
    }

    /**
     * show page for deleting a chorister.
     *
     * @Route("/delete/{id}", name="chorister_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $chorister = $em->getRepository('AppBundle:Chorister')->find($id);

        return $this->render('chorister/delete.html.twig', array(
            'chorister' => $chorister,
        ));
    }

    private function user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

}
