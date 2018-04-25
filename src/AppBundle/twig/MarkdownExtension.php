<?php
/**
 * Created by PhpStorm.
 * User: mostafa
 * Date: 4/25/18
 * Time: 11:15 AM
 */

namespace AppBundle\twig;


use AppBundle\Service\MarkdownTransformer;

class MarkdownExtension extends \Twig_Extension
{
    private $markdownTransformer;
    public function __construct(MarkdownTransformer $markdownTransformer)
    {
        $this->markdownTransformer = $markdownTransformer;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('markdownify', array($this, 'parseMarkdown'), [
                'is_safe' => ['html']
            ])
        ];
    }

    public function parseMarkdown($str){
        return $this->markdownTransformer->parse($str);
    }

    public function getName()
    {
        return 'app_markdown';
    }

}