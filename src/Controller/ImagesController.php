<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Events;
use App\Entity\Images;
use App\Entity\User;
use App\Form\ImageType;
use Knp\Component\Pager\PaginatorInterface;

class ImagesController extends AbstractController
{


    /**
     * @Route("/user/my_photos", name="my_photos" ,methods={"GET"})
     */
    public function index(): Response
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());

        return $this->render('images/index.html.twig', [
            'images'=>$user->getImages()
        ]);
    }


    /**
     * @Route("/single_image/{id}", name="single_image" ,methods={"GET"})
     */
    public function single(int $id): Response
    {

        $image = $this->getDoctrine()->getRepository(Images::class)->find($id);

        if(!$image){
          throw $this->createNotFoundException('Image inexistante!!');
        }

        return $this->render('images/single.html.twig', [
            'image'=>$image,
        ]);
    }



    /**
     * @Route("/user/my_single_image/{id}", name="my_single_image" ,methods={"GET"})
     */
    public function userImage(int $id): Response
    {

        $image = $this->getDoctrine()->getRepository(Images::class)->find($id);

        if(!$image || $image->getAuthor()->getId()!= $this->getUser()->getId()){
          throw $this->createNotFoundException('Image inexistante ou ne vous appartient pas!!');
        }

        return $this->render('images/my_image.html.twig', [
            'image'=>$image,
        ]);
    }


    /**
     * @Route("/user/delete_image/{id}", name="delete_image",methods={"DELETE"}))
     */
    public function delete(Request $request,int $id): Response
    {

        $submittedToken = $request->request->get('token');
        $image = $this->getDoctrine()->getRepository(Images::class)->find($id);

        if(!$image || $image->getAuthor()->getId()!= $this->getUser()->getId()){
          throw $this->createNotFoundException('Image inexistante ou ne vous appartient pas!!');
        }


        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
          $em = $this->getDoctrine()->getManager();
          $filepath = $this->getParameter('kernel.project_dir').'/public/uploads/'.$image->getFilename();
          $em->remove($image);
          $em->flush();
          //Delete file on server
          $filesystem = new Filesystem();
          $filesystem->remove($filepath);
          $this->addFlash('success', 'Image supprimée!');
        }


        return $this->redirectToRoute('my_photos');
    }


    /**
     * @Route("/user/create_image", name="create_image")
     */
    public function create(Request $request,EventDispatcherInterface $eventDispatcher)
    {
        $image = new Images();

        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $image->setFilename($newFilename);

            }
            $image->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();
            $this->addFlash('success', 'Image sauvegardée!');

            $event = new GenericEvent($image);
            $eventDispatcher->dispatch($event,Events::IMAGE_REGISTERED);


            return $this->redirectToRoute('my_photos');
        }

        return $this->render('images/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
