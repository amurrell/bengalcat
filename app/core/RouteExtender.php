<?php

namespace Bc\App\Core;

use ReflectionClass;
use const APP_DIR;

abstract class RouteExtender {

    protected $head;
    protected $header;
    protected $footer;
    protected $variant;
    protected $variants;
    protected $queryVars;
    protected $routeVars;
    protected $method;
    protected $nav;
    protected $theme;
    protected $routeData;
    protected $matchVars = [
        'get',
        'create',
        'update',
        'delete',
    ];

    public function __construct(Core $bc, $data = [])
    {
        $this->bc = $bc;
        $this->routeData = $data;

        $this->variant = $this->bc->getRouteVariant();
        $this->variants = empty($this->bc->getRouteVariants()) ?
            [] :
            array_filter(
                $this->bc->getRouteVariants(),
                function($variant){
                    return strlen($variant);
                }
            );
        $this->routePieces = $this->bc->getRoutePieces();
        $this->queryVars = $this->bc->getQueryVars();
        $this->method = $this->bc->getMethod();

        // Entry point for custom methods
        $this->customMethods();

        // Setup some templates
        $this->setTemplatePaths();

        $this->init();

        return $this;
    }
    
    protected function setTemplatePaths()
    {
        $this->setHeader($this->getThemePart('/src/templates/header.php'))
             ->setHead($this->getThemePart('/src/templates/head.php'))
             ->setFooter($this->getThemePart('/src/templates/footer.php'));
    }

    protected function customMethods() {

    }

    /**
     * Need to customize the process of each routeExtender with a init
     */
    abstract protected function init();

    protected function render($renderPath, $data = null, $tokens = array())
    {
        $reflectionClass = new ReflectionClass($this);
        $tokens['[:routeExtender]'] = $reflectionClass->getShortName();

        // Begin output buffering for render include
        $renderData = $this->bc->util->getTemplateContents($this->bc, $renderPath, $data);

        // Get Header Output
        $header = $this->bc->util->getTemplateContents($this->bc, $this->getHeader(), $data);
        $head = $this->bc->util->getTemplateContents($this->bc, $this->getHead(), $data);

        // Replace [head] token with head data - render will do automatically.
        // Other tokens to be dealt with separately.
        $renderHeader = str_replace('[:head]', $head, $header);

        // Get Footer Output
        $renderFooter = $this->bc->util->getTemplateContents($this->bc, $this->getFooter(), $data);

        // Fix any other tokens
        $content = $renderHeader . $renderData . $renderFooter;

        $echoContent = $this->applyTokens($content, $tokens);

        // Paint the render!
        echo $echoContent;
    }

    /**
     * Apply [:tokens] or other replacements to the content.
     * Be careful.
     *
     * @param string $content The content of the page/route output
     * @param array $tokens The array of token => replacement values
     * @return string The content with all replacements completed.
     */
    protected function applyTokens($content, $tokens)
    {
        if (empty($tokens) || !is_array($tokens)) {
            return $this->fixTokenResidue($content);
        }

        foreach ($tokens as $token => $replacement) {
            $content = str_replace($token, $replacement, $content);
        }

        return $this->fixTokenResidue($content);
    }

    private function fixTokenResidue($content) {
        // Check for remaining tokens
        $remainingTokens = array();
        preg_match_all('#(\[\:.*?\])#', $content, $remainingTokens);

        // Get default Tokens
        $tokenDefaults = (file_exists(APP_DIR . 'config/tokenDefaults.php')) ?
                (include APP_DIR . 'config/tokenDefaults.php') : array();
        
        // Get Token Overrides
        $tokenOverrides = (file_exists(APP_DIR . 'config/tokens.php')) ?
                (include APP_DIR . 'config/tokens.php') : array();
        
        $tokens = array_merge($tokenDefaults, $tokenOverrides);

        // Find default replacements if needed, and there are default tokens.
        if (!empty($remainingTokens) &&
                !empty($remainingTokens[1]) &&
                !empty($tokens)) {

            foreach ($remainingTokens[1] as $remToken) {
                if (isset($tokens[$remToken])) {
                    $content = str_replace($remToken, $tokens[$remToken], $content);
                }
            }
        }

        // remove token residue after all replacements have been applied
        // Only this pattern [:token]
        return preg_replace('#\[\:.*?\]#', '', $content);
    }

    public function getHead() {
        return $this->head;
    }

    public function getHeader() {
        return $this->header;
    }

    public function getFooter() {
        return $this->footer;
    }

    public function getVariant() {
        return $this->variant;
    }

    public function getVariants($num = null) {

        if ($num !== null) {
            return (!isset($this->variants[$num - 1]))
                ? null
                : $this->variants[$num - 1];
        }

        return $this->variants;
    }

    public function variantMatchValueExists($matchValue)
    {
        return array_reduce($this->variants, function($carry, $item) use ($matchValue) {
            if ($item == $matchValue) {
                return true;
            }
            return $carry;
        }, false);
    }

    public function getVariantMatchAfterMatchValue($matchValue)
    {
        $matched = array_reduce($this->variants, function($carry, $item) use ($matchValue) {
            if ($item == $matchValue) {
                return true;
            }
            if ($carry === true) {
                return $item;
            }
            return $carry;
        }, null);

        return ($matched === true) ? null : $matched;
    }
    
    public function buildRouteVars($matchVars = [])
    {
        $urlVars = !empty($matchVars) ? $matchVars : $this->matchVars;

        $route = $this;
        $values = array_map(function($item) use ($route) {
            return $route->getVariantMatchAfterMatchValue($item);
        }, $urlVars);

        $this->routeVars = (object) array_combine($urlVars, $values);

    }

    public function getQueryVars()
    {
        return $this->queryVars;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setHead($head)
    {
        $this->head = $head;
        return $this;
    }

    public function setHeader($header)
    {
        $this->header = $header;
        return $this;
    }

    public function setFooter($footer)
    {
        $this->footer = $footer;
        return $this;
    }
    
    public function getThemePart($part)
    {
        return $this->bc->getThemePath($this->getTheme(), $part);
    }
    
    public function getTheme() 
    {
        return !empty($this->theme)
            ?  $this->theme
            :  $this->bc->getDefaultTheme();
    }
    
    public function setTheme($theme)
    {
        $this->theme = !$this->bc->themeExists($theme)
                ? $this->bc->getDefaultTheme()
                : $theme;
    }
    
    public function getThemePartContents($partPath, $data = [])
    {
        return Util::getTemplateContents(
            $this->bc,
            $this->getThemePart($partPath),
            $data
        );
    }
    
    public function triggerError(
        $code = 404,
        $message = 'There are no defined routes.'
    ) {
        Util::triggerError(
            $this->bc,
            $this->bc->getSetting('errorRoute'),
            [
                'success' => false,
                'error_code' => $code,
                'message' => $message
            ]
        );
    }
}

