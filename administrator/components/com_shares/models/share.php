<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Methods supporting a list of search terms.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_search
 * @since       1.6
 */
class SharesModelShare extends JModelAdmin
{
    protected $text_prefix = 'COM_SHARES';

    public $typeAlias = 'com_shares.share';
    /**
     * Constructor.
     *
     * @param   array  An optional associative array of configuration settings.
     * @see     JController
     * @since   1.6
     */


    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   1.6
     */


    protected function prepareTable($table)
    {
        // Set the publish date to now
        $db = $this->getDbo();

    }

    public function getTable($type = 'Shares', $prefix = 'SharesTable', $config = array())
    {

        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($pk = null)
    {

        if ($item = parent::getItem($pk))
        {
            // Convert the params field to an array.

        }
        // Load associated content items
        $app = JFactory::getApplication();


        return $item;
    }


    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_shares.share', 'share', array('control' => 'jform', 'load_data' => $loadData));
        //var_dump($form);
        if (empty($form))
        {
            return false;
        }
        $jinput = JFactory::getApplication()->input;

        // The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
        if ($jinput->get('a_id'))
        {
            $id = $jinput->get('a_id', 0);
        }
        // The back end uses id so we use that the rest of the time and set it to 0 by default.
        else
        {
            $id = $jinput->get('id', 0);
        }
        // Determine correct permissions to check.
        if ($this->getState('article.id'))
        {
            $id = $this->getState('article.id');
            // Existing record. Can only edit in selected categories.
        }
        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_shares.edit.share.data', array());

        if (empty($data))
        {
            $data = $this->getItem();

            // Prime some default values.
        }

        $this->preprocessData('com_shares.share', $data);

        return $data;
    }

