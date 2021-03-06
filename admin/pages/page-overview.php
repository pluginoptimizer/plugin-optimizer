<div class="wrap container">
    <div class="wrap sos-wrap">
        <div class="container">
            <div class="row col-12">
                <h1>Plugin Optimizer</h1>
            </div>
            <div class="row col-12">
                <h2 id="name_page" class="filters">Overview</h2>
            </div>
            <div class="row sos-content">
                <div class="col-12">
                
                <?php
                    
                    require_once("page-overview-content.php");
                    
                    $tabs = [
                        10 => [
                            "title"     => "Plugin Registration",
                            "content"   => PO_Admin_Overview::tab_1()
                        ],
                        20 => [
                            "title"     => "Creating a Category Wizard",
                            "content"   => PO_Admin_Overview::tab_2()
                        ],
                        30 => [
                            "title"     => "Creating a Group Wizard",
                            "content"   => PO_Admin_Overview::tab_3()
                        ],
                        40 => [
                            "title"     => "Creating a Filter Wizard",
                            "content"   => PO_Admin_Overview::tab_4()
                        ],
                        50 => [
                            "title"     => "Paid installation section",
                            "content"   => PO_Admin_Overview::tab_5()
                        ],
                    ];
                    
                    $tabs = apply_filters( "sos_po_overview_tabs", $tabs );
                    
                    $completed_tabs = [ 10, 20, 25 ];// TODO read the array from wp_options
                    
                    foreach( $tabs as $index => $tab ){
                        
                        $tabs[ $index ]["completed"] = in_array( $index, $completed_tabs );
                    }
                    
                    $one_tab_opened = false;
                    
                    foreach( $tabs as $index => $tab ){
                        
                        $class_completed = $tab["completed"] ? " done" : "";
                        $button_complete = $tab["completed"] ? "" : '<div><button class="po_green_button mark_tab_complete">Mark Complete</button></div>';
                        $class_opened    = "trigger_closed";
                        $content_opened  = "closed";
                        
                        if( ! $tab["completed"] && ! $one_tab_opened ){
                            $class_opened    = "trigger_opened";
                            $content_opened  = "opened";
                            $one_tab_opened  = true;
                        }
                        
                        echo <<<EOF
        
                    <div class="tab tab-overview" id="tab_{$index}">
                        <div class="info-content">
                            <span class="info-passage{$class_completed}"></span>
                            <span class="title">{$tab["title"]}</span>
                        </div>
                        <span class="trigger {$class_opened}"></span>
                    </div>
                    <div class="container hidden-info_overview {$content_opened}">
                        <div class="container">
                            {$tab["content"]}
                            {$button_complete}
                        </div>
                    </div>
                    
EOF;
                    }
                ?>
                
                </div>
            </div>
        </div>
    </div>
</div>