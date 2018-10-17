<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 08/08/2018
 * Time: 05:48 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Form\ContactType;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     */
    public function newAction(Request $request)
    {
        // creates a task and gives it some dummy data for this example
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $contact = $form->getData();
            $contact->setUser($this->getUser());

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute("dashboard");
        }

        return $this->render('default/contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/contact_update", name="contact_update")
     */
    public function updateAction(Request $request)
    {
        $contactid = $request->query->get('contactid');
        print_r($contactid);
        // creates a task and gives it some dummy data for this example
        // $contact = new Contact();
        $entityManager = $this->getDoctrine()->getManager();
        $contact = $entityManager->getRepository(Contact::class)->find($contactid);
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            //$contact->setUser($this->getUser());
            //$contact->setId($contactid);
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $contact = $entityManager->getRepository(Contact::class)->find($contactid);
            $contact->setName($form->getData()->getName());
            $contact->setSurname($form->getData()->getSurname());
            //$entityManager->persist($contact);
            $entityManager->flush();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute("dashboard");
        }

        return $this->render('default/contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/contact_delete", name="contact_delete")
     */
    public function deleteAction(Request $request)
    {
        $contactid = $request->query->get('contactid');
        print_r($contactid);
        // creates a task and gives it some dummy data for this example
        $entityManager = $this->getDoctrine()->getManager();
        $contact = $entityManager->getRepository(Contact::class)->find($contactid);
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            //$contact->setUser($this->getUser());
            //$contact->setId($contactid);
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $contact = $entityManager->getRepository(Contact::class)->find($contactid);
            $entityManager->remove($contact);
            //$entityManager->persist($contact);
            $entityManager->flush();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute("dashboard");
        }

        return $this->render('default/contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}