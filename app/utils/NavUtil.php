<?php

namespace Bc\App\Utils;

use Bc\App\Core\Core;
use Bc\App\Core\Util;

class NavUtil {

    protected $bc;
    protected $addItemsLast = true;
    protected $nav;
    protected $navRenderPath;
    protected $navActiveClass;
    
    public $items;
    public $addItems = [];


    public function __construct(Core $bc, $items, $navRenderPath, $navActiveClass)
    {
        $this->bc = $bc;
        $this->items = Util::arrayifyObject($items);
        $this->navRenderPath = $navRenderPath;
        $this->$navActiveClass = $navActiveClass;
    }

    protected function navStructure($returnKeys = [], $excludeKeys = [])
    {
        $finalItems = ($this->addItemsLast)
                ? array_merge($this->items, $this->addItems)
                : array_merge($this->addItems, $this->items);

        if (!empty($returnKeys)) {
            $keep = array_intersect_key($finalItems, array_flip($returnKeys));
            $sorted = array_replace(array_flip($returnKeys), $keep);
            return $sorted;
        }

        if (!empty($excludeKeys)) {
            return array_diff_key($finalItems, array_flip($excludeKeys));
        }

        return $finalItems;
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
                'attributes' => array_merge(['class' => 'te-nav'], $attributes),
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
            $this->addItems[$name] = $item[$name];
        }

    }

    public function checkItemExists($name, $includeAddItems = true)
    {
        $itemsToCheck = array_merge($this->items, $this->addItems);
        return isset($itemsToCheck[$name]);
    }

    public function getNav($render = false, $activeKey = null, $returnKeys = [], $excludeKeys = [])
    {
        $this->nav = $this->navStructure($returnKeys, $excludeKeys);

        $this->limitDisplayNameLength();

        if ($render) {
            return $this->renderNav($activeKey);
        }

        return $this->nav;
    }

    protected function limitDisplayNameLength()
    {
        $this->nav = array_map(function($item){
            $item['display'] = Util::getStringSnippet(
                $item['display'], 20, true
            );
            return $item;
        }, $this->nav);
    }

    protected function renderNav($activeKey = null)
    {
        $this->addActiveClass($activeKey);

        return Util::getTemplateContents(
            $this->bc, 
            $this->navRenderPath, 
            $this->nav
        );
    }

    protected function addActiveClass($key)
    {
        if (!isset($this->nav[$key])) {
            return;
        }

        $this->nav[$key]['attributes']['class'] .= ' ' . $this->navActiveClass;
    }
    
    public function setNavRenderPath($path)
    {
        $this->navRenderPath = $path;
        return $this;
    }
    
    public function setNavActiveClass($class)
    {
        $this->navActiveClass = $class;
        return $this;
    }

    public function setAddItemsLast($bool = true)
    {
        $this->addItemsLast = (bool) $bool;
    }
}