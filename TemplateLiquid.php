<?php


namespace CodeMade\LiquidBundle;


use Liquid\Liquid;
use Liquid\Template;
use Symfony\Component\HttpKernel\KernelInterface;

class TemplateLiquid
{
    private $kernel;
    private $paths;
    private $defaultPath;
    private $settings;

    public function __construct(KernelInterface $kernel, array $settings = [])
    {
        $this->settings = $settings;
        $this->kernel = $kernel;
        $this->paths = isset($this->settings['paths']) ? $this->settings['paths'] : [];
        $this->defaultPath = isset($this->settings['default_path']) ? $this->settings['default_path']: '';
    }


    /**
     * @param string $view
     * @param array $parameters
     * @return string
     */
    public function render(string $view, array $parameters)
    {
        list($template_file, $template_path) = $this->getTemplateFile($view);

        Liquid::set('INCLUDE_SUFFIX', $this->settings['include_suffix']);
        Liquid::set('INCLUDE_PREFIX', $this->settings['include_prefix']);

        $cache = $this->getCacheSetting();


        $liquid = new Template($template_path, $cache);


        if (!empty($this->settings['tags'])) {
            foreach ($this->settings['tags'] as $key => $item) {
                $liquid->registerTag($key, $item);
            }
        }

        if (!empty($this->settings['filter'])) {
            $liquid->registerFilter(new $this->settings['filter']);
        }

        $html = file_get_contents($template_file . '.' . $this->settings['include_suffix']);
        $liquid->parse($html);
        $content = $liquid->render($parameters);

        return $content;

    }

    public function getTemplateFile($view)
    {
        $template_name = false;

        if (preg_match('/(^@[a-zA-Z]+)\//iu', $view, $match))
        {
            $template_name = isset($match[1]) ? str_replace('@', '', $match[1]) : null;
            $view = str_replace($match[0], '', $view);
        }

        if ($template_name && empty($this->paths[$template_name])) {
            throw new \LogicException('Path name "'.$template_name.'" not found in configuration file.');
        }

        $template_path = isset($this->paths[$template_name]) ? $this->paths[$template_name] : $this->defaultPath;

        $template_file = $template_path . '/' . $view;
        if (!file_exists($template_file . '.' . $this->settings['include_suffix'])) {
            throw new \LogicException('File template "'.$template_file . '.' . $this->settings['include_suffix'].'" not found.');
        }

        return [
          $template_file,
          $template_path
        ];
    }

    private function getCacheSetting()
    {
        if (isset($this->settings['cache']) && $this->settings['cache']) {
            if (!is_dir($this->settings['cache'])) {
                if (!mkdir($this->settings['cache'], 0777, true)) {
                    throw new \LogicException('Failed to create directory "'.$this->settings['cache'] .'".');
                }
            }
            return array('cache' => 'file', 'cache_dir' => $this->settings['cache']);
        }
        return null;
    }


    /**
     * Checks if the template exists
     *
     * @param $view
     * @return bool
     */
    public function exists($view)
    {
        $this->getTemplateFile($view);
        return true;
    }

    /**
     * Checks if the given template can be handled by this engine
     *
     * @param $view
     * @return bool
     */
    public function supports($view)
    {
        $this->getTemplateFile($view);
        return true;
    }




}