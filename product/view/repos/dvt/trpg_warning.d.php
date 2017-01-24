<?php 
//    var_dump(__FILE__,__LINE__,in_array(intval($trstate),[4]));
    if ( $trstate && in_array(intval($trstate),[4]) ) :
//        var_dump(__FILE__,__LINE__,$trstate_tm);
        $y1__ = FALSE;
        $dly_chk = FALSE;
        
        if ( !empty($trstate_tm) && is_string($trstate_tm) && count(split(',', $trstate_tm) === 2 ) ) {
            $tm__ = split(',', $trstate_tm)[0];
            $u__ = split(',', $trstate_tm)[1];
            
            switch ($u__) {
                case "d" :
                        $u__ = $dy_;
                        $ur__ = "urgent";
                        $dly_chk = TRUE;
                    break;
                case "h" :
                        $u__ = $hy_;
                        $ur__ = "urgent";
                        $dly_chk = TRUE;
                    break;
                case "m" :
                        $u__ = $my_;
                        $ur__ = "urgent";
                        $dly_chk = TRUE;
                    break;
            }
            
            if ( intval($tm__) === 1 ) {
                $u__ = substr($u__,0,-1); 
            }
            
        }
        
        switch ($trstate) {
            case "4" :
                    $y2__ = "Cette Tendance fait l'objet d'une demande de suppression. Elle ne sera plus accessible dans un délai de <span id=\"trpg-delay\" class=\"$ur__\">$tm__ $u__</span>.";
                    $y1__ = TRUE;
                break;
        }
        if ( $y1__ && $dly_chk ) :
?> 

<div id="trpg-state-wrng-sprt">
    <a id="trpg-state-wrng-fmr" class="jb-kxlib-close" data-target="trpg-state-wrng-sprt" href="javasvript:;" title="Fermer"><i class="fa fa-remove"></i></a>
    <div id="trpg-state-wrng-msg">
        <?php echo $y2__; ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>