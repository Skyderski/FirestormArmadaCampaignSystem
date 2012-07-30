<?php

class Zend_View_Helper_DisplayMap extends Zend_View_Helper_Abstract{
    
    public function displayMap($map,$elements,$scale){
        
        
        return array("style"=> $this->createStyleforMap($map,$scale),
            "script"=>$this->mapscript($map,$scale),
            "maphtml" =>  $this->map2html($map,$elements,$scale));
        
        
    }
    
    public function createStyleforMap($map,$scale){
        
        $MAP_WIDTH = $map->width;
        $MAP_HEIGHT = $map->height;
        $HEX_HEIGHT = 90;

        // --- Use this to scale the hexes smaller or larger than the actual graphics
        $HEX_SCALED_HEIGHT = $HEX_HEIGHT * $scale;
        $HEX_SIDE = $HEX_SCALED_HEIGHT / 2;
        
        $HEX_SCALED_HEIGHT_IMG = $HEX_SCALED_HEIGHT * 0.5;
        $HEX_SCALED_IMG_POS = ($HEX_SCALED_HEIGHT - $HEX_SCALED_HEIGHT_IMG) /2;
        
       
        $hexmapWidth = $MAP_WIDTH * $HEX_SIDE * 1.5 + $HEX_SIDE/2;
        $hexmapHeight = $MAP_HEIGHT * $HEX_SCALED_HEIGHT + $HEX_SIDE;
        $hexmapElementHeight = $HEX_HEIGHT * 1.5;
        $hexmapElementWidth = $HEX_HEIGHT * 1.5;
        $hexmapBackGround='/images/map/backgrounds/'.$map->background;
        
        $hexmapWidthBorder = $hexmapWidth+5;
        $hexmapHeightBorder = $hexmapHeight+5;
        
        $styleOutput = <<<EOD
        .hexmap{
            width: {$hexmapWidth}px;
            height: {$hexmapHeight}px;
            position: relative;
           
             background-image:url('{$hexmapBackGround}');
            
        }
             
        .hexmapborder{
             width: {$hexmapWidthBorder}px;
            height: {$hexmapHeightBorder}px;
        }
             
             
       
        .hex-key-element {
            width: {$hexmapElementWidth}px;
            height: {$hexmapElementHeight}px;
            border: 1px solid #fff;
            float: left;
            text-align: center;
        }

        .hex {
            position: absolute;
            width: {$HEX_SCALED_HEIGHT}px;
            height: {$HEX_SCALED_HEIGHT}px;
        }
        
        .hex img.bg {
            width: {$HEX_SCALED_HEIGHT}px;
            height: {$HEX_SCALED_HEIGHT}px;
            top:0;
            left:0;
            }
            
        .hex img {
           width: {$HEX_SCALED_HEIGHT_IMG}px;
           height: {$HEX_SCALED_HEIGHT_IMG}px;
           top:{$HEX_SCALED_IMG_POS}px;
           left:{$HEX_SCALED_IMG_POS}px;
 
        }
        .hex a {
            position: absolute;
            width: {$HEX_SCALED_HEIGHT}px;
            height: {$HEX_SCALED_HEIGHT}px;
            z-index: 270;
            text-decoration : none;
        }
        
        .hex a:hover{
            background-image:url('/images/map/hex-highlight.png');
            background-size:{$HEX_SCALED_HEIGHT}px {$HEX_SCALED_HEIGHT}px;
        }
        
        .hex a.selected{
            background-image:url('/images/map/greenbg.png');
            background-size:{$HEX_SCALED_HEIGHT}px {$HEX_SCALED_HEIGHT}px;
        }

        
EOD;
        
        return $styleOutput;
        
    }
    
    public function mapscript($map,$scale){
        
        $MAP_WIDTH = $map->width;
        $MAP_HEIGHT = $map->height;
        $HEX_HEIGHT = 90;

        // --- Use this to scale the hexes smaller or larger than the actual graphics
        $HEX_SCALED_HEIGHT = $HEX_HEIGHT * $scale;
        $HEX_SIDE = $HEX_SCALED_HEIGHT / 2;
       
        $hexmapWidth = $MAP_WIDTH * $HEX_SIDE * 1.5 + $HEX_SIDE/2;
        $hexmapHeight = $MAP_HEIGHT * $HEX_SCALED_HEIGHT + $HEX_SIDE;
        $hexmapElementHeight = $HEX_HEIGHT * 1.5;
        $hexmapElementWidth = $HEX_HEIGHT * 1.5;
        $hexmapBackGround="";
        
        $scriptoutput = <<<EOD
         
EOD;
        
        return $scriptoutput;
        
        
    }
    
