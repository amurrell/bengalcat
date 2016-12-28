<?php

namespace Bc\App\Utils;

use Bc\App\Util;
use Exception;

class NavUtil {

    const activeClass = 'ia-active';

    protected $nav;
    public $items;

    public function __construct()
    {
        $this->items = [
            'docs' => [
                'attributes' => [
                    'href' => 'https://github.com/amurrell/bengalcat/blob/master/README.md',
                ],
                'fontawesomeIcon' => 'copy',
                'display' => 'Docs',
            ],
            'about' => [
                'attributes' => [
                    'href' => '/about/',
                ],
                'fontawesomeIcon' => 'question',
                'display' => 'About',
                'matchingRoutePath' => '/about/'
            ],
            'download' => [
                'attributes' => [
                    'href' => 'https://github.com/amurrell/bengalcat/',
                ],
                'fontawesomeIcon' => 'download',
                'display' => 'Download',
            ],
        ];
    }

    protected function navStructure($returnKeys = [], $excludeKeys = [])
    {
        if (!empty($returnKeys)) {
            $keep = array_intersect_key($this->items, array_flip($returnKeys));
            $sorted = array_replace(array_flip($returnKeys), $keep);
            return $sorted;
        }

        if (!empty($excludeKeys)) {
            return array_diff_key($this->items, array_flip($excludeKeys));
        }

        return $this->items;
    }

    /**
     * Add a menu item
     *
     * @param string $name The name of the item, to refer to it.
     * @param array $attributes An array of html attribute/value pairs
     * @param string $icon The icon to append, "fa fa-$icon"
     * @param string $display The display text
     * @param string $routePath The matching route path (without hashes, queries)
     * @param string $after The name of a menu item to position this one after
     * @param string $before The name of a menu item to position this one before
     */
    public function addTempItem($name, $attributes, $icon, $display, $routePath, $after = '', $before = '') {
        $item = [
            $name => [
                'attributes' => array_merge(['class' => 'ia-button'], $attributes),
                'fontawesomeIcon' => $icon,
                'display' => $display,
                'matchingRoutePath' => $routePath,
            ]
        ];

        if (!empty($after)) {
            $this->items = Util::insertInArrayAfterKey($item, $after, $this->items);
        } else if (!empty($before)) {
            $this->items = Util::insertInArrayBeforeKey($item, $before, $this->items);
        } else {
            $this->items[$name] = $item[$name];
        }

    }

    public function checkItemExists($name)
    {
        return isset($this->items[$name]);
    }

    public function getNav($render = false, $activeKey = null, $returnKeys = [], $excludeKeys = [])
    {
        $this->nav = $this->navStructure($returnKeys, $excludeKeys);

        if ($render) {
            return $this->renderNav($activeKey);
        }

        return $this->nav;
    }

    protected function renderNav($activeKey = null)
    {
        $this->addActiveClass($activeKey);

        return Util::getTemplateContents(SRC_DIR . 'tokenHTML/nav.php', $this->nav);
    }

    protected function addActiveClass($key)
    {
        if (!isset($this->nav[$key])) {
            return;
        }

        $this->nav[$key]['attributes']['class'] .= ' ' . self::activeClass;
    }
}