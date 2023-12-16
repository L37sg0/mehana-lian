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
    public function createEntityCollectionFromCsv(
        UploadedFile $file,
        string $entityClass,
        array $requiredColumns,
    ): Collection {
        $data = $this->getArrayFromFileContent($file);
        $missingColumns = $this->validateHeaders(array_keys($data[0]), $requiredColumns);

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

    public function validateHeaders(array $headers, array $requiredColumns)
    {
        $requiredHeaders = $requiredColumns;//
        $result = array_diff($requiredHeaders, $headers);
        return $result;
    }

    public function getArrayFromFileContent(UploadedFile $file): array {

        $csvContent = file($file->getRealPath());

        $rows = array_map('str_getcsv', $csvContent);
        $headers = array_shift($rows);

        $data = array();
        foreach ($rows as $row) {
            $data[] = array_combine($headers, $row);
        }

        return $data;
    }
}