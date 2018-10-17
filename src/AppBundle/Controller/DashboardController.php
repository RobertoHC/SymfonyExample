<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 08/08/2018
 * Time: 08:52 PM
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;

# imports the Google Cloud client library
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {

        //echo $_GET['subject'];

        //echo __DIR__.'\..\..\..\Images\notas.png';
        //$file = $form['attachment']->getData();
        //$file->move($directory, $someNewFilename);

        # instantiates a client
        $imageAnnotator = new ImageAnnotatorClient();
        # the name of the image file to annotate
        $fileName = __DIR__.'\..\..\..\app\Resources\views\default\notas3.png';

        # prepare the image to be annotated
        $image = file_get_contents($fileName);

        $response = $imageAnnotator->textDetection($image);
        $texts = $response->getTextAnnotations();

        //printf('%d texts found:' . PHP_EOL, count($texts));
        $i = 0;
        $arrayi = array();
        $array = array();
        $count = 0;
        $fullword = "";
        foreach ($texts as $text) {
            $count = $count + 1;
            if($count <= 4){
                continue;
            }
            //print($text->getDescription() . PHP_EOL);
            //print(" HOLAAAAAAAA ");
            //foreach ($text as $word){
            //    print($word . PHP_EOL);
            //}
            $word = $text->getDescription();

            if (preg_match("/^[a-zA-Z]$/", $word[0])){
                $fullword = $fullword.$word." ";

            }
            else{
                if($fullword != ""){
                    $arrayi[$i] = $fullword;
                    $i = $i + 1;
                }

                $arrayi[$i] = $word;
                $fullword = "";

                if($i == 2){
                    $array[] = $arrayi;
                    $arrayi = array();
                }

                $i = ($i + 1) % 3;
            }

            # get bounds
            //$vertices = $text->getBoundingPoly()->getVertices();
            //$bounds = [];
            //foreach ($vertices as $vertex) {
            //    $bounds[] = sprintf('(%d,%d)', $vertex->getX(), $vertex->getY());
            //}
            //print('Bounds: ' . join(', ',$bounds) . PHP_EOL);
        }

        //var_dump($array);

        $imageAnnotator->close();

        # performs label detection on the image file
        //$response = $imageAnnotator->labelDetection($image);
        //$text = $imageAnnotator-
        //$text = $imageAnnotator->documentTextDetection($image)->getTextAnnotations();
        //var_dump($text);
        //$labels = $response->getFullTextAnnotation();
        //echo($labels);
        //var_dump($response->getTextAnnotations());
        //$labels = $response->getLabelAnnotations();

        /*$repository = $this->getDoctrine()->getRepository(Contact::class);
        $contacts = $repository->findBy(
            array('user' => $this->getUser())
        );*/
        //$array = array( array("3.0", "GESTION DE CONOCIMIENTO", "14"), array("1.0", "DEPORTE Y RECREACIÓN", "16"), array("3.0", "DESARROLLO DE TESIS", "12"), array("3.0", "SISTEMAS DISTRIBUIDOS", "15"), array("2.0", "DERECHO INFORMATICO", "17"), array("3.0", "AUDITORIA Y CONTROL DE TECNOLOGÍA INFORMÁTICA", "13"), array("3.0", "CALIDAD Y PRUEBA DE SOFTWARE", "14"), array("3.0", "ARQUITECTURA EMPRESARIAL", "14"));

        for($i = 0; $i < 8; $i++){
            $contact = new Contact();
            $contact->setName($array[$i][0]);
            $contact->setSurname($array[$i][1]);
            $contact->setNumber($array[$i][2]);
            $contacts[] = $contact;
        }

        // replace this example code with whatever you need
        return $this->render('default/dashboard.html.twig',
         array("contacts" => $contacts, "username" => $this->getUser()->getUsername()));
    }
}