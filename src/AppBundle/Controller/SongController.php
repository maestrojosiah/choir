<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Song controller.
 *
 * @Route("song")
 */
class SongController extends Controller
{
    /**
     * Lists all song entities.
     *
     * @Route("/", name="song_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        $songs = $em->getRepository('AppBundle:Song')
            ->findBy(
                array('choir' => $choir),
                array('id' => 'ASC')
            );

        return $this->render('song/index.html.twig', array(
            'choir' => $choir,
            'songs' => $songs,
        ));
    }

    /**
     * new song page.
     *
     * @Route("/new", name="song_new")
     * @Method("GET")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('song/new.html.twig', ['choir' => $choir]);
    }

    /**
     * edit song page.
     *
     * @Route("/edit/{id}", name="song_edit")
     * @Method("GET")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $song = $em->getRepository('AppBundle:Song')->find($id);
        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('song/edit.html.twig', ['choir' => $choir, 'song' => $song]);
    }

    /**
     * Finds and displays a song entity.
     *
     * @Route("/{id}", name="song_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Song $song)
    {
        $em = $this->getDoctrine()->getManager();

        $choir_id = $request->query->get('c');
        $choir = $em->getRepository('AppBundle:Choir')->find($choir_id);
        return $this->render('song/show.html.twig', array(
            'song' => $song,
            'choir' => $choir,
        ));
    }

    /**
     * show page for deleting a song.
     *
     * @Route("/delete/{id}", name="song_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $song = $em->getRepository('AppBundle:Song')->find($id);

        return $this->render('song/delete.html.twig', array(
            'song' => $song,
        ));
    }

    private function user(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

}
