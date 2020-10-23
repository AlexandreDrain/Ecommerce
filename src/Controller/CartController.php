<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    /**
     * @Route("/mon_panier", name="cart")
     */
    public function index()
    {
        return $this->render("cart/index.html.twig");
    }

    /**
     * @Route("/ajouter_au_panier/{id}", name="cart_add")
     */
    public function addItem(Product $product, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        $id = $product->getId();
        if(empty($cart[$id])) {
            $cart[$id] = [
                'quantity'  => 0,
                'title'      => $product->getTitle(),
                'picture'   => $product->getPicture(),
                'price'     => $product->getPrice(),
            ];
        }

        $cart[$id]['quantity']++;
        $session->set('cart', $cart);

        $this->addFlash("success", "produit bien ajouté du panier");
        return $this->redirectToRoute("products");

        // Part with Ajax
        /*if($request->isXmlHttpRequest() != true) {
            return new JsonResponse(['statut' => 'error', 'error' => 'Accès non autorisé.']);
        }
        $cart = $session->get('cart', []);
        
        $id = $product->getId();
        if(empty($cart[$id])) {
            $cart[$id] = [
                'quantity'  => 0,
                'name'      => $product->getName(),
                'picture'   => $product->getPicture(),
                'price'     => $product->getPrice(),
            ];
        }
        $cart[$id]['quantity'] += abs(ceil($request->query->get('quantity', 1)));
        $session->set('cart', $cart);
        return new JsonResponse(['statut' => 'ok', 'nb_product' => array_sum(array_column($cart,'quantity'))]);*/

    }

    /**
     * @Route("/supprimer_du_panier/{id}", name="cart_remove")
     */
    public function removeItem(Product $product, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        $id = $product->getId();
        if (!empty($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity'] = $cart[$id]['quantity'] - 1;
            } else {
                unset($cart[$id]);
            }

        }
        $session->set('cart', $cart);
        $this->addFlash("success", "produit bien supprimé du panier");
        return $this->redirect($_SERVER['HTTP_REFERER']);

    }

    /**
     * @Route("/proceder_achat", name="proceder_achat")
     */
    public function buyCart(SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        // Je fais mes calculs et je procéde a la méthode de payement etc je sais pas comment ça marche

        // Puis je vide le panier
        if (!empty($cart)) {
            $session->set('cart', []);

            $this->addFlash("success", "Achat effectué");
            return $this->redirect($_SERVER['HTTP_REFERER']);
        } else {

            $this->addFlash("warning", "Error");
            return $this->redirect($_SERVER['HTTP_REFERER']);
            
        }

    }

}