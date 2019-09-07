<?php


namespace CodeMade\LiquidBundle;

use CodeMade\LiquidBundle\DependencyInjection\LiquidExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LiquidBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new LiquidExtension();
    }
}