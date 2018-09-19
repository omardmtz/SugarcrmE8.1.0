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
    'ERR_ADD_RECORD' => 'Um número de registo deverá ser especificado para adicionar um Utilizador a esta equipa.',
    'ERR_DUP_NAME' => 'O Nome da Equipa já existe, por favor escolha outro.',
    'ERR_DELETE_RECORD' => 'Um número de registo deverá ser especificado para eliminar esta equipa.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Erro. A equipa selecionada <b>({0})</b> é uma equipa que escolheu para eliminar. Por favor, selecione uma outra equipa.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Erro. Não é possível apagar um utilizador cuja equipa privada ainda não foi apagada.',
    'LBL_DESCRIPTION' => 'Descrição:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globalmente visíveis',
    'LBL_INVITEE' => 'Membros da Equipa',
    'LBL_LIST_DEPARTMENT' => 'Departamento',
    'LBL_LIST_DESCRIPTION' => 'Descrição',
    'LBL_LIST_FORM_TITLE' => 'Lista de Equipas',
    'LBL_LIST_NAME' => 'Nome',
    'LBL_FIRST_NAME' => 'Nome Próprio:',
    'LBL_LAST_NAME' => 'Apelido:',
    'LBL_LIST_REPORTS_TO' => 'Reporta a',
    'LBL_LIST_TITLE' => 'Título',
    'LBL_MODULE_NAME' => 'Equipas',
    'LBL_MODULE_NAME_SINGULAR' => 'Equipa',
    'LBL_MODULE_TITLE' => 'Equipas: Ecrã Principal',
    'LBL_NAME' => 'Nome da Equipa:',
    'LBL_NAME_2' => 'Nome da Equipa(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Nome da Equipa Principal',
    'LBL_NEW_FORM_TITLE' => 'Nova Equipa',
    'LBL_PRIVATE' => 'Privado',
    'LBL_PRIVATE_TEAM_FOR' => 'Equipa privada para:',
    'LBL_SEARCH_FORM_TITLE' => 'Pesquisa de Equipas',
    'LBL_TEAM_MEMBERS' => 'Membros da Equipa',
    'LBL_TEAM' => 'Equipas:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Utilizadores',
    'LBL_USERS' => 'Utilizadores',
    'LBL_REASSIGN_TEAM_TITLE' => 'Há registos atribuídos à(s) seguinte(s) equipa(s): <b>{0}</b> <br> Antes de excluir a(s) equipa(s), deve primeiro reatribuir esses registos para uma nova equipa. Escolha uma equipa para ser usada como substituta.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Reatribuir',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Reatribuir',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Proceder à actualização dos registos afectados para utilizar a nova equipa?',
    'LBL_REASSIGN_TABLE_INFO' => 'Actualizando tabela {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operação concluída com sucesso.',
    'LNK_LIST_TEAM' => 'Equipas',
    'LNK_LIST_TEAMNOTICE' => 'Notícias da Equipa',
    'LNK_NEW_TEAM' => 'Criar Equipa',
    'LNK_NEW_TEAM_NOTICE' => 'Criar Nota de Equipa',
    'NTC_DELETE_CONFIRMATION' => 'Tem a certeza que deseja eliminar este registo?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Tem a certeza que pretende remover este utilizador desta equipa?',
    'LBL_EDITLAYOUT' => 'Editar Layout' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Permissões da equipa',
    'LBL_TBA_CONFIGURATION_DESC' => 'Permitir o acesso da equipa e gerir o acesso por módulo.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Ativar permissões da equipa',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Selecionar módulos para ativar',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Ativar as permissões da equipa permitirá atribuir direitos de acesso específicos para equipas e utilizadores em módulos individuais, através da Gestão de Funções.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Desativar as permissões de equipa num módulo irá reverter todos os dados associados com as permissões de equipa nesse módulo, incluindo todas as Definições de Processo ou Processos usando esse recurso. Isto inclui todas as Funções usando a opção "Proprietário e equipa selecionada" nesse módulo e todos os dados de permissões de equipa em registos nesse módulo. Além disso, recomendamos que utilize a ferramenta de Reconstrução e Reparação Rápida, para limpar a cache do sistema depois de desativar as permissões de equipa em qualquer módulo.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Aviso:</strong> Desativar as permissões de equipa num módulo irá reverter todos os dados associados com as permissões de equipa nesse módulo, incluindo todas as Definições de Processo ou Processos usando esse recurso. Isto inclui todas as Funções usando a opção "Proprietário e equipa selecionada" nesse módulo e todos os dados de permissões de equipa em registos nesse módulo. Além disso, recomendamos que utilize a ferramenta de Reconstrução e Reparação Rápida, para limpar a cache do sistema depois de desativar as permissões de equipa em qualquer módulo.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Desativar as permissões de equipa num módulo irá reverter todos os dados associados com as permissões de equipa nesse módulo, incluindo todas as Definições de Processo ou Processos usando esse recurso. Isso inclui todas as Funções usando a opção "Proprietário e equipa selecionada" nesse módulo e todos os dados de permissões de equipa em registos nesse módulo. Além disso, recomendamos o uso da ferramenta de Reconstrução e Reparação Rápida, para limpar a cache do sistema após desativar as permissões de equipa em qualquer módulo. Se não deseja usar a Reconstrução e Reparação Rápida, entre em contacto com um administrador com acesso ao menu Reparar.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Aviso:</strong> Desativar as permissões de equipa num módulo irá reverter todos os dados associados com as permissões de equipa nesse módulo, incluindo todas as Definições de Processo ou Processos usando esse recurso. Isso inclui todas as Funções usando a opção "Proprietário e equipa selecionada" nesse módulo e todos os dados de permissões de equipa em registos nesse módulo. Além disso, recomendamos o uso da ferramenta de Reconstrução e Reparação Rápida, para limpar a cache do sistema após desativar as permissões de equipa em qualquer módulo. Se não deseja usar a Reconstrução e Reparação Rápida, entre em contacto com um administrador com acesso ao menu Reparar.
STR
,
);
