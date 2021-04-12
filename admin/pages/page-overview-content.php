<?php
class SOSPO_Admin_Overview{
    
    static function get_tabs(){
        
        $tabs = [
            10 => [
                "title"     => "Plugin Optimizer Overview",
                "content"   => SOSPO_Admin_Overview::tab_1()
            ],
            20 => [
                "title"     => "Setup Your First Filter",
                "content"   => SOSPO_Admin_Overview::tab_2()
            ],
            30 => [
                "title"     => "How To Setup A Group",
                "content"   => SOSPO_Admin_Overview::tab_3()
            ],
            40 => [
                "title"     => "Organizing with Categories",
                "content"   => SOSPO_Admin_Overview::tab_4()
            ],
            50 => [
                "title"     => "How To Use Wildcards",
                "content"   => SOSPO_Admin_Overview::tab_5()
            ],
            60 => [
                "title"     => "EndPoints Vs PostTypes",
                "content"   => SOSPO_Admin_Overview::tab_5()
            ],
            70 => [
                "title"     => "Mistakes To Avoid",
                "content"   => SOSPO_Admin_Overview::tab_5()
            ],
            80 => [
                "title"     => "Testing Your Filters",
                "content"   => SOSPO_Admin_Overview::tab_5()
            ],
            90 => [
                "title"     => "Get It Done For You",
                "content"   => SOSPO_Admin_Overview::tab_5()
            ],
        ];
        
        return $tabs;
    }
    
    static function tab_1(){
        
        ob_start();
        
        ?><div class="justify-content-between">
            <div class="col-6">
                <div class="yt_video_wrapper" style="--aspect-ratio: 315 / 560;" style="background-image: url(https://via.placeholder.com/700x400.png?text=Video+Missing);">
                    <iframe src="https://www.youtube.com/embed/-x4xg59uJn0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-6">
                <h4>A Quick Run Through of Plugin Optimizer</h4>
                <p>Speed up WordPress by only loading the Plugins you need for each page.</p>
                <p>Optimizing your WordPress site with Plugin Optimizer is easy and can reduce load times by as much as 90%</p>
            </div>
        </div><?php
        
        return ob_get_clean();
    }
    
    static function tab_2(){
        
        ob_start();
        
        ?><div class="justify-content-between">
            <div class="col-6">
                <div class="yt_video_wrapper" style="--aspect-ratio: 315 / 560;">
                    <iframe src="https://www.youtube.com/embed/j_xsWpCj__A" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-6">
                <h4>Let’s set up your first filter!</h4>
                <p>Get started with something easy and then tinker from there!</p>
            </div>
        </div><?php
        
        return ob_get_clean();
    }
    
    static function tab_3(){
        
        ob_start();
        
        ?><div class="justify-content-between">
            <div class="col-6">
                <div class="yt_video_wrapper" style="--aspect-ratio: 315 / 560;">
                    <iframe src="https://www.youtube.com/embed/ZDayBkmYRXc" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-6">
                <h4>Make creating filters easier by using Filter Groups</h4>
                <p>Using Filter Groups you can create new Filters with ease.</p>
            </div>
        </div><?php
        
        return ob_get_clean();
    }
    
    static function tab_4(){
        
        ob_start();
        
        ?><div class="justify-content-between">
            <div class="col-6">
                <div class="yt_video_wrapper" style="--aspect-ratio: 315 / 560;">
                    <iframe src="https://www.youtube.com/embed/31ZIMIP_zI0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-6">
                <h4>Make your first Category and stay organized</h4>
                <p>Staying organized is easy by using Categories</p>
            </div>
        </div><?php
        
        return ob_get_clean();
    }
    
    static function tab_5(){
        
        ob_start();
        
        ?><div class="justify-content-between">
            <div class="col-6">
                <div class="yt_video_wrapper" style="--aspect-ratio: 315 / 560; background-image: url(https://via.placeholder.com/700x400.png?text=Video+Missing);">
                <?php /*
                    <iframe src="https://www.youtube.com/embed/-x4xg59uJn0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                */ ?>
                </div>
            </div>
            <div class="col-6">
                <h4>Learn how wildcards work with Plugin Optimizer</h4>
                <p>Wildcards can be powerful tools, however they can also cause us some trouble. Here’s how to use them correctly in your Filters.</p>
            </div>
        </div><?php
        
        return ob_get_clean();
    }
    
    static function tab_6(){
        
        ob_start();
        
        ?><div class="justify-content-between">
            <div class="col-6">
                <div class="yt_video_wrapper" style="--aspect-ratio: 315 / 560; background-image: url(https://via.placeholder.com/700x400.png?text=Video+Missing);">
                <?php /*
                    <iframe src="https://www.youtube.com/embed/-x4xg59uJn0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                */ ?>
                </div>
            </div>
            <div class="col-6">
                <h4>aaaaaaaaaaaaaaaa</h4>
                <p>bbbbbbbbbb</p>
            </div>
        </div><?php
        
        return ob_get_clean();
    }
    
    static function tab_7(){
        
        ob_start();
        
        ?><div class="justify-content-between">
            <div class="col-6">
                <div class="yt_video_wrapper" style="--aspect-ratio: 315 / 560; background-image: url(https://via.placeholder.com/700x400.png?text=Video+Missing);">
                <?php /*
                    <iframe src="https://www.youtube.com/embed/-x4xg59uJn0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                */ ?>
                </div>
            </div>
            <div class="col-6">
                <h4>aaaaaaaaaaaaaaaa</h4>
                <p>bbbbbbbbbb</p>
            </div>
        </div><?php
        
        return ob_get_clean();
    }
    
    static function tab_8(){
        
        ob_start();
        
        ?><div class="justify-content-between">
            <div class="col-6">
                <div class="yt_video_wrapper" style="--aspect-ratio: 315 / 560; background-image: url(https://via.placeholder.com/700x400.png?text=Video+Missing);">
                <?php /*
                    <iframe src="https://www.youtube.com/embed/-x4xg59uJn0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                */ ?>
                </div>
            </div>
            <div class="col-6">
                <h4>aaaaaaaaaaaaaaaa</h4>
                <p>bbbbbbbbbb</p>
            </div>
        </div><?php
        
        return ob_get_clean();
    }
    
    static function tab_9(){
        
        ob_start();
        
        ?><div class="justify-content-between">
            <div class="col-6">
                <div class="yt_video_wrapper" style="--aspect-ratio: 315 / 560; background-image: url(https://via.placeholder.com/700x400.png?text=Video+Missing);">
                <?php /*
                    <iframe src="https://www.youtube.com/embed/-x4xg59uJn0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                */ ?>
                </div>
            </div>
            <div class="col-6">
                <h4>aaaaaaaaaaaaaaaa</h4>
                <p>bbbbbbbbbb</p>
            </div>
        </div><?php
        
        return ob_get_clean();
    }
    
}
