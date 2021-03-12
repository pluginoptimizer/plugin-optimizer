jQuery(function($){
   
    // console.log( window.location.href );
    console.log( "Immediately", $.fn.jquery );
    // alert( $.fn.jquery );
    
    // Are we replacing the menu?
    if( po_object.original_menu ){
        
        // ----- replace the menu
        
        $('#adminmenu').html( po_object.original_menu );
        
        
        // ----- find the current menu items
        
        let current_url             = window.location.href;
        let $current_menu_item      = false;
        let $current_menu_sub_item  = false;
        
        // find the current menu sub_item
        $('#adminmenu > li > ul.wp-submenu > li > a').each(function(){
            
            let link_url = $(this).attr("href");
            
            if( current_url.endsWith( link_url ) ){
                
                console.log( "Current submenu item: ", $(this).attr("href") );
                
                $current_menu_sub_item = $(this).parent();
                
                return;
            }
            
        });
        
        // find the current menu item
        $('#adminmenu > li > a').each(function(){
            
            let link_url = $(this).attr("href");
            
            if( current_url.endsWith( link_url ) ){
                
                console.log( "Current menu item: ", $(this).attr("href") );
                
                $current_menu_item = $(this).parent();
                
                return;
            }
            
        });
        
        // if current menu item not found, use the parent of the sub menu item
        if( ! $current_menu_item && $current_menu_sub_item ){
            
            $current_menu_item = $current_menu_sub_item.parents('li.menu-top');
        }
        
        // ----- remove the incorrect menu classes
        
        $('#adminmenu > li > ul.wp-submenu > li').removeClass("current");
        $('#adminmenu > li').removeClass("wp-has-current-submenu wp-menu-open").addClass("wp-not-current-submenu");
        
        // ----- add correct menu classes
        
        if( $current_menu_item ){
            
            $current_menu_item.addClass("wp-has-current-submenu wp-menu-open").removeClass("wp-not-current-submenu");
            
            if( $current_menu_sub_item ){
                
                $current_menu_sub_item.addClass("current");
            }
        }
        
    }
    
    // show the menu
    $('#adminmenu').show();
    
});