<?php

namespace JangoBrick\SVG\Nodes\Shapes;

use JangoBrick\SVG\Nodes\SVGNode;
use JangoBrick\SVG\SVG;
use JangoBrick\SVG\SVGRenderingHelper;

class SVGCircle extends SVGNode
{
    private $cx, $cy, $r;

    public function __construct($cx, $cy, $r)
    {
        parent::__construct();

        $this->cx = $cx;
        $this->cy = $cy;
        $this->r  = $r;
    }

    public function getCenterX()
    {
        return $this->cx;
    }

    public function setCenterX($cx)
    {
        $this->cx = $cx;
        return $this;
    }

    public function getCenterY()
    {
        return $this->cy;
    }

    public function setCenterY($cy)
    {
        $this->cy = $cy;
        return $this;
    }

    public function getRadius()
    {
        return $this->r;
    }

    public function setRadius($r)
    {
        $this->r = $r;
        return $this;
    }

    public function toXMLString()
    {
        $s = '<circle';

        $s .= ' cx="'.$this->cx.'"';
        $s .= ' cy="'.$this->cy.'"';
        $s .= ' r="'.$this->r.'"';

        $this->addStylesToXMLString($s);
        $this->addAttributesToXMLString($s);

        $s .= ' />';

        return $s;
    }

    public function draw(SVGRenderingHelper $rh, $scaleX, $scaleY)
    {
        $rh->push();

        $opacity = $this->getStyle('opacity');
        if (isset($opacity) && is_numeric($opacity)) {
            $opacity = floatval($opacity);
            $rh->scaleOpacity($opacity);
        }

        // original (document fragment) width for unit parsing
        $ow = $rh->getWidth() / $scaleX;

        $cx = $this->cx * $scaleX;
        $cy = $this->cy * $scaleY;
        $rx = $this->r * $scaleX;
        $ry = $this->r * $scaleY;

        $fill = $this->getComputedStyle('fill');
        if (isset($fill) && $fill !== 'none') {
            $fillColor = SVG::parseColor($fill, true);
            $rh->fillEllipse($cx, $cy, $rx, $ry, $fillColor);
        }

        $stroke = $this->getComputedStyle('stroke');
        if (isset($stroke) && $stroke !== 'none') {
            $strokeColor = SVG::parseColor($stroke, true);
            $rh->setStrokeWidth(SVG::convertUnit($this->getComputedStyle('stroke-width'), $ow) * $scaleX);
            $rh->drawEllipse($cx, $cy, $rx, $ry, $strokeColor);
        }

        $rh->pop();
    }
}