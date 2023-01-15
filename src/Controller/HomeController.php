<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(TranslatorInterface $translator): Response
    {
        dump(
            // retrieve the locale
            $translator->getLocale(),

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
}
