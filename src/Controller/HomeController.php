<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(TranslatorInterface $translator, Request $request): Response
    {
        dump(
            // retrieve the locale from TranslatorInterface
            $translator->getLocale(),

            // locale can also be retrieve from the request
            $request->getLocale(),

            // Locale can also be set (with business logic then override the request value)
            [
                $request->setLocale('fr'),                          // set to fr
                $request->getLocale(),                              // observe that our set have properly work
                $request->setLocale($request->getDefaultLocale()),  // Set again to default locale (defined in config/package/translation.yaml
                $request->getLocale(),                              // observe that our set (with default) have properly work
            ],

            // Try to translate that ID from the default file (translate/messages.{locale}.yaml) with the current locale.
            $translator->trans('This is an english sentence.'),

            // translate from a different file than the default one
            $translator->trans('page.title', [], 'admin'),

            // Specify which locale should be used for translation
            $translator->trans('page.title', [], 'admin', 'en_GB'),
            $translator->trans('page.title', [], 'admin', 'en_US'),
        );

        // Do the translation in twig
        $pageTitleId = 'page.title';
        $pageContentId = 'page.content';

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'page_title_id' => $pageTitleId,
            'page_content_id' => $pageContentId,
        ]);
    }

    // Let's create a route with the locale define in the URL _locale slug is automatically bound to locale
    #[
        Route(
            // create a dynamic route
            '/{_locale}/about',
            // add some requirement because our app doesn't have translation for every language.
            requirements: [
                '_locale' => 'en|fr'
            ]
        )
    ]
    public function routeWithLocale(Request $request): Response
    {
        dump($request->getLocale());

        return $this->render('home/about.html.twig', [
                'controller_name' => 'HomeController'
        ]);
    }
}
