<?php

    function datatable($cols = [],$data = []){
        if(sizeof($cols) > 0){
            ?>
            <table class="table table-responsive-sm table-striped table-dark">
                <thead>
                    <tr>
            <?php
            $output = '';
            $footer = '';
            foreach($cols as $col){
                $output .= '<th>'.ucwords(str_replace('_',' ',$col)).'</th>'; 
                $footer .= '<td class="table-footer"><input class="form-control" id="'.$col.'" placeholder="'.ucwords(str_replace('_',' ',$col)).'"></td>';
            }
            $output .= '</tr></thead><tbody>';
            if(sizeof($data) > 0){
                foreach($data as $d){
                    $output .= '<tr>';
                    foreach($cols as $col) {
                        $output .= '<td>'.($d[$col] ?? 'N/A').'</td>';
                    }
                    $output .= '</tr>';
                }
            } else {
                $output .= "<td colspan='".sizeof($cols)."' class='text-center'>No Data Found</td>";
            }
            $output .= "</tbody><tfoot>$footer</tfoot></table>";
            echo $output;
        }
        echo '';
    }
