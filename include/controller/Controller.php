<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/*********************************************************************************

 * Description:
 ********************************************************************************/








class Controller extends SugarBean {
	
	var $focus;
	var $type;  //defines id this is a new list order or existing, or delete
				// New, Save, Delete
	
    public function __construct()
    {
		parent::__construct();

		$this->disable_row_level_security =true;

	}
	
	function init(& $seed_object, $type){
	
		$this->focus = & $seed_object;
		$this->type = $type;
	
	//end function Controller
	}	

	function change_component_order($magnitude, $direction, $parent_id=""){
		
		if(!empty($this->type) && $this->type=="Save"){
			
			//safety check
			$wall_test = $this->check_wall($magnitude, $direction, $parent_id);

			if($wall_test==false){
				return;
			}
				
				
				if($direction=="Left"){
					if($this->focus->controller_def['list_x']=="Y"){
						$new_x = $this->focus->list_order_x -1;
						$affected_x = $this->focus->list_order_x;
					} else {
						$new_x = "";
						$affected_x = "";
					}	
					if($this->focus->controller_def['list_y']=="Y"){	
						
						$new_y = $this->focus->list_order_y;
						$affected_y = $this->focus->list_order_y;
					} else {
						$new_y = "";
						$affected_y = "";	
					}	
					
					$affected_id = $this->get_affected_id($parent_id, $new_x, $new_y);
				//end if direction Left
				}	
				if($direction=="Right"){
					
					if($this->focus->controller_def['list_x']=="Y"){
						$new_x = $this->focus->list_order_x + 1;
						$affected_x = $this->focus->list_order_x;						
					} else {
						$new_x = "";
						$affected_x = "";
					}
					if($this->focus->controller_def['list_y']=="Y"){
						$new_y = $this->focus->list_order_y;
						$affected_y = $this->focus->list_order_y;
					} else {
						$new_y = "";
						$affected_y = "";
					}						
					$affected_id = $this->get_affected_id($parent_id, $new_x, $new_y);		
				//end if direction Right
				}
			
				if($direction=="Up"){
					if($this->focus->controller_def['list_x']=="Y"){
						$new_x = $this->focus->list_order_x;
						$affected_x = $this->focus->list_order_x;
					} else {
						$new_x = "";
						$affected_x = "";
					}	
					if($this->focus->controller_def['list_y']=="Y"){	
						
					$new_y = $this->focus->list_order_y - 1;
					$affected_y = $this->focus->list_order_y;
					} else {
						$new_y = "";
						$affected_y = "";	
					}	
					
					$affected_id = $this->get_affected_id($parent_id, $new_x, $new_y);
					
				//end if direction Up
				}	
				if($direction=="Down"){
					
					if($this->focus->controller_def['list_x']=="Y"){
						$new_x = $this->focus->list_order_x;
						$affected_x = $this->focus->list_order_x;
					} else {
						$new_x = "";
						$affected_x = "";
					}
					if($this->focus->controller_def['list_y']=="Y"){

						$new_y = $this->focus->list_order_y + 1;
						$affected_y = $this->focus->list_order_y;
					} else {
						$new_y = "";
						$affected_y = "";
					}						
					$affected_id = $this->get_affected_id($parent_id, $new_x, $new_y);		
				//end if direction Down
				}

			//This takes care of the component being pushed out of its position
			$this->update_affected_order($affected_id, $affected_x, $affected_y);
			
				//This takes care of the new positions for itself
			if($this->focus->controller_def['list_x']=="Y"){
				$this->focus->list_order_x = $new_x;
			}
				
			if($this->focus->controller_def['list_y']=="Y"){	
				$this->focus->list_order_y = $new_y;
			}
			
		} else {
		//this is a new component, set the x or y value to the max + 1
            $query = sprintf(
                'SELECT MAX(%s) max_start FROM %s WHERE %s = %s AND deleted=0',
                $this->focus->controller_def['start_var'],
                $this->focus->table_name,
                $this->focus->controller_def['parent_var'],
                $this->db->quoted($parent_id)
            );
				$row = $this->db->fetchOne($query,true," Error capturing max start order: ");

			if(!is_null($row['max_start'])){		
				
				if($this->focus->controller_def['start_axis']=="x")	{
					$this->focus->list_order_x = $row['max_start'] + 1;
					if($this->focus->controller_def['list_y']=="Y") $this->focus->list_order_y = 0;
				}
				
				if($this->focus->controller_def['start_axis']=="y")	{
					$this->focus->list_order_y = $row['max_start'] + 1;
					if($this->focus->controller_def['list_x']=="Y") $this->focus->list_order_x = 0;
				}
				
			} else {
				//first row
				if($this->focus->controller_def['list_x']=="Y") $this->focus->list_order_x = 0;
				if($this->focus->controller_def['list_y']=="Y") $this->focus->list_order_y = 0;

			//end if else to check if this is first entry
			}
		//end if else on whether this is a new entry
		}	
	//end function change_component_order	
	}

