<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]

    public function index( SessionInterface $session, MovieRepository $movierepository ): Response
    {
        $panier = $session->get('panier', []);

        $panierWithData = [];
     
        foreach($panier as $id => $quantity) {
            $panierWithData[] = [
                'movie' => $movierepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach($panierWithData as $item) {
            $totalItem = $item['movie']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' =>$total
        ]);
    }

    //pour ajouter un film au panier 
    #[Route('/cart/add/{id}' , name:'cart_add')]

    public function add($id, SessionInterface $session) {
       
        //avec session interface je n'ai plus besoin de la request 
       // $session = $request->getSession();

        $panier = $session->get('panier', []);

        if(!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        
        $session->set('panier', $panier);
       

        
        return $this->redirectToRoute("cart_index");

        //dd($session->get('panier'));

    }


    // pour supprimer la totalité du film ajouté  
    #[Route('/cart/remove/{id}' , name:'cart_remove')]

    public function remove($id, SessionInterface $session){

        $panier = $session->get('panier' , []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier' , $panier);

        return $this->redirectToRoute("cart_index");

    }

    // pour supprimer 1 par 1 
    #[Route('/cart/delete/{id}' , name:'cart_delete')]

    public function delete($id, SessionInterface $session) {
       
        //avec session interface je n'ai plus besoin de la request 
       // $session = $request->getSession();

        $panier = $session->get('panier', []);

        if(!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        } 
        
        $session->set('panier', $panier);
       

        
        return $this->redirectToRoute("cart_index");

        //dd($session->get('panier'));

    }
}
