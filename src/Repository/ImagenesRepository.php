<?php

namespace App\Repository;

use App\Entity\Imagenes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Imagenes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Imagenes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Imagenes[]    findAll()
 * @method Imagenes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagenesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Imagenes::class);
        $this->manager = $manager;
    }


    public function saveImagen($url)
    {
        $newImagen = new Imagenes();

        $newImagen
            ->setUrl($url);


        $this->manager->persist($newImagen);
        $this->manager->flush();
    }
}
