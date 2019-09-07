# Symfony Liquid Bundle - template engine for PHP 

Liquid is a PHP port of the [Liquid template engine for Ruby](https://github.com/Shopify/liquid), which was written by Tobias Lutke. Although there are many other templating engines for PHP, including Smarty (from which Liquid was partially inspired)

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

 * PHP 7.1+

## Fork notes and contributors

This bundle create for Symfony and use  
[harrydeluxe](https://github.com/harrydeluxe/php-liquid/issues) library Liquid!
