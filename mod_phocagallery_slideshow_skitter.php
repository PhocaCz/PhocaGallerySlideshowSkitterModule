<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Module
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die('Restricted access');

// Include Phoca Gallery
if (!JComponentHelper::isEnabled('com_phocagallery', true)) {
    echo '<div class="alert alert-danger">Phoca Gallery Error: Phoca Gallery component is not installed or not published on your system</div>';
    return;
}

if (!class_exists('PhocaGalleryLoader')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocagallery/libraries/loader.php');
}

phocagalleryimport('phocagallery.path.path');
phocagalleryimport('phocagallery.path.route');
phocagalleryimport('phocagallery.library.library');
phocagalleryimport('phocagallery.text.text');
phocagalleryimport('phocagallery.access.access');
phocagalleryimport('phocagallery.file.file');
phocagalleryimport('phocagallery.file.filethumbnail');
phocagalleryimport('phocagallery.image.image');
phocagalleryimport('phocagallery.image.imagefront');
phocagalleryimport('phocagallery.render.renderfront');
phocagalleryimport('phocagallery.render.renderadmin');
phocagalleryimport('phocagallery.render.renderdetailwindow');
phocagalleryimport('phocagallery.ordering.ordering');
phocagalleryimport('phocagallery.picasa.picasa');
phocagalleryimport('phocagallery.html.category');


$db 		= JFactory::getDBO();
$document	= JFactory::getDocument();

$module_css_style				= trim( $params->get( 'module_css_style' ) );
$catId 							= $params->get( 'category_id', 0 );
$count							= $params->get( 'count_images', 5 );
$width 							= $params->get( 'width', 970 );
$height							= $params->get( 'height', 230 );
$image_ordering 				= $params->get( 'image_ordering', 9 );
$url_link 						= $params->get( 'url_link', 0 );
$single_link	 				= $params->get( 'single_link', '' );
$target		 					= $params->get( 'target', '_self' );

