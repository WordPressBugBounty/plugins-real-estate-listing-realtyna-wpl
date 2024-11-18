<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Images Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 07/28/2013
 * @package WPL
 */
class wpl_images
{
    /**
     * Resizes an image
     * @author Howard R <howard@realtyna.com>
     * @param string $source
     * @param string $dest
     * @param int $width
     * @param int $height
     * @param int $crop
     * @return string
     */
    public static function resize_image($source, $dest, $width, $height, $crop = 0)
    {
        $source = $source ?? '';
		// Don't execute the function if source file doesn't exists.
        if(!wpl_file::exists($source) and strpos($source, '://') === false) return $source;

        // Set memory limit
        @ini_set('memory_limit', '-1');
        
        $extension = wpl_file::getExt(strtolower($source));
		ini_set("gd.jpeg_ignore_warning", 1);

        switch($extension)
        {
            case 'jpg':
            case 'jpeg':
                $src_image = imagecreatefromjpeg($source);
                break;
            case 'gif':
                $src_image = imagecreatefromgif($source);
                break;
            case 'png':
                $src_image = imagecreatefrompng($source);
                break;
            case 'webp':
                $src_image = imagecreatefromwebp($source);
                break;
            default:
                return $source;
        }

		/**
		 * to fix wrong extension issue
		 */
		if(empty($src_image)) {
			if($extension == 'png') {
				$src_image = imagecreatefromjpeg($source);
			}
			if($extension == 'jpg' || $extension == 'jpeg') {
				$src_image = imagecreatefrompng($source);
			}
		}

    	if(empty($src_image)) {
        	return $source;
        }

    
        list($src_width, $src_height) = getimagesize($source);
        
		$rotate_degree = static::get_rotate_degree($source);
		if(!empty($rotate_degree)) {
			$src_image = static::rotate_image($src_image, $rotate_degree);
			if($rotate_degree != 180) {
				$temp_width = $src_width;
				$src_width = $src_height;
				$src_height = $temp_width;
			}
		}

        // Set default width if both width and height are unspecified or invalid
        if ((empty($width) || !intval($width)) && (empty($height) || !intval($height))) {
            $width = 800;
        }
        
        // If Destination height is Null, Use approximate according to ratio.
        if($height == '' || $height == 0 || $height == '0' || $height == 'auto')
        {
            // Width ratio
            $width_ratio = $src_width / $width;
            $height = round($src_height / $width_ratio);
        }

        // If Destination width is Null, Use approximate according to ratio.
        if($width == '' || $width == 0 || $width == '0' || $width == 'auto')
        {
            // height ratio
            $height_ratio = $src_height / $height;
            $width = round($src_width / $height_ratio);
        }

        $dest_width = $width;
        $dest_height = $height;

        $dest_image = imagecreatetruecolor($dest_width, $dest_height);

        if($extension == 'png') 
        {
            imagealphablending($dest_image, false);
            imagesavealpha($dest_image, true);
            $transparent = imagecolorallocatealpha($dest_image, 255, 255, 255, 127);
            imagefilledrectangle($dest_image, 0, 0, $dest_width, $dest_height, $transparent);
        }

        if($extension == 'gif') 
        {
            // keeping transparency 
            $transparent_index = imagecolortransparent($src_image);
            if($transparent_index >= 0) 
            {
                imagepalettecopy($src_image, $dest_image);
                imagefill($dest_image, 0, 0, $transparent_index);
                imagecolortransparent($dest_image, $transparent_index);
                imagetruecolortopalette($dest_image, true, 256);
            }
        }

        if($crop > 0) 
        {
            $original_ratio = $src_width / $src_height;
            $crop_resize_ratio = $width / $height;
            if($crop_resize_ratio > $original_ratio) 
            { 
                // Check if cropped image is becoming wider
                // Checking which side to keep for resizing. it calculates if the new size for resize doesn't get smaller than the one specified in function parameters
                if($height * $original_ratio < $width) 
                {
                    $tmpx = $width;
                    $tmpy = $width / $original_ratio;
                    $src_x = 0;
                    $src_y = ($tmpy - $height) / 2;
                }
                else 
                {
                    $tmpy = $height;
                    $tmpx = $height / $original_ratio;
                    $src_x = ($tmpx - $width) / 2;
                    $src_y = 0;
                }
            }
            // if cropped image is becoming narrower
            else 
            {
                // checking which side to keep for resizing. it calculates if the new size for resize doesn't get smaller than the one specified in function parameters
                if($width / $original_ratio < $height) 
                {
                    $tmpy = $height;
                    $tmpx = $height * $original_ratio;
                    $src_x = ($tmpx - $width) / 2;
                    $src_y = 0;
                }
                else 
                {
                    $tmpx = $width;
                    $tmpy = $width / $original_ratio;
                    $src_x = 0;
                    $src_y = ($tmpy - $height) / 2;
                }
            }

            $tmp_dest = imagecreatetruecolor($tmpx, $tmpy);
            if($extension == 'png')
            {
                imagealphablending($tmp_dest, false);
                imagesavealpha($tmp_dest, true);
                $transparent = imagecolorallocatealpha($tmp_dest, 255, 255, 255, 127);
                imagefilledrectangle($tmp_dest, 0, 0, $dest_width, $dest_height, $transparent);
            }

            if($extension == 'gif')
            {
                // keeping transparency 
                $transparent_index = imagecolortransparent($src_image);
                if($transparent_index >= 0)
                {
                    imagepalettecopy($src_image, $tmp_dest);
                    imagefill($tmp_dest, 0, 0, $transparent_index);
                    imagecolortransparent($tmp_dest, $transparent_index);
                    imagetruecolortopalette($tmp_dest, true, 256);
                }
            }

            //resizing image to the calculated temporary sizes
            imagecopyresampled($tmp_dest, $src_image, 0, 0, 0, 0, $tmpx, $tmpy, $src_width, $src_height);

            //crops the temporary resized image to the size given by function parameters
            if($crop == 1) imagecopy($dest_image, $tmp_dest, 0, 0, 0, 0, $width, $height);
            else imagecopy($dest_image, $tmp_dest, 0, 0, $src_x, $src_y, $width, $height);
        }
        else imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
		
        if($extension == 'jpg' || $extension == 'jpeg') 
        {
			$quality = 95;
			if(wpl_global::check_addon('optimizer')) $quality = wpl_addon_optimizer::optimize_image(wpl_addon_optimizer::IMAGE_JPEG, $dest_image);
            
            ob_start();
            imagejpeg($dest_image, NULL, $quality);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'png') 
        {
			$quality = 9;
			if(wpl_global::check_addon('optimizer')) $quality = wpl_addon_optimizer::optimize_image(wpl_addon_optimizer::IMAGE_PNG, $dest_image);
            
            ob_start();
            imagepng($dest_image, NULL, $quality);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        } 
        elseif($extension == 'gif') 
        {
            ob_start();
            imagegif($dest_image);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'webp')
        {
            ob_start();
            imagewebp($dest_image);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }

        imagedestroy($src_image);

		return apply_filters('wpl_images/resize_image/after_resize', $dest, $extension);
    }
    
