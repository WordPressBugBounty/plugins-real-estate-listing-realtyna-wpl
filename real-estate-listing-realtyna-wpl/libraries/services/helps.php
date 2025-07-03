<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Helps Service
 * @author Howard <howard@realtyna.com>
 * @date 9/13/2014
 * @package WPL
 */
class wpl_service_helps
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run()
	{
        // Automatically call WPL Tour on WPL Dashboard
        if(wpl_request::getVar('page') == 'WPL_main_menu' and get_option('wpl_auto_tour', 0))
        {
            wpl_request::setVar('wpltour', 1);
            delete_option('wpl_auto_tour');
        }
        
        // Run WPL Tour
        if(wpl_request::getVar('wpltour')) $this->tour();
        
        // Run WPL Help
        $this->help();
	}
    
    public function help()
    {
    	add_action('current_screen', array($this, 'show_help_tab'), 10);
    }
    
    public function show_help_tab()
    {
    	// Current Page
    	$page = wpl_request::getVar('page', '');
        
        // First Validation
        if(!trim($page)) return false;
        
        $tabs = array();
		if(!$this->file_exists($page, 'helps')) {
			return false;
		}
        $path = _wpl_import('assets.helps.'.$page, true, true);
        if(wpl_file::exists($path)) $tabs = include_once $path;
        
        /** No Help **/
        if(!is_array($tabs) or (is_array($tabs) and !count($tabs))) return false;
        
        // Screen
        $screen = get_current_screen();
        
        foreach($tabs['tabs'] as $tab)
        {
            /** Add Help Tab **/
            $screen->add_help_tab(array('id'=>$tab['id'], 'title'=>$tab['title'], 'content'=>$tab['content']));
        }
        
        if(!isset($tabs['sidebar'])) $tabs['sidebar'] = array('content'=>'<a class="wpl_contextual_help_tour button" href="'.wpl_global::add_qs_var('wpltour', 1).'">'.wpl_esc::return_html_t('Introduce Tour').'</a>');
        $screen->set_help_sidebar($tabs['sidebar']['content']);
    }
    
    public function tour()
    {
        add_action('admin_enqueue_scripts', array($this, 'import_styles_scripts'), 0);
        add_action('admin_print_footer_scripts', array($this, 'show_tips'), 0);
    }
    
    public function import_styles_scripts()
    {
        wp_enqueue_style('wp-pointer');
        wp_enqueue_script('wp-pointer');
    }
    
    public function show_tips()
    {
        $page = wpl_request::getVar('page', '');
        
        /** First Validation **/
        if(!trim($page)) return false;
        
        $tips = array();
        if(!$this->file_exists($page, 'tips')) {
			return false;
		}
        $path = _wpl_import('assets.tips.'.$page, true, true);
        if(wpl_file::exists($path)) $tips = include_once $path;
        
        /** Generate script **/
        $this->generate_scripts($tips);
    }

	private function file_exists($page, $directory) {
		$path = WPL_ABSPATH .DS. 'assets' .DS. $directory;
		$files = wpl_folder::files($path, '.php$');
		return in_array(basename($page) . '.php', $files);
	}
    
    public function generate_scripts($tips = array())
    {
        if(!count($tips)) return false;
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function()
        {
            // Remove wpltour from query strings
            var url = wpl_update_qs('wpltour');
            window.history.pushState('', '', url);
            
            <?php foreach($tips as $key=>$tip): ?>
            /****************************** Tip(<?php wpl_esc::numeric($tip['id']); ?>) ******************************/
            var wpltip<?php wpl_esc::numeric($tip['id']); ?> =
            {
                content: '<?php wpl_esc::e(addslashes($tip['content'])); ?>',
                position:
                {
                    edge: '<?php wpl_esc::js($tip['position']['edge'] ?? 'left'); ?>',
                    align: '<?php wpl_esc::js($tip['position']['align'] ?? 'center'); ?>'
                },
                open: function()
                {
                    <?php if(isset($tip['buttons'][2])): ?>
                    wplj('.wp-pointer-buttons').append('<a class="wpl-pointer-primary button-primary wpl-btn-next"><?php wpl_esc::html($tip['buttons'][2]['label']); ?></a>');
                    wplj('.wpl-pointer-primary').click(function()
                    {
                        wpltip<?php wpl_esc::numeric($tip['id']); ?>.next();
                    });
                    <?php endif; ?>
                    
                    <?php if(isset($tip['buttons'][3])): ?>
                    wplj('.wp-pointer-buttons').append('<a class="wpl-pointer-prev button-secondary wpl-btn-prev"><?php wpl_esc::html($tip['buttons'][3]['label']); ?></a>');
                    wplj('.wpl-pointer-prev').click(function()
                    {
                        wpltip<?php wpl_esc::numeric($tip['id']); ?>.prev();
                    });
                    <?php endif; ?>
                },
                close: function()
                {
                    wplpointer<?php wpl_esc::numeric($tip['id']); ?>.pointer('close');
                },
                buttons: function(event, t)
                {
                    var button = wplj('<a class="wpl-pointer-close button-secondary wpl-btn-close"><?php wpl_esc::html_t('Close'); ?></a>');
                    button.bind('click.pointer', function()
                    {
                        wpltip<?php wpl_esc::numeric($tip['id']); ?>.close();
                    });
                    
                    return button;
                },
                prev: function()
                {
                    <?php if(isset($tip['buttons'][3]['code'])) wpl_esc::e($tip['buttons'][3]['code']); ?>
                            
                    wpltip<?php wpl_esc::numeric($tip['id']); ?>.close();
                    if(typeof wplpointer<?php wpl_esc::numeric($tip['id']-1); ?> !== 'undefined') wplpointer<?php wpl_esc::numeric($tip['id']-1); ?>.pointer('open');
                    <?php if(isset($tips[($key-1)])): ?>Realtyna.scrollTo('<?php wpl_esc::js($tips[($key-1)]['selector']); ?>', -300);<?php endif; ?>
                },
                next: function()
                {
                    <?php if(isset($tip['buttons'][2]['code'])) wpl_esc::e($tip['buttons'][2]['code']); ?>
                    
                    wpltip<?php wpl_esc::numeric($tip['id']); ?>.close();
                    if(typeof wplpointer<?php wpl_esc::numeric($tip['id']+1); ?> !== 'undefined') wplpointer<?php wpl_esc::numeric($tip['id']+1); ?>.pointer('open');
                    <?php if(isset($tips[($key+1)])): ?>Realtyna.scrollTo('<?php wpl_esc::js($tips[($key+1)]['selector']); ?>', -300);<?php endif; ?>
                }
            };

            wplpointer<?php wpl_esc::numeric($tip['id']); ?> = wplj("<?php wpl_esc::attr($tip['selector']); ?>").pointer(wpltip<?php wpl_esc::numeric($tip['id']); ?>)<?php wpl_esc::e($tip['id'] == 1 ? '.pointer("open")' : ''); ?>;
            <?php endforeach; ?>
        });
        </script>
        <?php
    }
}