$s['velocity'] 					= (float)$params->get( 'velocity', '1');
$s['interval'] 					= (int)$params->get( 'interval','2500');
$s['animation'] 				= $params->get( 'animation','');
$s['numbers'] 					= $params->get( 'numbers','true');
$s['navigation'] 				= $params->get( 'navigation','true');
$s['label'] 					= $params->get( 'label','true');
$s['easing_default'] 			= $params->get( 'easing_default','');
//$s['box_skitter'] 			= $params->get( 'box_skitter','null');
//$s['time_interval'] 			= $params->get( 'time_interval','null');
//$s['images_links'] 			= $params->get( 'images_links','null');
//$s['image_atual'] 			= $params->get( 'image_atual','null');
//$s['link_atual'] 				= $params->get( 'link_atual','null');
//$s['label_atual']				= $params->get( 'label_atual','null');
//$s['target_atual']			= $params->get( 'target_atual','_self');
//$s['width_skitter'] 			= $params->get( 'width_skitter','null');
//$s['height_skitter'] 			= $params->get( 'height_skitter','null');
//$s['image_i'] 				= $params->get( 'image_i','1');
//$s['is_animating'] 			= $params->get( 'is_animating','false');
//$s['is_hover_box_skitter'] 	= $params->get( 'is_hover_box_skitter','false');
//$s['random_ia'] 				= $params->get( 'random_ia','null');
$s['show_randomly'] 			= $params->get( 'show_randomly','false');
$s['thumbs'] 					= $params->get( 'thumbs','false');
$s['animateNumberOut'] 			= $params->get( 'animateNumberOut','{backgroundColor:\'#333\', color:\'#fff\'}');
$s['animateNumberOver'] 		= $params->get( 'animateNumberOver','{backgroundColor:\'#fff\', color:\'#000\'}');
$s['animateNumberActive'] 		= $params->get( 'animateNumberActive','{backgroundColor:\'#cc3333\', color:\'#fff\'}');
$s['hideTools'] 				= $params->get( 'hideTools','false');
//$s['fullscreen'] 				= $params->get( 'fullscreen','false');
//$s['xml'] 					= $params->get( 'xml','false');
$s['dots'] 						= $params->get( 'dots','false');
$s['width_label'] 				= $params->get( 'width_label','null');
//$s['opacity_elements'] 		= $params->get( 'opacity_elements','0.75');
//$s['interval_in_elements'] 	= (int)$params->get( 'interval_in_elements','300');
//$s['interval_out_elements'] 	= (int)$params->get( 'interval_out_elements','500');
$s['onLoad'] 					= 'null';//= $params->get( 'onLoad','null');
//$s['imageSwitched'] 			= $params->get( 'imageSwitched','null');
//$s['max_number_height'] 		= //(int)$params->get( 'max_number_height','20');
$s['numbers_align'] 			= $params->get( 'numbers_align','left');
$s['preview'] 					= $params->get( 'preview','false');
$s['focus'] 					= $params->get( 'focus','false');
//$s['foucs_active'] 			= $params->get( 'foucs_active','false');
$s['focus_position'] 			= $params->get( 'focus_position','center');
$s['controls'] 					= $params->get( 'controls','false');
$s['controls_position'] 		= $params->get( 'controls_position','center');
$s['progressbar'] 				= $params->get( 'progressbar','false');
$s['progressbar_css'] 			= $params->get( 'progressbar_css','{top:\'5px\', left:\'10px\', height:10, borderRadius:\'2px\', width:200, backgroundColor:\'#000\', opacity:0.7}');
//$s['is_paused'] 				= $params->get( 'is_paused','false');
//$s['is_blur'] 				= $params->get( 'is_blur','false');
//$s['is_paused_time']			= $params->get( 'is_paused_time','false');
//$s['timeStart'] 				= $params->get( 'timeStart','0');
//$s['elapsedTime'] 			= $params->get( 'elapsedTime','0');
$s['stop_over'] 				= true;//$params->get( 'stop_over','true');
$s['enable_navigation_keys'] 	= $params->get( 'enable_navigation_keys','false');
$s['with_animations'] 			= $params->get( 'with_animations','[]');
$s['mouseOverButton'] 			= $params->get( 'mouseOverButton','function() { $(this).stop().animate({opacity:0.5}, 200); }');
$s['mouseOutButton'] 			= $params->get( 'mouseOutButton','function() { $(this).stop().animate({opacity:1}, 200); }');
$s['auto_play'] 				= true;//$params->get( 'auto_play','true');//AutoPlay must be set to true because of conflict in javascript
$s['structure'] 				= $params->get( 'structure','\'<a href="#" class="prev_button">prev</a>\'
						+ \'<a href="#" class="next_button">next</a>\'
						+ \'<span class="info_slide"></span>\'
						+ \'<div class="container_skitter">\'
							+ \'<div class="image">\'
								+ \'<a href=""><img class="image_main" /></a>\'
								+ \'<div class="label_skitter"></div>\'
							+ \'</div>\'
						+ \'</div>\'');

JHTML::stylesheet('media/mod_phocagallery_slideshow_skitter/css/skitter.styles.css' );
JHTML::stylesheet('media/mod_phocagallery_slideshow_skitter/css/style.css' );
//$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/jquery/jquery-1.6.4.min.js');
JHtml::_('jquery.framework', true);
$document->addScript(JURI::base(true).'/media/mod_phocagallery_slideshow_skitter/javascript/jquery.skitter.min.js');
$document->addScript(JURI::base(true).'/media/mod_phocagallery_slideshow_skitter/javascript/jquery.easing.1.3.js');
//$document->addScript(JURI::base(true).'/media/mod_phocagallery_slideshow_skitter/javascript/jquery.animate-colors-min.js');

$catidSQL = '';
if ((int)$catId > 0) {
	$catidSQL = ' AND a.catid = ' . (int)$catId;
}

if ($image_ordering == 9) {
	$imageOrdering = ' ORDER BY RAND()';
} else {

	$iOA = PhocaGalleryOrdering::getOrderingString($image_ordering);
	$imageOrdering = $iOA['output'];
}

// IMAGES
$query      = ' SELECT a.id, a.title, a.description, a.filename, a.extl, a.extlink1, a.extlink2, cc.id as categoryid, cc.alias as categoryalias'
			. ' FROM #__phocagallery AS a'
			. ' LEFT JOIN #__phocagallery_categories AS cc ON a.catid = cc.id'
			. ' WHERE a.published = 1'
			. $catidSQL
			//. ' WHERE cc.published = 1 AND a.published = 1 AND a.catid = ' . (int)$catId
			//. ' ORDER BY RAND()'
			. $imageOrdering
			. ' LIMIT '.(int)$count;
