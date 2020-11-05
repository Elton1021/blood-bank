<?php

    function datatable($datatableData,$tableCSS = 'table-sm-responsive'){
        $cols = $datatableData['columns'] ?? [];
        $data = $datatableData['data'] ?? [];
        $count = $datatableData['count'] ?? 0;
        $page = $datatableData['page'] ?? 1;
        $totalPages = $datatableData['totalPages'] ?? 1;
        if(sizeof($cols) > 0){
            ?>
            <table class="table <?php echo $tableCSS;?> table-striped table-dark">
                <thead>
                    <tr>
            <?php
            $output = '';
            $footer = '<tfoot>';
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
            $footer .= '</tfoot>';
            //work on footer search if possible
            $footer = '';
            $output .= "</tbody>$footer</table>";
            echo $output;
            //pagination and count details
            $paginationStyle = 'background-color: #242729;border-color: #3c4144;';
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $url = explode('?',$url)[0];
            ?>
            <div class="row">
                <div class="col-md-6 col-ls-12">
                    <p class="mt-2 text-md-left text-center">Showing <?php echo sizeof($data);?> out of <?php echo $count;?></p>
                </div>
                <div class="col-md-6 col-ls-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-md-end justify-content-center bg-dark mb-0">
                            <li class="page-item <?php echo $page == 1 ? 'disabled':'';?>">
                                <a class="page-link" style="<?php echo $paginationStyle;?>" href="<?php echo $url.'?p='.($page - 1);?>" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <?php
                                $paginationStart = 1;
                                if($totalPages - $page < 2 && $page > 2) {
                                    $paginationStart = $page - (4 - ($totalPages - $page));
                                } else if($page > 2 && $page-2 < $totalPages){
                                    $paginationStart = $page - 2;
                                }
                                for ($i = $paginationStart; $i <= $totalPages && $i < $paginationStart + 5; $i++){
                                    ?>
                                    <li class="page-item <?php echo $i == $page ? 'active' : '';?>"><a class="page-link" style="<?php echo $paginationStyle;?>" <?php echo ($i != $page ? 'href="'.$url.'?p='.$i.'"' : '');?>><?php echo $i;?></a></li>
                                    <?php
                                }
                            ?>
                            <li class="page-item <?php echo $page == $totalPages ? 'disabled':'';?>">
                                <a class="page-link" style="<?php echo $paginationStyle;?>" href="<?php echo $url.'?p='.($page + 1);?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php
        }
        echo '';
    }
