<div class="wrap container">
    <div class="wrap sos-wrap">
        <div class="container">
        
            <div id="main_title">
                <h1>Plugin Optimizer</h1>
                <h2 id="name_page" class="$class">Overview</h2>
            </div>

            <div class="sos-content">
            
                <?php if( sospo_mu_plugin()->has_premium ){ ?>
            
                <div id="premium_bootcamp" class="bootcamp">
                
                    <div id="premium_bootcamp_header" class="bootcamp_header opened">
                    
                        <h2 class="bootcamp_title">Premium Bootcamp</h2>
                        <span class="toggler is_opened">Hide</span>
                        <span class="toggler is_closed">Show</span>
                        
                    </div>
                    
                    <div id="premium_bootcamp_content" class="bootcamp_content">
                        
                        <?php
                            include("parts/overview/content-premium_bootcamp.php");
                            
                            $tabs = SOSPO_Admin_Overview_Premium_Bootcamp::get_tabs();
                            
                            include("parts/overview/display_tabs.php");
                        ?>
                        
                    </div>
                    
                </div>
                
                <?php } ?>
            
                <div id="free_bootcamp" class="bootcamp">
                
                    <div id="free_bootcamp_header" class="bootcamp_header opened">
                    
                        <h2 class="bootcamp_title">FREE Bootcamp</h2>
                        <span class="toggler is_opened">Hide</span>
                        <span class="toggler is_closed">Show</span>
                        
                    </div>
                    
                    <div id="free_bootcamp_content" class="bootcamp_content">
                        
                        <?php
                            include("parts/overview/content-free_bootcamp.php");
                            
                            $tabs = SOSPO_Admin_Overview_Free_Bootcamp::get_tabs();
                            
                            include("parts/overview/display_tabs.php");
                        ?>
                        
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>