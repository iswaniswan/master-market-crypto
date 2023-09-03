<?php

namespace app\components;

use app\models\Downline;

class ChartBinary2
{
    public $svgWidth = 2000;
    public $svgHeight = 1600;
    public $svgViewBox = '0 0 2000 1600';

    public $width = 220;
    public $height = 120;
    public $marginVertical = 50;
    public $marginHorizontal = 165;

    public $dataChart;

    protected $nodes = [];
    protected $lastNode = [];
    protected $svg;

    public $x;
    public $y;
    public $level;

    const LEFT = 0;
    const RIGHT = 1;
    const SETS = 2;

    /** steps
     1. buat 7 node, 3 level
     2. assing data ke node
     */

    public function initStructure()
    {
        // init root nodes
        $this->nodes = [
            'x' => $this->x,
            'y' => $this->y,
            'level' => $this->level 
        ];
    }

    public function __init()
    {
        $this->svg = null;
    }

    public function render($params=[])
    {
        // child node
        $x = @$params['x'];
        $y = @$params['y'];
        $level = @$params['level'];

        // init root node
        if (@$params['posisi'] === 'root') {
            $x = 400;
            $y = 50;
            $level = 0;
        }

        $params['x'] = $x;
        $params['y'] = $y;
        $params['level'] = $level;

        $this->createCardSvg($params);

        $this->lastNode = $params;

        $level += 1;
        if ($level >= 3) {
            return;
        }

        // create downline
        // pengecilan margin untuk level menaik
        $marginHorizontal = $this->marginHorizontal;
        if ($level > 1) {
            $marginHorizontal = 10;
        }

        $lastY = $y;
        $y = $y + $this->height + $this->marginVertical;

        $params_left = [
            'px' => $x + ($this->width * 0.5),
            'py' => $lastY + $this->height,
            'x' => $x - ($this->width * 0.5) - $marginHorizontal,
            'y' => $y + ($this->height * 0.5)
        ];

        $params_right = [
            'px' => $x + ($this->width * 0.5),
            'py' => $lastY + $this->height,
            'x' => $x + ($this->width * 0.5) + $marginHorizontal,
            'y' => $y + ($this->height * 0.5)
        ];

        $downline = @$params['downline'];
        $parentId = @$this->lastNode['id'];
        $idGroup = @$this->lastNode['idGroup'];
        
        $defaultPosition = (@$downline[0]['posisi'] == 0) ? 0 : 1;

        for ($i = 0; $i < static::SETS; $i++) {
            $params = ($defaultPosition == 0) ? $params_left : $params_right;

            $this->createLineToParent($params);

            $downline[$i]['parentId'] = $parentId;
            $downline[$i]['idGroup'] = $idGroup;
            $downline[$i]['x'] = $params['x'];
            $downline[$i]['y'] = $y;
            $downline[$i]['level'] = $level;
            $downline[$i]['posisi'] = $defaultPosition;

            $this->render($downline[$i]);

            $defaultPosition = 1 - $defaultPosition; // Toggle between 0 and 1
        }       
    }

    public function render_old($params=[])
    {
        // child node
        $x = @$params['x'];
        $y = @$params['y'];
        $level = @$params['level'];

        // init root node
        if (@$params['posisi'] === 'root') {
            $x = 400;
            $y = 50;
            $level = 0;
        }

        $params['x'] = $x;
        $params['y'] = $y;
        $params['level'] = $level;

        $this->createCardSvg($params);

        $this->lastNode = $params;

        $level += 1;
        if ($level >= 3) {
            return;
        }

        // create downline
        // pengecilan margin untuk level menaik
        $marginHorizontal = $this->marginHorizontal;
        if ($level > 1) {
            $marginHorizontal = 10;
        }

        $lastY = $y;
        $y = $y + $this->height + $this->marginVertical;

        $params_left = [
            'px' => $x + ($this->width * 0.5),
            'py' => $lastY + $this->height,
            'x' => $x - ($this->width * 0.5) - $marginHorizontal,
            'y' => $y + ($this->height * 0.5)
        ];

        $params_right = [
            'px' => $x + ($this->width * 0.5),
            'py' => $lastY + $this->height,
            'x' => $x + ($this->width * 0.5) + $marginHorizontal,
            'y' => $y + ($this->height * 0.5)
        ];

        $downline = @$params['downline'];
        $parentId = @$this->lastNode['id'];
        
        if (@$downline[0]['posisi'] == 0) {
            $defaultPosition = 0;

            for ($i=0; $i<static::SETS; $i++) {            
                
                if ($defaultPosition == 0) {
                    $params = $params_left;
                } else {
                    $params = $params_right;
                }
    
                $this->createLineToParent($params);
                $downline[$i]['parentId'] = $parentId;
                $downline[$i]['x'] = $params['x'];
                $downline[$i]['y'] = $y;
                $downline[$i]['level'] = $level;
                // $downline[$i]['posisi'] = $defaultPosition;
                $this->render($downline[$i]);
    
                $defaultPosition++;
            }
        } else {
            $defaultPosition = 0;

            for ($i=0; $i<static::SETS; $i++) {            
                
                if ($defaultPosition == 0) {
                    $params = $params_right;
                } else {
                    $params = $params_left;
                }
    
                $this->createLineToParent($params);
                $downline[$i]['parentId'] = $parentId;
                $downline[$i]['x'] = $params['x'];
                $downline[$i]['y'] = $y;
                $downline[$i]['level'] = $level;
                // $downline[$i]['posisi'] = $defaultPosition;
                $this->render($downline[$i]);
    
                $defaultPosition++;
            }
        }        
    }

