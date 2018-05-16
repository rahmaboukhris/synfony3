<?php
/**
 * Created by PhpStorm.
 * User: mostafa
 * Date: 5/15/18
 * Time: 12:35 PM
 */

namespace AppBundle\Controller;


use AppBundle\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SecurityController extends Controller
{
    public function loginAction(){

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username'     => $lastUsername
        ]);

        return $this->render('security/login.html.twig', array(
            'form'          => $form->createView(),
            'error'         => $error,
        ));
    }

    public function logoutAction(){

        throw new \Exception('Just logout');
    }
}