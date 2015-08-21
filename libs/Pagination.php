<?php

class Pagination {
    private $_countEntriesOnPage = 5;
    private $_prevLinkTitle = 'Prev';
    private $_nextLinkTitle = 'Next';
    private $_limit = 9;
    private $_url = '';
    
    function __construct() {
        echo $this->createLinks($_GET['page'], 13, '/test.php');
    }
    
    public function createLinks($currentPageNum, $totalRows, $url){
        $this->_url = $url;
        
        $pageCount = ceil($totalRows / $this->_countEntriesOnPage);
        $html = "";
        
        $countLinks = ($pageCount > $this->_limit) ? $this->_limit : $pageCount;
        
        // First Page
        if($currentPageNum == 1){
            $html .= $this->_setCurrentLink(1);            
            for($i = 2; $i <= $countLinks; $i++){
                $html .= $this->_setSimpleLink($i);
            }

            if($pageCount > $countLinks){
                $html .= $this->_setEllipsis();
                $html .= $this->_setSimpleLink($pageCount);
            }
            
            if($currentPageNum != $totalRows){
                $html .= $this->_setNextLink($currentPageNum + 1);
            }
        } 
        // Last Page
        else if($currentPageNum == $pageCount) {
            $html .= $this->_setPrevLink($currentPageNum - 1); 
            $html .= $this->_setEllipsis();
            
            $start = ($pageCount - $countLinks) > 1 ? ($pageCount - $countLinks) : 1;
            for($i = $start; $i <= $pageCount; $i++){
                if($i == $currentPageNum){
                    $html .= $this->_setCurrentLink($i);
                } else {
                    $html .= $this->_setSimpleLink($i);
                }
            }
        }
        // First pages
        else if($currentPageNum < $this->_limit){
            $html .= $this->_setPrevLink($currentPageNum - 1); 
            
            for($i = 1; $i <= $countLinks; $i++){
                if($i == $currentPageNum){
                    $html .= $this->_setCurrentLink($i);
                } else {
                    $html .= $this->_setSimpleLink($i);
                }
            }

            if($pageCount > $countLinks){
                $html .= $this->_setEllipsis();
                $html .= $this->_setSimpleLink($pageCount);
            }
            
            if($currentPageNum != $totalRows){
                $html .= $this->_setNextLink($currentPageNum + 1);
            }            
        }
        // Last pages
        else if($currentPageNum > ($pageCount - $this->_limit +1)){
            $html .= $this->_setPrevLink($currentPageNum - 1); 
            $html .= $this->_setSimpleLink(1);
            $html .= $this->_setEllipsis();
            
            for($i = ($pageCount - $this->_limit + 1); $i <= $pageCount; $i++){
                if($i == $currentPageNum){
                    $html .= $this->_setCurrentLink($i);
                } else {
                    $html .= $this->_setSimpleLink($i);
                }
            }

            if($currentPageNum != $totalRows){
                $html .= $this->_setNextLink($currentPageNum + 1);
            }             
        }
        else {
            $html .= $this->_setPrevLink($currentPageNum - 1); 
            $html .= $this->_setSimpleLink(1);
            $html .= $this->_setEllipsis();

            $x = floor($countLinks / 2);
            for($i = $currentPageNum - $x; $i <= $currentPageNum + $x; $i++){
                if($i == $currentPageNum){
                    $html .= $this->_setCurrentLink($i);
                } else {
                    $html .= $this->_setSimpleLink($i);
                }
            }

            $html .= $this->_setEllipsis();
            $html .= $this->_setSimpleLink($pageCount);
            $html .= $this->_setNextLink($currentPageNum + 1);
        }
        
        return $html;
    }
    
    private function _setPrevLink($num){
        return '<a href="' . $this->_url . '?page=' . $num . '">' . $this->_prevLinkTitle . '</a>';
    }

    private function _setNextLink($num){
        return '<a href="' . $this->_url . '?page=' . $num . '">' . $this->_nextLinkTitle . '</a>';
    }
    
    private function _setCurrentLink($num){
        return '<strong>' . $num . '</strong>';
    }
    
    private function _setSimpleLink($num){
        return '<a href="' . $this->_url . '?page=' . $num . '">' . $num . '</a>';
    }

    private function _setEllipsis(){
        return '<span>...</span>';
    }
}