    public function createLineToParent($params=[])
    {
        // end axis or parent axis
        $px = @$params['px'];
        $py = @$params['py'];

        // init axis
        $x = @$params['x'];
        $y = @$params['y'];

        /* gap for curve, 5px
        docs:
        C x1 y1, x2 y2, x y
        (x1,y1) is the control point for the start of the curve
        (x2,y2) is the control point for the end
        (x,y) specify where the line should end
        */

        $my = $y;
        $cx = $px;
        $cy1 = $my;
        $cx2 = $px;
        // direction LTR (Left To Right)
        if ($px > $x) {
            $mx = $px - 5;
            $cy = $y - 5;
            $cx1 = $mx + 1;
            $cy2 = $my + 1;
        } else {
            $mx = $px + 5;
            $cy = $y - 5;
            $cx1 = $mx - 1;
            $cy2 = $my;
        }
        $curve = '<path d="'."M {$mx} {$my} C {$cx1} {$cy1}, {$cx2} {$cy2}, {$cx} {$cy}". '" stroke="#bbb" fill="transparent"/>';

        $svg ='<line x1="'.$x.'" y1="'.$y.'" x2="'.$mx.'" y2="'.$my.'" stroke="#bbb" />';
        $svg .= $curve;
        $svg .='<line x1="'.$cx.'" y1="'.$cy.'" x2="'.$px.'" y2="'.$py.'" stroke="#bbb" />';
        $this->svg .= $svg;
    }

     public function createCardSvg($node)
    {
        $x = @$node['x'];
        $y = @$node['y'];
        $id = @$node['id'];
        $parentId = @$node['parentId'];
        $idGroup = @$node['idGroup'];
        $imageUrl = @$node['imageUrl'];
        $name = @$node['name'];
        $posisi = @$node['posisi'];
        $level = @$node['level'];
        $link = @$node['link'];
        $verified = @$node['is_active'];

        /** for default create blank card */
        $styleCreate = 'btn btn-sm btn-success p-3';
        $iconCreate = '<span class="ti-plus"></span>';
        if ($parentId == null) {
            $styleCreate = 'btn btn-sm btn-danger p-3 disabled';
            $iconCreate = '<span class="ti-close"></span>';
        }        

        $svg = <<<HTML
            <g class="node" id="$id" parent-id="$parentId" group-id="$idGroup" position="$posisi">
                <foreignObject class="node-foreign-object" x="$x" y="$y">
                    <div class="bt-node_content" style="width:{$this->width}px; height:{$this->height}px;">
                        <button class="$styleCreate" style="position: absolute; top: 24px; left: 80px">$iconCreate</button>
                    </div>
                </foreignObject>
            </g>
        HTML;

        if ($id != null) {
            $count_downline = Downline::getDownlineCount(['id_member' => $id]);
            
            $count_omzet = Downline::getDownlineOmzetCount(['id_member' => $id]);
            $omzetLeft = number_format($count_omzet['left'], 0, ',', '.');
            $omzetRight = number_format($count_omzet['right'], 0, ',', '.');
            

            $iconVerified = '';
            if ($verified) {
                $iconVerified = '<span class="ti-medall-alt text-warning" style="font-size: 32px"></span>';
            }

            // $svg = '<g class="node" id="'.$id.'" parent-id="'.$parentId.'" position="'.$posisi.'">
            //         <foreignObject class="node-foreign-object" x="'.$x.'" y="'.$y.'">
            //             <div class="bt-node_content" style="width:'.$this->width.'px;height:'.$this->height.'px;">
            //                 <img src="'.$imageUrl.'" class="bt-node_content_img">
            //                 <div class="bt-node_content_label" style="">
            //                     '.$iconVerified.'
            //                 </div>
            //                 <div class="bt-node_content_name" style="">'.ucwords($name).'</div>
            //                 <div class="bt-node_content_position" style=""><span data-toggle="tooltip" data-placement="top" title="Total downline left | right">'. $count_downline['left'] . ' | ' . $count_downline['right'] .'</span></div>
            //                 <div class="bt-node_content_position" style=""><span data-toggle="tooltip" data-placement="top" title="Total omzet left | right">'. $count_omzet['left'] . ' | ' . $count_omzet['right'] .'</span></div>
            //             </div>
            //         </foreignObject>
            //     </g>';

            $svg = <<<HTML
                <g class="node" id="$id" parent-id="$parentId" group-id="$idGroup" position="$posisi">
                    <foreignObject class="node-foreign-object" x="$x" y="$y">
                        <div class="bt-node_content" style="width:{$this->width}px; height:{$this->height}px;">
                            <img src="$imageUrl" class="bt-node_content_img">
                            <div class="bt-node_content_label" style="">$iconVerified</div>
                            <div class="bt-node_content_name" style="">{$name}</div>
                            <div class="bt-node_content_position" style="">
                                <span data-toggle="tooltip" data-placement="top" title="Total downline left | right">{$count_downline['left']} | {$count_downline['right']}</span>
                            </div>
                            <div class="bt-node_content_position" style="">
                                <span data-toggle="tooltip" data-placement="top" title="Total omzet left | right">{$omzetLeft} | {$omzetRight}</span></div>
                        </div>
                    </foreignObject>
                </g>
            HTML;
        }

        $this->svg .= $svg;
    }

