jQuery(function($){
    
    $('body').append('<div id="po_please_wait"><div id="po_please_wait_message">Please wait...</div></div>');
    
    
    // TODO If we need to clean the menu we're getting, this would be the best spot, before we grab it from the DOM
    
    
    if( po_object.alphabetize_menu ){
        
        // remove separators
        $('#adminmenu > li.wp-menu-separator:not(:eq(0))').remove();
        
        // define items we don't want to sort
        let $items_to_exclude = $('#adminmenu > li#menu-dashboard');
        $items_to_exclude.add( $('#adminmenu > li#collapse-menu') );
        
        // define items to sort
        let $items_to_sort = $('#adminmenu > li').not( $items_to_exclude )
        
        // sort the items
        let $sorted_items = $items_to_sort.sort(function( a, b ){
            
            let title_a = $( a ).children('a').children('.wp-menu-name').text().toUpperCase();
            let title_b = $( b ).children('a').children('.wp-menu-name').text().toUpperCase();
            
            return title_a.localeCompare( title_b );
            
        });
        
        // move the sorted items
        $sorted_items.insertBefore('#adminmenu li#collapse-menu');
        
    }
    
    
    // grab the menu html
    let menu_html = $('#adminmenu').html();
    
    // save the menu in wp_options
    $.post( po_object.ajax_url, { action  : 'po_save_original_menu', menu_html : menu_html }, function( response ) {
        
        $('#po_please_wait_message').html('Refreshing...');
        
        if( po_object.redirect_to ){
            
            console.log( "Redirecting to the original page..." );
            window.location.href = po_object.redirect_to;
        }
        
    }, "json");
    
    
});