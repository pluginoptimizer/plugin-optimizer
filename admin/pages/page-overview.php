<?php global $sospo_appsero; ?>
<div class="wrap container">
    <div class="wrap sos-wrap">
        <div class="container">
        
            <div id="main_title">
                <h1>Plugin Optimizer</h1>
                <h2 id="name_page" class="$class">Overview</h2>
            </div>

            <div class="sos-content">
            
                <div id="scan-container">
                    <button id="scan-now">Scan Now</button> Scan now to see how many Plugin Optimizer Premium filters we have that match your site.

                </div>

                <div id="overview_summary" class="bootcamp">
                
                    <?php if( sospo_mu_plugin()->has_premium ){ ?>
                    
                        <div class="summary_row" id="licence_section">
                        
                            <?php $sospo_appsero["premium"]->license()->menu_output() ?>
                            
                            <?php $validity_class = $sospo_appsero["premium"]->license()->is_valid() ? "valid" : "invalid"; ?>
                            
                            <script>// Fixes:
                                
                                // WP automatically moves .notice to the top with javascript (!)
                                jQuery('.notice.appsero-license-section').addClass('inline');
                                
                                // We can asign a class only after license()->menu_output()
                                jQuery('#licence_section').addClass('<?php echo $validity_class; ?>');
                            </script>
                        </div>
                    
                    <?php } ?>
                    
                    <div class="summary_row" id="licence_teaser">
                    
                        <div class="half first_half">
                        
                            <?php if( sospo_mu_plugin()->has_premium ){ ?>
                            
                                <?php if( $sospo_appsero["premium"]->license()->is_valid() ){ ?>
                                    
                                    <div>Thank you for buying Premium!</div>
                                    <p>You currently have X premium filters.</p>
                                    <p>There are currently X premium filters available for your site.</p>
                                    
                                <?php } else { ?>
                                    
                                    <div>Please activate your license.</div>
                                    <p>You could benefit from X premium filters available for your site.</p>
                                    
                                <?php } ?>
                            
                            <?php } else { ?>
                                
                                <div>Get Premium!</div>
                                <p>You could benefit from X premium filters available for your site.</p>
                                
                            <?php } ?>
                        
                        </div>
                    
                        <div class="half second_half">
                        
                            <div>Knowledgebase</div>
                            <p><a href="/">Contact Support</a></p>
                            <p><a href="/">Read FAQ</a></p>
                            
                        </div>
                    
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
<script>
    jQuery(document).ready(function($){

        $('#scan-now').on('click', function(){
            $.ajax({
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    action: 'po_scan_prospector'
                },
                beforeSend: function(){
                    $('#scan-container .results').remove();
                },
                success: function(d){
                    if( d.status == 'success' ){
                        $('#scan-container').append('<div class="results">Your site could benefit from '+d.data.count+' Premium Filters.</div>');
                    }
                }, 
                complete: function(){

                }
            })
        });

    })
</script>