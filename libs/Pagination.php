<?php

class Pagination {
    /**
     * Number of links before and after current element
     * @var int 
     */
    private $_interval = 2;
    /**
     * Items per page
     * @var int 
     */
    private $_perPage = 3;
    private $_prevLink = '';
    private $_nextLink = '';
    private $_firstLink = '';
    private $_lastLink = '';
    
    
    public function createLinks($curNum, $link, $totalRows){
        $totalNum = (int) ceil($totalRows / $this->_perPage);
        $html = '';
        
        $start = $curNum - $this->_interval;
        if($start < 1){
            $start = 1;
        }
        
        $end = $curNum + $this->_interval;
        if($end > $totalNum){
            $end = $totalNum;
        }    
        
        if($start > 1){
            $html .= '<a href="' . $link . '1" class="first">' . $this->_firstLink . '</a>';
        }
        if($curNum > 1){
            $html .= '<a href="' . $link . ($curNum - 1) . '" class="prev">' . $this->_prevLink . '</a>';
        }
        
        for($i = $start; $i <= $end; $i++){
            if($i == $curNum){
                $html .= '<strong>' . $i . '</strong>';
            } else {
                $html .= '<a href="' . $link . $i . '">' . $i . '</a>';
            }
        }
        
        if($curNum < $totalNum){
            $html .= '<a href="' . $link . ($curNum + 1) . '" class="next">' . $this->_nextLink . '</a>';
        }
        
        if(($curNum + $this->_interval) < $totalNum){
            $html .= '<a href="' . $link .  $totalNum . '" class="last">' . $this->_lastLink . '</a>';
        }
        
        if($html != ''){
            return '<div class="pagination">' . $html . '</div>';
        }
        
        return $html;
    }
}