	private static function rotate_image($src_image, $rotate_degree) {
		if(!empty($rotate_degree)) {
			return imagerotate($src_image, $rotate_degree, 0);
		}
		return $src_image;
	}

	private static function get_rotate_degree($source) {
		if(!function_exists('exif_read_data')) {
			return 0;
		}
		$exif = exif_read_data($source);
		if(!empty($exif['Orientation'])) {
			switch($exif['Orientation'])
			{
				case 3: // 180 rotate left
					return 180;
				case 6: // 90 rotate right
					return -90;
				case 8:    // 90 rotate left
					return 90;
			}
		}
		return 0;
	}

    /**
     * Add watermark to an image
     * @author Francis R <francis@realtyna.com>
     * @param string $source: source file string path
     * @param string $dest  : destination file string path
     * @param array|string $options: array consist of status, opacity, position and user_logo
     * @return string       : destination file path
     */
    public static function add_watermark_image($source, $dest, $options = '') 
    {
        if($options == '') $options['status'] = 0;
        if($options['status'] != 1) return $source;
        
        $filename = $source;

        //default path for watermark
        $watermark = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'system' . DS;

        if(trim($options['url'] ?? '') != '') $watermark .= trim($options['url'] ?? '');
        if(!wpl_file::exists($watermark)) return $source;

        $source = strtolower($source);
        $extension = wpl_file::getExt($source);
               
        $w_extension = wpl_file::getExt($watermark);
        
        list($w_width, $w_height) = getimagesize($filename);
        list($markwidth, $markheight) = getimagesize($watermark);
		
		$markwidth_m = $markwidth;
		$markheight_m = $markheight;
		
		// Watermark Change Size
		if(!empty($options['size']))
		{
			if($options['size_unit'] == '%')
			{
				$markwidth = ($w_width*$options['size'])/100;
				$markheight = $markwidth*$markheight_m/$markwidth_m;
			}else
			{
				$markwidth = $options['size'];
				$markheight = $options['size']*$markheight_m/$markwidth_m;
			}
		}
        ini_set("gd.jpeg_ignore_warning", 1);
		
        switch($extension) 
        {
            case 'jpg':
            case 'jpeg':
                $w_dest = imagecreatefromjpeg($filename);
                break;
            case 'gif':
                $w_dest = imagecreatefromgif($filename);
                break;
            case 'png':
                $w_dest = imagecreatefrompng($filename);
                break;
            case 'webp':
                $w_dest = imagecreatefromwebp($filename);
                break;
            default:
                return $source;
        }
        if(empty($w_dest)) {
            return $source;
        }
        
        switch($w_extension) 
        {
            case 'jpg':
            case 'jpeg':
                $w_src = imagecreatefromjpeg($watermark);
                break;
            case 'gif':
                $w_src = imagecreatefromgif($watermark);
                break;
            case 'png':
                $w_src = imagecreatefrompng($watermark);
                break;
            case 'webp':
                $w_src = imagecreatefromwebp($watermark);
                break;
            default:
                return $source;
        }
        if(empty($w_src)) {
            return $source;
        }

        // Copy and merge
        $opacity = $options['opacity'];
        $position = strtolower($options['position']);
        switch($position) 
        {
            case 'center':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) >> 1, ($w_height - $markheight) >> 1, 0, 0, $markwidth, $markheight, $markwidth_m, $markheight_m, $opacity);
                break;

