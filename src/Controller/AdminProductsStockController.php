<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/admin/productsStock")
 * 
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MARCHAND')")
 * 
 */
class AdminProductsStockController extends AbstractController
{
    /**
     * @Route("/liste", name="products_stock")
     */
    public function index(ProductRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        // KNP paginator
        $limit = 5;
        $page = $request->query->getInt('page', 1);
        $offset = ($page*$limit)-$limit;
        // on limite la requête aux données nécessaires
        $products = $paginator->paginate($repo->findBy([],[], $limit, $offset), 1, $limit);
        // On donne les vraies infos à paginator
        $products->setTotalItemCount(
            $repo->createQueryBuilder('p')->select('count(p.id)')
                ->getQuery()->getSingleScalarResult()
        );
        $products->setCurrentPageNumber($page);
        
        // si pas de données sur la page courante on retourne en page 1
        if(sizeof($products) === 0 && $page > 1) {
            // redirection vers la page 1
            return $this->redirectToRoute('product_list');
        }
        return $this->render('admin/productsStock/index.html.twig', [
            'products' => $products,
        ]);
    }
    
    /**
     * @Route("/add/{slug}/{stock}", name="admin_stock_add", methods={"POST"})
     * @ParamConverter("stock", options={"mapping": {"stock": "id"}})
     */
    public function changeStockOfProduct(Request $request, EntityManagerInterface $entityManager, Product $product, Stock $stock)
    {
        if (array_key_exists("stock", $_POST)) {
            if (isset($_POST["stock"]) && !empty($_POST["stock"])) {

                $stock->setQuantity($_POST["stock"]);
                $entityManager->persist($stock);
                $entityManager->flush();
                
                $this->addFlash("success", "Stock ajouté");
                return $this->redirectToRoute("products_stock");
            } else {
                $this->addFlash("danger", "Erreur interne");
                return $this->redirectToRoute("products_stock");

            }
        } else {
            $this->addFlash("danger", "Erreur interne");
            return $this->redirectToRoute("products_stock");

        }
    }
}