    public function  formatElements($elements){
        
        $output = array();
        
        foreach ($elements as $element){
           
            $output[$element->Xcoord][$element->Ycoord]=$element;
            
        }
        
        return $output;
        
    }
    
    
    public function map2html($map,$elements,$scale){
        // -------------------------------------------------------------
        // --- This function renders the map to HTML.  It uses the $map
        // --- array to determine what is in each hex, and the 
        // --- $terrain_images array to determine what type of image to
        // --- draw in each cell.
        // -------------------------------------------------------------
        $MAP_WIDTH = $map->width;
        $MAP_HEIGHT = $map->height;
        $HEX_HEIGHT = 90;

        // --- Use this to scale the hexes smaller or larger than the actual graphics
        $HEX_SCALED_HEIGHT = $HEX_HEIGHT * $scale;
        $HEX_SIDE = $HEX_SCALED_HEIGHT / 2;
        
        $elementsArray = array();
        if(count($elements->toArray()))$elementsArray = $this->formatElements($elements);

        $front = Zend_Controller_Front::getInstance();
        $controllerName = $front->getRequest()->getControllerName();
        
$output="";

$outputScript="";

        // -------------------------------------------------------------
        // --- Draw each hex in the map
        // -------------------------------------------------------------
        for ($x=0; $x<$MAP_WIDTH; $x++) {
            for ($y=0; $y<$MAP_HEIGHT; $y++) {
                               // --- Coordinates to place hex on the screen
                $tx = $x * $HEX_SIDE * 1.5;
                $ty = $y * $HEX_SCALED_HEIGHT + ($x % 2) * $HEX_SCALED_HEIGHT / 2;

                // --- Style values to position hex image in the right location
                $style = sprintf("left:%dpx;top:%dpx", $tx, $ty);

                $img = "/images/map/bleubg.png";
                
                $output.="<div title='X{$x}Y{$y}' id='X{$x}Y{$y}' class='hex' style='zindex:99;$style'>";
                // --- Output the image tag for this hex
              // $jquery = $this->view->jQuery();
        
                
                $output.= "<img  src='$img' alt='$terrain' class='bg' />\n";
                
                // on place les elements
                
                $x=intval($x);
                $y=intval($y);
                
                
                if($elementsArray[$x][$y]){
                 
                    
                    $currentElement = $elementsArray[$x][$y];
                    $output.= "<img title='X{$x}Y{$y}' data-content='lol' src='/images/elements/".$currentElement->image."' alt='$terrain' class='' style='zindex:99;'/>\n";
                    
                    
                    $elementDetail = $this->view->displayElement($currentElement);
                    
                    $outputScript.= "$('#X{$x}Y{$y} a').popover({'delay':{show : 100 , hide : 500} , 'trigger' : 'manual' , 'content' : ' ".trim( preg_replace( '/\s+/', ' ', $elementDetail ) )  ." ', title : '{$currentElement['name']}' });";
                    
                    
                }
                
                
                 
                
                
                //$output.= "<img title='X{$x}Y{$y}' src='/images/fleet/directorate2.png' alt='$terrain' class='bg' style='zindex:99;'/>\n";
                
                $url ="/".$controllerName."/elementshow/mapid/".$map->id."/xcoord/".$x."/ycoord/".$y;
                
                
                $output.="<a href='#' id='X{$x}Y{$y}link' class='hexlink'>&nbsp;</a>";
                /*
                $output.= $this->view->ajaxLink("&nbsp;",
                    $url ,
                    array(
                            'class' => 'hexlink',
                            'beforeSend' => "$('.selected').removeClass('selected');$(this).addClass('selected');",
                            'complete' => "$('#X{$x}Y{$y}').popover({'content': data, 'delay': { show: 500, hide: 100 }});"
                            ));
                */
               
                $output.= "</div>";
                 
                 
            }
            
           
           
        }
        
        $outputScript.= "\n$('.hexlink').hover(function(){ $(this).popover('show')},function(){ if(lastclick!=$(this).attr('id')) $(this).popover('hide')});\n";
        
        $outputScript.= "$('.hexlink').click(function(){ $('.selected').removeClass('selected');$(this).addClass('selected');$('#'+lastclick).popover('hide');lastclick=$(this).attr('id')  ;return false;});";
        
        
        
        
        $this->view->headScript()->appendScript("var lastclick=''; $(document).ready(function(){ ".$outputScript."});");
         return $output;
    
        
    }   
    
}

    
?>
