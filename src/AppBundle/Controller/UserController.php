<?php
/**
 * Created by PhpStorm.
 * User: mostafa
 * Date: 5/20/18
 * Time: 2:04 PM
 */

namespace AppBundle\Controller;


use AppBundle\Form\UserRegistrationForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function registerAction(Request $request){

        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);

        if($form->isValid()){
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'welcome '.$user->getEmail());

//            return $this->redirectToRoute('homepage');

            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                  $user,
                  $request,
                  $this->get('app.security.login_form_authenticator'),
                  'main'
                );

        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}