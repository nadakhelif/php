<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PersonneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class PersonneController extends AbstractController
{
    
  
    #[Route('personne/add', name: 'personne.add')]

    public function addPersonne (ManagerRegistry $doctrine, Request $request,SluggerInterface $slugger){
        $personne =new Personne();
        // $personne->setFirstName($firstName);
        // $personne->setLastName($lastName);
        // $personne->setAge($age);
        $manager=$doctrine->getManager();
        // $manager->persist($personne);
        // $manager->flush();
        $form=$this->createForm(PersonneType::class,$personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('personne_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $personne->setImage($newFilename);
            }
            $manager->persist($personne);
            $manager->flush();
            $this->addFlash('success', "la personne  a ete ajoute avec success ");
            return $this->redirectToRoute('personne.all');
        }
        else{
            $this->addFlash('erreur', "nothing was submitted ");
            return $this->render('personne/add-personne.html.twig', [
                'form' =>$form->createView()
            ]);
        }
        

    
    }
    #[Route('personne/{page?1}', name:'personne.all')]
    public function all(ManagerRegistry $doctrine,$page): Response
    {
        $repository=$doctrine->getRepository(Personne::class);
        
        $personne = $repository->findBy([],['age'=>'asc'],10,($page-1)*10);
        return $this->render('personne/select.html.twig',['personnes'=>$personne] );
    }
   
    #[Route('personne/update/{personne}', name: 'personne.update')]

    public function updatepersonne (Personne $personne=null,EntityManagerInterface $manager,Request $request){
       if($personne){
        $form=$this->createForm(PersonneType::class,$personne);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);
        
        
        if($form->isSubmitted()){
            $manager->persist($personne);
            $manager->flush();
            $this->addFlash('success', "la personne  a ete updated avec success ");
            return $this->redirectToRoute('personne.all');
        }

        return $this->render('personne/add-personne.html.twig', [
            'form' =>$form->createView()
        ]);
        
       }
    }
    #[Route('personne/delete/{personne}', name: 'delete.personne')]

    public function deletepersonne (Personne $personne=null, EntityManagerInterface $manager){
        
       if($personne){

        $productName=$personne->getFirstName();
        $manager->remove($personne);
        $manager->flush();
       
        $this->addFlash('success', "le personne $productName a ete detruit ");}
        else{
            $this->addFlash('error', "le personne nexiste pas   ");
        }
        
        return $this->redirectToRoute('personne.all');

       
       
       
    }
    #[Route('personne/detail/{personne}', name: 'detail_personne')]

    public function detailProduct (Personne $personne=null ){
        return $this->render('personne/index.html.twig', ['personne'=>$personne,]);
    }
}
