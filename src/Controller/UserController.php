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
            $response = new Response(json_encode(array('error' => 'Account not found.')));
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $items = ['login', 'password', 'email'];

            foreach ($items as $item)
                $items[$item] = $user->__get($item);

            $response = new Response(json_encode($items));
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @route(path="/delete")
     */
    public function delete(int $id, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($id);
        $entityManager->flush();

        //chceck if not exist
        $response = new Response(json_encode(array('error' => 'Account removed correctly.')));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
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

            //chceck if edited
            $response = new Response(json_encode(array('error' => 'Account actualization complete correctly.')));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
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

            //chceck if edited
            $response = new Response(json_encode(array('error' => 'Account created correctly.')));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }
 }