            case 'left':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) > 1, ($w_height - $markheight) >> 1, 0, 0, $markwidth, $markheight, $markwidth_m, $markheight_m, $opacity);
                break;

            case 'right':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth), ($w_height - $markheight) >> 1, 0, 0, $markwidth, $markheight, $markwidth_m, $markheight_m, $opacity);
                break;

            case 'top':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) >> 1, ($w_height - $markheight) > 1, 0, 0, $markwidth, $markheight, $markwidth_m, $markheight_m, $opacity);
                break;

            case 'bottom':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) >> 1, ($w_height - $markheight), 0, 0, $markwidth, $markheight, $markwidth_m, $markheight_m, $opacity);
                break;

            case 'top-left':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) > 1, ($w_height - $markheight) > 1, 0, 0, $markwidth, $markheight, $markwidth_m, $markheight_m, $opacity);
                break;

            case 'top-right':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth), ($w_height - $markheight) > 1, 0, 0, $markwidth, $markheight, $markwidth_m, $markheight_m, $opacity);
                break;

            case 'bottom-left':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth) > 1, ($w_height - $markheight), 0, 0, $markwidth, $markheight, $markwidth_m, $markheight_m, $opacity);
                break;

            case 'bottom-right':
                wpl_images::imagecopymerge_alpha($w_dest, $w_src, ($w_width - $markwidth), ($w_height - $markheight), 0, 0, $markwidth, $markheight, $markwidth_m, $markheight_m, $opacity);
                break;
        }

        if($extension == 'jpg' || $extension == 'jpeg') 
        {
			$quality = 95;
			if(wpl_global::check_addon('optimizer') && wpl_global::get_client() === 0) $quality = wpl_addon_optimizer::optimize_image(wpl_addon_optimizer::IMAGE_JPEG, $w_dest);
            
            ob_start();
            imagejpeg($w_dest, NULL, $quality);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'png') 
        {
			$quality = 9;
			if(wpl_global::check_addon('optimizer') && wpl_global::get_client() === 0) $quality = wpl_addon_optimizer::optimize_image(wpl_addon_optimizer::IMAGE_PNG, $w_dest);
            
            ob_start();
            imagepng($w_dest, NULL, $quality);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'gif') 
        {
            ob_start();
            imagegif($w_dest);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }
        elseif($extension == 'webp')
        {
            ob_start();
            imagewebp($w_dest);
            $out_image = ob_get_clean();
            wpl_file::write($dest, $out_image);
        }

        imagedestroy($w_src);
        imagedestroy($w_dest);
        
        // Return Destination
        return $dest;
    }
    
    /**
     * Same as imagecopymerge but it handles alpha channel and PNG images well!
     * @author Peter P <peter@realtyna.com>
     * @param string|resource $w_dest  Destination image link resource.
     * @param string|resource $w_src   Source image link resource.
     * @param integer $dst_x   x-coordinate of destination point.
     * @param integer $dst_y   y-coordinate of destination point.
     * @param integer $src_x   x-coordinate of source point.
     * @param integer $src_y   y-coordinate of destination point.
     * @param integer $src_w   Source width.
     * @param integer $src_h   Source height.
     * @param integer $src_w_m   Source Main width.
     * @param integer $src_h_m   Source Main height.	 
     * @param integer $opacity Transparency
     */
    public static function imagecopymerge_alpha($w_dest, $w_src, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $src_w_m, $src_h_m, $opacity)
    {
		$dst_x = intval($dst_x);
		$dst_y = intval($dst_y);
		$src_x = intval($src_x);
		$src_y = intval($src_y);
		$src_w = intval($src_w);
		$src_h = intval($src_h);
		$opacity = intval($opacity);
        // creating a cut resource
        $cut = imagecreatetruecolor($src_w, $src_h);
        // copying that section of the background to the cut
        imagecopy($cut, $w_dest, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
        // placing the watermark now
		imagecopyresized($cut, $w_src, 0, 0, 0, 0, $src_w, $src_h, $src_w_m, $src_h_m);
        imagecopymerge($w_dest, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
    }
    
    /**
     * Converts text to an image
     * @author Howard <howard@realtyna.com>
     * @param string $text : text string
     * @param string $color: color hex code string
     * @param string $dest : destination file path string
     */
    public static function text_to_image($text, $color, $dest)
    {
        $len = strlen($text);
        $im = imagecreate($len*8, 20);

        $color = str_split($color, 2);
        $color[0] = hexdec($color[0]);
        $color[1] = hexdec($color[1]);
        $color[2] = hexdec($color[2]);

        // Make the background transparent
        $black = imagecolorallocate($im, 0, 0, 0);
        imagecolortransparent($im, $black);

        $textcolor = imagecolorallocate($im, $color[0], $color[1], $color[2]);

        // write the string at the top left
        imagestring($im, 4, 1, 0, $text, $textcolor);
        
        // output the image
        imagepng($im, $dest);
    }
    
    /**
     * Resize and add watermark to an image
     * @author Francis <francis@realtyna.com>
     * @param String $source: source file string path
     * @param String $dest  : destination file string path
     * @param integer $width
     * @param integer $height
     */
    public static function resize_watermark_image($source, $dest, $width = NULL, $height = NULL)
    {
        //get gallery category settings
        $settings = wpl_settings::get_settings(2);
        if(trim($width ?? '') == '') $width = $settings['default_resize_width'];
        if(trim($height ?? '') == '') $height = $settings['default_resize_height'];
        
        $crop = $settings['image_resize_method'];
        
        $watermark_options = array();
        $watermark_options['status'] = $settings['watermark_status'];
        $watermark_options['position'] = $settings['watermark_position'];
        $watermark_options['opacity'] = $settings['watermark_opacity'];
		$watermark_options['size'] = $settings['watermark_size'];
		$watermark_options['size_unit'] = $settings['watermark_size_unit'];		
        $watermark_options['url'] = $settings['watermark_url'];

        $dest = self::resize_image($source, $dest, $width, $height, $crop);

        if($watermark_options['status'] == 1) $dest = self::add_watermark_image($dest, $dest, $watermark_options);
        return $dest;
    }
    
    /**
     * Use wpl_images::create_gallery_image function instead
     * @deprecated since version 2.7.0
     * @param int $width
     * @param int $height
     * @param array $params
     * @param boolean|int $watermark
     * @param boolean|int $rewrite
     * @param int|string $crop
     * @return string
     */
    public static function create_gallary_image($width, $height, $params, $watermark = 0, $rewrite = 0, $crop = '')
    {
        return wpl_images::create_gallery_image($width, $height, $params, $watermark, $rewrite, $crop);
    }
    
    /**
     * Resize and watermark images specially for gallery activity
     * @author Francis <francis@realtyna.com>
     * @param int $width
     * @param int $height
     * @param array $params
     * @param boolean|int $watermark
     * @param boolean|int $rewrite
     * @param int|string $crop
     * @return string
     */
    public static function create_gallery_image($width, $height, $params, $watermark = 0, $rewrite = 0, $crop = '')
    {
        // Get blog ID of property
        $blog_id = wpl_property::get_blog_id($params['image_parentid']);
        
        $image_name = wpl_file::stripExt($params['image_name']);
        $image_ext = wpl_file::getExt($params['image_name']);

        $webp_image_optimization_setting = wpl_settings::get('image_webp_optimization_setting');

        if($webp_image_optimization_setting) $image_ext = 'webp';

        $resized_image_name = 'th'.$image_name.'_'.$width.'x'.$height.'.'.$image_ext;
        $image_dest = wpl_items::get_path($params['image_parentid'], $params['image_parentkind'], $blog_id).$resized_image_name;
        $image_url = wpl_items::get_folder($params['image_parentid'], $params['image_parentkind'], $blog_id).$resized_image_name;

		/** check resized files existence and rewrite option **/
		if($rewrite or !wpl_file::exists($image_dest))
		{
			if($watermark) $resized = self::resize_watermark_image($params['image_source'], $image_dest, $width, $height);
			else
			{
				/** if crop was not set, read from wpl settings **/
				if(!trim($crop ?? ''))
				{
					$settings = wpl_settings::get_settings(2);
					$crop = $settings['image_resize_method'];
				}

                $resized = self::resize_image($params['image_source'], $image_dest, $width, $height, $crop);
			}

            if($resized == $params['image_source']) {
                $image_url = wpl_items::get_folder($params['image_parentid'], $params['image_parentkind'], $blog_id) . $image_name . '.' . $image_ext;
            }
		}
		
		return $image_url;
    }
	
	/**
     * Creates profile image
     * @author Howard <howard@realtyna.com>
     * @param string $source
	 * @param int $width
     * @param int $height
     * @param array $params
     * @param int $watermark
     * @param int $rewrite
	 * @param int|string $crop
     * @return string
     */
    public static function create_profile_images($source, $width, $height, $params, $watermark = 0, $rewrite = 0, $crop = '')
    {
		/** first validation **/
		if(!trim($source ?? '')) return NULL;
		
        $image_name = wpl_file::stripExt($params['image_name']);
        $image_ext = wpl_file::getExt($params['image_name']);
        
        $webp_image_optimization_setting = wpl_settings::get('image_webp_optimization_setting');
        if($webp_image_optimization_setting) $image_ext = 'webp';

        $resized_image_name = 'th'.$image_name.'_'.$width.'x'.$height.'.'.$image_ext;
        $image_dest = wpl_items::get_path($params['image_parentid'], 2).$resized_image_name;
        $image_url = wpl_items::get_folder($params['image_parentid'], 2).$resized_image_name;

		/** check resized files existence and rewrite option **/
		if($rewrite or !wpl_file::exists($image_dest))
		{
		   if($watermark) $resized = self::resize_watermark_image($source, $image_dest, $width, $height);
		   else $resized = self::resize_image($source, $image_dest, $width, $height, $crop);
           if($resized == $source) {
               $image_url = wpl_items::get_folder($params['image_parentid'], 2) . $image_name . '.' . $image_ext;
           }
		}
		
		return $image_url;
    }
    
    public static function watermark_original_image($params)
    {
        // Get blog ID of property
        $blog_id = wpl_property::get_blog_id($params['image_parentid']);

        $watermarked_image_ready = false;
        $original_image_url = wpl_items::get_folder($params['image_parentid'], $params['image_parentkind'], $blog_id) . $params['image_name'];
        
        $image_name = wpl_file::stripExt($params['image_name']);
        $image_ext = wpl_file::getExt($params['image_name']);
        $watermarked_image_name = 'wm'.$image_name.'.'.$image_ext;
        $image_dest = wpl_items::get_path($params['image_parentid'], $params['image_parentkind'], $blog_id).$watermarked_image_name;
        $image_url = wpl_items::get_folder($params['image_parentid'], $params['image_parentkind'], $blog_id).$watermarked_image_name;

		/** check resized files existance**/
		if(!wpl_file::exists($image_dest))
		{
            $settings = wpl_settings::get_settings(2);
            
            $watermark_options = array();
            $watermark_options['status'] = $settings['watermark_status'];
            $watermark_options['position'] = $settings['watermark_position'];
            $watermark_options['opacity'] = $settings['watermark_opacity'];
			$watermark_options['size'] = $settings['watermark_size'];
			$watermark_options['size_unit'] = $settings['watermark_size_unit'];			
            $watermark_options['url'] = $settings['watermark_url'];

            if($watermark_options['status'] == 1)
            {
                $watermark_added = self::add_watermark_image($params['image_source'], $image_dest, $watermark_options);
                if($watermark_added && trim($watermark_added ?? '') !== '') $watermarked_image_ready = true;
            }
		}
        else $watermarked_image_ready = true;
		
		return $watermarked_image_ready ? $image_url : $original_image_url;
    }
    
    /**
     * Trim whitespaces from an image and save it
     * @author Howard R <howard@realtyna.com>
     * @param string $src full path of src image
     * @param string $dest full path of destination
     * @param mixed $color
     * @return boolean
     */
    public static function trim_white_spaces($src, $dest, $color = 0xFFFFFF)
    {
        $types = array(1=>'gif', 2=>'jpg', 3=>'png', 4=>'swf', 5=>'psd', 6=>'bmp', 7=>'tiff', 8=>'tiff', 9=>'jpc', 10=>'jp2', 11=>'jpx', 12=>'jb2', 13=>'swc', 14=>'iff', 15=>'wbmp', 16=>'xbm');
        
        list($width, $height, $type) = getimagesize($src);

        $valid_ext = 0;
        $img = NULL;
        $fileExtension = $types[$type];

        if($fileExtension == 'jpg')
        {
            $img 		= imagecreatefromjpeg($src);
            $valid_ext 	= 1;
        }
        else if($fileExtension == 'gif')
        {
            $imgorg		= imagecreatefromgif($src);

            $img 		= imagecreatetruecolor($width, $height);
            $white 		= imagecolorallocate($img, 255, 255, 255);
            imagefill($img, 0, 0, $white);

            imagecopyresampled(
                $img, $imgorg,
                0, 0, 0, 0,
                $width, $height,
                $width, $height);

            $valid_ext 	= 1;
        }
        else if($fileExtension == 'png')
        {
            $imgorg		= imagecreatefrompng($src);

            $img 		= imagecreatetruecolor($width, $height);
            $white 		= imagecolorallocate($img, 255, 255, 255);
            imagefill($img, 0, 0, $white);

            imagecopyresampled(
                $img, $imgorg,
                0, 0, 0, 0,
                $width, $height,
                $width, $height);

            $valid_ext 	= 1;
        }
        
        if(!$valid_ext) return false;
        
        $color_r = ($color >> 16) & 0xFF;
        $color_g = ($color >> 8) & 0xFF;
        $color_b = $color & 0xFF;
        
        // Top
        for($img_top = 0; $img_top < imagesy($img); ++$img_top)
        {
            for($x = 0; $x < imagesx($img); ++$x)
            {
                $p_color = imagecolorat($img, $x, $img_top);
                
                $p_r = ($p_color >> 16) & 0xFF;
                $p_g = ($p_color >> 8) & 0xFF;
                $p_b = $p_color & 0xFF;
                
                if(($p_r > ($color_r+15) or $p_r < ($color_r-15)) or $p_g > ($color_g+15) or $p_g < ($color_g-15) or $p_b > ($color_b+15) or $p_b < ($color_b-15))  break 2;
            }
        }
        
        // Bottom
        for($img_bottom = 0; $img_bottom < imagesy($img); ++$img_bottom)
        {
            for($x = 0; $x < imagesx($img); ++$x)
            {
                $p_color = imagecolorat($img, $x, imagesy($img) - $img_bottom-1);
                
                $p_r = ($p_color >> 16) & 0xFF;
                $p_g = ($p_color >> 8) & 0xFF;
                $p_b = $p_color & 0xFF;
                
                if(($p_r > ($color_r+15) or $p_r < ($color_r-15)) or $p_g > ($color_g+15) or $p_g < ($color_g-15) or $p_b > ($color_b+15) or $p_b < ($color_b-15))  break 2;
            }
        }

        // Left
        for($img_left = 0; $img_left < imagesx($img); ++$img_left)
        {
            for($y = 0; $y < imagesy($img); ++$y)
            {
                $p_color = imagecolorat($img, $img_left, $y);
                
                $p_r = ($p_color >> 16) & 0xFF;
                $p_g = ($p_color >> 8) & 0xFF;
                $p_b = $p_color & 0xFF;
                
                if(($p_r > ($color_r+15) or $p_r < ($color_r-15)) or $p_g > ($color_g+15) or $p_g < ($color_g-15) or $p_b > ($color_b+15) or $p_b < ($color_b-15))  break 2;
            }
        }

        // Right
        for($img_right = 0; $img_right < imagesx($img); ++$img_right)
        {
            for($y = 0; $y < imagesy($img); ++$y)
            {
                $p_color = imagecolorat($img, imagesx($img) - $img_right-1, $y);
                
                $p_r = ($p_color >> 16) & 0xFF;
                $p_g = ($p_color >> 8) & 0xFF;
                $p_b = $p_color & 0xFF;
                
                if(($p_r > ($color_r+15) or $p_r < ($color_r-15)) or $p_g > ($color_g+15) or $p_g < ($color_g-15) or $p_b > ($color_b+15) or $p_b < ($color_b-15))  break 2;
            }
        }
        
        $newimg_width = $width;
        if(($img_left + $img_right) < $width)
        {
            $newimg_width = $width-($img_left+$img_right);
        }
        
        $newimg_height = $height;
        if(($img_top+$img_bottom) < $height)
        {
            $newimg_height = $height-($img_top+$img_bottom);
        }
        
        $newimg = imagecreatetruecolor($newimg_width, $newimg_height);
        imagecopy($newimg, $img, 0, 0, $img_left, $img_top, $newimg_width, $newimg_height);
        imagedestroy($img);
        
        unset($img);
        
        if($fileExtension == 'gif') imagegif($newimg, $dest);
        else if($fileExtension == 'jpg') imagejpeg($newimg, $dest);
        else if($fileExtension == 'png') imagepng($newimg, $dest);
        
        return $dest;
    }
}

/**
 * Color Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL2.0.0
 * @date 11/19/2014
 * @package WPL
 */
class wpl_color
{
    /**
     * Convert a hex color to lighter or darker version based on radiance
     * @author Howard R <howard@realtyna.com>
     * @param string $hex
     * @param int $radiance
     * @param boolean $trim
     * @return string
     */
    public function convert($hex, $radiance, $trim = false)
    {
        $RGB = $this->hex2rgb($hex);
        $result = $this->rgb2hex($this->radiance($RGB, $radiance));
        
        if($trim) $result = trim($result ?? '', '# ');
        return $result;
    }
    
    /**
     * Convert hex color to RGB color
     * @author Howard R <howard@realtyna.com>
     * @param string $hex
     * @return mixed
     */
    public function hex2rgb($hex)
    {
        if($hex[0] == '#')
            $hex = substr($hex, 1);

        if(strlen($hex) == 3)
        {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = isset($hex[0], $hex[1]) ? hexdec($hex[0] . $hex[1]) : 0;
        $g = isset($hex[2], $hex[3]) ? hexdec($hex[2] . $hex[3]) : 0;
        $b = isset($hex[4], $hex[5]) ? hexdec($hex[4] . $hex[5]) : 0;

        return $b + ($g << 0x8) + ($r << 0x10);
    }

    /**
     * Converts RGB to hex color
     * @author Howard R <howard@realtyna.com>
     * @param mixed $RGB
     * @return string
     */
    public function rgb2hex($RGB)
    {
        $r = 0xFF & ($RGB >> 0x10);
        $g = 0xFF & ($RGB >> 0x8);
        $b = 0xFF & $RGB;

        $r = dechex($r);
        $g = dechex($g);
        $b = dechex($b);

        return "#" . str_pad($r, 2, "0", STR_PAD_LEFT) . str_pad($g, 2, "0", STR_PAD_LEFT) . str_pad($b, 2, "0", STR_PAD_LEFT);
    }
    
    /**
     * Creates lighter or darker version of an RGB color
     * @author Howard R <howard@realtyna.com>
     * @param mixed $RGB
     * @param int $radiance
     * @return mixed
     */
    public function radiance($RGB, $radiance)
    {
        $HSL = self::rgb2hsl($RGB);
        $NewHSL = (int)(((float) $radiance / 100) * 255) + (0xFFFF00 & $HSL);
        return self::hsl2rgb($NewHSL);
    }
    
    /**
     * Converts RGB to HSL
     * @author Howard R <howard@realtyna.com>
     * @param mixed $RGB
     * @return mixed
     */
    public function rgb2hsl($RGB)
    {
        $r = 0xFF & ($RGB >> 0x10);
        $g = 0xFF & ($RGB >> 0x8);
        $b = 0xFF & $RGB;

        $r = ((float) $r) / 255.0;
        $g = ((float) $g) / 255.0;
        $b = ((float) $b) / 255.0;

        $maxC = max($r, $g, $b);
        $minC = min($r, $g, $b);

        $l = ($maxC + $minC) / 2.0;

        if($maxC == $minC)
        {
            $s = 0;
            $h = 0;
        }
        else
        {
            if($l < .5) $s = ($maxC - $minC) / ($maxC + $minC);
            else $s = ($maxC - $minC) / (2.0 - $maxC - $minC);

            $h = NULL;
            if($r == $maxC) $h = ($g - $b) / ($maxC - $minC);
            if($g == $maxC) $h = 2.0 + ($b - $r) / ($maxC - $minC);
            if($b == $maxC) $h = 4.0 + ($r - $g) / ($maxC - $minC);

            $h = $h / 6.0; 
        }

        $h = (int) round(255.0 * $h);
        $s = (int) round(255.0 * $s);
        $l = (int) round(255.0 * $l);

        $HSL = $l + ($s << 0x8) + ($h << 0x10);
        return $HSL;
    }

    /**
     * Converts HSL to RGB
     * @author Howard R <howard@realtyna.com>
     * @param mixed $HSL
     * @return mixed
     */
    public function hsl2rgb($HSL)
    {
        $h = 0xFF & ($HSL >> 0x10);
        $s = 0xFF & ($HSL >> 0x8);
        $l = 0xFF & $HSL;

        $h = ((float) $h) / 255.0;
        $s = ((float) $s) / 255.0;
        $l = ((float) $l) / 255.0;

        if($s == 0)
        {
            $r = $l;
            $g = $l;
            $b = $l;
        }
        else
        {
            if($l < .5)
            {
                $t2 = $l * (1.0 + $s);
            }
            else
            {
                $t2 = ($l + $s) - ($l * $s);
            }
            
            $t1 = 2.0 * $l - $t2;

            $rt3 = $h + 1.0/3.0;
            $gt3 = $h;
            $bt3 = $h - 1.0/3.0;

            if($rt3 < 0) $rt3 += 1.0;
            if($rt3 > 1) $rt3 -= 1.0;
            if($gt3 < 0) $gt3 += 1.0;
            if($gt3 > 1) $gt3 -= 1.0;
            if($bt3 < 0) $bt3 += 1.0;
            if($bt3 > 1) $bt3 -= 1.0;

            if(6.0 * $rt3 < 1) $r = $t1 + ($t2 - $t1) * 6.0 * $rt3;
            elseif(2.0 * $rt3 < 1) $r = $t2;
            elseif(3.0 * $rt3 < 2) $r = $t1 + ($t2 - $t1) * ((2.0/3.0) - $rt3) * 6.0;
            else $r = $t1;

            if(6.0 * $gt3 < 1) $g = $t1 + ($t2 - $t1) * 6.0 * $gt3;
            elseif(2.0 * $gt3 < 1) $g = $t2;
            elseif(3.0 * $gt3 < 2) $g = $t1 + ($t2 - $t1) * ((2.0/3.0) - $gt3) * 6.0;
            else $g = $t1;

            if(6.0 * $bt3 < 1) $b = $t1 + ($t2 - $t1) * 6.0 * $bt3;
            elseif(2.0 * $bt3 < 1) $b = $t2;
            elseif(3.0 * $bt3 < 2) $b = $t1 + ($t2 - $t1) * ((2.0/3.0) - $bt3) * 6.0;
            else $b = $t1;
        }

        $r = (int) round(255.0 * $r);
        $g = (int) round(255.0 * $g);
        $b = (int) round(255.0 * $b);

        $RGB = $b + ($g << 0x8) + ($r << 0x10);
        return $RGB;
    }
}