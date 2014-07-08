/**
 * @package Bullet FAQs
 */
/*
Plugin Name: Bullet FAQs
Plugin URI: http://bappi-d-great.com
Description: Provides nice Frequently Asked Questions Page with answers hidden untill the question is clicked then the desired answer fades smoothly into view, like accordion. User will have options to add categories, and questions based on those categories. Users can show question from a single category using shortcode. They will have control to change theme (among 9 themes), animation speed and custom CSS.
Version: 1.0
Author: Bappi D Great
Author URI: http://bappi-d-great.com
License: GPLv2 or later
*/
;(function($) {
    
    //Options for user
    var defaults = {
            animation:      'slide',
            expandAll:    true,
            animationSpeed: 500,         //The lower the value, the faster the animation
            theme:          'theme-1',
            showCategory:   false
        }
        
    $.fn.faqAccordion = function(options) {
        
        var _this = this,
            config = $.extend({}, defaults, options);
        
        /*
         * Main Function
         */
        function init() {
            /*
             * If "Expand all rows" is enabled
             */
            if(config.expandAll)
                _this
                    .find('.accordion_title')
                    .on('click', function() {
                        $(this).next('.smartItemsDetails').slideToggle(config.animationSpeed);
                    })
                    .addClass(config.theme);
            /*
             * If "Expand all rows" is disabled
             */
            else
                _this
                    .find('.accordion_title')
                    .on('click', function() {
                        var obj = $(this).next('.smartItemsDetails');
                        if(obj.is(':visible'))
                            obj.slideUp(config.animationSpeed);
                        else{
                            $(this)
                                .closest('.accod_parent')
                                    .find('.smartItemsDetails')
                                    .slideUp(config.animationSpeed);
                            obj.slideDown(config.animationSpeed);
                        }
                    })
                    .addClass(config.theme);
            
            /*
             * Scroll animation for category filtering
             */
            $('.faq-labels li a').on('click', function(e) {
                e.preventDefault();
                scrollById($(this).attr('href'), config.animationSpeed);
                
            });
            
            /*
             * If FAQ from all Categories are showed
             */
            if(config.showCategory) {
                $('.faq-cat-title')
                    .append('<a href="#faq-top">Back to top</a>')
                    .find('a')
                    .on('click', function(e) {
                        e.preventDefault();
                        scrollById($(this).attr('href'), config.animationSpeed);
                    });
            }
            
        }
        
        /*
         * Scroll to any point function
         */
        function scrollById(id, speed) {
            $('html,body')
                .animate({
                    scrollTop: $(id).offset().top - 30
                },
                speed
            );
        }
        
        /*
         * Calling the main function
         */
        return this.each(function() {
            init();
        });
        
    }
    
})(jQuery);