<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\CartService;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', $cartService->getCart());
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id, SessionInterface $session)
    {
        //avec session interface je n'ai plus besoin de la request 
        // $session = $request->getSession();

        $cart = $session->get('cart');

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("cart_index");
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove($id, SessionInterface $session)
    {
        $cart = $session->get('cart');

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("cart_index");
    }

    #[Route('/cart/delete/{id}', name: 'cart_delete')]
    public function delete($id, SessionInterface $session)
    {

        //avec session interface je n'ai plus besoin de la request 
        // $session = $request->getSession();

        $cart = $session->get('cart');

        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute("cart_index");
    }
}
