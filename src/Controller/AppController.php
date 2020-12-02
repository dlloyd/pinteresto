<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Images;
use App\Entity\ImageCategories;
use Knp\Component\Pager\PaginatorInterface;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $imgs = $this->getDoctrine()->getRepository(Images::class)->findAll();
        $images = $paginator->paginate($imgs,$request->query->getInt('page',1),10);
        return $this->render('app/index.html.twig',['images'=>$images]);
    }



    /**
     * @Route("/category/{code}", name="category")
     */
    public function categoryResult($code): Response
    {
        $categ = $this->getDoctrine()->getRepository(ImageCategories::class)->findOneBy(['code'=>$code]);

        return $this->render('images/category.html.twig', [
            'categ' => $categ,
        ]);
    }


    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository(Images::class)->createQueryBuilder('i');

        $filter = $request->query->get('filter');
        if ($filter) {
            $queryBuilder->where('MATCH_AGAINST(i.name) AGAINST(:searchterm boolean)>0')
                         ->setParameter('searchterm', $filter);
        }

        $res= $queryBuilder->getQuery()->getResult();


        return $this->render(
            'images/search.html.twig',
            [
                'res' => $res,
            ]
        );
    }
}
