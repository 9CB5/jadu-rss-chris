<?php

namespace App\DataFixtures;

use App\Entity\Link;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LinkFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $link = new Link();
        $link->setName("Slashdot");
        $link->setLink("https://slashdot.org/rss/slashdot.rss");
        $manager->persist($link);

        $link2 = new Link();
        $link2->setName("PHP");
        $link2->setLink("https://www.php.net/news.rss");
        $manager->persist($link2);

        $link3 = new Link();
        $link3->setName("Tech crunch");
        $link3->setLink("https://techcrunch.com/feed/");
        $manager->persist($link3);

        $link4 = new Link();
        $link4->setName("NASA");
        $link4->setLink("https://www.nasa.gov/feeds/iotd-feed");
        $manager->persist($link4);

        $link5 = new Link();
        $link5->setName("Al Jazeera");
        $link5->setLink("https://www.aljazeera.com/xml/rss/all.xml");
        $manager->persist($link5);

        $manager->flush();

        $this->addReference("link_1", $link);
        $this->addReference("link_2", $link2);
        $this->addReference("link_3", $link3);
        $this->addReference("link_4", $link4);
        $this->addReference("link_5", $link5);
    }
}
