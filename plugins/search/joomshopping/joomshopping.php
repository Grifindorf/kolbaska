<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
error_reporting(E_ALL & ~E_NOTICE);

jimport('joomla.plugin.plugin');

/*JPlugin::loadLanguage( 'plg_search_joomshopping' );*/

class plgSearchJoomshopping extends JPlugin {


    function onContentSearchAreas(){
        static $areas = array(
            'joomshopping' => 'JoomShopping'
        );
        return $areas;
    }


    function onContentSearch( $text, $phrase='', $ordering='', $areas=null )
    {
        require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
        require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');      
        
        $db =  JFactory::getDBO();
        $user =  JFactory::getUser();
        $lang = JSFactory::getLang();
        
        if (is_array($areas)) {
            if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
                return array();
            }
        }
        
        $limit = $this->params->def( 'search_limit', 50 );

        $text = trim( $text );
        if ($text == '') {
            return array();
        }

        switch ( $ordering ) {
            case 'alpha':
                $order = "prod.`".$lang->get('name')."` ASC";
                break;          
            case 'newest':
                $order = "prod.`product_date_added` DESC";
                break; 
            case 'oldest':
                $order = "prod.`product_date_added` ASC";
                break;     
            case 'popular':
                $order = "prod.`hits` DESC";
                break;                                    
            case 'category':
                $order = 'cat.`'.$lang->get('name').'` ASC, prod.`'.$lang->get('name').'` ASC';
                break;
            default:
                $order = 'prod.product_id DESC';
        }
        
        switch ($phrase) {
            case 'exact':
                $text        = $db->Quote( '%'.$db->escape( $text, true ).'%', false );
                $wheres2     = array();
                $wheres2[]     = "prod.`".$lang->get('name')."` LIKE ".$text;
                $wheres2[]     = "prod.`".$lang->get('short_description')."` LIKE ".$text;
                $wheres2[]     = "prod.`".$lang->get('description')."` LIKE ".$text;
                $wheres2[]     = "prod.product_ean LIKE ".$text;
                $where         = '(' . implode( ') OR (', $wheres2 ) . ')';
            break;

            case 'all':
            case 'any':
            default:
                $words = explode( ' ', $text );
                $wheres = array();
                foreach ($words as $word) {
                    $word        = $db->Quote( '%'.$db->escape( $word, true ).'%', false );
                    
                    $wheres2     = array();
                    $wheres2[]     = "prod.`".$lang->get('name')."` LIKE ".$word;
                    $wheres2[]     = "prod.`".$lang->get('short_description')."` LIKE ".$word;
                    $wheres2[]     = "prod.`".$lang->get('description')."` LIKE ".$word;
                    $wheres2[]     = "prod.product_ean LIKE ".$word;
                    $wheres[]     = implode( ' OR ', $wheres2 );
                }
                $where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
            break;
        }
        
        $query = "SELECT prod.product_id AS slug, pr_cat.category_id AS catslug, prod.`".$lang->get('name')."` as title, 
                  CONCAT(prod.`".$lang->get('short_description')."`,' ',prod.`".$lang->get('description')."`) as text, 
                  '2' AS browsernav,
                  prod.product_date_added AS created,
                  cat.`".$lang->get('name')."` AS section
                  FROM `#__jshopping_products` AS prod
                  LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
                  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id                  
                  WHERE ($where) AND prod.product_publish = '1' AND cat.category_publish='1'                
                  GROUP BY prod.product_id
                  ORDER BY $order 
                  ";    
        
        $db->setQuery( $query, 0, $limit );
        $rows = $db->loadObjectList();
        
        if ($rows){
            foreach($rows as $key => $row) {
                $rows[$key]->href = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$row->catslug.'&product_id='.$row->slug, 1);
            }
        }
        return $rows;
    }
}
?>