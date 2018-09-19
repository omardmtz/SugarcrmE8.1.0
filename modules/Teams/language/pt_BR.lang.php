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

$mod_strings = array(
    'ERR_ADD_RECORD' => 'Um número de registro deverá ser especificado para adicionar um usuário a esta Equipe.',
    'ERR_DUP_NAME' => 'O Nome da Equipe já existe, escolha outro.',
    'ERR_DELETE_RECORD' => 'Um número de registro deverá ser especificado para excluir esta Equipe.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Erro. A Equipe selecionada <b>({0})</b> é uma Equipe que você selecionou para excluir. Selecione outra Equipe.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Erro. Não é possível excluir um usuário cuja Equipe privada ainda não foi excluída.',
    'LBL_DESCRIPTION' => 'Descrição:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globalmente visíveis',
    'LBL_INVITEE' => 'Membros da Equipe',
    'LBL_LIST_DEPARTMENT' => 'Departamento',
    'LBL_LIST_DESCRIPTION' => 'Descrição',
    'LBL_LIST_FORM_TITLE' => 'Lista de Equipes',
    'LBL_LIST_NAME' => 'Nome',
    'LBL_FIRST_NAME' => 'Primeiro Nome:',
    'LBL_LAST_NAME' => 'Sobrenome:',
    'LBL_LIST_REPORTS_TO' => 'Reporta a',
    'LBL_LIST_TITLE' => 'Título',
    'LBL_MODULE_NAME' => 'Equipes',
    'LBL_MODULE_NAME_SINGULAR' => 'Equipe',
    'LBL_MODULE_TITLE' => 'Equipes: Tela Principal',
    'LBL_NAME' => 'Nome da Equipe:',
    'LBL_NAME_2' => 'Nome da Equipe(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Nome da Equipe Principal',
    'LBL_NEW_FORM_TITLE' => 'Nova Equipe',
    'LBL_PRIVATE' => 'Privado',
    'LBL_PRIVATE_TEAM_FOR' => 'Equipe privada para:',
    'LBL_SEARCH_FORM_TITLE' => 'Pesquisa de Equipes',
    'LBL_TEAM_MEMBERS' => 'Membros da Equipe',
    'LBL_TEAM' => 'Equipes:',
    'LBL_USERS_SUBPANEL_TITLE' => 'usuários',
    'LBL_USERS' => 'usuários',
    'LBL_REASSIGN_TEAM_TITLE' => 'Há registros atribuídos à(s) seguinte(s) Equipe(s): <b>{0}</b> <br> Antes de excluir a(s) Equipe(s), deve primeiro redesignar esses registros para uma nova Equipe. Escolha uma Equipe para ser usada como substituta.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Redesignar',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Redesignar',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Prosseguir com a atualização dos registros afetados para utilizar a nova Equipe?',
    'LBL_REASSIGN_TABLE_INFO' => 'Atualizando tabela {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operação concluída com sucesso.',
    'LNK_LIST_TEAM' => 'Equipes',
    'LNK_LIST_TEAMNOTICE' => 'Notícias da Equipe',
    'LNK_NEW_TEAM' => 'Criar Equipe',
    'LNK_NEW_TEAM_NOTICE' => 'Criar Notícia de Equipe',
    'NTC_DELETE_CONFIRMATION' => 'Tem certeza de que deseja excluir este registro?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Tem certeza que pretende excluir este usuário de Equipe?',
    'LBL_EDITLAYOUT' => 'Editar Layout' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Permissões de equipe',
    'LBL_TBA_CONFIGURATION_DESC' => 'Permitir o acesso da equipe e gerenciar o acesso por módulo.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Habilitar permissões de equipe',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Selecionar módulos a serem habilitados',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Habilitar as permissões de equipe permitirá que você atribua direitos de acesso específicos para equipes e usuários em módulos individuais, por meio do gerenciamento de funções.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Desabilitar as permissões de equipe em um módulo irá reverter todos os dados associados com as permissões de equipe nesse módulo, incluindo todas as definições de processo ou processos usando esse recurso. Isso inclui todas as funções usando a opção "proprietário e equipe selecionada" nesse módulo e todos os dados de permissões de equipe em registros no módulo. Além disso, recomendamos o uso da ferramenta de reconstrução e reparação rápida, para apagar o cache do sistema após desabilitar as permissões de equipe em qualquer módulo.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Aviso:</strong> Desabilitar as permissões de equipe em um módulo irá reverter todos os dados associados com as permissões de equipe nesse módulo, incluindo todas as definições de processo ou processos usando esse recurso. Isso inclui todas as funções usando a opção "proprietário e equipe selecionada" nesse módulo e todos os dados de permissões de equipe em registros no módulo. Além disso, recomendamos o uso da ferramenta de reconstrução e reparação rápida, para apagar o cache do sistema após desabilitar as permissões de equipe em qualquer módulo.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Desabilitar as permissões de equipe em um módulo irá reverter todos os dados associados com as permissões de equipe nesse módulo, incluindo todas as definições de processo ou processos usando esse recurso. Isso inclui todas as funções usando a opção "proprietário e equipe selecionada" nesse módulo e todos os dados de permissões de equipe em registros no módulo. Além disso, recomendamos o uso da ferramenta de reconstrução e reparação rápida, para apagar o cache do sistema após desabilitar as permissões de equipe em qualquer módulo. Se você não deseja usar a Reconstrução e Reparação Rápida, entre em contato com um administrador acessando o menu Reparar.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Aviso</strong> Desabilitar as permissões de equipe em um módulo irá reverter todos os dados associados com as permissões de equipe nesse módulo, incluindo todas as definições de processo ou processos usando esse recurso. Isso inclui todas as funções usando a opção "proprietário e equipe selecionada" nesse módulo e todos os dados de permissões de equipe em registros no módulo. Além disso, recomendamos o uso da ferramenta de reconstrução e reparação rápida, para apagar o cache do sistema após desabilitar as permissões de equipe em qualquer módulo. Se você não deseja usar a Reconstrução e Reparação Rápida, entre em contato com um administrador acessando o menu Reparar.
STR
,
);