    public function getOutputSvg()
    {
        if ($this->dataChart == null or empty($this->dataChart)) {
            echo 'empty data';
            return null;
        }

        if ($this->svg != null) {
            return $this->svg;
        }

        $this->render($this->dataChart);
        $this->svgWrapper();
        return $this->svg;
    }

    public function getCss()
    {
        $style = <<<CSS
            .node {
                cursor: pointer; opacity:1; font: 12px sans-serif;
            }
            
            .node-foreign-object {
                width: 220px; height: 120px; overflow: visible; text-align: center;
            }
            
            .bt-btn_content {        
                color:#716E7B;border-radius:5px;padding:4px;font-size:10px;margin:auto auto;background-color:white;border: 2px solid #E4E2E9;
            }
            .bt-node_content {
                font-family:sans-serif;background-color:#fff; position:absolute;margin-top:-1px; margin-left:-1px;border-radius:10px;border: 1px solid #aaa;
            }
            .bt-node_content_child {
                background-color:#fff;position:absolute;margin-top:-25px;margin-left:15px;border-radius:100px;width:50px;height:50px;
            }
            .bt-node_content_img {
                position:relative;margin-top:-20px;border-radius:100px;width:64px;height:64px;border:1px solid #aaa;
            }
            .bt-node_content_label {
                color:#08011E;position:absolute;right:0px;top:8px;font-size:10px;
            }
            .bt-node_content_name {
                font-size:15px;color:#08011E;margin-top:10px
            }
            .bt-node_content_position {
                color:#716E7B;margin-top:3px;font-size:13px;
            }
            .btn-sm-x {
                padding: 0px 5px !important; background-color: #f1f1f1 !important;
            }
CSS;
        return $style;
    }

    public function svgWrapper($params=[])
    {
        if (@$params['svgWidth'] != null) {
            $this->svgWidth = $params['svgWidth'];
        }

        if (@$params['svgHeight'] != null) {
            $this->svgHeight = $params['svgHeight'];
        }

        if (@$params['svgViewBox'] != null) {
            $this->svgViewBox = $params['svgViewBox'];
        }

        $svg = '<svg id="chart-binary-svg"
                            width="'.$this->svgWidth.'" 
                            height="'.$this->svgHeight.'" 
                            viewBox="'.$this->svgViewBox.'" 
                            xmlns="http://www.w3.org/2000/svg">';
        $svg .= $this->svg;
        $svg .= '</svg>';
        $this->svg = $svg;
    }

    public static function getPosisi($posisi)
    {
        if ($posisi === static::LEFT) {
            return 'Left';
        }

        if ($posisi === static::RIGHT) {
            return 'Right';
        }

        return $posisi;
    }

}