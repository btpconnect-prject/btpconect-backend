<?php

namespace App\State;

use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\CategorieEntity;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @implements ProcessorInterface<CategorieEntity, CategorieEntity|void>
 */
final readonly class CategoryProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param CategorieEntity $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($operation instanceof DeleteOperationInterface && $data instanceof CategorieEntity) {
            $this->entityManager->remove($data);
            $this->entityManager->flush();
            return null;
        }

        if (!$data instanceof CategorieEntity) {
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        // Traitement des sous-catégories
        $childCategoryData = $data->getChildCategories();

        if (!empty($childCategoryData)) {
            foreach ($childCategoryData as $childRaw) {
                $decoded = json_decode($childRaw, true);

                if (!is_array($decoded) || empty($decoded['name'])) {
                    // Format invalide
                    continue;
                }

                $childTitle = trim($decoded['name']);
                $childIcon  = $decoded['icon'] ?? null;

                // Vérifier si la sous-catégorie existe déjà
                $existing = $this->entityManager
                    ->getRepository(CategorieEntity::class)
                    ->findOneBy(['title' => $childTitle]);

                if (!$existing) {
                    $existing = new CategorieEntity();
                    $existing
                        ->setTitle($childTitle)
                        ->setIcon($childIcon)
                        ->setIsSubCategory(true);
                    
                    $this->entityManager->persist($existing);
                }

                // Lier la sous-catégorie à la catégorie principale
                $data->addSubsCategory($existing);
            }
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
