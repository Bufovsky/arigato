<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class UserController
 * @package App\Controller
 * @route(path="/user")
 */
 class UserController extends AbstractController
 {
    /**
     * @route(path="/show")
     */
    public function show(int $id):Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No users found for id '.$id
            );
        } else {
            return new Response(
                sprintf("%s | %s | %s </br>", $user->__get('login'), $user->__get('password'), $user->__get('email'))
            );
        }
    }

    /**
     * @route(path="/delete")
     */
    public function delete(int $id, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($id);
        $entityManager->flush();

        return new Response("Usunięto prawidłowo użytkownika.");
    }

    /**
     * @route(path="/edit")
     */
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager):mixed
    {
        if ('POST' === $request->getMethod()) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $user->setLogin($request->get('login'));
            $user->setPassword($request->get('password'));
            $user->setEmail($request->get('email'));
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response("Zedytowano prawidłowo użytkownika.");
        }
    }

    /**
     * @route(path="/create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager):mixed
    {
        if ('POST' === $request->getMethod()) {
            $user = new User();
            $user->setLogin($request->get('login'));
            $user->setPassword($request->get('password'));
            $user->setEmail($request->get('email'));
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response("Utworzono prawidłowo użytkownika.");
        }
    }
 }