    public function save($data)
    {
        $db = $this->getDbo();

        $app = JFactory::getApplication();
        $jinput = JFactory::getApplication()->input;

        // The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
        //var_dump($data);

        $datavr = $jinput->get('jform','ARRAY','ARRAY');
        if ($datavr['id']) {

            $query = "DELETE FROM `#__shares_products` WHERE shares_id = ".$datavr['id']." ";
            $db->setQuery($query);
            $result = $db->LoadAssocList();

            if ($datavr['type']!=0) {

                if ($datavr['type']==4) {

                    if ($datavr['typesearch']==1) {
                        foreach($datavr['selectedcategory'] as $category) {
                            $oldarray = $datavr['selectedbonusproductprice'][$category];
                            asort($datavr['selectedbonusproductprice']['sort'][$category]);
                            $newarray = array();
                            foreach($datavr['selectedbonusproductprice']['sort'][$category] as $key=>$arrayval){
                                $newarray[$key] = $datavr['selectedbonusproductprice'][$category][$key];
                            }
                            $oldarray = json_encode($newarray);
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`, `bonusproducts`, `ordering`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($category).", ".$db->quote(json_encode($datavr['selectedbonusproductprice'][$category])).", ".$db->quote(json_encode($datavr['selectedbonusproductprice']['sort'][$category]))." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==2) {
                        foreach($datavr['selectedbrend'] as $brend) {
                            $oldarray = $datavr['selectedbonusproductprice'][$brend];
                            asort($datavr['selectedbonusproductprice']['sort'][$brend]);
                            $newarray = array();
                            foreach($datavr['selectedbonusproductprice']['sort'][$brend] as $key=>$arrayval){
                                $newarray[$key] = $datavr['selectedbonusproductprice'][$brend][$key];
                            }
                            $oldarray = json_encode($newarray);
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`, `brend_id`, `bonusproducts`, `ordering`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).", ".$db->quote(json_encode($datavr['selectedcategory'][$brend])).",  ".$db->quote($brend).", ".$db->quote(json_encode($datavr['selectedbonusproductprice'][$brend])).", ".$db->quote(json_encode($datavr['selectedbonusproductprice']['sort'][$brend]))." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==3) {
                        foreach($datavr['selectedproduct'] as $product) {
                            $oldarray = $datavr['selectedbonusproductprice'][$product];
                            asort($datavr['selectedbonusproductprice']['sort'][$product]);
                            $newarray = array();
                            foreach($datavr['selectedbonusproductprice']['sort'][$product] as $key=>$arrayval){
                                $newarray[$key] = $datavr['selectedbonusproductprice'][$product][$key];
                            }
                            $oldarray = json_encode($newarray);
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `product_id`, `bonusproducts`, `ordering`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($product).", ".$db->quote($oldarray).", ".$db->quote(json_encode($datavr['selectedbonusproductprice']['sort'][$product]))." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                }

                if ($datavr['type']==2) {

                    if ($datavr['typesearch']==1) {
                        foreach($datavr['selectedcategory'] as $category) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`, `bonusproducts`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($category).", ".$db->quote(json_encode($datavr['selectedbonusproductprice'][$category]))." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==2) {
                        foreach($datavr['selectedbrend'] as $brend) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`, `brend_id`, `bonusproducts`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).", ".$db->quote(json_encode($datavr['selectedcategory'][$brend])).",  ".$db->quote($brend).", ".$db->quote(json_encode($datavr['selectedbonusproductprice']))." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==3) {
                        foreach($datavr['selectedproduct'] as $product) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `product_id`, `bonusproducts`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($product).", ".$db->quote(json_encode($datavr['selectedbonusproductprice'][$product]))." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                }

                if ($datavr['type']==6) {

                    if ($datavr['typesearch']==1) {
                        foreach($datavr['selectedcategory'] as $category) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`, `main_product_price`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($category).", ".$db->quote($datavr['mainproductprice'][$category])." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==2) {
                        foreach($datavr['selectedbrend'] as $brend) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`, `brend_id`, `main_product_price`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).", ".$db->quote(json_encode($datavr['selectedcategory'][$brend])).",  ".$db->quote($brend).", ".$db->quote(json_encode($datavr['mainproductprice']))." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==3) {
                        foreach($datavr['selectedproduct'] as $product) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `product_id`, `main_product_price`,`mainordering`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($product).", ".$db->quote($datavr['mainproductprice'][$product]).", ".$db->quote($datavr['productsort']['sort'][$product])." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                }

                if ($datavr['type']==7) {

                    if ($datavr['typesearch']==1) {
                        foreach($datavr['selectedcategory'] as $category) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($category)." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==2) {
                        foreach($datavr['selectedbrend'] as $brend) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`, `brend_id`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).", ".$db->quote(json_encode($datavr['selectedcategory'][$brend])).",  ".$db->quote($brend)." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==3) {
                        foreach($datavr['selectedproduct'] as $product) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `product_id`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($product)." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                }

                if ($datavr['type']==1) {

                    if ($datavr['typesearch']==1) {
                        foreach($datavr['selectedcategory'] as $category) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($category)." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==2) {
                        foreach($datavr['selectedbrend'] as $brend) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `category_id`, `brend_id`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).", ".$db->quote(json_encode($datavr['selectedcategory'][$brend])).",  ".$db->quote($brend)." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                    if ($datavr['typesearch']==3) {
                        foreach($datavr['selectedproduct'] as $product) {
                            $query = "INSERT INTO `#__shares_products` (`shares_id`, `typesearch`, `product_id`) VALUES (".$datavr['id'].", ".$db->quote($datavr['typesearch']).",  ".$db->quote($product)." )  ";
                            $db->setQuery($query);
                            $db->query();
                        }
                    }

                }

            }
        }

        if (parent::save($data))
        {

            return true;
        }

        return false;
    }


    protected function preprocessForm(JForm $form, $data, $group = 'content')
    {
        // Association content items
        $app = JFactory::getApplication();

        parent::preprocessForm($form, $data, $group);
    }

    public function publish(&$pks,$value)
    {

        $pks = (array) $pks;

        $db = $this->getDbo();

        $query = "UPDATE `#__shares` SET `enabled` = ".$value." WHERE `id` in (".implode(',',$pks).") ";
        $db->setQuery($query);
        $db->query();

        return true;
    }

    /**
     * Method to reset the add shares.
     *
     * @return  boolean
     * @since   1.6
     */
    /*public function save($data)
    {
        $app = JFactory::getApplication();

        if (isset($data['images']) && is_array($data['images']))
        {
            $registry = new JRegistry;
            $registry->loadArray($data['images']);
            $data['images'] = (string) $registry;
        }

        if (isset($data['urls']) && is_array($data['urls']))
        {

            foreach ($data['urls'] as $i => $url)
            {
                if ($url != false && ($i == 'urla' || $i == 'urlb' || $i == 'urlc'))
                {
                    $data['urls'][$i] = JStringPunycode::urlToPunycode($url);
                }

            }
            $registry = new JRegistry;
            $registry->loadArray($data['urls']);
            $data['urls'] = (string) $registry;
        }

        // Alter the title for save as copy
        if ($app->input->get('task') == 'save2copy')
        {
            list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
            $data['title'] = $title;
            $data['alias'] = $alias;
            $data['state'] = 0;
        }

        if (parent::save($data))
        {

            if (isset($data['featured']))
            {
                $this->featured($this->getState($this->getName() . '.id'), $data['featured']);
            }

            $assoc = JLanguageAssociations::isEnabled();
            if ($assoc)
            {
                $id = (int) $this->getState($this->getName() . '.id');
                $item = $this->getItem($id);

                // Adding self to the association
                $associations = $data['associations'];

                foreach ($associations as $tag => $id)
                {
                    if (empty($id))
                    {
                        unset($associations[$tag]);
                    }
                }

                // Detecting all item menus
                $all_language = $item->language == '*';

                if ($all_language && !empty($associations))
                {
                    JError::raiseNotice(403, JText::_('COM_CONTENT_ERROR_ALL_LANGUAGE_ASSOCIATED'));
                }

                $associations[$item->language] = $item->id;

                // Deleting old association for these items
                $db = JFactory::getDbo();
                $query = $db->getQuery(true)
                    ->delete('#__associations')
                    ->where('context=' . $db->quote('com_content.item'))
                    ->where('id IN (' . implode(',', $associations) . ')');
                $db->setQuery($query);
                $db->execute();

                if ($error = $db->getErrorMsg())
                {
                    $this->setError($error);
                    return false;
                }

                if (!$all_language && count($associations))
                {
                    // Adding new association for these items
                    $key = md5(json_encode($associations));
                    $query->clear()
                        ->insert('#__associations');

                    foreach ($associations as $id)
                    {
                        $query->values($id . ',' . $db->quote('com_content.item') . ',' . $db->quote($key));
                    }

                    $db->setQuery($query);
                    $db->execute();

                    if ($error = $db->getErrorMsg())
                    {
                        $this->setError($error);
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }*/

}
