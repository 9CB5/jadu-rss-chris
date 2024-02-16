<?php

namespace App\Controller;

// Symfony
// ================================================================================================
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Bundle\SecurityBundle\Security;

// Doctine
// ================================================================================================
use Doctrine\ORM\EntityManagerInterface;

// Entities
// ================================================================================================
use App\Entity\Link;
use App\Entity\User;

// Forms
// ================================================================================================
use App\Form\LinkFormType;

// Other
// ================================================================================================
use SimplePie\SimplePie;


class FeedController extends AbstractController
{
    private $em;
    private $feed;
    private $channels;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;

        $this->feed = new SimplePie();
        $this->feed->enable_cache(false);

        $this->channels = $this->getChannels();
    }

    #[Route("/feed", name: "feeds", methods:["GET"])]
    public function index(): Response
    {
        $link = new Link();
        $link->setName("All");

        $articles = [];

        return $this->render("/feed/index.html.twig", [
            "title" => "Jadu | Feed",
            "channels" => $this->channels,
            "link" => $link,
            "articles" => $articles
        ]);
    }

    #[Route("/feed/create", name: "create_feed")]
    public function create(Request $request): Response
    {
        $link = new Link();

        $form = $this->createForm(LinkFormType::class, $link);
        $form->handleRequest($request);

        $isValidXml = false;

        // When a user submits a valid form, add to the database
        if ($form->isSubmitted() && $form->isValid()) {
            $newLink = $form->getData();

            // Check if the link already exists in the database
            $existingLink = $this->em->getRepository(Link::class)
                ->findOneBy(["link" => $newLink->getLink()]);

            // Check if the rss link returns an xml file
            $this->feed->set_feed_url($newLink->getLink());

            if ($this->feed->init()) {
                $isValidXml = true;
            }

            if ($isValidXml) {
                $user = $this->getUser();

                if ($existingLink) {
                    // If the link already exists, associate it with the current user
                    $existingLink->addUser($user);

                    $this->addFlash("success", "You have successfully added the channel.");
                } else {
                    // If the link doesn't exist, save it to the database and associate it with the current user
                    $this->em->persist($newLink);
                    $newLink->addUser($user);

                    $this->addFlash("success", "You have successfully subbed to the channel.");
                }

                // Persist changes
                $this->em->flush();
            }

            // Redirect to feed
            return $this->redirectToRoute("feeds");
        }

        return $this->render("feed/create.html.twig", [
            "form" => $form->createView(),
            "channels" => $this->channels
        ]);
    }

    #[Route("/feed/{name}", name: "feed", methods:["GET"])]
    public function show($name): Response
    {
        $repository = $this->em->getRepository(Link::class);
        $link = $repository->findOneBy(["name" => $name]);

        $articles = [];

        if ($link) {
            $this->feed->set_feed_url($link->getLink());
            $this->feed->init();

            if ($this->feed->error()) {
                echo $this->feed->error();
            }

            $items = $this->feed->get_items(0, 15);

            // Parse each item then extract what we need
            foreach ($items as $item) {
                $description = mb_strimwidth($item->get_description(), 0, 500, "...") ?? "";
                $article = (object) [
                    "link" => $item->get_link(),
                    "title" => $item->get_title(),
                    "description" => $description,
                    "image_url" => "",
                    "date" => $item->get_date()
                ];

                array_push($articles, $article);
            }
        }

        return $this->render("/feed/index.html.twig", [
            "articles" => $articles,
            "channels" => $this->channels,
            "link" => $link,
            "title" => "Jadu | Feed"
        ]);
    }

    #[Route("/feed/edit/{id}", name: "edit_movie", methods: ["GET", "POST", "PATCH"])]
    public function edit($id, Request $request): Response
    {
        $repository = $this->em->getRepository(Link::class);

        $link = $repository->find($id);

        $form = $this->createForm(LinkFormType::class, $link);
        $form->handleRequest($request);

        $this->feed->set_feed_url($form->get("link")->getData());

        // Handles the request
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->feed->init($form->get("link")->getData())) {
                $link->setName($form->get("name")->getData());
                $link->setLink($form->get("link")->getData());

                $this->em->flush();
                $this->addFlash("success", "You have successfully edited the channel.");
            } else {
                $this->addFlash("error",
                    "Something went wrong. Ensure that the RSS link is correct then try again."
                );
            }

            return $this->redirectToRoute("feeds");
        }

        return $this->render("feed/edit.html.twig", [
            "link" => $link,
            "channels" => $this->channels,
            "form" => $form->createView()
        ]);
    }

    #[Route("/feed/delete/{id}", name: "delete_movie", methods: ["GET", "DELETE"])]
    public function delete($id, Request $request): Response
    {
        $user = $this->getUser();
        $userRoles = $user ? $user->getRoles() : [];

        $link = $this->em->getRepository(Link::class)->find($id);

        // If the user is an admin, delete the link entirely, otherwise just remove user from the link
        if (in_array("ROLE_ADMIN", $userRoles, true)) {
            $this->em->remove($link);
        } else {
            $link->removeUser($user);
        }

        $this->em->flush();
        $this->addFlash("success", "Channel successfully removed.");

        return $this->redirectToRoute("feeds");
    }

    public function getChannels() {
        $channels = [];

        $httpClient = HttpClient::create([
            'timeout' => 1, // After 1 second, stop the api request to speed things up
        ]);

        $repository = $this->em->getRepository(Link::class);

        // Get user data
        $user = $this->security->getUser();

        // If a user is logged in, retrieve their roles
        $userRoles = $user ? $user->getRoles() : [];

        // If the user is an admin, get all links; otherwise, get subscribed channels
        if (in_array("ROLE_ADMIN", $userRoles, true)) {
            $links = $repository->findAll();
        } else {
            $links = $user ? $user->getLinks() : [];
        }

        foreach ($links as $link) {
            $linkCurrent = $link->getLink();
            $channel_image_url = "";

            try {
                // Make a GET request with a timeout
                $response = $httpClient->request('GET', $linkCurrent);

                // Get the response content
                $content = $response->getContent();

                // Setup Simplepie
                $this->feed->set_raw_data($content);
                $this->feed->init();

                $channel_image_url = $this->feed->get_favicon();

                if ($this->feed->error()) {
                    echo $this->feed->error();
                }
            } catch (TransportException $e) {
                $channel_image_url = "https://upload.wikimedia.org/wikipedia/commons/a/ab/United_States_sign_-_Slow.svg";
            }

            $channel_name = $link->getName();
            $channel = (object) [
                "image_url" => $channel_image_url,
                "name" => $channel_name
            ];

            array_push($channels, $channel);
        }

        return $channels;
    }
}