    private function update_affected_order($affected_id, $affected_new_x = "", $affected_new_y = "")
    {
        $qb = $this->db->getConnection()->createQueryBuilder();

        $qb->update($this->focus->table_name);

        if ($this->focus->controller_def['list_x'] == "Y") {
            $qb->set('list_order_x', $qb->createPositionalParameter($affected_new_x));
        }

        if ($this->focus->controller_def['list_y'] == "Y") {
            $qb->set('list_order_y', $qb->createPositionalParameter($affected_new_y));
        }

        $qb->where($qb->expr()->eq('id', $qb->createPositionalParameter($affected_id)))
            ->andWhere($qb->expr()->eq('deleted', 0));

        $qb->execute();
    }

    private function get_affected_id($parent_id, $list_order_x = "", $list_order_y = "")
    {
        $qb = $this->db->getConnection()->createQueryBuilder();

        $qb->select('id')
            ->from($this->focus->table_name)
            ->where(
                $qb->expr()->eq(
                    $this->focus->controller_def['parent_var'],
                    $qb->createPositionalParameter($parent_id)
                )
            )
            ->andWhere($qb->expr()->eq('deleted', 0));

        if ($this->focus->controller_def['list_x'] == "Y") {
            $qb->andWhere($qb->expr()->eq('list_order_x', $qb->createPositionalParameter($list_order_x)));
        }

        if ($this->focus->controller_def['list_y'] == "Y") {
            $qb->andWhere($qb->expr()->eq('list_order_y', $qb->createPositionalParameter($list_order_y)));
        }

        $stmt = $qb->execute();
        $row = $stmt->fetchColumn();

        return $row;
    }
	

/////////////Wall Functions////////////////////


function check_wall($magnitude, $direction, $parent_id){
	
//TODO: jgreen - this is only single axis check_wall mechanism, will need to upgrade this to double axis	
//This function determines if you can't move the direction you want, because you are at the edge

	
//If down or Right, then check max list_order value
	if($direction=="Down" || $direction =="Right"){
            $variable_name = $this->focus->controller_def['start_var'];
            $parent_name = $this->focus->controller_def['parent_var'];

            $qb = $this->db->getConnection()->createQueryBuilder();

            $qb->select('MAX('.$variable_name.') AS max_start')
                ->from($this->focus->table_name)
                ->where($qb->expr()->eq($parent_name, $qb->createPositionalParameter($parent_id)))
                ->andWhere($qb->expr()->eq('deleted', 0));

            $stmt = $qb->execute();
            $row = $stmt->fetch();

			if($this->focus->controller_def['start_axis']=="x")	{
				if($row['max_start'] == $this->focus->list_order_x){
					return false;	
				}	
			}
			if($this->focus->controller_def['start_axis']=="y")	{
				if($row['max_start'] == $this->focus->list_order_y){
					return false;	
				}	
			}
	//end if up or right
	}

//If up or left, then simply check the 0 value	
	if($direction=="Up" || $direction =="Left"){
			if($this->focus->controller_def['start_axis']=="x")	{
				if($this->focus->list_order_x==0){
					return false;	
				}	
			}
			if($this->focus->controller_def['start_axis']=="y")	{
				if($this->focus->list_order_y==0){
					return false;	
				}	
			}
		
	//end if down or left
	}	
	
	//If you get here, then you are not at the wall and can change order
	return true;
		
//end function check_wall	
}	
	
//End Wall Functions/////////////////////////

//Delete adjust functions////////////////////


    public function delete_adjust_order($parent_id)
    {
        //Currently handles single axis motion only!!!!!!!!!
        //TODO: jgreen - Add dual axis motion

        //adjust along start_axis
        $variable_name = $this->focus->controller_def['start_var'];
        $parent_name = $this->focus->controller_def['parent_var'];
        $current_position = $this->focus->$variable_name;

        $qb = $this->db->getConnection()->createQueryBuilder();
        $qb->update($this->focus->table_name)
            ->set($variable_name, $variable_name.'-1')
            ->where($qb->expr()->gt($variable_name, $qb->createPositionalParameter($current_position)))
            ->andWhere($qb->expr()->eq($parent_name, $qb->createPositionalParameter($parent_id)))
            ->andWhere($qb->expr()->eq('deleted', 0))
            ->execute();
    }
//End Delete Functions/////////////////////////
//end class Controller
}	

