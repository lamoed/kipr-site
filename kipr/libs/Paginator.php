<?php

class Paginator extends Library
{
    private $max_page;
    private $link;
    private $page;
    
    function build($max_page, $page, $link) {
        $this->link = $link;
        // „тобы показывало нормальные номера страниц
        $this->page = $page + 1;
        $this->max_page = $max_page + 1;
        return $this->listBuild();
    }
    
    public function listBuild() {
        $str = '<div class="b-paginator">';
        $prevarrow = $this->prevArrow($this->page);
        $list  = $this->previous(2);
        $list .= $this->current($this->page);
        $list .= $this->next(2);
        $nextarrow = $this->nextArrow($this->page);
        if(empty($list)) return NULL;
        $str .= "{$prevarrow}
                 <ul>{$list}</ul>
                  {$nextarrow}
                </div>";
        return $str;
    }
    
    private function next($number) {
        $str = '';
        for($i = 1; $i <= $number; $i++) {
            $value = $this->page + $i;
            if($value > $this->max_page) break;
            $str .= "<li><a href=\"{$this->link}/{$value}\">{$value}</a></li>";
        }
        return $str;
    }

    private function previous($number) {
        $str = '';
        for($i = $number; $i > 0; $i--) {
            $value = $this->page - $i;
            if($value < 1) continue;
            $str .= "<li><a href=\"{$this->link}/{$value}\">{$value}</a></li>";
        }
        return $str;
    }
    
    private function current($number) {
        return "<li class=\"active\"><a href=\"{$this->link}/{$number}\">{$number}</a></li>";
    }
    
    private function prevArrow($number) {
        $value = $number - 1;
        if($value > 1) return "<div class=\"b-previous\"><a href=\"{$this->link}/{$value}\">&lt;&lt;</a></div>";
        return NULL;
    }
    
    private function nextArrow($number) {
        $value = $number + 1;
        if($value < $this->max_page) return "<div class=\"b-next\"><a href=\"{$this->link}/{$value}\">&gt;&gt;</a></div>";
        return NULL;
    }
}