<?php

namespace App\Controller;

use App\Entity\Beer;
use App\Entity\Pub;
use App\Entity\Wine;
use App\Event\IrmatEvent;
use App\Form\BeerType;
use App\Form\WineType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BrewerController
 *
 * @package App\Controller
 *
 * @Route("/brewer")
 */
class BrewerController extends Controller
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     *
     * @Route("/", name="brewer")
     * @Template()
     */
    public function index(Request $request)
    {
        return [
            'controller_name' => 'BrewerController',
        ];
    }

    /**
     * @param \App\Entity\Beer $beer
     *
     * @Route("/minimalist/{name}")
     * @Method({"GET", "POST"})
     * @Cache(expires="+1 day", maxage=30, )
     * @Template("brewer/detail.html.twig")
     */
    public function minimalist(Beer $beer)
    {
    }

    /**
     * @Route("/pub")
     * @Security("has_role('ROLE_USER') && is_granted('SELL', '$beer')")
     */
    public function showPub(EntityManagerInterface $em, EventDispatcherInterface $dispatcher, Request $request)
    {
        $response = new Response();
        $response->setLastModified(new \DateTime('2018-02-25'));
        $response->setEtag(md5('ma bière'));

        if ($response->isNotModified($request)) {
            return $response;
        }


        $beer = new Beer();
        $beer->setName('Blanche Ermine');
        $dispatcher->dispatch(IrmatEvent::NAME, new IrmatEvent($beer));

        //throw new AccessDeniedHttpException();

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $pub = $em->getRepository(Pub::class)->findByNameCoucou('Coin Mousse');
        dump($pub->getBeer()->getName());

        $wines = $pub->getWines()->slice(0, 1);

        dump($wines);

        $response = $this->render('brewer/show_pub.html.twig', ['pub' => $pub]);

        $response->setPublic();
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    /**
     * @param \App\Entity\Beer $beer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/detail-pc/{name}")
     * @Method({"GET", "POST"})
     */
    public function detail2(Beer $beer = null)
    {
        return $this->render('brewer/detail.html.twig', [
            'beer_name' => $beer->getName(),
        ]);
    }


    /**
     * @param \App\Entity\Beer $beer
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Symfony\Component\HttpKernel\Profiler\Profiler $profiler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/detail-pc-json/{name}", defaults={"_format"="json"})
     * @Method({"GET", "POST"})
     */
    public function detailJson(Beer $beer, SerializerInterface $serializer, Profiler $profiler)
    {

        $response = [
            'name' => $beer->getName(),
            'ABV' => $beer->getAlcoholContent(),
        ];

        return new Response($serializer->serialize($beer, 'json'));
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Beer $beer
     * @param \Doctrine\ORM\EntityManagerInterface $em
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/edit/{name}")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function edit(Request $request, Beer $beer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $validator->validate($beer);
        $beerForm = $this->createForm(BeerType::class, $beer);

        $beerForm->add('submit', SubmitType::class, ['label' => 'app.beer.form.editButton']);

        $beerForm->handleRequest($request);

        if ($beerForm->isSubmitted() && $beerForm->isValid()) {

            $em->persist($beer);
            $em->flush();

            $this->addFlash('success', 'Your beer recipe is changed');
            return $this->redirectToRoute('app_brewer_minimalist', ['name' => $beer->getName()]);
        }

        dump($beerForm);
        return ['beerForm' => $beerForm->createView()];
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface $em
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/new-pub")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function createMultiple(Request $request, EntityManagerInterface $em)
    {
        $pub = new Pub();
        $pub->addBeer((new Beer())->setName('Chouffe'));
        $pub->addBeer((new Beer())->setName('cuvée des Trolls'));
        $pub->addWine((new Wine())->setName('Muscadet'));
        $pub->addWine((new Wine())->setName('Beaujolais'));

        $pubForm = $this->createFormBuilder($pub, [
   //         'validation_groups' => ['subscription']
        ])
            ->add('name')
            ->add('beers', CollectionType::class, [
                'entry_type' => BeerType::class,

            ])
            ->add('wines', CollectionType::class, [
                'entry_type' => WineType::class,
                'error_bubbling' => false,

            ])
            ->getForm();

        $pubForm->add('submit', SubmitType::class, ['label' => 'app.beer.form.editButton']);

        $pubForm->handleRequest($request);

        if ($pubForm->isSubmitted() && $pubForm->isValid()) {

            $em->beginTransaction();
            try {
                $em->persist($pub);
                $em->flush();
                $em->commit();
            }
            catch(\Exception $e) {
                $em->rollback();
            }

            $this->addFlash('success', 'Your beer recipe is changed');
            return $this->redirectToRoute('app_brewer_createmultiple');
        }

        return ['pubForm' => $pubForm->createView()];
    }
}
