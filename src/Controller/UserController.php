<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

#[Route('/api', name: 'api') ]
class UserController extends AbstractController
{
    #[Route('/users', name: 'user_create' , methods:[ 'post' ]) ]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setRoles($request->request->get('roles'));        
        $user->setPassword($request->request->get('password'));
        $user->setNom($request->request->get('nom'));
        $user->setPrenom($request->request->get('prenom'));
        $user->setFonction($request->request->get('fonction'));
        $user->setTelephone($request->request->get('telephone'));
        

        $entityManager->persist($user);
        $entityManager->flush();

        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'fonction' => $user->getFonction(),
            'telephone' => $user->getTelephone()
        ];
        return $this->json($data);
    }

    #[Route('/users', name: 'user_index' , methods:[ 'get' ]) ]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $users = $doctrine
        ->getRepository(User::class)
        ->findAll();

        $data= [];

        foreach ($users as $user) {
           $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'fonction' => $user->getFonction(),
            'telephone' => $user->getTelephone()
        ];
        }
        
        return $this->json($data);
    }
    #[Route('/users/{id}', name: 'user_show' , methods:[ 'get' ]) ]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $user = $doctrine
        ->getRepository(User::class)
        ->find($id);

    

        if (!$user) {

           return $this->json('pas utilisateur trouver sous cet id' . $id, 404);
        }
        
        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'fonction' => $user->getFonction(),
            'telephone' => $user->getTelephone()
        ];
        
        return $this->json($data);
    }


}
