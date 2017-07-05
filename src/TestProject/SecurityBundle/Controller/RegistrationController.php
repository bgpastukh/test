<?php

namespace TestProject\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use TestProject\SecurityBundle\Entity\User;
use TestProject\SecurityBundle\Form\RegistrationType;

class RegistrationController extends Controller
{
    /**
     * @Route("/registration", name="security_bundle.registration.registration")
     */
    public function registrationAction(Request $request): Response
    {
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = $form->getData();
            $this->get('security.registration.service')->registerUser($newUser);
        }

        return $this->render('@TestProjectSecurity/Registration/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/confirm/{confirmationToken}", name="security_bundle.registration.confirm")
     */
    public function confirmRegistrationAction(string $confirmationToken): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findOneBy(['confirmationToken' => $confirmationToken]);

        if (!$user) {
            throw new BadRequestHttpException('Bad token');
        }

        $this->get('security.registration.service')->activateUser($user);

        return $this->redirectToRoute('security_bundle.registration.registration');
    }
}
