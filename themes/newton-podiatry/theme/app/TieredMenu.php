<?php

use Timber\Site;

Class TieredMenu extends Site {
    public function generate_tiers($current_menu) {
        $menu_array = wp_get_nav_menu_items($current_menu);
        if(empty($menu_array)) return [];
        $tiers = array(
            'tier1' => array_filter($menu_array, function($item) {
                return $item->menu_item_parent == 0;
            }),
            'tier2' => array(),
            'tier3' => array(),
            'tier4' => array(),
        );
    
        $tier1Keys = array_column($tiers['tier1'], 'ID');
        $tier2Items = array_filter($menu_array, function($item) use ($tier1Keys) {
            return in_array($item->menu_item_parent, $tier1Keys);
        });
        
        
        // Sort tier2 items by menu_item_parent
        usort($tier2Items, function($a, $b) {
            return $a->menu_order <=> $b->menu_order;
        });
        
        // Assign sorted items to tier2 in tiers array
        $tiers['tier2'] = $tier2Items;
    
    
        foreach($tiers as $key => $array){
            if($key == 'tier1') continue;
    
            $groupedItems = [];
    
            foreach ($array as $item) {
                $category = $item->menu_item_parent;
                if (!isset($groupedItems[$category])) {
                    $groupedItems[$category] = [];
                }
                $groupedItems[$category][] = $item;
            }
            $tiers[$key] = $groupedItems;
        }
    
        // Add has_children property
        foreach ($tiers['tier1'] as &$item) {
            $item->has_children = !empty($tiers['tier2'][$item->ID]);
        }
    
        foreach ($tiers['tier2'] as &$group) {
            foreach ($group as &$item) {
                $item->has_children = !empty($tiers['tier3'][$item->ID]);
            }
        }

    
        return $tiers;
    }
}