$db->setQuery($query);
$images = $db->loadObjectList();


$i 	= count($images);
if ($i > 0) {

	echo '<div class="box_skitter box_skitter_large" style="width: '.$width.'px;height: '.$height.'px;">';
	echo '<ul>'. "\n";
	foreach ($images as $k => $v) {

    echo '<li>';//. "\n";

	$urlLink 	= '';
	if ($url_link == 0) {

	} else if ($url_link == 1) {
		if (isset($v->extlink1)) {
			$v->extlink1	= explode("|", $v->extlink1, 4);
			if (isset($v->extlink1[0]) && $v->extlink1[0] != '' && isset($v->extlink1[1])) {
				$urlLink = 'http://'.$v->extlink1[0];
				if (!isset($v->extlink1[2])) {
					$target = '_self';
				} else {
					$target = $v->extlink1[2];
				}
			}
		}
	} else if ($url_link == 2) {
		if (isset($v->extlink2)) {
			$v->extlink2	= explode("|", $v->extlink2, 4);
			if (isset($v->extlink2[0]) && $v->extlink2[0] != '' && isset($v->extlink2[1])) {
				$urlLink =  'http://'.$v->extlink2[0];
				if (!isset($v->extlink2[2])) {
					$target = '_self';
				} else {
					$target = $v->extlink2[2];
				}
			}
		}
	} else if ($url_link == 3) {
		$urlLink =  PhocaGalleryRoute::getCategoryRoute($v->categoryid, $v->categoryalias);

	} else {
		if ($single_link != '') {
			$urlLink 	= 'http://'.$single_link;
			$target		= '_self';
		}
	}

	echo '<a href="'.$urlLink.'" target="'.$target.'">';

	if (isset($v->extl) &&  $v->extl != '') {
		echo '<img src="'.PhocaGalleryText::strTrimAll($v->extl).'" alt="'.htmlspecialchars($v->title).'" class="block" />';
	} else {
		$thumbLink	= PhocaGalleryFileThumbnail::getThumbnailName($v->filename, 'large');
		echo '<img src="'.JURI::base(true).'/'.$thumbLink->rel.'" alt="'.htmlspecialchars($v->title).'" class="block" />';
	}

	echo '</a>';//. "\n";

	// Label
	$label = '';
	if ($s['label'] == '1') {
		$label = $v->title;
	} else if ($s['label'] == '2') {
		$label = $v->description;
	} else if ($s['label'] == '3') {
		$label = $v->title;
		if ($v->description != '') {
			$label .= ' - '. $v->description;
		}
	}

	if ($label != '') {
		echo '<div class="label_text">';
		echo '<p>'.strip_tags($label).'</p>';
		echo '</div>'. "\n";
    }

	echo '</li>'. "\n";
	}
	echo '</ul>';
	echo '</div>';
}


$js = '
<script type="text/javascript">
var pgSSJQ = jQuery.noConflict();
(function($) {
    pgSSJQ(document).ready(function() {
		
		var options = {';

$i = 0;
foreach ($s as $k => $v) {
	if ($i > 0) {
		$js .= ','."\n";
	}

	switch ($k) {
		case 'animation':
		case 'easing_default':
		case 'target_atual':
		case 'numbers_align':
		case 'focus_position':
		case 'controls_position':
		case 'width_label':
			if ($v == 'null') {
				$vOutput = $v;
			} else {
				$vOutput = '\''. $v.'\'';
			}
		break;


		case 'label':
			if ($v == '1' || $v == '2' || $v == '3') {
				$vOutput = 'true';
			} else {
				$vOutput = $v;
			}
		break;

		default:
			$vOutput = $v;
		break;
	}


	if ($vOutput == '') {
		$vOutput = '\'\'';
	}

	$js .= '		  '.$k.':   '.$vOutput;
	$i++;

}

$js .= '		};

		$(\'.box_skitter_large\').skitter(options);
    });
})(pgSSJQ);
</script>';

$document->addCustomTag($js);
?>
