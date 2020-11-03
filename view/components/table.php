<?php

    // $columns = ['col1','col2','col3','col4'];
    // $db_data = [
    //     [
    //         "col1" => 'val1',
    //         "col2" => 'valvalval2',
    //         "col3" => 'valvalvalvalvalvalvalvalval3',
    //         "col4" => '<button class="btn btn-primary">Request</button>',
    //     ],
    //     [
    //         "col1" => 'val5',
    //         "col2" => 'valvalval6',
    //         "col3" => 'valvalvalvalvalvalvalvalval7',
    //         "col4" => '<button class="btn btn-primary">Request</button>',
    //     ],
    // ];

    function datatable($cols = [],$data = []){
        if(sizeof($cols) > 0){
            $output = '<table class="table table-responsive-sm table-striped table-dark"><thead><tr>';
            foreach($cols as $col){
                $output .= '<th>'.$col.'</th>';
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
            $output .= "</tbody></table>";
            return $output;
        }
        return '';
    }
