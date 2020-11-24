<?php

namespace App\Repository;

use App\Entity\Libros;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Libros|null find($id, $lockMode = null, $lockVersion = null)
 * @method Libros|null findOneBy(array $criteria, array $orderBy = null)
 * @method Libros[]    findAll()
 * @method Libros[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibrosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Libros::class);
        $this->manager = $manager;
    }
    public function saveLibro($isbm, $title, $subtitle, $author, $published, $publisher, $pages, $description, $category, $website, $imagenes = "")
    {
        $newLibro = new Libros();

        $newLibro
            ->setIsbn($isbm)
            ->setTitle($title)
            ->setSubtitle($subtitle)
            ->setAuthor($author)
            ->setPublished($published)
            ->setPublisher($publisher)
            ->setPages($pages)
            ->setDescription($description)
            ->setWebsite($website)
            ->setCategory($category)
            ->setImagenes($imagenes);


        $this->manager->persist($newLibro);
        $this->manager->flush();
    }

    public function saveImgToLibro(Libros $libro): Libros
    {    

        $this->manager->persist($libro);
        $this->manager->flush();

        return $libro;
    }


    public function removeLibro(Libros $libro)
    {
        $this->manager->remove($libro);
        $this->manager->flush();
    }
}
