<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Topic>
 *
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

//    /**
//     * @return Topic[] Returns an array of Topic objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Topic
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findTopicsByCategory(int $categoryId)
{
    // Récupération de l'EntityManager de Doctrine
    $em = $this->getEntityManager();
    // Création d'un QueryBuilder pour construire la requête
    $qb = $em->createQueryBuilder();

    // Construction de la requête DQL
    // Sélection de l'entité 'Topic' en tant que 't'
    $qb->select('t')
        // Définition de l'entité source 'Topic' avec l'alias 't'
        ->from('App\Entity\Topic', 't')
        // Ajout d'une condition où la catégorie du topic doit correspondre à l'ID de catégorie fourni
        ->where('t.categoryTopic = :categoryId')
        // Liaison du paramètre 'categoryId' à la variable $categoryId
        ->setParameter('categoryId', $categoryId)
        // Tri des résultats par 'dateCreation' en ordre décroissant
        ->orderBy('t.dateCreation', 'DESC');

    // Exécution de la requête et retour des résultats
    return $qb->getQuery()->getResult();
}



    public function findMostClickedTopics($limit = 3)
    {
        // Récupération de l'EntityManager de Doctrine
        $em = $this->getEntityManager();
        // Création d'un QueryBuilder pour construire la requête
        $qb = $em->createQueryBuilder();
    
        // Construction de la requête DQL
        // Sélection de l'entité 'Topic' en tant que 't'
        $qb->select('t')
            // Définition de l'entité source 'Topic' avec l'alias 't'
            ->from('App\Entity\Topic', 't')
            // Tri des résultats par 'clickCount' en ordre décroissant
            ->orderBy('t.clickCount', 'DESC')
            // Limitation du nombre de résultats retournés
            ->setMaxResults($limit);
    
        // Exécution de la requête et retour des résultats
        return $qb->getQuery()->getResult();
    }
    

    public function findLatestTopics($limit = 3)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        $qb->select('t')
            ->from('App\Entity\Topic', 't')
            ->orderBy('t.dateCreation', 'DESC')
            ->setMaxResults($limit);
    
        return $qb->getQuery()->getResult();
    }
    
}
