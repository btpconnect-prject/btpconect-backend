<?php

/**
 * Api plateform upload file bundle: https://api-platform.com/docs/symfony/file-upload/
 * 
 */

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use App\State\MediaProcessor;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[Vich\Uploadable]
#[ORM\Entity]
#[
    ApiResource(
        paginationItemsPerPage: 300, // Nombre d'éléments par page
        paginationMaximumItemsPerPage: 200, // Nombre maximum d'éléments par page 
        paginationEnabled: true, // Activer la pagination
        normalizationContext: ['groups' => ['mediaObject::read', 'product::read']],
        types: ['https://schema.org/MediaObject'],
        outputFormats: ['jsonld' => ['application/ld+json']],
        operations: [
            new Get(uriTemplate: "/mediaobject/{id}", forceEager: false),
            new GetCollection(uriTemplate: "/mediaobjects", forceEager: false),
            new Delete(uriTemplate: "/mediaobject/{id}", forceEager: false, processor: MediaProcessor::class),
            new Post(
                uriTemplate: "/mediaobject",
                inputFormats: ['multipart' => ['multipart/form-data']],
                openapi: new Model\Operation(
                    requestBody: new Model\RequestBody(
                        content: new \ArrayObject([
                            'multipart/form-data' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'file' => [
                                            'type' => 'string',
                                            'format' => 'binary'
                                        ]
                                    ]
                                ]
                            ]
                        ])
                    )
                )


            )
        ]
    )
]
class MediaObject
{

    use UuidTrait;
    #[ApiProperty(types: ['https://schema.org/contentUrl'], writable: false)]
    #[Groups(['mediaObject::read', 'product::read'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'filePath')]
    #[Assert\NotNull]
    #[Groups(['mediaObject::read', 'product::read'])]
    public ?File $file = null;

    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: true)]
    #[Groups(['mediaObject::read', 'product::read'])]
    public ?string $filePath = null;


    public function getFilePath(): ?string
    {
        return $this->filePath;
    }


    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }
}
