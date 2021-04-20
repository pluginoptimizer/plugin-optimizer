<div class="wrap container">
    <div class="wrap sos-wrap">
        <div class="container">
        
            <div id="main_title">
                <h1>Plugin Optimizer</h1>
                <h2 id="name_page" class="$class">Overview</h2>
            </div>

            <div class="sos-content">
            
                <div id="overview_summary" class="bootcamp">
                
                    <div class="first_half">
                    
                    <?php if( sospo_mu_plugin()->has_premium ){ ?>
                        
                        <div>Thank you for buying Premium!</div>
                        <p>You currently have X premium filters.</p>
                        <p>There are currently X premium filters available for your site.</p>
                        
                    <?php } else { ?>
                        
                        <div>Get Premium!</div>
                        <p>You could benefit from X premium filters available for your site.</p>
                        
                    <?php } ?>
                    
                    </div>
                    
                    <div class="second_half">
                    
                    <?php if( sospo_mu_plugin()->has_premium ){ ?>
                        
                    <?php } else { ?>
                        
                    <?php } ?>
                    
                        <div>Knowledgebase</div>
                        <p><a href="/">Contact Support</a></p>
                        <p><a href="/">Read FAQ</a></p>
                        
                    </div>
                    
                </div>
                
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