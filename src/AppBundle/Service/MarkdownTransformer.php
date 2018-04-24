<?php
/**
 * Created by PhpStorm.
 * User: mostafa
 * Date: 4/24/18
 * Time: 5:38 PM
 */

namespace AppBundle\Service;


class MarkdownTransformer
{
    private $markdownParser;

    public function __construct($markdownparser)
    {
        $this->markdownParser = $markdownparser;
    }

    public function parse($str){
        return $funfact = $this->markdownParser
            ->transform($str);
    }
}