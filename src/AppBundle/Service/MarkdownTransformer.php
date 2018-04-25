<?php
/**
 * Created by PhpStorm.
 * User: mostafa
 * Date: 4/24/18
 * Time: 5:38 PM
 */

namespace AppBundle\Service;


use Doctrine\Common\Cache\Cache;
use Knp\Bundle\MarkdownBundle\Parser\MarkdownParser;

class MarkdownTransformer
{
    private $markdownParser;
    private $cache;
    public function __construct(MarkdownParser $markdownparser,Cache $cache)
    {
        $this->markdownParser = $markdownparser;
        $this->cache = $cache;
    }

    public function parse($str){

        $key = md5($str);

        if($this->cache->contains($key)){
            return $this->cache->fetch($key);
        }

        $str = $this->markdownParser
            ->transform($str);
        $this->cache->save($key, $str);

        return $str;
    }
}