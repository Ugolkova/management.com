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
    private $_prevLink = '';
    private $_nextLink = '';
    private $_firstLink = '';
    private $_lastLink = '';
    
    /**
     * Create links
     * 
     * @param int $curPageNum Current page number
     * @param string $link 
     * @param int $totalRows
     * @return string Returns pagination div
     */
    public function createLinks( $curPageNum, $totalRows ){
        $requestUri = preg_replace( "/[p]\d+\/?/", "", $_SERVER['REQUEST_URI']);
        $link = URL . trim( $requestUri, '/') . '/p';
        
        $totalNum = (int) ceil( $totalRows / COUNT_ENTRIES_ON_PAGE );
        $html = '';
        
        $start = $curPageNum - $this->_interval;
        if( $start < 1 ){
            $start = 1;
        }
        
        $end = $curPageNum + $this->_interval;
        if( $end > $totalNum ){
            $end = $totalNum;
        }    
        
        if( $start > 1 ){
            $html .= '<a href="' . $link . '1/" class="first">' 
                    . $this->_firstLink . '</a>';
        }
        if( $curPageNum > 1 ){
            $html .= '<a href="' . $link . ( $curPageNum - 1 ) 
                    . '/" class="prev">' . $this->_prevLink . '</a>';
        }
        
        for( $i = $start; $i <= $end; $i++ ){
            if($i == $curPageNum){
                $html .= '<strong>' . $i . '</strong>';
            } else {
                $html .= '<a href="' . $link . $i . '/">' . $i . '</a>';
            }
        }
        
        if( $curPageNum < $totalNum ){
            $html .= '<a href="' . $link . ( $curPageNum + 1 ) 
                    . '/" class="next">' . $this->_nextLink . '</a>';
        }
        
        if( ( $curPageNum + $this->_interval ) < $totalNum ){
            $html .= '<a href="' . $link .  $totalNum . '/" class="last">' . $this->_lastLink . '</a>';
        }

        if( $html != '' && $totalNum > 1 ){
            return '<div class="pagination">' . $html . '</div>';
        } else {
            return '';
        }
        
    }
}