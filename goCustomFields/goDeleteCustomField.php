<?php
/**
 * @file        goDeleteCustomField.php
 * @brief       API to delete a custom field
 * @copyright   Copyright (C) GOautodial Inc.
 * @author      Noel Umandap  <jeremiah@goautodial.com>
 * @author      Alexander Jim Abenoja  <alex@goautodial.com>
 *
 * @par <b>License</b>:
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
    $list_id        = $astDB->escape($_REQUEST['list_id']);
    $field_label    = str_replace(" ","_",trim($astDB->escape($_REQUEST['field_label'])));
    $field_id       = $astDB->escape($_REQUEST['field_id']);
    
    $selectTable = "SHOW TABLES LIKE 'custom_$list_id'";
    $queryResult = $astDB->rawQuery($selectTable);
    $countResult = $astDB->getRowCount();
    
    if($countResult > 0){
        //$selectColumns = "SHOW COLUMNS FROM `custom_$list_id` LIKE '$field_label';";
        //$queryResult1 = $astDB->rawQuery($selectColumns);
        //$countResult1 = $astDB->getRowCount();
        $astDB->where('field_label', $field_label);
        $astDB->where('field_id', $field_id);
        $astDB->where('list_id', $list_id);
        $queryResult1 = $astDB->getOne('vicidial_lists_fields');
        $countResult1 = $astDB->getRowCount();
        
        if($countResult1 > 0 && $field_label != "lead_id"){
            $table_name = 'custom_'.$list_id;

            $astDB->where('field_label', $field_label);
            $astDB->where('field_id', $field_id);
            $astDB->where('list_id', $list_id);
            $queryDeleteCF = $astDB->delete('vicidial_lists_fields');
            
            if($queryDeleteCF){
              $astDB->dropColumnFromTable($table_name, $field_label);

              $apiresults = array("result" => "success");
            }else{
              $apiresults = array("result" => "Error: Custom Field does not exist");
            }
        }else{
            $apiresults = array("result" => "Error: $field_label does not exist");
        }
    }else{
        $apiresults = array("result" => "Error: List does not exist");
    }

?>