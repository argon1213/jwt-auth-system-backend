<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserController extends AbstractController
{
    /**
     * @Route("/api", name="api_")
    */

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUserInfo(Request $request):Response
    {
        /**
         * @Route("/register", name="get_user_info", methods={"GET"})
        */

        try {
            $userId = $this->getUser()->getId();
    
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->find($userId);
    
            $name = $user->getName();
            $email = $user->getEmail();
            $address = $user->getAddress();
            $houseNumber = $user->getHouseNumber();
            $city = $user->getCity();
            $postCode = $user->getPostCode();

            $result = [
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'houseNumber' => $houseNumber,
                'city' => $city,
                'postCode' => $postCode,
            ];
        } catch (Exception $e) {
            return $this->json(['message' => $e -> getMessage()], 400);
        }

        return $this->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function editUserInfo(ManagerRegistry $doctrine, Request $request):Response
    {
        /**
         * @Route("/register", name="edit_user_info", methods={"PUT"})
        */

        try {
            $em = $doctrine->getManager();
            $decoded = json_decode($request->getContent());

            $name = $decoded->name;
            $address = $decoded->address;
            $houseNumber = $decoded->houseNumber;
            $city = $decoded->city;
            $postCode = $decoded->postCode;

            $userId = $this->getUser()->getId();
    
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->find($userId);

            $user->setName($name);
            $user->setAddress($address);
            $user->setHouseNumber($houseNumber);
            $user->setCity($city);
            $user->setPostCode($postCode);

            $em->persist($user);
            $em->flush();

            $message = "successfully updated";
            $result = [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'address' => $user->getAddress(),
                'houseNumber' => $user->getHouseNumber(),
                'city' => $user->getCity(),
                'postCode' => $user->getPostCode(),
            ];
    
        } catch (Exception $e) {
            return $this->json(['message' => $e -> getMessage()], 400);
        }

        return $this->json([
            'success' => true,
            'message' => $message,
            'data' => $result,
        ]);
    }
}
