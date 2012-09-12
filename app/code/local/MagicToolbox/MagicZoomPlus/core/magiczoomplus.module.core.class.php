<?php

if(!in_array('MagicZoomPlusModuleCoreClass', get_declared_classes())) {

    require_once(dirname(__FILE__) . '/magictoolbox.params.class.php');

    class MagicZoomPlusModuleCoreClass {
        var $uri;
        var $jsPath;
        var $cssPath;
        var $imgPath;
        var $params;
        var $general;//initial parameters
        var $mainImageID;
        var $type = 'standard';

        function MagicZoomPlusModuleCoreClass() {
            $this->params = new MagicToolboxParams();
            $this->general = new MagicToolboxParams();
            $this->_paramDefaults();
        }

        function headers($jsPath = '', $cssPath = null, $notCheck = false) {
            if($cssPath == null) $cssPath = $jsPath;
            $headers = array();
            $headers[] = '<!-- Magic Zoom Plus Magento module version v4.4.29 [v1.0.44:v4.0.7] -->';
            $headers[] = '<link type="text/css" href="' . $cssPath . '/magiczoomplus.css" rel="stylesheet" media="screen" />';
            $headers[] = '<script type="text/javascript" src="' . $jsPath . '/magiczoomplus.js"></script>';
            $headers[] = "<script type=\"text/javascript\">\n\tMagicZoomPlus.options = {\n\t\t".implode(",\n\t\t", $this->options($notCheck))."\n\t}\n</script>\n";
            return implode("\r\n", $headers);
        }

        function options($notCheck = false) {

            // load module options
            $options = array(
                "'expand-speed': " . $this->params->getValue("expand-speed"),
                "'restore-speed': " . $this->params->getValue("restore-speed"),
                "'expand-effect': '" . $this->params->getValue("expand-effect") . "'",
                "'restore-effect': '" . $this->params->getValue("restore-effect") . "'",
                "'expand-align': '" . $this->params->getValue("expand-align") . "'",
                "'expand-position': '" . $this->params->getValue("expand-position") . "'",
                "'expand-size': '" . $this->params->getValue("expand-size") . "'",
                //"'keep-thumbnail': " . $this->params->getValue("keep-thumbnail"),
                //"'click-to-initialize': " . $this->params->getValue("click-to-initialize"),
                "'background-color': '" . $this->params->getValue("background-color") . "'",
                "'background-opacity': " . $this->params->getValue("background-opacity"),
                "'background-speed': " . $this->params->getValue("background-speed"),
                "'caption-speed': " . $this->params->getValue("caption-speed"),
                "'caption-position': '" . $this->params->getValue("caption-position") . "'",
                "'caption-height': " . $this->params->getValue("caption-height"),
                "'caption-width': " . $this->params->getValue("caption-width"),
                "'buttons': '" . $this->params->getValue("buttons") . "'",
                "'buttons-position': '" . $this->params->getValue("buttons-position") . "'",
                "'buttons-display': '" . $this->params->getValue("buttons-display") . "'",
                //"'show-loading': " . $this->params->getValue("show-loading"),
                "'loading-msg': '" . $this->params->getValue("loading-msg") . "'",
                "'loading-opacity': " . $this->params->getValue("loading-opacity"),
                "'slideshow-effect': '" . $this->params->getValue("slideshow-effect") . "'",
                "'slideshow-speed': " . $this->params->getValue("slideshow-speed"),
                //"'slideshow-loop': " . $this->params->getValue("slideshow-loop"),
                //"'link': '" . $this->params->getValue("link") . "'",
                //"'link-target': '" . $this->params->getValue("link-target") . "'",
                //"'thumb-id': '" . $this->params->getValue("thumb-id") . "'",
                //"'group': '" . $this->params->getValue("group") . "'",
                //"'keyboard': " . $this->params->getValue("keyboard"),
                //"'keyboard-ctrl': " . $this->params->getValue("keyboard-ctrl"),
                "'z-index': " . $this->params->getValue("z-index"),
                "'expand-trigger': '" . $this->params->getValue("expand-trigger") . "'",
                "'restore-trigger': '" . $this->params->getValue("restore-trigger") . "'",
                "'expand-trigger-delay': " . $this->params->getValue("expand-trigger-delay"),


                "'opacity': " . $this->params->getValue('opacity'),
                "'zoom-width': " . $this->params->getValue('zoom-width'),
                "'zoom-height': " . $this->params->getValue('zoom-height'),
                "'zoom-position': '" . $this->params->getValue('zoom-position') ."'",
                "'selectors-change': '" . $this->params->getValue('selectors-change') ."'",
                "'selectors-mouseover-delay': " . $this->params->getValue('selectors-mouseover-delay') ,
                "'smoothing-speed': " . $this->params->getValue('smoothing-speed'),
                "'zoom-distance': " . $this->params->getValue('zoom-distance'),
                "'zoom-fade-in-speed': " . $this->params->getValue('zoom-fade-in-speed'),
                "'zoom-fade-out-speed': " . $this->params->getValue('zoom-fade-out-speed'),
                "'fps': " . $this->params->getValue('fps'),
                "'loading-position-x': " . $this->params->getValue('loading-position-x'),
                "'loading-position-y': " . $this->params->getValue('loading-position-y'),
                "'x': " . $this->params->getValue('x'),
                "'y': " . $this->params->getValue('y'),
                "'show-title': " . ($this->params->checkValue('show-title', 'disable')?'false':"'".$this->params->getValue('show-title')."'"),
                "'selectors-effect': '" . ($this->params->checkValue('selectors-effect', 'disable')?'false':$this->params->getValue('selectors-effect')) ."'",
                "'selectors-effect-speed': " . $this->params->getValue('selectors-effect-speed'),
                "'zoom-align': '" . $this->params->getValue('zoom-align') ."'",
                "'zoom-window-effect': '" . $this->params->getValue('zoom-window-effect') ."'",
                "'selectors-class': '" . $this->params->getValue('selectors-class') ."'",
                "'hint-text': '" . $this->params->getValue('hint-text') ."'",
                "'hint-opacity': " . $this->params->getValue('hint-opacity'),
                "'initialize-on': '" . $this->params->getValue('initialize-on') ."'",

            );
            switch($this->params->getValue('hint-position')) {
                case 'top left':
                    $options[] = "'hint-position': 'tl'";
                    break;
                case 'top right':
                    $options[] = "'hint-position': 'tr'";
                    break;
                case 'top center':
                    $options[] = "'hint-position': 'tc'";
                    break;
                case 'bottom left':
                    $options[] = "'hint-position': 'bl'";
                    break;
                case 'bottom right':
                    $options[] = "'hint-position': 'br'";
                    break;
                case 'bottom center':
                    $options[] = "'hint-position': 'bc'";
                    break;
            }
            switch($this->params->getValue('right-click')) {
                case 'Yes':
                    $options[] = "'right-click': 'true'";
                    break;
                case 'Original':
                    $options[] = "'right-click': 'original'";
                    break;
                case 'Expanded':
                    $options[] = "'right-click': 'expanded'";
                    break;
                case 'No':
                    $options[] = "'right-click': 'false'";
                    break;
            }
            if($notCheck) {
                $options = array_merge($options, array(
                    "'disable-zoom': " . $this->params->getValue("disable-zoom"),
                    "'disable-expand': " . $this->params->getValue("disable-expand"),
                    "'keep-thumbnail': " . $this->params->getValue("keep-thumbnail"),
                    "'show-loading': " . $this->params->getValue("show-loading"),
                    "'slideshow-loop': " . $this->params->getValue("slideshow-loop"),
                    "'keyboard': " . $this->params->getValue("keyboard"),
                    "'keyboard-ctrl': " . $this->params->getValue("keyboard-ctrl"),

                    "'drag-mode': " . $this->params->getValue('drag-mode'),
                    "'always-show-zoom': " . $this->params->getValue('always-show-zoom'),
                    "'smoothing': " . $this->params->getValue('smoothing'),
                    "'opacity-reverse': " . $this->params->getValue('opacity-reverse'),
                    "'click-to-activate': " . $this->params->getValue('click-to-activate'),
                    "'click-to-deactivate': " . $this->params->getValue('click-to-deactivate'),
                    "'preload-selectors-small': " . $this->params->getValue('preload-selectors-small'),
                    "'preload-selectors-big': " . $this->params->getValue('preload-selectors-big'),
                    "'zoom-fade': " . $this->params->getValue('zoom-fade'),
                    "'move-on-click': " . $this->params->getValue('move-on-click'),
                    "'preserve-position': " . $this->params->getValue('preserve-position'),
                    "'fit-zoom-window': " . $this->params->getValue('fit-zoom-window'),
                    "'entire-image': " . $this->params->getValue('entire-image'),
                    "'hint': " . $this->params->getValue('hint'),
                    "'pan-zoom': ". $this->params->getValue('pan-zoom'),
                ));
            } else {
                $options = array_merge($options, array(
                    "'disable-zoom': " . ($this->params->checkValue('disable-zoom', 'Yes')?'true':'false'),
                    "'disable-expand': " . ($this->params->checkValue('disable-expand', 'Yes')?'true':'false'),
                    "'keep-thumbnail': " . ($this->params->checkValue('keep-thumbnail', 'Yes')?'true':'false'),
                    "'show-loading': " . ($this->params->checkValue('show-loading', 'Yes')?'true':'false'),
                    "'slideshow-loop': " . ($this->params->checkValue('slideshow-loop', 'Yes')?'true':'false'),
                    "'keyboard': " . ($this->params->checkValue('keyboard', 'Yes')?'true':'false'),
                    "'keyboard-ctrl': " . ($this->params->checkValue('keyboard-ctrl', 'Yes')?'true':'false'),

                    "'drag-mode': " . ($this->params->checkValue('drag-mode', 'Yes') ? 'true' : 'false'),
                    "'always-show-zoom': " . ($this->params->checkValue('always-show-zoom', 'Yes') ? 'true' : 'false'),
                    "'smoothing': " . ($this->params->checkValue('smoothing', 'Yes') ? 'true' : 'false'),
                    "'opacity-reverse': " . ($this->params->checkValue('opacity-reverse', 'Yes') ? 'true' : 'false'),
                    "'click-to-activate': " . ($this->params->checkValue('click-to-activate', 'Yes') ? 'true' : 'false'),
                    "'click-to-deactivate': " . ($this->params->checkValue('click-to-deactivate', 'Yes') ? 'true' : 'false'),
                    "'preload-selectors-small': " . ($this->params->checkValue('preload-selectors-small', 'Yes') ? 'true' : 'false'),
                    "'preload-selectors-big': " . ($this->params->checkValue('preload-selectors-big', 'Yes') ? 'true' : 'false'),
                    "'zoom-fade': " . ($this->params->checkValue('zoom-fade', 'Yes') ? 'true' : 'false'),
                    "'move-on-click': " . ($this->params->checkValue('move-on-click', 'Yes') ? 'true' : 'false'),
                    "'preserve-position': " . ($this->params->checkValue('preserve-position', 'Yes') ? 'true' : 'false'),
                    "'fit-zoom-window': " . ($this->params->checkValue('fit-zoom-window', 'Yes') ? 'true' : 'false'),
                    "'entire-image': " . ($this->params->checkValue('entire-image', 'Yes') ? 'true' : 'false'),
                    "'hint': " . ($this->params->checkValue('hint', 'Yes') ? 'true' : 'false'),
                    "'pan-zoom': " . ($this->params->checkValue('pan-zoom', 'Yes') ? 'true' : 'false'),
                ));
            }
            $cSource = $this->params->get("caption-source");
            if(is_array($cSource) && isset($cSource['core']) && $cSource['core']) {
                $options = array_merge($options, array(
                    "'caption-source': '" . $this->params->getValue("caption-source") . "'"
                ));
            } else {
                $options = array_merge($options, array(
                    "'caption-source': 'span'"
                ));
            }
            return $options;
        }

        function template($params) {
            // TODO =)
            //extract(array_fill_keys(array('img', 'thumb', 'id'), ''));
            extract($params);

            if(!isset($img) || empty($img)) return false;
            if(!isset($thumb) || empty($thumb)) $thumb = $img;
            if(!isset($id) || empty($id)) $id = md5($img);

            if(!isset($alt) || empty($alt)) {
                $alt = '';
            } else {
                $alt = htmlspecialchars(htmlspecialchars_decode($alt, ENT_QUOTES));
            }
            if(!isset($title) || empty($title)) $title = '';
            if(!isset($description)) $description = '';

            if($this->params->checkValue('show-caption', 'Yes')) {
                $captionSource = $this->params->getValue('caption-source');
                $captionSource = trim($captionSource);
                if(strtolower($captionSource) == 'all' || strtolower($captionSource) == 'both') {
                    $captionSource = $this->params->getValues('caption-source');
                } else {
                    $captionSource = explode(',',$captionSource);
                }
                $fullTitle = array();
                foreach($captionSource as $caption) {
                    $caption = trim($caption);
                    $caption = strtolower($caption);
                    $caption = lcfirst(implode(explode(' ', ucwords($caption))));
                    if($caption == 'all' || $caption == 'both') continue;
                    if(!isset($$caption)) continue;
                    if($$caption == '') continue;
                    if($caption == 'title') {
                        $fullTitle[] = '<b>' . $$caption . '</b>';
                    } else {
                        $fullTitle[] = $$caption;
                    }
                }
                $description_new = implode('<br/>',$fullTitle);
            } else $description_new = '';
            $description = $description_new;
            $description = trim(preg_replace("/\s+/is", " ", $description));
            if(!empty($description)) {
                $description = preg_replace("/<(\/?)a([^>]*)>/is", "[$1a$2]", $description);
                $description = "<span>{$description}</span>";
            }
            if(!empty($title) && !$this->params->checkValue('show-title', 'disable')) {
                $title = htmlspecialchars(htmlspecialchars_decode($title, ENT_QUOTES));
                if(empty($alt)) $alt = $title;
                $title = " title=\"{$title}\"";
            } else $title = '';

            if(!isset($width) || empty($width)) $width = "";
            else $width = " width=\"{$width}\"";
            if(!isset($height) || empty($height)) $height = "";
            else $height = " height=\"{$height}\"";

            if($this->params->checkValue('show-message', 'Yes')) {
                $message = '<div class="MagicToolboxMessage">' . $this->params->getValue('message') . '</div>';
            } else $message = '';

            $this->mainImageID = $id;

            // TODO rel should be fill from serialize method in params class
            $rel = $this->getRel();

            if(!isset($link) || empty($link)) {
                $link = '';
            } else if($this->params->checkValue('disable-expand', 'Yes')) {
                //onclick only if MagicThumb disabled
                $link = ' onclick="document.location.href=\'' . ($link) . '\'"';
            } else {
                $rel .= 'link: ' . ($link) . ';';
                $link = '';
            }

            if(isset($group) && !empty($group)) {
                $rel .= 'group: ' . ($group) . ';';
            }
            if(isset($advanced_option)) {
                $rel .= $advanced_option . ';';
            }
            if(isset($hotspots) && $hotspots) {
                $rel .= 'hotspots: ' . $hotspots;
            }
            if(!empty($rel)) $rel = 'rel="'.$rel.'"';

            return "<a{$link} class=\"MagicZoomPlus\"{$title} id=\"MagicZoomPlusImage{$id}\" href=\"{$img}\" {$rel}><img{$width}{$height} src=\"{$thumb}\" alt=\"{$alt}\" />{$description}</a>" . $message;
        }

        function subTemplate($params) {
            extract($params);

            if(!isset($alt) || empty($alt)) {
                $alt = '';
            } else {
                $alt = htmlspecialchars(htmlspecialchars_decode($alt, ENT_QUOTES));
            }
            if(!isset($img) || empty($img)) return false;
            if(!isset($medium) || empty($medium)) $medium = $img;
            if(!isset($thumb) || empty($thumb)) $thumb = $img;
            if(!isset($id) || empty($id)) $id = md5($img);
            if(!isset($title) || empty($title) || $this->params->checkValue('show-caption', 'No')) $title = '';
            else {
                $title = htmlspecialchars(htmlspecialchars_decode($title, ENT_QUOTES));
                if(empty($alt)) $alt = $title;
                $title = " title=\"{$title}\"";
            }
            if(!isset($width) || empty($width)) $width = "";
            else $width = " width=\"{$width}\"";
            if(!isset($height) || empty($height)) $height = "";
            else $height = " height=\"{$height}\"";

            /* onclick - to allow change image for MagicThumb effect */
            //$onclick = " onclick=\"MagicZoomPlusResreshMagicThumb(this);\"";

            $rel = $this->getRel();
            if(isset($advanced_option)) {
                $rel .= $advanced_option . ';';
            }
            if(isset($hotspots) && $hotspots) {
                $rel .= 'hotspots: ' . $hotspots;
            }

            return "<a{$title} href=\"{$img}\" rel=\"zoom-id: MagicZoomPlusImage{$id};caption-source: a:title;{$rel}\" rev=\"{$medium}\"><img{$width}{$height} src=\"{$thumb}\" alt=\"{$alt}\" /></a>";
        }

        function addonsTemplate($imgPath = '') {
            if ($this->params->checkValue("loading-animation", "Yes")){
                return '<img style="display:none;" class="MagicZoomLoading" src="' . $imgPath . '/' . $this->params->getValue("loading-image") . '" alt="' . $this->params->getValue("loading-text") . '"/>';
            } else return '';
        }

        function getRel() {
            if(in_array('MagicToolboxOptions', get_declared_classes())) {

                //workaround
                $hp = array(
                    'top left' => 'tl' ,
                    'top right' => 'tr' ,
                    'top center' => 'tc' ,
                    'bottom left' => 'bl' ,
                    'bottom right' => 'br' ,
                    'bottom center' => 'bv'
                );
                $this->params->set('hint-position',$hp[$this->params->getValue('hint-position')]);

                return $this->params->serialize() . ';selectors-change:' . $this->params->get('selectors-change') . ';'
                    . 'disable-zoom: ' . ($this->params->check('disable-zoom', 'Yes') ? 'true' : 'false') . ';'
                    . 'disable-expand: ' . ($this->params->check('disable-expand', 'Yes') ? 'true' : 'false') . ';';
            }
            $rel = array();
            if(count($this->general->params)) {
                foreach($this->general->params as $name => $param) {
                    if($this->params->checkValue($name, $param['value'])) continue;
                    switch($name) {
                        case 'expand-speed':
                            $rel[] = 'expand-speed: ' . $this->params->getValue('expand-speed');
                            break;
                        case 'restore-speed':
                            $rel[] = 'restore-speed: ' . $this->params->getValue('restore-speed');
                            break;
                        case 'expand-effect':
                            $rel[] = 'expand-effect: ' . $this->params->getValue('expand-effect');
                            break;
                        case 'restore-effect':
                            $rel[] = 'restore-effect: ' . $this->params->getValue('restore-effect');
                            break;
                        case 'expand-align':
                            $rel[] = 'expand-align: ' . $this->params->getValue('expand-align');
                            break;
                        case 'expand-position':
                            $rel[] = 'expand-position: ' . $this->params->getValue('expand-position');
                            break;
                        case 'expand-size':
                            $rel[] = 'expand-size: ' . $this->params->getValue('expand-size');
                            break;
                        case 'background-color':
                            $rel[] = 'background-color: ' . $this->params->getValue('background-color');
                            break;
                        case 'background-opacity':
                            $rel[] = 'background-opacity: ' . $this->params->getValue('background-opacity');
                            break;
                        case 'background-speed':
                            $rel[] = 'background-speed: ' . $this->params->getValue('background-speed');
                            break;
                        case 'caption-speed':
                            $rel[] = 'caption-speed: ' . $this->params->getValue('caption-speed');
                            break;
                        case 'caption-position':
                            $rel[] = 'caption-position: ' . $this->params->getValue('caption-position');
                            break;
                        case 'caption-height':
                            $rel[] = 'caption-height: ' . $this->params->getValue('caption-height');
                            break;
                        case 'caption-width':
                            $rel[] = 'caption-width: ' . $this->params->getValue('caption-width');
                            break;
                        case 'buttons':
                            $rel[] = 'buttons: ' . $this->params->getValue('buttons');
                            break;
                        case 'buttons-position':
                            $rel[] = 'buttons-position: ' . $this->params->getValue('buttons-position');
                            break;
                        case 'buttons-display':
                            $rel[] = 'buttons-display: ' . $this->params->getValue('buttons-display');
                            break;
                        case 'loading-msg':
                            $rel[] = 'loading-msg: ' . $this->params->getValue('loading-msg');
                            break;
                        case 'loading-opacity':
                            $rel[] = 'loading-opacity: ' . $this->params->getValue('loading-opacity');
                            break;
                        case 'selectors-change':
                            $rel[] = 'selectors-change: ' . $this->params->getValue('selectors-change');
                            break;
                        case 'selectors-mouseover-delay':
                            $rel[] = 'selectors-mouseover-delay: ' . $this->params->getValue('selectors-mouseover-delay');
                            break;
                        case 'slideshow-effect':
                            $rel[] = 'slideshow-effect: ' . $this->params->getValue('slideshow-effect');
                            break;
                        case 'slideshow-speed':
                            $rel[] = 'slideshow-speed: ' . $this->params->getValue('slideshow-speed');
                            break;
                        case 'z-index':
                            $rel[] = 'z-index: ' . $this->params->getValue('z-index');
                            break;
                        case 'expand-trigger':
                            $rel[] = 'expand-trigger: ' . $this->params->getValue('expand-trigger');
                            break;
                        case 'restore-trigger':
                            $rel[] = 'restore-trigger: ' . $this->params->getValue('restore-trigger');
                            break;
                        case 'expand-trigger-delay':
                            $rel[] = 'expand-trigger-delay: ' . $this->params->getValue('expand-trigger-delay');
                            break;

                        case 'opacity':
                            $rel[] = 'opacity: ' . $this->params->getValue('opacity');
                            break;
                        case 'zoom-width':
                            $rel[] = 'zoom-width: ' . $this->params->getValue('zoom-width');
                            break;
                        case 'zoom-height':
                            $rel[] = 'zoom-height: ' . $this->params->getValue('zoom-height');
                            break;
                        case 'zoom-position':
                            $rel[] = 'zoom-position: ' . $this->params->getValue('zoom-position');
                            break;
                        case 'smoothing-speed':
                            $rel[] = 'smoothing-speed: ' . $this->params->getValue('smoothing-speed');
                            break;
                        case 'zoom-distance':
                            $rel[] = 'zoom-distance: ' . $this->params->getValue('zoom-distance');
                            break;
                        case 'zoom-fade-in-speed':
                            $rel[] = 'zoom-fade-in-speed: ' . $this->params->getValue('zoom-fade-in-speed');
                            break;
                        case 'zoom-fade-out-speed':
                            $rel[] = 'zoom-fade-out-speed: ' . $this->params->getValue('zoom-fade-out-speed');
                            break;
                        case 'fps':
                            $rel[] = 'fps: ' . $this->params->getValue('fps');
                            break;
                        case 'loading-position-x':
                            $rel[] = 'loading-position-x: ' . $this->params->getValue('loading-position-x');
                            break;
                        case 'loading-position-y':
                            $rel[] = 'loading-position-y: ' . $this->params->getValue('loading-position-y');
                            break;
                        case 'x':
                            $rel[] = 'x: ' . $this->params->getValue('x');
                            break;
                        case 'y':
                            $rel[] = 'y: ' . $this->params->getValue('y');
                            break;
                        case 'show-title':
                            $rel[] = 'show-title: ' . ($this->params->checkValue('show-title', 'disable')?'false':$this->params->getValue('show-title'));
                            break;
                        case 'selectors-effect':
                            if($this->params->checkValue('selectors-effect', 'disable')) {
                                $rel[] = 'selectors-effect: false';
                            } else {
                                $rel[] = 'selectors-effect: ' . $this->params->getValue('selectors-effect');
                            }
                            break;
                        case 'selectors-effect-speed':
                            $rel[] = 'selectors-effect-speed: ' . $this->params->getValue('selectors-effect-speed');
                            break;
/*                        case 'disable-zoom':
                            $rel[] = 'disable-zoom: ' . ($this->params->checkValue('disable-zoom', 'Yes')?'true':'false');
                            break;*/
/*                        case 'disable-expand':
                            $rel[] = 'disable-expand: ' . ($this->params->checkValue('disable-expand', 'Yes')?'true':'false');
                            break;*/
                        case 'keep-thumbnail':
                            $rel[] = 'keep-thumbnail: ' . ($this->params->checkValue('keep-thumbnail', 'Yes')?'true':'false');
                            break;
/*                        case 'click-to-initialize':
                            $rel[] = 'click-to-initialize: ' . ($this->params->checkValue('click-to-initialize', 'Yes')?'true':'false');
                            break;*/
                        case 'show-loading':
                            $rel[] = 'show-loading: ' . ($this->params->checkValue('show-loading', 'Yes')?'true':'false');
                            break;
                        case 'slideshow-loop':
                            $rel[] = 'slideshow-loop: ' . ($this->params->checkValue('slideshow-loop', 'Yes')?'true':'false');
                            break;
                        case 'keyboard':
                            $rel[] = 'keyboard: ' . ($this->params->checkValue('keyboard', 'Yes')?'true':'false');
                            break;
                        case 'keyboard-ctrl':
                            $rel[] = 'keyboard-ctrl: ' . ($this->params->checkValue('keyboard-ctrl', 'Yes')?'true':'false');
                            break;
                        case 'drag-mode':
                            $rel[] = 'drag-mode: ' . ($this->params->checkValue('drag-mode', 'Yes') ? 'true' : 'false');
                            break;
                        case 'always-show-zoom':
                            $rel[] = 'always-show-zoom: ' . ($this->params->checkValue('always-show-zoom', 'Yes') ? 'true' : 'false');
                            break;
                        case 'smoothing':
                            $rel[] = 'smoothing: ' . ($this->params->checkValue('smoothing', 'Yes') ? 'true' : 'false');
                            break;
                        case 'opacity-reverse':
                            $rel[] = 'opacity-reverse: ' . ($this->params->checkValue('opacity-reverse', 'Yes') ? 'true' : 'false');
                            break;
                        case 'click-to-activate':
                            $rel[] = 'click-to-activate: ' . ($this->params->checkValue('click-to-activate', 'Yes') ? 'true' : 'false');
                            break;
                        case 'click-to-deactivate':
                            $rel[] = 'click-to-deactivate: ' . ($this->params->checkValue('click-to-deactivate', 'Yes') ? 'true' : 'false');
                            break;
                        case 'preload-selectors-small':
                            $rel[] = 'preload-selectors-small: ' . ($this->params->checkValue('preload-selectors-small', 'Yes') ? 'true' : 'false');
                            break;
                        case 'preload-selectors-big':
                            $rel[] = 'preload-selectors-big: ' . ($this->params->checkValue('preload-selectors-big', 'Yes') ? 'true' : 'false');
                            break;
                        case 'zoom-fade':
                            $rel[] = 'zoom-fade: ' . ($this->params->checkValue('zoom-fade', 'Yes') ? 'true' : 'false');
                            break;
                        case 'move-on-click':
                            $rel[] = 'move-on-click: ' . ($this->params->checkValue('move-on-click', 'Yes') ? 'true' : 'false');
                            break;
                        case 'preserve-position':
                            $rel[] = 'preserve-position: ' . ($this->params->checkValue('preserve-position', 'Yes') ? 'true' : 'false');
                            break;
                        case 'fit-zoom-window':
                            $rel[] = 'fit-zoom-window: ' . ($this->params->checkValue('fit-zoom-window', 'Yes') ? 'true' : 'false');
                            break;
                        case 'entire-image':
                            $rel[] = 'entire-image: ' . ($this->params->checkValue('entire-image', 'Yes') ? 'true' : 'false');
                            break;
                        case 'pan-zoom':
                            $rel[] = 'pan-zoom: ' . ($this->params->checkValue('pan-zoom', 'Yes') ? 'true' : 'false');
                            break;
                        case 'zoom-align':
                            $rel[] = 'zoom-align: ' . $this->params->getValue('zoom-align');
                            break;
                        case 'zoom-window-effect':
                            $rel[] = 'zoom-window-effect: ' . $this->params->getValue('zoom-window-effect');
                            break;
                        case 'selectors-class':
                            $rel[] = 'selectors-class: ' . $this->params->getValue('selectors-class');
                            break;
                        case 'hint':
                            $rel[] = 'hint: ' . ($this->params->checkValue('hint', 'Yes') ? 'true' : 'false');
                            break;
                        case 'hint-text':
                            $rel[] = 'hint-text: ' . $this->params->getValue('hint-text');
                            break;
                        case 'hint-position':
                            switch($this->params->getValue('hint-position')) {
                                case 'top left':
                                    $rel[] = 'hint-position: tl';
                                    break;
                                case 'top right':
                                    $rel[] = 'hint-position: tr';
                                    break;
                                case 'top center':
                                    $rel[] = 'hint-position: tc';
                                    break;
                                case 'bottom left':
                                    $rel[] = 'hint-position: bl';
                                    break;
                                case 'bottom right':
                                    $rel[] = 'hint-position: br';
                                    break;
                                case 'bottom center':
                                    $rel[] = 'hint-position: bc';
                                    break;
                            }
                            break;
                        case 'hint-opacity':
                            $rel[] = 'hint-opacity: ' . $this->params->getValue('hint-opacity');
                            break;
                        case 'right-click':
                            switch($this->params->getValue('right-click')) {
                                case 'Yes':
                                    $rel[] = 'right-click: true';
                                    break;
                                case 'Original':
                                    $rel[] = 'right-click: original';
                                    break;
                                case 'Expanded':
                                    $rel[] = 'right-click: expanded';
                                    break;
                                case 'No':
                                    $rel[] = 'right-click: false';
                                    break;
                            }
                            break;
                        case 'initialize-on':
                            $rel[] = 'initialize-on: ' . $this->params->getValue('initialize-on');
                            break;

                    }
                }
            }
            $rel[] = 'disable-zoom: ' . ($this->params->checkValue('disable-zoom', 'Yes') ? 'true' : 'false');
            $rel[] = 'disable-expand: ' . ($this->params->checkValue('disable-expand', 'Yes') ? 'true' : 'false');
            $rel = implode(';',$rel) . ';';
            return $rel;
        }

        function _paramDefaults() {
            $params = array("template"=>array("id"=>"template","group"=>"General","order"=>"20","default"=>"original","label"=>"Thumbnail layout","type"=>"array","subType"=>"select","values"=>array("original","bottom","left","right","top"),"scope"=>"profile"),"magicscroll"=>array("id"=>"magicscroll","group"=>"General","order"=>"22","default"=>"No","label"=>"Use Magic Scroll™ for thumbnails","description"=>"(does not work with template=original)","type"=>"array","subType"=>"select","values"=>array("Yes","No"),"scope"=>"profile"),"thumb-max-width"=>array("id"=>"thumb-max-width","group"=>"Positioning and Geometry","order"=>"10","default"=>"250","label"=>"Maximum width of thumbnail (in pixels)","type"=>"num"),"thumb-max-height"=>array("id"=>"thumb-max-height","group"=>"Positioning and Geometry","order"=>"11","default"=>"250","label"=>"Maximum height of thumbnail (in pixels)","type"=>"num"),"category-thumb-max-width"=>array("id"=>"category-thumb-max-width","group"=>"Positioning and Geometry","order"=>"20","default"=>"135","label"=>"Maximum width of thumbnail on category pages (in pixels)","type"=>"num"),"category-thumb-max-height"=>array("id"=>"category-thumb-max-height","group"=>"Positioning and Geometry","order"=>"21","default"=>"135","label"=>"Maximum height of thumbnail on category pages (in pixels)","type"=>"num"),"square-images"=>array("id"=>"square-images","group"=>"Positioning and Geometry","order"=>"40","default"=>"No","label"=>"Allways create square thumbnails","description"=>"","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"zoom-width"=>array("id"=>"zoom-width","group"=>"Positioning and Geometry","order"=>"140","default"=>"300","label"=>"Zoomed area width (in pixels)","type"=>"num","scope"=>"tool"),"zoom-height"=>array("id"=>"zoom-height","group"=>"Positioning and Geometry","order"=>"150","default"=>"300","label"=>"Zoomed area height (in pixels)","type"=>"num","scope"=>"tool"),"zoom-position"=>array("id"=>"zoom-position","group"=>"Positioning and Geometry","order"=>"160","default"=>"right","label"=>"Zoomed area position","type"=>"array","subType"=>"select","values"=>array("top","right","bottom","left","inner"),"scope"=>"tool"),"zoom-align"=>array("id"=>"zoom-align","group"=>"Positioning and Geometry","order"=>"161","default"=>"top","label"=>"How to align zoom window to an image","type"=>"array","subType"=>"select","values"=>array("right","left","top","bottom","center"),"scope"=>"tool"),"zoom-distance"=>array("id"=>"zoom-distance","group"=>"Positioning and Geometry","order"=>"170","default"=>"15","label"=>"Distance between small image and zoom window (in pixels)","type"=>"num","scope"=>"tool"),"expand-size"=>array("id"=>"expand-size","group"=>"Positioning and Geometry","order"=>"210","default"=>"fit-screen","label"=>"Size of the expanded image","type"=>"text","description"=>"The value can be 'fit-screen', 'original' or width/height. E.g. 'width=400' or 'height=350'","scope"=>"tool"),"expand-position"=>array("id"=>"expand-position","group"=>"Positioning and Geometry","order"=>"220","default"=>"center","label"=>"Precise position of enlarged image (px)","type"=>"text","description"=>"The value can be 'center' or coordinates. E.g. 'top=0, left=0' or 'bottom=100, left=100'","scope"=>"tool"),"expand-align"=>array("id"=>"expand-align","group"=>"Positioning and Geometry","order"=>"230","default"=>"screen","label"=>"Align expanded image relative to screen or thumbnail","type"=>"array","subType"=>"select","values"=>array("screen","image"),"scope"=>"tool"),"expand-effect"=>array("id"=>"expand-effect","group"=>"Effects","order"=>"10","default"=>"back","label"=>"Effect while expanding image","type"=>"array","subType"=>"select","values"=>array("linear","cubic","back","elastic","bounce"),"scope"=>"tool"),"restore-effect"=>array("id"=>"restore-effect","group"=>"Effects","order"=>"20","default"=>"linear","label"=>"Effect while restoring image","type"=>"array","subType"=>"select","values"=>array("linear","cubic","back","elastic","bounce"),"scope"=>"tool"),"expand-speed"=>array("id"=>"expand-speed","group"=>"Effects","order"=>"30","default"=>"500","label"=>"Expand duration (milliseconds: 0-10000)","type"=>"num","scope"=>"tool"),"restore-speed"=>array("id"=>"restore-speed","group"=>"Effects","order"=>"40","default"=>"-1","label"=>"Restore duration (milliseconds: 0-10000, -1: use expand-speed value)","type"=>"num","scope"=>"tool"),"expand-trigger"=>array("id"=>"expand-trigger","group"=>"Effects","order"=>"50","default"=>"click","label"=>"Trigger for the enlarge effect","type"=>"array","subType"=>"select","values"=>array("click","mouseover"),"scope"=>"tool"),"expand-trigger-delay"=>array("id"=>"expand-trigger-delay","group"=>"Effects","order"=>"60","default"=>"200","label"=>"Delay before mouseover triggers expand effect (milliseconds: 0 or larger)","type"=>"num","scope"=>"tool"),"restore-trigger"=>array("id"=>"restore-trigger","group"=>"Effects","order"=>"70","default"=>"auto","label"=>"Trigger to restore image to its small state","type"=>"array","subType"=>"select","values"=>array("auto","click","mouseout"),"scope"=>"tool"),"keep-thumbnail"=>array("id"=>"keep-thumbnail","group"=>"Effects","order"=>"80","default"=>"Yes","label"=>"Show/hide thumbnail when image enlarged","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"opacity"=>array("id"=>"opacity","group"=>"Effects","order"=>"270","default"=>"50","label"=>"Square opacity","type"=>"num","scope"=>"tool"),"opacity-reverse"=>array("id"=>"opacity-reverse","group"=>"Effects","order"=>"280","default"=>"No","label"=>"Add opacity to background instead of hovered area","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"zoom-fade"=>array("id"=>"zoom-fade","group"=>"Effects","order"=>"290","default"=>"Yes","label"=>"Zoom window fade effect","type"=>"array","subType"=>"select","values"=>array("Yes","No"),"scope"=>"tool"),"zoom-window-effect"=>array("id"=>"zoom-window-effect","group"=>"Effects","order"=>"291","default"=>"shadow","label"=>"Apply shadow or glow on a zoom window","type"=>"array","subType"=>"select","values"=>array("shadow","glow","false"),"scope"=>"tool"),"zoom-fade-in-speed"=>array("id"=>"zoom-fade-in-speed","group"=>"Effects","order"=>"300","default"=>"200","label"=>"Zoom window fade-in speed (in milliseconds)","type"=>"num","scope"=>"tool"),"zoom-fade-out-speed"=>array("id"=>"zoom-fade-out-speed","group"=>"Effects","order"=>"310","default"=>"200","label"=>"Zoom window fade-out speed  (in milliseconds)","type"=>"num","scope"=>"tool"),"fps"=>array("id"=>"fps","group"=>"Effects","order"=>"320","default"=>"25","label"=>"Frames per second for zoom effect","type"=>"num","scope"=>"tool"),"smoothing"=>array("id"=>"smoothing","group"=>"Effects","order"=>"330","default"=>"Yes","label"=>"Enable smooth zoom movement","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"smoothing-speed"=>array("id"=>"smoothing-speed","group"=>"Effects","order"=>"340","default"=>"40","label"=>"Speed of smoothing (1-99)","type"=>"num","scope"=>"tool"),"pan-zoom"=>array("id"=>"pan-zoom","group"=>"Effects","order"=>"341","default"=>"Yes","label"=>"Zoom/pan the expanded image","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"selector-max-width"=>array("id"=>"selector-max-width","group"=>"Multiple images","order"=>"10","default"=>"56","label"=>"Maximum width of additional thumbnails (in pixels)","type"=>"num"),"selector-max-height"=>array("id"=>"selector-max-height","group"=>"Multiple images","order"=>"11","default"=>"56","label"=>"Maximum height of additional thumbnails (in pixels)","type"=>"num"),"show-selectors-on-category-page"=>array("id"=>"show-selectors-on-category-page","group"=>"Multiple images","order"=>"20","default"=>"No","label"=>"Show selectors on category page","type"=>"array","subType"=>"select","values"=>array("Yes","No")),"category-selector-max-width"=>array("id"=>"category-selector-max-width","group"=>"Multiple images","order"=>"30","default"=>"56","label"=>"Maximum width of additional thumbnails on category pages (in pixels)","type"=>"num"),"category-selector-max-height"=>array("id"=>"category-selector-max-height","group"=>"Multiple images","order"=>"31","default"=>"56","label"=>"Maximum height of additional thumbnails on category pages (in pixels)","type"=>"num"),"use-individual-titles"=>array("id"=>"use-individual-titles","group"=>"Multiple images","order"=>"40","default"=>"Yes","label"=>"Use individual image titles for additional images?","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"selectors-margin"=>array("id"=>"selectors-margin","group"=>"Multiple images","order"=>"40","default"=>"5","label"=>"Margin between selectors and main image (in pixels)","type"=>"num"),"selectors-change"=>array("id"=>"selectors-change","group"=>"Multiple images","order"=>"110","default"=>"click","label"=>"Method to switch between multiple images","type"=>"array","subType"=>"select","values"=>array("click","mouseover"),"scope"=>"tool"),"selectors-class"=>array("id"=>"selectors-class","group"=>"Multiple images","order"=>"111","default"=>"","label"=>"Define a CSS class of the active selector","type"=>"text","scope"=>"tool"),"preload-selectors-small"=>array("id"=>"preload-selectors-small","group"=>"Multiple images","order"=>"120","default"=>"Yes","label"=>"Multiple images, preload small images","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"preload-selectors-big"=>array("id"=>"preload-selectors-big","group"=>"Multiple images","order"=>"130","default"=>"No","label"=>"Multiple images, preload large images","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"selectors-effect"=>array("id"=>"selectors-effect","group"=>"Multiple images","order"=>"140","default"=>"dissolve","label"=>"Dissolve or cross fade thumbnail when switching thumbnails","type"=>"array","subType"=>"select","values"=>array("dissolve","fade","pounce","disable"),"scope"=>"tool"),"selectors-effect-speed"=>array("id"=>"selectors-effect-speed","group"=>"Multiple images","order"=>"150","default"=>"400","label"=>"Selectors effect speed, ms","type"=>"num","scope"=>"tool"),"selectors-mouseover-delay"=>array("id"=>"selectors-mouseover-delay","group"=>"Multiple images","order"=>"160","default"=>"60","label"=>"Multiple images delay in ms before switching thumbnails","type"=>"num","scope"=>"tool"),"initialize-on"=>array("id"=>"initialize-on","group"=>"Initialization","order"=>"70","default"=>"load","label"=>"How to initialize Magic Zoom and download large image","type"=>"array","subType"=>"radio","values"=>array("load","click","mouseover"),"scope"=>"tool"),"click-to-activate"=>array("id"=>"click-to-activate","group"=>"Initialization","order"=>"80","default"=>"No","label"=>"Click to show the zoom","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"click-to-deactivate"=>array("id"=>"click-to-deactivate","group"=>"Initialization","order"=>"81","default"=>"No","label"=>"Allow click to hide the zoom window","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"show-loading"=>array("id"=>"show-loading","group"=>"Initialization","order"=>"90","default"=>"Yes","label"=>"Loading message","type"=>"array","subType"=>"select","values"=>array("Yes","No"),"scope"=>"tool"),"loading-msg"=>array("id"=>"loading-msg","group"=>"Initialization","order"=>"100","default"=>"Loading zoom...","label"=>"Loading message text","type"=>"text","scope"=>"tool"),"loading-opacity"=>array("id"=>"loading-opacity","group"=>"Initialization","order"=>"110","default"=>"75","label"=>"Loading message opacity (0-100)","type"=>"num","scope"=>"tool"),"loading-position-x"=>array("id"=>"loading-position-x","group"=>"Initialization","order"=>"120","default"=>"-1","label"=>"Loading message X-axis position, -1 is center","type"=>"num","scope"=>"tool"),"loading-position-y"=>array("id"=>"loading-position-y","group"=>"Initialization","order"=>"130","default"=>"-1","label"=>"Loading message Y-axis position, -1 is center","type"=>"num","scope"=>"tool"),"entire-image"=>array("id"=>"entire-image","group"=>"Initialization","order"=>"140","default"=>"No","label"=>"Show entire large image on hover","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"show-title"=>array("id"=>"show-title","group"=>"Title and Caption","order"=>"10","default"=>"top","label"=>"Show the title of the image in the zoom window","type"=>"array","subType"=>"select","values"=>array("top","bottom","disable"),"scope"=>"tool"),"show-caption"=>array("id"=>"show-caption","group"=>"Title and Caption","order"=>"20","default"=>"Yes","label"=>"Show caption","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"caption-source"=>array("id"=>"caption-source","group"=>"Title and Caption","order"=>"30","default"=>"Title","label"=>"Caption source","type"=>"text","values"=>array("Title","Short description","Description","All")),"caption-width"=>array("id"=>"caption-width","group"=>"Title and Caption","order"=>"40","default"=>"300","label"=>"Max width of bottom caption (pixels: 0 or larger)","type"=>"num","scope"=>"tool"),"caption-height"=>array("id"=>"caption-height","group"=>"Title and Caption","order"=>"50","default"=>"300","label"=>"Max height of bottom caption (pixels: 0 or larger)","type"=>"num","scope"=>"tool"),"caption-position"=>array("id"=>"caption-position","group"=>"Title and Caption","order"=>"60","default"=>"bottom","label"=>"Where to position the caption","type"=>"array","subType"=>"select","values"=>array("bottom","right","left"),"scope"=>"tool"),"caption-speed"=>array("id"=>"caption-speed","group"=>"Title and Caption","order"=>"70","default"=>"250","label"=>"Speed of the caption slide effect (milliseconds: 0 or larger)","type"=>"num","scope"=>"tool"),"use-effect-on-product-page"=>array("id"=>"use-effect-on-product-page","group"=>"Miscellaneous","order"=>"10","default"=>"Zoom&amp;Expand","label"=>"Use effect on product page","type"=>"array","subType"=>"select","values"=>array("Zoom&amp;Expand","Zoom","Expand","No")),"use-effect-on-category-page"=>array("id"=>"use-effect-on-category-page","group"=>"Miscellaneous","order"=>"20","default"=>"No","label"=>"Use effect on category page","type"=>"array","subType"=>"select","values"=>array("Zoom&amp;Expand","Zoom","Expand","No")),"link-to-product-page"=>array("id"=>"link-to-product-page","group"=>"Miscellaneous","order"=>"30","default"=>"Yes","label"=>"Link enlarged image to the product page","type"=>"array","subType"=>"select","values"=>array("Yes","No")),"option-associated-with-images"=>array("id"=>"option-associated-with-images","group"=>"Miscellaneous","order"=>"40","default"=>"color","label"=>"Options names associated with images separated by commas (e.g 'Color,Size')","description"=>"You should named all product images associated with option values. e.g If option values is 'red', 'blue' and 'white' then you should have 3 images with labels 'red', 'blue' and 'white'","type"=>"text"),"load-associated-product-images"=>array("id"=>"load-associated-product-images","group"=>"Miscellaneous","order"=>"41","default"=>"No","label"=>"Show associated product's images when selecting options","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"ignore-magento-css"=>array("id"=>"ignore-magento-css","group"=>"Miscellaneous","order"=>"50","default"=>"No","label"=>"Ignore magento CSS width/height styles for additional images","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"show-message"=>array("id"=>"show-message","group"=>"Miscellaneous","order"=>"370","default"=>"Yes","label"=>"Show message under image?","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"message"=>array("id"=>"message","group"=>"Miscellaneous","order"=>"380","default"=>"Move your mouse over image or click to enlarge","label"=>"Message under images","type"=>"text"),"right-click"=>array("id"=>"right-click","group"=>"Miscellaneous","order"=>"385","default"=>"No","label"=>"Show right-click menu on the image","type"=>"array","subType"=>"radio","values"=>array("Yes","Original","Expanded","No"),"scope"=>"tool"),"image-magick-path"=>array("id"=>"image-magick-path","group"=>"Miscellaneous","order"=>"570","default"=>"/usr/bin","label"=>"Image magick binaries path","type"=>"text"),"background-opacity"=>array("id"=>"background-opacity","group"=>"Background","order"=>"10","default"=>"30","label"=>"Opacity of the background effect (0-100)","type"=>"num","scope"=>"tool"),"background-color"=>array("id"=>"background-color","group"=>"Background","order"=>"20","default"=>"#000000","label"=>"Fade background color (RGB)","type"=>"text","scope"=>"tool"),"background-speed"=>array("id"=>"background-speed","group"=>"Background","order"=>"30","default"=>"200","label"=>"Speed of the fade effect (milliseconds: 0 or larger)","type"=>"num","scope"=>"tool"),"buttons"=>array("id"=>"buttons","group"=>"Buttons","order"=>"10","default"=>"show","label"=>"Whether to show navigation buttons","type"=>"array","subType"=>"select","values"=>array("show","hide","autohide"),"scope"=>"tool"),"buttons-display"=>array("id"=>"buttons-display","group"=>"Buttons","order"=>"20","default"=>"previous, next, close","label"=>"Display button","type"=>"text","description"=>"Show all three buttons or just one or two. E.g. 'previous, next' or 'close, next'","scope"=>"tool"),"buttons-position"=>array("id"=>"buttons-position","group"=>"Buttons","order"=>"30","default"=>"auto","label"=>"Location of navigation buttons","type"=>"array","subType"=>"select","values"=>array("auto","top left","top right","bottom left","bottom right"),"scope"=>"tool"),"always-show-zoom"=>array("id"=>"always-show-zoom","group"=>"Zoom mode","order"=>"10","default"=>"No","label"=>"Always show zoom?","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"drag-mode"=>array("id"=>"drag-mode","group"=>"Zoom mode","order"=>"20","default"=>"No","label"=>"Use drag mode?","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"move-on-click"=>array("id"=>"move-on-click","group"=>"Zoom mode","order"=>"30","default"=>"Yes","label"=>"Click alone will also move zoom (drag mode only)","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"x"=>array("id"=>"x","group"=>"Zoom mode","order"=>"40","default"=>"-1","label"=>"Initial zoom X-axis position for drag mode, -1 is center","type"=>"num","scope"=>"tool"),"y"=>array("id"=>"y","group"=>"Zoom mode","order"=>"50","default"=>"-1","label"=>"Initial zoom Y-axis position for drag mode, -1 is center","type"=>"num","scope"=>"tool"),"preserve-position"=>array("id"=>"preserve-position","group"=>"Zoom mode","order"=>"60","default"=>"No","label"=>"Position of zoom can be remembered for multiple images and drag mode","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"fit-zoom-window"=>array("id"=>"fit-zoom-window","group"=>"Zoom mode","order"=>"70","default"=>"Yes","label"=>"Resize zoom window if big image is smaller than zoom window","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"slideshow-effect"=>array("id"=>"slideshow-effect","group"=>"Expand mode","order"=>"10","default"=>"dissolve","label"=>"Visual effect for switching images","type"=>"array","subType"=>"select","values"=>array("dissolve","fade","expand"),"scope"=>"tool"),"slideshow-loop"=>array("id"=>"slideshow-loop","group"=>"Expand mode","order"=>"20","default"=>"Yes","label"=>"Restart slideshow after last image","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"slideshow-speed"=>array("id"=>"slideshow-speed","group"=>"Expand mode","order"=>"30","default"=>"800","label"=>"Speed of slideshow effect (milliseconds: 0 or larger)","type"=>"num","scope"=>"tool"),"z-index"=>array("id"=>"z-index","group"=>"Expand mode","order"=>"40","default"=>"10001","label"=>"The z-index for the enlarged image","type"=>"num"),"keyboard"=>array("id"=>"keyboard","group"=>"Expand mode","order"=>"50","default"=>"Yes","label"=>"Ability to use keyboard shortcuts","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"keyboard-ctrl"=>array("id"=>"keyboard-ctrl","group"=>"Expand mode","order"=>"60","default"=>"No","label"=>"Require Ctrl key to permit shortcuts","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"hint"=>array("id"=>"hint","group"=>"Hint","order"=>"10","default"=>"Yes","label"=>"Display a hint to suggest that image is zoomable","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"tool"),"hint-text"=>array("id"=>"hint-text","group"=>"Hint","order"=>"15","default"=>"Zoom","label"=>"Show text in the hint","type"=>"text","scope"=>"tool"),"hint-position"=>array("id"=>"hint-position","group"=>"Hint","order"=>"20","default"=>"top left","label"=>"Position of the hint","type"=>"array","subType"=>"select","values"=>array("top left","top right","top center","bottom left","bottom right","bottom center"),"scope"=>"tool"),"hint-opacity"=>array("id"=>"hint-opacity","group"=>"Hint","order"=>"25","default"=>"75","label"=>"Opacity of the hint (0-100)","type"=>"num","scope"=>"tool"),"scroll-style"=>array("id"=>"scroll-style","group"=>"Scroll","order"=>"5","default"=>"default","label"=>"Style","type"=>"array","subType"=>"select","values"=>array("default","with-borders"),"scope"=>"profile"),"loop"=>array("id"=>"loop","group"=>"Scroll","order"=>"10","default"=>"continue","label"=>"Restart scroll after last image","description"=>"Continue to next image or scroll all the way back","type"=>"array","subType"=>"radio","values"=>array("continue","restart"),"scope"=>"MagicScroll"),"speed"=>array("id"=>"speed","group"=>"Scroll","order"=>"20","default"=>"5000","label"=>"Scroll speed","description"=>"Change the scroll speed in miliseconds (0 = manual)","type"=>"num","scope"=>"MagicScroll"),"width"=>array("id"=>"width","group"=>"Scroll","order"=>"30","default"=>"0","label"=>"Scroll width (pixels)","description"=>"0 - auto","type"=>"num","scope"=>"MagicScroll"),"height"=>array("id"=>"height","group"=>"Scroll","order"=>"40","default"=>"0","label"=>"Scroll height (pixels)","description"=>"0 - auto","type"=>"num","scope"=>"MagicScroll"),"item-width"=>array("id"=>"item-width","group"=>"Scroll","order"=>"50","default"=>"0","label"=>"Scroll item width (pixels)","description"=>"0 - auto","type"=>"num","scope"=>"MagicScroll"),"item-height"=>array("id"=>"item-height","group"=>"Scroll","order"=>"60","default"=>"0","label"=>"Scroll item height (pixels)","description"=>"0 - auto","type"=>"num","scope"=>"MagicScroll"),"step"=>array("id"=>"step","group"=>"Scroll","order"=>"70","default"=>"3","label"=>"Scroll step","type"=>"num","scope"=>"MagicScroll"),"items"=>array("id"=>"items","group"=>"Scroll","order"=>"80","default"=>"3","label"=>"Items to show","description"=>"0 - manual","type"=>"num","scope"=>"MagicScroll"),"arrows"=>array("id"=>"arrows","group"=>"Scroll Arrows","order"=>"10","default"=>"outside","label"=>"Show arrows","label"=>"Where arrows should be placed","type"=>"array","subType"=>"radio","values"=>array("outside","inside","false"),"scope"=>"MagicScroll"),"arrows-opacity"=>array("id"=>"arrows-opacity","group"=>"Scroll Arrows","order"=>"20","default"=>"60","label"=>"Opacity of arrows (0-100)","type"=>"num","scope"=>"MagicScroll"),"arrows-hover-opacity"=>array("id"=>"arrows-hover-opacity","group"=>"Scroll Arrows","order"=>"30","default"=>"100","label"=>"Opacity of arrows on mouse over (0-100)","type"=>"num","scope"=>"MagicScroll"),"slider-size"=>array("id"=>"slider-size","group"=>"Scroll Slider","order"=>"10","default"=>"10%","label"=>"Slider size (numeric or percent)","type"=>"text","scope"=>"MagicScroll"),"slider"=>array("id"=>"slider","group"=>"Scroll Slider","order"=>"20","default"=>"false","label"=>"Slider postion","type"=>"array","subType"=>"select","values"=>array("top","right","bottom","left","false"),"scope"=>"MagicScroll"),"direction"=>array("id"=>"direction","group"=>"Scroll effect","order"=>"10","default"=>"right","value"=>"bottom","label"=>"Direction of scroll","type"=>"array","subType"=>"select","values"=>array("top","right","bottom","left"),"scope"=>"MagicScroll"),"duration"=>array("id"=>"duration","group"=>"Scroll effect","order"=>"20","default"=>"1000","label"=>"Duration of effect (miliseconds)","type"=>"num","scope"=>"MagicScroll"));
            $params = array_merge($params, array(
                'disable-expand' => array(
                    'label' => 'Disable expand effect',
                    'id' => 'disable-expand',
                    'scope' => 'tool',
                    'default' => 'No',
                    'value' => 'No',
                    'type' => 'text'
                ),
                'disable-zoom' => array(
                    'label' => 'Disable zoom effect',
                    'id' => 'disable-zoom',
                    'scope' => 'tool',
                    'default' => 'No',
                    'value' => 'No',
                    'type' => 'text'
                )
            ));
            $this->params->appendArray($params);
        }
    }

}
?>
