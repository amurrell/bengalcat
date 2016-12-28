<?php

namespace Bc\App;

abstract class RouteExtender {

    protected $head;
    protected $header;
    protected $footer;
    protected $variant;
    protected $variants;
    protected $queryVars;
    protected $method;
    protected $nav;

    public function __construct(Core $bc)
    {
        $this->bc = $bc;

        $this->variant = $this->bc->getRouteVariant();
        $this->variants = empty($this->bc->getRouteVariants()) ?
            [] :
            array_filter(
                $this->bc->getRouteVariants(),
                function($variant){
                    return strlen($variant);
                }
            );
        $this->queryVars = $this->bc->getQueryVars();
        $this->method = $this->bc->getMethod();

        // Entry point for custom methods
        $this->customMethods();

        // Setup some templates

        /** @note Rewrite render or init method to bypass
         *  rendering these templates.
         *  Comes in handy when utilizing these for, say, a json api return.
         */
        $this->setHeader(SRC_DIR . 'templates/header.php')
             ->setHead(SRC_DIR . 'templates/head.php')
             ->setFooter(SRC_DIR . 'templates/footer.php');

        $this->init();

        return $this;
    }

    protected function customMethods() {

    }

    /**
     * Need to customize the process of each routeExtender with a init
     */
    abstract protected function init();

    protected function render($renderPath, $data = null, $tokens = array())
    {
        $tokens['[bc:routeExtender]'] = $this->bc->getRouteExtender();

        // Begin output buffering for render include
        $renderData = $this->bc->util->getTemplateContents($renderPath, $data);

        // Get Header Output
        $header = $this->bc->util->getTemplateContents($this->getHeader(), $data);
        $head = $this->bc->util->getTemplateContents($this->getHead(), $data);

        // Replace [head] token with head data - render will do automatically.
        // Other tokens to be dealt with separately.
        $renderHeader = str_replace('[bc:head]', $head, $header);

        // Get Footer Output
        $renderFooter = $this->bc->util->getTemplateContents($this->getFooter(), $data);

        // Fix any other tokens
        $content = $renderHeader . $renderData . $renderFooter;

        $echoContent = $this->applyTokens($content, $tokens);

        // Paint the render!
        echo $echoContent;
    }

    /**
     * Apply [bc:tokens] or other replacements to the content.
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
        preg_match_all('#(\[bc\:.*?\])#', $content, $remainingTokens);

        // Get default Tokens
        $tokenDefaults = (file_exists(APP_DIR . 'config/tokenDefaults.php')) ?
                (include APP_DIR . 'config/tokenDefaults.php') : array();

        // Find default replacements if needed, and there are default tokens.
        if (!empty($remainingTokens) &&
                !empty($remainingTokens[1]) &&
                !empty($tokenDefaults)) {

            foreach ($remainingTokens[1] as $remToken) {
                if (isset($tokenDefaults[$remToken])) {
                    $content = str_replace($remToken, $tokenDefaults[$remToken], $content);
                }
            }
        }

        // remove token residue after all replacements have been applied
        // Only this pattern [bc:token]
        return preg_replace('#\[bc\:.*?\]#', '', $content);
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
        return array_reduce($this->variants, function($carry, $item) use ($matchValue) {
            if ($item == $matchValue) {
                return true;
            }
            if ($carry === true) {
                return $item;
            }
            return $carry;
        }, null);
    }

    public function getQueryVars() {
        return $this->queryVars;
    }

    public function getMethod() {
        return $this->method;
    }

    public function setHead($head) {
        $this->head = $head;
        return $this;
    }

    public function setHeader($header) {
        $this->header = $header;
        return $this;
    }

    public function setFooter($footer) {
        $this->footer = $footer;
        return $this;
    }

}

