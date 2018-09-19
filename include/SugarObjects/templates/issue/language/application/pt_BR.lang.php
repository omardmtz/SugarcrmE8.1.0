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
$object_name = strtolower($object_name);
 $app_list_strings = array (

  $object_name.'_type_dom' =>
  array (
  	'Administration' => 'Administration',
    'Product' => 'Produto',
    'User' => 'Usuário',
  ),
   $object_name.'_status_dom' =>
  array (
    'New' => 'Novo',
    'Assigned' => 'Atribuída',
    'Closed' => 'Encerrado',
    'Pending Input' => 'Entrada Pendente',
    'Rejected' => 'Rejeitado',
    'Duplicate' => 'Duplicar',
  ),
  $object_name.'_priority_dom' =>
  array (
    'P1' => 'Alta',
    'P2' => 'Média',
    'P3' => 'Baixa',
  ),
  $object_name.'_resolution_dom' =>
  array (
  	'' => '',
  	'Accepted' => 'Aceito',
    'Duplicate' => 'Duplicar',
    'Closed' => 'Encerrado',
    'Out of Date' => 'Obsoleto',
    'Invalid' => 'Inválido',
  ),
  );
?>