<?php
/**
 * Tree class for phpMyFAQ category implementation based on the SPL 
 * RecursiveIteratorIterator class to iterate through recursive iterators.
 * 
 * For more information about the RecursiveIteratorIterator class: 
 * http://www.php.net/manual/en/class.recursiveiteratoriterator.php
 *
 * PHP Version 5.2.0
 * 
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * @category  phpMyFAQ
 * @package   PMF_Category
 * @author    Johannes Schlüter <johannes@schlueters.de>
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2010 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/MPL-1.1.html Mozilla Public License Version 1.1
 * @link      http://www.phpmyfaq.de
 * @since     2001-01-05
 */

/**
 * PMF_Category_Tree
 * 
 * @category  phpMyFAQ
 * @package   PMF_Category
 * @author    Johannes Schlüter <johannes@schlueters.de>
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2010 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/MPL-1.1.html Mozilla Public License Version 1.1
 * @link      http://www.phpmyfaq.de
 * @since     2001-01-05
 */
class PMF_Category_Tree extends IteratorIterator implements RecursiveIterator
{
    /**
     * Parent category object
     *
     * @var PMF_Category
     */
    private $parent = null;
    
    /**
     * Constructor
     *
     * @param PMF_Category $parent Parent PMF_Category object
     * 
     * @return void
     */
    public function __construct(PMF_Category $parent = NULL)
    {
        $dataProvider = new PMF_Category_Tree_DataProvider();
        $parentId     = $parent ? (int)$parent->getId() : 0;
        $resultset    = $dataProvider->getData($parentId);
        parent::__construct($resultset);
        $this->parent = $parent;
    }
    
    /**
     * Previous element
     *
     * @return void
     */
    public function rewind()
    {
        parent::rewind();
        $this->setCurrent();
    }

    /**
     * Next element
     *
     * @return void
     */
    public function next()
    {
        parent::next();
        $this->setCurrent();
    }
    
    /**
     * Set current element
     *
     * @return void
     */
    private function setCurrent()
    {
        if ($current = parent::current()) {
            $this->current = new PMF_Category(parent::current(), $this->parent);
        } else {
            $this->current = null;
        }
    }

    /**
     * Returns the key of the element
     *
     * @return integer
     */
    public function key()
    {
        return $this->current()->getId();
    }
    
    /**
     * Returns current element
     *
     * @return mixed
     */
    public function current()
    {
        return $this->current;
    }
    
    /**
     * Returns if an iterator can be created fot the current entry.
     *
     * @return boolean
     */
    public function hasChildren()
    {
        return $this->current()->hasChildren();
    }
    
    /**
     * Returns an iterator for the current entry.
     *
     * @return PMF_Category_Tree
     */
    public function getChildren()
    {
        return new self($this->current());
    }
}