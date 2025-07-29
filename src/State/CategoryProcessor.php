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
        if (str_contains(strtolower($operation->getName()), "put")) {
            $categoryId = $uriVariables['id'] ?? null;

            /** @var CategorieEntity|null $existingCategory */
            $existingCategory = $this->entityManager
                ->getRepository(CategorieEntity::class)
                ->find($categoryId);

            if (!$existingCategory) {
                throw new \RuntimeException("Category not found for ID: $categoryId");
            }

            // Mise à jour des champs principaux
            $existingCategory
                ->setTitle($data->getTitle())
                ->setDescription($data->getDescription())
                ->setIcon($data->getIcon())
                ->setUpdatedAt(new \DateTimeImmutable());

            // Nettoyage des sous-catégories actuelles
            foreach ($existingCategory->getSubsCategories() as $sub) {
                $existingCategory->removeSubsCategory($sub);
            }

            $childCategoryData = $data->getChildCategories() ?? [];

            foreach ($childCategoryData as $childRaw) {
                $decoded = json_decode($childRaw, true);
                if (!is_array($decoded) || empty($decoded['name'])) {
                    continue;
                }

                $childTitle = trim($decoded['name']);
                $childIcon  = $decoded['icon'] ?? null;
                $childId    = $decoded['id'] ?? null;

                if ($childId) {
                    $childCategory = $this->entityManager
                        ->getRepository(CategorieEntity::class)
                        ->find($childId);

                    if (!$childCategory) {
                        throw new \RuntimeException("Child category not found for ID: $childId");
                    }

                    $childCategory
                        ->setTitle($childTitle)
                        ->setIcon($childIcon)
                        ->setIsSubCategory(true);
                } else {
                    $childCategory = new CategorieEntity();
                    $childCategory
                        ->setTitle($childTitle)
                        ->setIcon($childIcon)
                        ->setIsSubCategory(true);

                    $this->entityManager->persist($childCategory);
                }


                $data->addSubsCategory($childCategory);
            }
            $this->entityManager->flush();
            return $this->processor->process($existingCategory, $operation, $uriVariables, $context);
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
                $existing = new CategorieEntity();
                $existing
                    ->setTitle($childTitle)
                    ->setIcon($childIcon)
                    ->setIsSubCategory(true);
                $this->entityManager->persist($existing);
                $data->addSubsCategory($existing);
            }
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
