<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;


class UserController extends AbstractController
{   //afficher tout les utilisateur
    #[Route('api/users', name: 'users', methods: ['GET'])]
    public function getAllUsers(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $userList = $userRepository->findAll();
    
        $jsonUserList = $serializer->serialize($userList, 'json', ['groups' => 'getUsers']);
        return new JsonResponse($jsonUserList, Response::HTTP_OK, [], true);
    }
    
    //cree un utilisateur
    #[Route('api/users/create', name: 'createUser', methods: ['POST'])]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $em->persist($user);
        $em->flush();

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);
        
        $location = $urlGenerator->generate('detailUser', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);

    }
    //fonction touver un utilisateur
    #[Route('api/user/{id}', name: 'userShow', methods: ['GET'])]
    public function userShow(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    //fonction modifier
    #[Route('api/users/{id}', name:'updateUser', methods:['PUT'])]

    public function updateUser(Request $request, SerializerInterface $serializer, User $currentUser, EntityManagerInterface $em): JsonResponse 
    {
        $updatedUser= $serializer->deserialize($request->getContent(), 
                User::class, 
                'json', );     

        
        $em->persist($updatedUser);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
    //fonction supprimer un utilisateur
    #[Route('api/users/{id}', name: 'deleteUser', methods: ['DELETE'])]
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
