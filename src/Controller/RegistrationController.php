<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/api", name="api_")
     */
    
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        /**
         * @Route("/register", name="register", methods={"POST"})
         */
          
        try {
            $em = $doctrine->getManager();
            $decoded = json_decode($request->getContent());
            $email = $decoded->email;
            $name = $decoded->name;
    
            $plaintextPassword = $decoded->password;
      
            $user = new User();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setEmail($email);
            $user->setName($name);
            $user->setUsername($email);
            $em->persist($user);
            $em->flush();
        } catch (Exception $e) {
            return $this->json(['message' => $e -> getMessage()], 400);
        }
  
        return $this->json(['message' => 'Registered Successfully']);
    }
}
