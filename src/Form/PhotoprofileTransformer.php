<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
// use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class PhotoProfileTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (is_string($value) && is_file($value)) {
            return new File($value);
        }

    }

    public function reverseTransform($value)
    {
        if ($value instanceof File) {
            return $value->getFilename();
        }

        return null;
    }


}