<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CsvImporter
{
    /**
     * @param UploadedFile $file
     * @param string $entityClass
     * @param array<int, string> $requiredColumns
     * @return Collection<int, mixed>
     */
    public function createEntityCollectionFromCsv(
        UploadedFile $file,
        string $entityClass,
        array $requiredColumns,
    ): Collection {
        $data = $this->getArrayFromFileContent($file);
        $headers = array_keys($data[0]);
        $missingColumns = $this->validateHeaders($headers, $requiredColumns);

        $entityCollection = new ArrayCollection();

        if (empty($missingColumns)) {
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers,$encoders);

            foreach ($data as $item) {
                $entity = $serializer->deserialize($serializer->serialize($item, 'json'), $entityClass, 'json');
                $entityCollection->add($entity);
            }
        }
        return $entityCollection;
    }

    /**
     * @param array<int, mixed> $headers
     * @param array<int, string> $requiredColumns
     * @return array<int, mixed>
     */
    public function validateHeaders(array $headers, array $requiredColumns): array
    {
        $requiredHeaders = $requiredColumns;//
        $result = array_diff($requiredHeaders, $headers);
        return $result;
    }

    /**
     * @param UploadedFile $file
     * @return array<int, array<mixed,mixed>>
     */
    public function getArrayFromFileContent(UploadedFile $file): array {

        $csvContent = file($file->getRealPath());

        /** @phpstan-ignore-next-line */
        $rows = array_map('str_getcsv', $csvContent);
        $headers = array_shift($rows);

        $data = array();
        foreach ($rows as $row) {
            /** @phpstan-ignore-next-line */
            $data[] = array_combine($headers, $row);
        }

        return $data;
    }
}