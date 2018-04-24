<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use AppBundle\Entity\GenusNote;
use AppBundle\Service\MarkdownTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{

    public function newAction(){

        $genus = new Genus();
        $genus->setName('Octopus'.rand(1, 100));
        $genus->setSubFamily('Octodrine');
        $genus->setSpeciesCount(rand(100,99999));

        $em = $this->getDoctrine()->getManager();
        $em->persist($genus);
        $em->flush();

        return new Response('<html><body>Data Saved</body></html>');
    }

    public function listAction(){
//        return new Response('<html><body>List action</body></html>');
        $em = $this->getDoctrine()->getManager();

        $genuses = $em->getRepository('AppBundle:Genus')
            ->findAllPublishedOrderedByRecentlyActive();

        return $this->render('/genus/list.html.twig',[
            'genuses' => $genuses
        ]);
    }

    public function showAction($genusName)
    {
        $em = $this->getDoctrine()->getManager();
        $genus = $em->getRepository('AppBundle:Genus')
            ->findOneBy([
                'name' => $genusName
            ]);
        if(!$genus){
//            throw $this->createNotFoundException('No Genus Found');
            $this->get('session')->getFlashBag()->add('error','No Genus Found');
            return $this->render('default/404.html.twig');
        }
//        $funfact = "Octopuses can change the color of their body in just *three-tenths* of a second!";

        /*$cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        $key = md5($funfact);

        if($cache->contains($key)){
            $funfact = $cache->fetch($key);
        }else{
            sleep(1);
            $funfact = $this->get('markdown.parser')
                ->transform($funfact);
            $cache->save($key, $funfact);
        }
        */


        /*$recentNotes = $genus->getNotes()
            ->filter(function(GenusNote $note){
            return $note->getCreatedAt() > new \DateTime('-3 months');
        });*/

        $transformer = new MarkdownTransformer($this->get('markdown.parser'));
        $funfact = $transformer->parse($genus->getFunFact());


        $recentNotes = $em->getRepository('AppBundle:GenusNote')
            ->findAllRecentNotesForGenus($genus);

        return $this->render('genus/show.html.twig', array(
            'genus' => $genus,
            'funfact' => $funfact,
            'recentNoteCount' => count($recentNotes)
        ));
    }


    public function getNotesAction(Genus $genus)
    {

        $notes = [];
        foreach($genus->getNotes() as $note){
            $notes[] = [
                'id' => $note->getId(),
                'username' => $note->getUsername(),
                'avatarUri' => '/images/'.$note->getUserAvatarFileName(),
                'note' => $note->getNote(),
                'date' => $note->getCreatedAt()->format('M d, Y')
            ];
        }
        /*$notes = [
            ['id' => 1, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Octopus asked me a riddle, outsmarted me', 'date' => 'Dec. 10, 2015'],
            ['id' => 2, 'username' => 'AquaWeaver', 'avatarUri' => '/images/ryan.jpeg', 'note' => 'I counted 8 legs... as they wrapped around me', 'date' => 'Dec. 1, 2015'],
            ['id' => 3, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Inked!', 'date' => 'Aug. 20, 2015'],
        ];*/

        $data = [
            'notes' => $notes
        ];

        return new JsonResponse($data);
    }
}
