# Symfony Liquid Bundle - template engine for PHP 

Liquid is a PHP port of the [Liquid template engine for Ruby](https://github.com/Shopify/liquid), which was written by Tobias Lutke. Although there are many other templating engines for PHP, including Smarty (from which Liquid was partially inspired), Liquid had some advantages that made porting worthwhile:

 * Readable and human friendly syntax, that is usable in any type of document, not just html, without need for escaping.
 * Quick and easy to use and maintain.
 * 100% secure, no possibility of embedding PHP code.
 * Clean OO design, rather than the mix of OO and procedural found in other templating engines.
 * Seperate compiling and rendering stages for improved performance.
 * Easy to extend with your own "tags and filters":https://github.com/harrydeluxe/php-liquid/wiki/Liquid-for-programmers.
 * 100% Markup compatibility with a Ruby templating engine, making templates usable for either.
 * Unit tested: Liquid is fully unit-tested. The library is stable and ready to be used in large projects.

## Why Liquid?

Why another templating library?

Liquid was written to meet three templating library requirements: good performance, easy to extend, and simply to use.

## Installing

You can install this lib via [composer](https://getcomposer.org/):

    composer create-project codemade-xyz/php-liquid-bundle

## Example template

	{% if products %}
		<ul id="products">
		{% for product in products %}
		  <li>
			<h2>{{ product.name }}</h2>
			Only {{ product.price | price }}

			{{ product.description | prettyprint | paragraph }}

			{{ 'it rocks!' | paragraph }}

		  </li>
		{% endfor %}
		</ul>
	{% endif %}

## How to use Liquid in Symfony

#### 1. Connect bundle in file bundle.php or kernel.php

Example add in kernel.php

    public function registerBundles()
    {
        $bundles = array(
            ...
            new \CodeMade\LiquidBundle\LiquidBundle()
        );
        
        return $bundles;
    }

#### 2. Here is a simple example config

In config file add setting, if no values ​​are specified, then the default settings will be used.

    liquid:
      cache: '%kernel.cache_dir%/liquid'
      default_path: '%kernel.project_dir%/templates'
      filter: App\Kernel\LiquidTemplateFilter
      include_suffix: 'tpl'
      include_prefix: ''
      tags:
        section: App\Kernel\LiquidTagSection
      paths:
        'App': '%kernel.project_dir%/templates/App'

#### 3. In config file framework.yaml
Add setting in framework.yaml

    framework:
      ...
      templating:
        engines: ['liquid']

#### 4. Use the standard functions in the controller to process the template.

    <?php
    namespace App\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    
    class Index extends AbstractController
    {
    
        /**
         * @Route("/")
         */
        public function index()
        {
            return $this->render('@App/home', [
                'document' => [
                    'title' => 'Home page'
                ]
            ]);
    
        }
    
    }

## Requirements

 * PHP 5.6+

## Fork notes and contributors

This bundle create for Symfony and use  
[kalimatas](https://github.com/kalimatas/php-liquid) library Liquid!
