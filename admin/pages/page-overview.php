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
                    
                    foreach( $tabs as $index => $tab ){
                        
                        echo <<<EOF
        
                    <div class="tab tab-overview" id="tab_{$index}">
                        <div class="info-content">
                            <span class="info-passage"></span>
                            <span class="title">{$tab["title"]}</span>
                        </div>
                        <span class="trigger trigger_exit"></span>
                    </div>
                    <div class="container hidden-info_overview">
                        <div class="container">
                        {$tab["content"]}
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