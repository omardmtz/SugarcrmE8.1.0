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
    'LBL_LOADING' => 'Carregando' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Esconder opções' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Excluir' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Desenvolvido por SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Função',
'help'=>array(
    'package'=>array(
            'create'=>'Fornecer um <b>Nome</b> para o pacote. O nome deve começar com uma letra e pode consistir apenas de letras, números e sublinhado. Não pode conter espaços ou outros caracteres especiais. (Exemplo: Recursos_Humanos)<br/><br/> Você pode fornecer informações de <b>Autor</b> e <b>Descrição</b> do pacote. <br/><br/>Clique em <b>Salvar</b> para criar o pacote.',
            'modify'=>'As propriedades e ações possíveis para o <b>Pacote</b> aparecem aqui.<br><br>É possível modificar o <b>Nome</b>, o <b>Autor</b> e a <b>Descrição</b> do pacote, assim como ver e personalizar todos os módulos contidos dentro do pacote.<br><br>Clique em <b>Novo Módulo</b> para criar um módulo para o pacote.<br><br>Se o pacote contiver pelo menos um módulo, é possível <b>Publicar</b> e <b>Implementar</b> o pacote, assim como <b>Exportar</b> as personalizações feitas ao pacote.',
            'name'=>'Este é o <b>Nome</b> do pacote atual. <br/><br/>O nome deve iniciar com uma letra e pode conter apenas letras, números e sublinhado. Não pode conter espaços ou outros caracteres especiais. (Exemplo: Recursos_Humanos)',
            'author'=>'Este é o <b>Autor</b> que é exibido durante a instalação, como o nome da conta que criou o pacote.<br><br>O Autor poderá ser um individuo ou uma empresa.',
            'description'=>'Esta é a <b>Descrição</b> do pacote que é exibida durante a instalação.',
            'publishbtn'=>'Clique em <b>Publicar</b> para salvar todos os dados inseridos e criar um arquivo .zip que é a versão de instalação do pacote.<br><br>Use o <b>Carregador de Módulo</b> para carregar o arquivo .zip e instalar o pacote.',
            'deploybtn'=>'Clique em <b>Implementar</b> para salvar todos os dados inseridos e para instalar o pacote, inclusive todos os módulos, na instância atual.',
            'duplicatebtn'=>'Clique em <b>Duplicar</b> para copiar o conteúdo do pacote para outro pacote e para exibir o novo pacote. <br/><br/>Para o novo pacote, um novo nome será gerado automaticamente, com a adição de um número no fim do nome do pacote usado para criar o novo. É possível renomear o novo pacote inserindo um novo <b>Nome</b> e clicando em <b>Salvar</b>.',
            'exportbtn'=>'Clique em <b>Exportar</b> para criar um arquivo .zip contendo as personalizações feitas ao pacote.<br><br> O arquivo gerado não será uma versão de instalação do pacote.<br><br>Use o <b>Carregador de Módulo</b> para importar o arquivo .zip e para que o pacote, inclusive as personalizações, apareça no Construtor de Módulo.',
            'deletebtn'=>'Clique em <b>Excluir</b> para excluir este pacote e todos os arquivos relacionados a este pacote.',
            'savebtn'=>'Clique em <b>Salvar</b> para salvar todos os dados inseridos relacionados ao pacote.',
            'existing_module'=>'Clique no ícone do <b>Módulo</b> para editar as propriedades e personalizar os campos, relações e layouts associados com o módulo.',
            'new_module'=>'Clique em <b>Novo Módulo</b> para criar um novo módulo para este pacote.',
            'key'=>'Esta <b>Chave</b> de cinco letras, alfanumérica será usada como prefixo em todas os diretórios, nomes de classes e tabelas de banco de dados para todos os módulos do pacote atual.<br><br>A chave é usada para garantir que o nome das tabelas seja único.',
            'readme'=>'Clique para adicionar texto <b>Leia-me</b> para este pacote.<br><br>O Leia-me estará disponível no momento da instalação.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Forneça um <b>Nome</b> para o módulo. O <b>Rótulo</b> que indicou aparecerá na guia de navegação. <br/><br/>Escolha exibir a guia de navegação para o módulo clicando na caixa de seleção <b>Guia de Navegação</b>.<br/><br/>Marque a caixa de seleção <b>Segurança de Equipe</b> para ter um campo de seleção de Equipe dentro dos registros do módulo. <br/><br/>Depois, escolha o tipo de módulo que gostaria de criar. <br/><br/>Escolha um tipo de modelo. Cada modelo contém um conjunto de campos específico, assim como layouts predefinidos, para usar como base para o seu módulo. <br/><br/>Clique em <b>Salvar</b> para criar o módulo.',
        'modify'=>'É possível alterar as propriedades do módulo ou personalizar os <b>Campos</b>, <b>Relações</b> e <b>Layouts</b> relacionados com o módulo.',
        'importable'=>'Marcar a caixa de seleção <b>Importável</b> permitirá importar este módulo.<br><br>Um link para o Assistente de Importação aparecerá no painel de Atalhos dentro do módulo.  O Assistente de Importação facilita a importação de dados de fontes externas para o módulo personalizado.',
        'team_security'=>'Marcar a caixa de seleção <b>Segurança de Equipe</b> disponibilizará segurança de equipe para este módulo. <br/><br/>Se a segurança de equipe estiver ativada, o campo de seleção Equipe aparecerá nos registros do módulo ',
        'reportable'=>'Selecionar esta caixa permitirá que este módulo execute relatórios sobre ele.',
        'assignable'=>'Selecionar esta caixa permitirá que um registro deste módulo seja atribuído a um usuário selecionado.',
        'has_tab'=>'Selecionar <b>Guia de Navegação</b> fornecerá uma guia de navegação para o módulo.',
        'acl'=>'Selecionar esta caixa permitirá Controles de Acesso neste módulo, incluindo Segurança de Nível de Campo.',
        'studio'=>'Selecionar esta caixa permitirá aos administradores personalizar este módulo dentro do Studio.',
        'audit'=>'Selecionar esta caixa permitirá Auditorias para este módulo. Alterações a determinados campos serão registadas para que os administradores possam analisar o histórico de alterações.',
        'viewfieldsbtn'=>'Clique em <b>Visualizar Campos</b> para ver os campos associados com o módulo e criar ou editar campos personalizados.',
        'viewrelsbtn'=>'Clique em <b>Visualizar Relações</b> para ver as relações associadas com este módulo e para criar novas relações.',
        'viewlayoutsbtn'=>'Clique em <b>Visualizar Layouts</b> para ver os layouts para o módulo e para personalizar a disposição dos campos dentro dos layouts.',
        'viewmobilelayoutsbtn' => 'Clique em <b>Visualizar Layouts Móveis</b> para visualizar os layouts móveis para o módulo e personalizar a organização dos campos nos layouts.',
        'duplicatebtn'=>'Clique em <b>Duplicar</b> para copiar as propriedades do módulo para um novo módulo e exibir o novo módulo. <br/><br/>Para este novo módulo, um novo nome será gerado automaticamente com a adição de um número no fim do nome do módulo usado para criar o novo.',
        'deletebtn'=>'Clique em <b>Excluir</b> para excluir este módulo.',
        'name'=>'Este é o <b>Nome</b> do módulo atual.<br/><br/>O nome deve ser alfanumérico, começar com uma letra e não conter espaços. (Exemplo: Recursos_Humanos)',
        'label'=>'Este é o <b>Rótulo</b> que aparecerá na guia de navegação do módulo. ',
        'savebtn'=>'Clique em <b>Salvar</b> para salvar todos os dados inseridos relacionados com o módulo.',
        'type_basic'=>'O tipo de modelo <b>Básico</b> fornece campos básicos, como o Nome, Atribuído a, Equipe, Data de Criação e Descrição.',
        'type_company'=>'O tipo de modelo <b>Companhia</b> fornece campos específicos de organizações, como Nome da Companhia, Indústria e Endereço para faturamento.<br/><br/>Use este modelo para criar módulos que são similares ao módulo padrão de Contas.',
        'type_issue'=>'O tipo de modelo <b>Assunto</b> fornece campos específicos de Ocorrências e Bugs, como por exemplo Número, Estado, Prioridade e Descrição.<br/><br/>Use este modelo para criar módulos que são similares aos módulos padrão Ocorrências e Rastreador de bugs.',
        'type_person'=>'O tipo de modelo <b>Pessoa</b> fornece campos específicos de indivíduos, como a Saudação, Título, Nome, Endereço e Número de Telefone.<br/><br/>Use este modelo para criar módulos que são similares aos módulos padrão Contatos e Potenciais.',
        'type_sale'=>'O tipo de modelo <b>Venda</b> fornece campos específicos de oportunidade, como Origem do Potencial, Fase, Quantidade e Probabilidade.<br/><br/>Use este modelo para criar módulos que são similares ao módulo padrão Oportunidades.',
        'type_file'=>'O tipo de modelo <b>Arquivo</b> fornece campos específicos de Documento, como o Nome do Arquivo, tipo de Documento e Data de Publicação.<br><br>Use este modelo para criar módulos que são similares ao módulo padrão Documentos.',

    ),
    'dropdowns'=>array(
        'default' => 'Todas as <b>Listas suspensas</b> para o aplicativo estão listadas aqui.<br><br>As listas suspensas de seleção podem ser usadas em campos suspensos em qualquer módulo.<br><br>Para fazer alterações em uma caixa suspensa existente, clicar no nome da caixa.<br><br>Clique em <b>Adicionar lista suspensa</b> para criar uma nova lista suspensa.',
        'editdropdown'=>'Listas suspensas podem ser usadas em campos de caixas suspensas padrão ou personalizadas em qualquer módulo.<br><br>Forneça um <b>Nome</b> para a lista suspensa.<br><br>Se algum pacote de idioma estiver instalado na aplicação, você pode Selecionar o <b>Idioma</b> para usar nos itens da lista.<br><br>No campo <b>Nome do Item</b>, forneça um nome para a opção na lista da caixa suspensa.  Este nome não aparecerá na lista suspensa que é visível aos usuários.<br><br>No campo <b>Exibir Rótulo</b>, forneça um rótulo que será visível aos usuários. <br><br>Depois de fornecer o nome do item e o rótulo, clique em <b>Adicionar</b> para adicionar o item à lista suspensa.<br><br>Para reordenar os itens na lista, arraste e solte os itens na posição desejada.<br><br>Para editar um rótulo de um item, clique no <b>ícone de Edição</b> e insira um novo rótulo. Para excluir um item da lista suspensa, clique no <b>ícone de Exclusão</b>.<br><br>Para desfazer a alteração feita a um rótulo, clique em <b>Desfazer</b>. Para refazer a alteração que foi desfeita, clique em <b>Refazer</b>.<br><br>Clique em <b>Salvar</b> para salvar a lista suspensa.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Clique em <b>Salvar e Implementar</b> para salvar as alterações ativá-las dentro do módulo.',
        'historyBtn'=> 'Clique em <b>Visualizar Histórico</b> para visualizar e restaurar um layout salvo anteriormente do histórico.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar Layout Padrão</b> para restaurar uma visualização para seu layout original.',
        'Hidden' 	=> 'Campos <b>Ocultos</b> que não aparecem no subpainel.',
        'Default'	=> 'Campos <b>Padrão</b> aparecem no subpainel.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Clique em <b>Salvar e Implementar</b> para salvar as alterações ativá-las dentro do módulo.',
        'historyBtn'=> 'Clique em <b>Visualizar Histórico</b> para visualizar e restaurar um layout salvo anteriormente do histórico.<br><br><b>Restaurar</b> dentro de <b>Visualizar Histórico</b> restaura o posicionamento do campo dentro do layout salvo anteriormente. Para alterar rótulos de campos, clique no ícone de Edição próximo a cada campo.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar layout padrão</b> para restaurar o layout original de uma visualização.<br><br><b>Restaurar layout padrão</b> restaura apenas a posição dos campos no layout original. Para alterar rótulos de campo, clique no ícone Editar em cada campo.',
        'Hidden' 	=> 'Campos <b>Ocultos</b> indisponíveis no momento para aos usuários para visualização de lista.',
        'Available' => 'Campos <b>Disponíveis</b> não são mostrados por padrão, mas poderão ser adicionados à Visualização de lista pelos usuários.',
        'Default'	=> 'Campos <b>Padrão</b> aparecem nas Visualizações de lista que não são personalizadas pelos usuários.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Clique em <b>Salvar e Implementar</b> para salvar as alterações ativá-las dentro do módulo.',
        'historyBtn'=> 'Clique em <b>Visualizar Histórico</b> para visualizar e restaurar um layout salvo anteriormente do histórico.<br><br><b>Restaurar</b> dentro de <b>Visualizar Histórico</b> restaura o posicionamento do campo dentro do layout salvo anteriormente. Para alterar rótulos de campos, clique no ícone de Edição próximo a cada campo.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar layout padrão</b> para restaurar o layout original de uma visualização.<br><br><b>Restaurar layout padrão</b> restaura apenas a posição dos campos no layout original. Para alterar rótulos de campo, clique no ícone Editar em cada campo.',
        'Hidden' 	=> 'Campos <b>Ocultos</b> indisponíveis no momento para aos usuários para visualização de lista.',
        'Default'	=> 'Campos <b>Padrão</b> aparecem nas Visualizações de lista que não são personalizadas pelos usuários.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Clicar em <b>Salvar e Implementar</b> vai salvar todas as alterações e torná-las ativas',
        'Hidden' 	=> 'Campos <b>Ocultos</b> não aparecem na Pesquisa.',
        'historyBtn'=> 'Clique em <b>Visualizar Histórico</b> para visualizar e restaurar um layout salvo anteriormente do histórico.<br><br><b>Restaurar</b> dentro de <b>Visualizar Histórico</b> restaura o posicionamento do campo dentro do layout salvo anteriormente. Para alterar rótulos de campos, clique no ícone de Edição próximo a cada campo.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar layout padrão</b> para restaurar o layout original de uma visualização.<br><br><b>Restaurar layout padrão</b> restaura apenas a posição dos campos no layout original. Para alterar rótulos de campo, clique no ícone Editar em cada campo.',
        'Default'	=> 'Campos <b>Padrão</b> aparecem na Pesquisa.'
    ),
    'layoutEditor'=>array(
        'defaultdetailview'=>'The <b>Layout</b> area contains the fields that are currently displayed within the <b>DetailView</b>.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'defaultquickcreate'=>'The <b>Layout</b> area contains the fields that are currently displayed within the <b>QuickCreate</b> form.<br><br>The QuickCreate form appears in the subpanels for the module when the Create button is clicked.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        //this defualt will be used for edit view
        'default'	=> 'The <b>Layout</b> area contains the fields that are currently displayed within the <b>EditView</b>.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        //this defualt will be used for edit view
        'defaultrecordview'   => 'The <b>Layout</b> area contains the fields that are currently displayed within the <b>Record View</b>.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'saveBtn'	=> 'Clique em <b>Salvar</b> para preservar as alterações feitas ao layout desde a ultima vez que foi salvo.<br><br>As alterações não serão exibidas no módulo enquanto não forem Implementadas as alterações salvas.',
        'historyBtn'=> 'Clique em <b>Visualizar Histórico</b> para visualizar e restaurar um layout salvo anteriormente do histórico.<br><br><b>Restaurar</b> dentro de <b>Visualizar Histórico</b> restaura o posicionamento do campo dentro do layout salvo anteriormente. Para alterar rótulos de campos, clique no ícone de Edição próximo a cada campo.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar layout padrão</b> para restaurar o layout original de uma visualização.<br><br><b>Restaurar layout padrão</b> restaura apenas a posição dos campos no layout original. Para alterar rótulos de campo, clique no ícone Editar em cada campo.',
        'publishBtn'=> 'Clique em <b>Salvar e Implementar</b> para salvar todas as alterações feitas ao layout desde a última vez que foi salvo, e para tornar as alterações ativas no módulo.<br><br>O layout será exibido imediatamente no módulo.',
        'toolbox'	=> 'A <b>Caixa de Ferramentas</b> contêm a <b>Lixeira</b>, elementos adicionais de layout e um conjunto de campos disponíveis para adicionar ao layout.<br/><br/>Os elementos de layout e os campos na Caixa de Ferramentas podem ser arrastados e soltos no layout, e os elementos de layout e os campos podem ser arrastados e soltos do layout para a Caixa de Ferramentas.<br><br>O elementos de layout são os <b>Painéis</b> e as <b>Linhas</b>. Adicionar uma nova linha ou um novo painel ao layout proporciona locais adicionais no layout para os campos.<br/><br/>Arraste e solte qualquer um dos campos na Caixa de Ferramentas ou layout para uma posição ocupada por um campo para trocar as localizações destes dois campos.<br/><br/>O campo <b>Preenchimento</b> cria espaços em branco no layout onde for colocado.',
        'panels'	=> 'A área <b>Layout</b> fornece uma visão de como o layout ficará dentro do módulo quando as alterações feitas ao layout forem implementadas.<br/><br/>Poderá reposicionar campos, linhas e painéis, arrastando-os e sotando-os na localização pretendida.<br/><br/>Remova elementos arrastando-os e soltando-os na <b>Lixeira</b> nas Caixa de Ferramentas, ou então adicione novos elementos e campos arrastando-os da <b>Caixa de Ferramentas</b> e soltando-os na localização pretendida do layout.',
        'delete'	=> 'Arraste e solte qualquer elemento aqui para o removê-lo do layout',
        'property'	=> 'Edite o <b>Rótulo</b> exibido para este campo. <br><br>A <b>Largura</b> fornece o valor da largura em pixels para módulos de Resseguro e como porcentagem da largura de tabela para módulos reversos compatíveis.',
    ),
    'fieldsEditor'=>array(
        'default'	=> 'Os <b>Campos</b> que estão disponíveis para o módulo estão listados aqui por Nome do Campo.<br><br>Campos personalizados criados para o módulo aparecem sobre os campos que estão disponíveis para o módulo por padrão.<br><br>Para editar um campo, clique no <b>Nome do Campo</b>.<br/><br/>Para criar um novo campo, clique em <b>Adicionar Campo</b>.',
        'mbDefault'=>'Os <b>Campos</b> que estão disponíveis para o módulo estão listados aqui por Nome do Campo.<br><br>Para configurar as propriedades para um campo, clicar no Nome do Campo.<br><br>Para criar um novo campo, clique em <b>Adicionar Campo</b>. O rótulo e as outras propriedades do novo campo podem ser editadas depois da criação clicando no Nome do Campo.<br><br>Depois do módulo ser implementado, os novos campos criados no Construtor de Módulo são considerados como campos padrão no módulo implementado no Studio.',
        'addField'	=> 'Selecione um <b>Tipo de Dados</b> para o novo campo. O tipo escolhido determina que tipo de caracteres podem ser inseridos no campo. Por exemplo, apenas números inteiros poderão ser inseridos em campos que são do tipo de dados Inteiros.<br><br> Forneça um <b>Nome</b> para o campo. O nome deve ser alfanumérico e não pode conter nenhum espaço. Sublinhados são válidos.<br><br> O <b>Rótulo de Exibição</b> é o rótulo que aparecerá para os campos nos layouts do módulo. O <b>Rótulo de Sistema</b> é usado para referir o campo no código.<br><br> Dependendo do tipo de dados selecionado para o campo, alguma ou todas as seguintes propriedades podem ser definidas para o campo:<br><br> <b>Texto de Ajuda</b> aparece temporariamente enquanto o usuário passa o mouse sobre o campo e pode ser usado para perguntar ao usuário qual o tipo de entrada desejada.<br><br> <b>Texto de Comentário</b> é apenas visível dentro do Studio e/ou no Construtor de Módulo e pode ser usado para descrever o campo para administradores.<br><br> <b>Valor Padrão</b> aparecerá no campo. Os usuários poderão inserir um novo valor no campo ou usar o valor padrão.<br><br> Selecione a caixa de seleção <b>Atualização em massa</b> para habilitar a funcionalidade de Atualização em massa para o campo.<br><br>O valor <b>Tamanho Máximo</b> determina o número máximo de caracteres que podem ser inserido no campo.<br><br> Selecione a caixa de seleção <b>Campo Obrigatório</b> para tornar o campo obrigatório. Um valor terá que ser fornecido para o campo de maneira para ser possível salvar o registro que contenha o campo.<br><br> Selecione a caixa de seleção <b>Reportável</b> para permitir ao campo ser usado por filtros e para exibir dados em Relatórios.<br><br> Selecione a caixa de seleção <b>Auditoria</b> para ser possível controlar alterações ao campo no registro de alterações.<br><br>Selecione a opção no campo <b>Importável</b> para permitir, desabilitar ou tornar obrigatório que o campo seja importado para o Assistente de Importação<br><br>Selecione uma opção no campo <b>Fundir Duplicados</b> para permitir ou desabilitar as funcionalidades de Fundir Duplicados e Encontrar Duplicados.<br><br>Propriedades adicionais podem ser definidas para determinados tipos de dados.',
        'editField' => 'As propriedades deste campo podem ser personalizadas.<br><br>Clique em <b>Clonar</b> para criar um novo campo com as mesmas propriedades.',
        'mbeditField' => 'O <b>Rótulo de Exibição</b> de um campo de modelo pode ser personalizado. As outras propriedades do campo não poderão ser personalizadas.<br><br>Clique em <b>Clonar</b> para criar um novo campo com as mesmas propriedades.<br><br>Para remover o campo de um modelo para que não seja exibido no módulo, remova o campo dos <b>Layouts</b> apropriados.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Exporte personalizações feitas no Studio criando pacotes que possam ser carregados em outra instância do Sugar por meio do <b>Carregador de Módulo</b>.<br><br>Primeiro, forneça um <b>Nome do Pacote</b>.  É possível também fornecer a informação do <b>Autor</b> e da <b>Descrição</b> para o pacote.<br><br>Selecione o(s) módulo(s) que contenham as personalizações que deseja exportar. Apenas módulos que contenham personalizações aparecerão para seleção.<br><br>Clique em <b>Exportar</b> para criar um arquivo .zip para o pacote contendo as personalizações.',
        'exportCustomBtn'=>'Clique em <b>Exportar</b> para criar um arquivo .zip para o pacote contendo as personalizações que deseja exportar.',
        'name'=>'Este é o <b>Nome</b> do pacote. Este nome será exibido durante a instalação.',
        'author'=>'Este é o <b>Autor</b> que é exibido durante a instalação como o nome da entidade que criou o pacote. O Autor poderá ser um indivíduo ou uma companhia.',
        'description'=>'Esta é a <b>Descrição</b> do pacote que é exibida durante a instalação.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bem-vindo à área de <b>Ferramentas de desenvolvimento</b>. <br/><br/>Use as ferramentas desta área para criar e gerenciar módulos e campos padrão e personalizados.',
        'studioBtn'	=> 'Use o <b>Studio</b> para personalizar módulos implementados.',
        'mbBtn'		=> 'Use o <b>Construtor de Módulo</b> para criar novos módulos.',
        'sugarPortalBtn' => 'Use o <b>Editor do Portal Sugar</b> para gerenciar e personalizar o Portal Sugar.',
        'dropDownEditorBtn' => 'Use o <b>Editor de lista suspensa</b> para adicionar e editar listas suspensas globais para campos de lista suspensa.',
        'appBtn' 	=> 'Modo de Aplicativo é onde se pode personalizar várias propriedades do programa, como por exemplo quantos relatórios TPS são exibidos na página principal',
        'backBtn'	=> 'Volte ao passo anterior.',
        'studioHelp'=> 'Use o <b>Studio</b> para determinar qual e como a informação é mostrada nos módulos.',
        'studioBCHelp' => ' indica que o módulo é compatível com versões anteriores',
        'moduleBtn'	=> 'Clique para editar este módulo.',
        'moduleHelp'=> 'Os componentes do módulo que podem ser personalizados aparecem aqui.<br><br>Clicar num ícone para Selecionar o componente a editar.',
        'fieldsBtn'	=> 'Crie e personalize os <b>Campos</b> para armazenar informação no módulo.',
        'labelsBtn' => 'Edite os <b>Rótulos</b> que são exibidos para os campos e outros títulos do módulo.'	,
        'relationshipsBtn' => 'Adicione uma nova ou veja as <b>Relações</b> existentes no módulo.' ,
        'layoutsBtn'=> 'Personalize o módulo <b>Layouts</b>. Os layouts são visualizações diferentes do módulo contendo campos.<br><br>É possível determinar quais campos são exibidos e como são organizados em cada layout.',
        'subpanelBtn'=> 'Determine quais campos são exibidos nos <b>Subpainéis</b> no módulo.',
        'portalBtn' =>'Personalize o módulo <b>Layouts</b> que aparece no <b>Portal Sugar</b>.',
        'layoutsHelp'=> 'O módulo <b>Layouts</b> que pode ser personalizado aparece aqui.<br><br>Os layouts mostram campos e dados dos campos.<br><br>Clique em um ícone para Selecionar o layout a editar.',
        'subpanelHelp'=> 'Os <b>Subpainéis</b> no módulo que podem ser personalizados aparecem aqui.<br><br>Clique no ícone para Selecionar o módulo a editar.',
        'newPackage'=>'Clique em <b>Novo Pacote</b> para criar um novo pacote.',
        'exportBtn' => 'Clique em <b>Exportar Personalizações</b> para criar e fazer download do pacote contendo as personalizações feitas no Studio para módulos específicos.',
        'mbHelp'    => 'Use o <b>Construtor de Módulo</b> para criar pacotes contendo módulos personalizados baseados em módulos padrão ou objetos personalizados.',
        'viewBtnEditView' => 'Personalizar o layout da <b>Visualização de edição</b> do módulo.<br><br>A Visualização de edição é um formulário que contém os campos para capturar os dados inseridos pelo usuário.',
        'viewBtnDetailView' => 'Personalize o layout da <b>Visualização de detalhe</b> do módulo.<br><br>A Visualização de detalhe exibe os dados de campo inseridos pelo usuário.',
        'viewBtnDashlet' => 'Personalize o <b>Sugar Dashlet</b> do módulo, inclusive a Visualização de listagem do Sugar Dashlet e a Pesquisa.<br><br>O Sugar Dashlet estará disponível para adicionar a páginas no módulo Principal.',
        'viewBtnListView' => 'Personalize o layout da <b>Visualização de lista</b> do layout.<br><br>Os resultados da Pesquisa aparecem na Visualização de lista.',
        'searchBtn' => 'Personalize o layout da <b>Pesquisa</b> do módulo.<br><br>Determine quais campos podem ser usados para filtrar registros que aparecem na Visualização de Lista.',
        'viewBtnQuickCreate' =>  'Personalize o layout de <b>Criação rápida</b> do módulo.<br><br>O formulário de Criação rápida aparece em subpainéis e no módulo de E-mails.',

        'searchHelp'=> 'Os formulários de <b>Pesquisa</b> que podem ser personalizados aparecem aqui.<br><br>Os formulários de Pesquisa contêm campos para filtrar registros.<br><br>Clique em um ícone para escolher o layout de pesquisa a editar.',
        'dashletHelp' =>'Os layouts do <b>Sugar Dashlet</b> que podem ser personalizados aparecem aqui.<br><br>O Sugar Dashlet estará disponível para adicionar às páginas no módulo Principal.',
        'DashletListViewBtn' =>'A <b>Visualização de Lista do Sugar Dashlet</b> exibe registros baseados nos filtros de pesquisa do Sugar Dashlet.',
        'DashletSearchViewBtn' =>'A <b>Pesquisa do Sugar Dashlet</b> filtra registros para a visualização de lista do Sugar Dashlet.',
        'popupHelp' =>'Os layouts de <b>Pop-up</b> que podem ser personalizados aparecem aqui.<br>',
        'PopupListViewBtn' => 'O layout da <b>Visualização de lista de Pop-up</b> é usado para visualizar uma lista de registros ao selecionar um ou mais registros para relacionar ao registro atual.',
        'PopupSearchViewBtn' => 'A <b>Pesquisa de Pop-up</b> permite que os usuários pesquisem registros relacionados ao registro atual e é exibida acima da visualização de lista pop-up na mesma janela. Módulos legados usam esse layout para pesquisa de pop-up enquanto módulos de resseguro usam a configuração de layouts de Pesquisa.',
        'BasicSearchBtn' => 'Personalize o formulário de <b>Pesquisa Básica</b> que aparece na guia da Pesquisa Básica na área de Pesquisa para o módulo.',
        'AdvancedSearchBtn' => 'Personalize o formulário da <b>Pesquisa Avançada</b> que aparece na guia da Pesquisa Avançada na área de Pesquisa para o módulo.',
        'portalHelp' => 'Gerencie e personalize o <b>Portal Sugar</b>.',
        'SPUploadCSS' => 'Carregue uma <b>Folha de Estilo</b> no Portal Sugar.',
        'SPSync' => '<b>Sincronize</b> personalizações com a instância do Portal Sugar.',
        'Layouts' => 'Personalize os <b>Layouts</b> dos módulos do Portal Sugar.',
        'portalLayoutHelp' => 'Os módulos dentro do Portal Sugar aparecem nesta área.<br><br>Selecione o módulo para editar os <b>Layouts</b>.',
        'relationshipsHelp' => 'Todas as <b>Relações</b> que existem entre os módulos e outros módulos implementados aparecem aqui.<br><br>O <b>Nome</b> da relação é um nome gerado pelo sistema para esta relação.<br><br>O <b>Módulo Primário</b> é o módulo que detém as relações. Por exemplo, todas as propriedades das relações cujo módulo Contas é o módulo primário estão armazenadas nas tabelas do banco de dados das Contas.<br><br>O <b>Tipo</b> é o tipo de relação que existe entre o módulo Primário e o <b>Módulo Relacionado</b>.<br><br>Clique em um título da coluna para ordenar pela coluna.<br><br>Clique na linha da tabela de relações para ver as propriedades associadas com a relação.<br><br>Clique em <b>Adicionar Relação</b> para criar uma nova relação.<br><br>Relações podem ser criadas entre dois módulos implementados quaisquer.',
        'relationshipHelp'=>'<b>Relações</b> podem ser criadas entre o módulo e outro módulo implementado.<br><br>Relações são expressadas visualmente por meio de subpainéis e campos relacionados nos registros do módulo.<br><br>Selecione um dos seguintes <b>Tipos</b> de relações para o módulo:<br><br> <b>Um-para-Um</b> - Ambos os registros dos módulos terão campos relacionados.<br><br> <b>Um-para-Muitos</b> - Os registros do Módulo Primário terão um subpainel e os registros do Módulo Relacionado terão um campo relacionado.<br><br> <b>Muitos-para-Muitos</b> - Ambos os registros dos módulos mostrarão subpainéis.<br><br> Selecione o <b>Módulo Relacionado</b> para a relação.<br><br>Se o tipo de relação envolver subpainéis, escolha uma visualização de subpainel para os módulos apropriados.<br><br> Clique em <b>Salvar</b> para criar a relação.',
        'convertLeadHelp' => "Aqui pode-se adicionar módulos para a tela de layouts de conversão e modificar os layouts existentes.<br/><br/>
<b>Ordenamento:</b><br/>
Contatos, Contas e Oportunidades devem manter sua ordem. Você pode reordenar os módulos arrastando as suas linhas na tabela.<br/><br/>
<b>Dependência:</b><br/>
Se Oportunidades estiver incluído, Contas ou deve ser obrigatório ou deve ser removido do layout de conversão.<br/><br/>
<b>Módulo:</b>O nome do módulo.<br/><br/>
<b>Obrigatório:</b>Módulos obrigatórios devem ser criados ou selecionados antes que o potencial possa ser convertido.<br/><br/>
<b>Copiar dados:</b> Se selecionado, os campos do potencial serão copiados para campos com o mesmo nome para o registro criado recentemente.<br/><br/>
<b>Excluir:</b> Remove este módulo do layout de conversão.<br/><br/>        ",
        'editDropDownBtn' => 'Editar uma lista suspensa global',
        'addDropDownBtn' => 'Adicionar uma nova lista suspensa global',
    ),
    'fieldsHelp'=>array(
        'default'=>'Os <b>Campos</b> do módulo são listados aqui por Nome de Campo.<br><br>O modelo do módulo inclui um conjunto predeterminado de campos.<br><br>Para criar um novo campo, clique em <b>Adicionar Campo</b>.<br><br>Para editar um campo, clique em <b>Nome do Campo</b>.<br/><br/>Depois do módulo ser implementado, os novos campos criados no Construtor de Módulo, juntamente com os campos do modelo, são considerados como campos padrão no Studio.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'As <b>Relações</b> que foram criadas entre o módulo e outros módulos aparecem aqui.<br><br>O <b>Nome</b> da relação é o nome gerado pelo sistema para a relação.<br><br>O <b>Módulo Primário</b> é o módulo proprietário das relações. As propriedades da relação são armazenadas nas tabelas do banco de dados pertencente ao módulo primário.<br><br>O <b>Tipo</b> é o tipo de relação que existe entre o módulo Primário e o <b>Módulo Relacionado</b>.<br><br>Clique no título da coluna para ordenar pela coluna.<br><br>Clique em uma linha da tabela da relação para visualizar e editar as propriedades associadas à relação.<br><br>Clique em <b>Adicionar Relação</b> para criar uma nova relação.',
        'addrelbtn'=>'passe sobre o mouse sobre a ajuda para adicionar relação..',
        'addRelationship'=>'<b>Relações</b> podem ser criadas entre o módulo e outro módulo personalizado ou um módulo implementado.<br><br> Relações são expressadas visualmente por meio de subpainéis e relaciona campos nos registros do módulo.<br><br>Selecione um dos seguintes <b>Tipos</b> de relações para o módulo:<br><br> <b>Um-para-Um</b> - Ambos os registros dos módulos terão campos relacionados.<br><br> <b>Um-para-Muitos</b> - Os registros do Módulo Primário terão um subpainel e os registros do Módulo Relacionado terão um campo relacionado.<br><br> <b>Muitos-para-Muitos</b> - Ambos os registros dos módulos mostrarão subpainéis.<br><br> Selecione o <b>Módulo Relacionado</b> para a relação.<br><br>Se o tipo de relação envolver subpainéis, escolha uma visualização de subpainel para os módulos apropriados.<br><br> Clique em <b>Salvar</b> para criar a relação.',
    ),
    'labelsHelp'=>array(
        'default'=> 'Os <b>Rótulos</b> para os campos e outros títulos no módulo podem ser alterados.<br><br>Edite o rótulo clicando dentro do campo, insira o novo rótulo e clique em <b>Salvar</b>.<br><br>Se algum pacote de idioma estiver instalado na aplicação, você pode selecionar o <b>Idioma</b> a ser usado nos rótulos.',
        'saveBtn'=>'Clique em <b>Salvar</b> para salvar todas as alterações.',
        'publishBtn'=>'Clique em <b>Salvar e Implementar</b> para salvar todas as alterações e torná-las ativas.',
    ),
    'portalSync'=>array(
        'default' => 'Insira a <b>URL do Portal Sugar</b> da instância do portal a ser atualizado, e clique em <b>Ir</b>.<br><br>Depois insira um nome de usuário Sugar válido e senha e clique em <b>Começar Sincronização</b>.<br><br>As personalizações feitas aos <b>Layouts</b> do Portal Sugar, juntamente com a <b>Folha de Estilo</b> se alguma foi carregada, serão transferidas para a instância do portal especificada.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Você pode personalizar a aparência do Portal Sugar usando uma folha de estilo.<br><br>Selecione uma <b>Folha de Estilo</b> para carregar.<br><br>A folha de estilo será implementada no Portal Sugar na próxima vez que uma sincronização for realizada.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Para começar um projeto, clique em <b>Novo Pacote</b> para criar um novo pacote para hospedar o(s) seu(s) módulo(s) personalizado(s). <br/><br/>Cada pacote pode conter um ou mais módulos.<br/><br/>Por exemplo, você pode desejar criar um pacote contendo um módulo personalizado que está relacionado com o módulo padrão Contas. Ou poderá querer criar um pacote contendo vários módulos novos que trabalhem em conjunto como um projeto e que estão relacionados entre si e com outros módulos já existentes no aplicativo.',
            'somepackages'=>'Um <b>pacote</b> funciona com um recipiente para módulos personalizados que fazem parte de um projeto. O pacote pode conter um ou mais <b>módulos</b> personalizados que podem estar relacionados entre si ou com outros módulos da aplicação.<br/><br/>Depois de criar um pacote para o projeto, você pode criar módulos para o pacote logo em seguida, ou poderá voltar ao Construtor de Módulo mais tarde para completar o projeto.<br><br>Quando o projeto é concluído, você pode <b>Implementar</b> o pacote para instalar os módulos personalizados no aplicativo.',
            'afterSave'=>'O novo pacote deverá conter pelo menos um módulo. Você pode criar um ou mais módulos personalizados para o pacote.<br/><br/>Clique em <b>Novo Módulo</b> para criar um módulo personalizado para este pacote.<br/><br/>Depois de criar pelo menos um módulo, você pode publicar ou implementar o pacote para torná-lo disponível para sua instância e/ou para as instâncias de outros usuários.<br/><br/> Para implementar o pacote com apenas um passo dentro da sua instância do Sugar, clique em <b>Implementar</b>.<br><br>Clique em <b>Publicar</b> para salvar o pacote como um arquivo .zip. Depois que o arquivo .zip estiver salvo no seu sistema, use o <b>Carregador de Módulo</b> para carregar e instalar o pacote dentro da sua instância do Sugar.<br/><br/>Você pode distribuir o arquivo por outros usuários para eles carregarem e instalarem nas suas próprias instâncias do Sugar.',
            'create'=>'Um <b>pacote</b> funciona com um recipiente para módulos personalizados que fazem parte de um projeto. O pacote pode conter um ou mais <b>módulos</b> personalizados que podem estar relacionados entre si ou com outros módulos do aplicativo.<br/><br/>Depois de criar um pacote para o projeto, você pode criar módulos para o pacote logo em seguida, ou pode voltar ao Construtor de Módulo mais tarde para concluir o projeto.',
            ),
    'main'=>array(
        'welcome'=>'Use as <b>Ferramentas de desenvolvimento</b> para criar e gerenciar módulos e campos padrão e personalizados. <br/><br/>Para gerenciar módulos no aplicativo, clique em <b>Studio</b>. <br/><br/>Para criar módulos personalizados, clique em <b>Construtor de Módulo</b>.',
        'studioWelcome'=>'Todos os módulos instalados atualmente, inclusive os objetos padrão e carregados por módulos, são personalizáveis dentro do Studio.'
    ),
    'module'=>array(
        'somemodules'=>"Como o pacote atual contém pelo menos um módulo, você pode <b>Implementar</b> os módulos do pacote dentro da sua instância do Sugar ou <b>Publicar</b> o pacote a ser instalado na instância atual do Sugar ou em outra instância usando o <b>Carregador de Módulo</b>.<br/><br/>Para instalar o pacote diretamente dentro da sua instância do Sugar, clique em <b>Implementar</b>.<br><br>Para criar um arquivo .zip para o pacote que possa ser carregado e instalado na instância atual de Sugar e outras instâncias usando o <b>Carregador de Módulo</b>, clique em <b>Publicar</b>.<br/><br/>Você pode construir os módulos para este pacote em etapas, e publicar ou implementar quanto estiver pronto para tal.<br/><br/>Depois de publicar ou implementar o pacote, você pode fazer alterações nas propriedades do pacote e personalizar mais os módulos. Em seguida, publique ou implemente novamente o pacote para aplicar as alterações." ,
        'editView'=> 'Aqui você pode editar os campos existentes. Você pode remover quaisquer campos existentes ou adicionar campos disponíveis no painel esquerdo.',
        'create'=>'Ao escolher o tipo de <b>Tipo</b> de módulo que deseja criar, lembre-se de quais tipos de campos que gostaria de ter dentro do módulo. <br/><br/>Cada modelo de módulo contém um conjunto de campos pertencentes ao tipo de módulo descrito pelo título.<br/><br/><b>Básico</b> - Fornece campos básicos que aparecem em módulos padrão, como o campo Nome, Atribuído a, Equipe, Data de Criação e Descrição.<br/><br/> <b>Companhia</b> - Fornece campos específicos de organizações, como Nome da Companhia e Endereço para faturamento. Use este modelo para criar módulos que são similares ao módulo de Contas padrão.<br/><br/> <b>Pessoa</b> - Fornece campos específicos de indivíduos, como Saudação, Título, Nome, Endereço e Número de Telefone. Use este modelo para criar módulos que são similares aos módulos Contatos e Potenciais padrão.<br/><br/><b>Assunto</b> - Fornece campos relacionados com ocorrências e de bugs, como Número, Estado, Prioridade e Descrição. Use este modelo para criar módulos que são similares aos módulos padrão Ocorrências e Rastreador de bugs.<br/><br/>Nota: Depois de criar o módulo, você pode editar os rótulos dos campos fornecidos pelo modelo, assim como criar campos personalizados para adicionar ao layout do módulo.',
        'afterSave'=>'Personalize o módulo para atender as suas necessidades editando e criando campos, estabelecendo relações com outros módulos e organizando os campos dentro dos layouts.<br/><br/>Para ver os campos do modelo e gerenciar os campos personalizados dentro do módulo, clique em <b>Visualizar campos</b>.<br/><br/>Para criar e gerenciar relações entre o módulo e outros módulos, seja os módulos já no aplicativo ou outros módulos personalizados dentro do mesmo pacote, clique em <b>Visualizar relações</b>.<br/><br/>Para editar os layouts dos módulos, clique em <b>Visualizar Layouts</b>. Você pode alterar os layouts na Visualização de detalhes, Visualização de edição e Visualização de lista do módulo tal como faria com módulos já na aplicação dentro do Studio.<br/><br/> Para criar um módulo com as mesmas propriedades que o módulo atual, clique em <b>Duplicar</b>.  Depois é possível personalizar mais o novo módulo.',
        'viewfields'=>'Os campos no módulo podem ser personalizados para atender as suas necessidades.<br/><br/>Não é possível apagar os campos padrão, mas é possível removê-los dos layouts adequados dentro das páginas de Layout.<br/><br/>Você pode criar rapidamente novos campos que têm propriedades similares aos campos existentes clicando em <b>Clonar</b> no formulário das <b>Propriedades</b>. Insira quaisquer novas propriedades e clique em <b>Salvar</b>.<br/><br/>É recomendado que você defina todas as propriedades para os campos padrão e os campos personalizados antes de publicar e instalar o pacote que contém o módulo personalizado.',
        'viewrelationships'=>'Você pode criar relações muitos-para-muitos entre o módulo atual e outros módulos do pacote, e/ou entre o módulo atual e módulos já instalados no aplicativo.<br><br> Para criar relações um-para-muitos e um-para-um, crie campos <b>Relacionado</b> e <b>Relacionado Flexível </b> para os módulos.',
        'viewlayouts'=>'Você pode controlar quais os campos estão disponíveis para capturar dados dentro da <b>Visualização de edição</b>. Você pode também controlar quais dados são exibidos dentro da <b>Visualização de detalhes</b>.  As visualizações não precisam ser correspondentes. <br/><br/>O formulário de Criação rápida é mostrado quando <b>Criar</b> é clicado num subpainel de um módulo. Por padrão, o layout do formulário <b>Criação rápida </b> é o mesmo que o layout de <b>Edição</b> por padrão. Você pode personalizar o formulário Criação rápida para que ele contenha menos campos e/ou campos diferentes que o layout da Visualização de edição.<br><br>Você pode determinar a segurança do módulo usando a personalização do Layout, juntamente com a <b>Gestão de Função</b>.<br><br>',
        'existingModule' =>'Depois de criar e personalizar este módulo, é possível criar módulos adicionais ou voltar ao pacote para <b>Publicar</b> ou <b>Implementar</b> o pacote.<br><br>Para criar módulos adicionais, clique em <b>Duplicar</b> para criar um módulo com as mesmas propriedades que o módulo atual, ou voltar ao pacote e clique em <b>Novo Módulo</b>.<br><br>Se já estiver pronto para <b>Publicar</b> ou <b>Implementar</b> o pacote que contém este módulo, volte ao pacote para realizar estas funções. É possível publicar e implementar pacotes que contenham pelo menos um módulo.',
        'labels'=> 'Os rótulos dos campos padrão assim com os dos campos personalizados podem ser alterados. Alterar os rótulos dos campos não afeta os dados armazenados nos campos.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Existem três colunas exibidas à esquerda. A coluna "Padrão" contêm os campos que são mostrados na visualização de lista por padrão, a coluna "Disponíveis" contém campos que um usuário pode escolher usar quando está criando uma visualização de lista personalizada e a coluna "Ocultos" contém campos ocultos, mas que estão disponíveis ao administrador para adicionar às colunas por padrão ou disponíveis para serem utilizados pelos usuários.',
        'savebtn'	=> 'Clicar em <b>Salvar</b> vai salvar todas as alterações e torná-las ativas.',
        'Hidden' 	=> 'Campos ocultos são campos que não estão visíveis aos usuários para utilização nas visualizações de lista.',
        'Available' => 'Campos disponíveis são campos que não são exibidos por padrão, mas podem ser ativados pelos usuários.',
        'Default'	=> 'Campos padrão são exibidos aos usuários que não criaram configurações personalizadas de visualização de lista.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Existem duas colunas exibidas à esquerda. A coluna "Padrão" contém os campos que são mostrados na pesquisa e a coluna "Ocultos" contém campos ocultos, mas que estão disponíveis ao administrador para adicionar à visualização.',
        'savebtn'	=> 'Clicar em <b>Salvar e Implementar</b> vai guardar todas as alterações e torná-las ativas.',
        'Hidden' 	=> 'Campos ocultos são campos que não estão visíveis na visualização de pesquisa.',
        'Default'	=> 'Campos padrão serão exibidos na visualização de pesquisa.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Existem duas colunas exibidas à esquerda. A coluna da direita, rotulada de Layout atual ou Pré-visualização de Layout é onde você pode alterar o layout do módulo. A coluna da esquerda, intitulada Caixa de Ferramentas, contém elementos úteis e ferramentas para utilizar ao editar o layout.<br/><br/>Se a área do layout tem o título de Layout atual, então você está trabalhando em uma cópia do layout usado atualmente pelo módulo para exibição.<br/><br/>Se é intitulada Pré-visualização de Layout, você está trabalhando em uma cópia criada anteriormente quando o botão Salvar foi clicado, que pode já ter sido alterada da versão visualizada pelos usuários deste módulo.',
        'saveBtn'	=> 'Clicar neste botão grava o layout para que você possa preservar as suas alterações. Quando voltar a este módulo, você começará a partir deste layout alterado. No entanto o seu layout não será visualizado pelos usuários do módulo, até que você clique no botão Salvar e Publicar.',
        'publishBtn'=> 'Clique neste botão para implementar o layout. Isso significa que este layout será exibido aos usuários deste módulo imediatamente.',
        'toolbox'	=> 'A caixa de ferramentas contém vários recursos úteis para a edição de layouts, incluindo uma área de reciclagem, um conjunto de elementos adicionais e um conjunto de campos disponíveis. Qualquer um deles pode ser arrastados e solto no layout.',
        'panels'	=> 'Esta área mostra como o layout será exibido aos usuários deste módulo quando implementado.<br/><br/>Você pode reposicionar elementos como campos, linhas e painéis arrastando e soltando-os; excluir elementos arrastando e soltando-os na área da lixeira na caixa de ferramentas, ou adicionar novos elementos arrastando e soltando-os no layout na posição desejada.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Existem duas colunas exibidas à esquerda. A coluna da direita, rotulada de Layout atual ou Pré-visualização de Layout é onde você pode alterar o layout do módulo. A coluna da esquerda, intitulada Caixa de Ferramentas, contém elementos úteis e ferramentas para utilizar ao editar o layout.<br/><br/>Se a área do layout tem o título de Layout atual, então você está trabalhando em uma cópia do layout usado atualmente pelo módulo para exibição.<br/><br/>Se é intitulada Pré-visualização de Layout, você está trabalhando em uma cópia criada anteriormente quando o botão Salvar foi clicado, que pode já ter sido alterada da versão visualizada pelos usuários deste módulo.',
        'dropdownaddbtn'=> 'Clicar neste botão adiciona um novo item no menu suspenso.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Personalizações feitas no Studio dentro desta instância podem ser empacotadas e implementadas em outra instância.  <br><br>Forneça um <b>Nome de Pacote</b>. Você pode fornecer a informação do <b>Autor</b> e da <b>Descrição</b> para o pacote.<br><br>Selecione o(s) módulo(s) que contenham as personalizações a exportar. (Apenas módulos que contenham personalizações aparecerão para seleção.)<br><br>Clique <b>Exportar</b> para criar um arquivo .zip para o pacote contendo as personalizações. O arquivo .zip pode ser carregado para outra instância por meio do <b>Carregador de Módulo</b>.',
        'exportCustomBtn'=>'Clique em <b>Exportar</b> para criar um arquivo .zip para o pacote contendo as personalizações que deseja exportar.
',
        'name'=>'Este é o <b>Nome</b> do pacote a ser exibido no Carregador de Módulo depois do pacote ser carregado para instalação no Studio.',
        'author'=>'O <b>Autor</b> é o nome da entidade que criou o pacote. O Autor poderá ser um indivíduo ou uma empresa.<br><br>O Autor será exibido no Carregador de Módulo depois do pacote ser carregado para instalação no Studio.
',
        'description'=>'A <b>Descrição</b> do pacote será exibida no Carregador de Módulo depois do pacote ser carregado para instalação no Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bem-vindo à área de <b>Ferramentas de desenvolvimento</b1>. <br/><br/>Use as ferramentas desta área para criar e gerenciar módulos e campos padrão e personalizados.',
        'studioBtn'	=> 'Use o <b>Studio</b> para personalizar módulos instalados alterando a disposição dos campos, escolhendo quais os campos estão disponíveis e criando novos campos de dados personalizados.',
        'mbBtn'		=> 'Use o <b>Construtor de Módulo</b> para criar novos módulos.',
        'appBtn' 	=> 'Use o modo de aplicativo para personalizar várias propriedades do programa, como por exemplo quantos relatórios TPS são exibidos na página principal',
        'backBtn'	=> 'Volte ao passo anterior.',
        'studioHelp'=> 'Use o <b>Studio</b> para personalizar módulos instalados.',
        'moduleBtn'	=> 'Clique para editar este módulo.',
        'moduleHelp'=> 'Selecione o componente do módulo que deseja editar',
        'fieldsBtn'	=> 'Edite a informação que está armazenada no módulo controlando os <b>Campos</b> no módulo.<br/><br/>Você pode editar e criar campos personalizados aqui.',
        'layoutsBtn'=> 'Personalize os <b>Layouts</b> das visualizações de Edição, Detalhe, Lista e pesquisa.',
        'subpanelBtn'=> 'Edite qual a informação será exibida nos subpainéis deste módulo.',
        'layoutsHelp'=> 'Selecione o <b>Layout a editar</b>.<br/<br/>Para alterar o layout que contém campos de dados para inserir dados, clique em <b>Visualização de edição</b>.<br/><br/>Para alterar o layout que exibe os dados inseridos nos campos da Visualização de edição, clique em <b>Visualização de detalhes</b>.<br/><br/>Para alterar as colunas que aparecem na lista por padrão, clique na <b>Visualização de Lista</b>.<br/><br/>Para alterar o layout do formulário de pesquisa Básica e Avançada, clique <b>Pesquisa</b>.',
        'subpanelHelp'=> 'Selecione um <b>Subpainel</b> para editar.',
        'searchHelp' => 'Selecione um layout de <b>Pesquisa</b> para editar.',
        'labelsBtn'	=> 'Edite os <b>Rótulos</b> para exibir os valores neste módulo.',
        'newPackage'=>'Clique em <b>Novo Pacote</b> para criar um novo pacote.',
        'mbHelp'    => '<b>Bem-vindo ao Construtor de módulo.</b><br/><br/>Use o <b>Construtor de módulo</b> para criar pacotes que contenham módulos personalizados baseados em objetos padrão ou personalizados. <br/><br/>Para começar, clique em <b>Novo Pacote</b> para criar um novo pacote, ou selecione um pacote para editar.<br/><br/> Um <b>pacote</b> funciona como um recipiente para módulos personalizados, que fazem parte de um projeto. O pacote pode conter um ou mais módulos personalizados que podem estar relacionados um com os outros ou com módulos do aplicativo. <br/><br/>Exemplos: Você pode desejar criar um pacote que contenha um módulo personalizado que está relacionado com o módulo padrão de Contas. Ou pode querer criar um pacote que contenha novos módulos que funcionem em conjunto como um projeto que estão relacionados entre si e com outros módulos do aplicativo.',
        'exportBtn' => 'Clique em <b>Exportar personalizações</b> para criar o pacote contendo as personalizações feitas no Studio para módulos específicos.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Editor da lista suspensa',

//ASSISTANT
'LBL_AS_SHOW' => 'Mostrar Assistente no futuro.',
'LBL_AS_IGNORE' => 'Ignorar Assistente no futuro.',
'LBL_AS_SAYS' => 'Assistente diz:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Construtor de módulo',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Editor da lista suspensa',
'LBL_EDIT_DROPDOWN'=>'Editar a lista suspensa',
'LBL_DEVELOPER_TOOLS' => 'Ferramentas de desenvolvimento',
'LBL_SUGARPORTAL' => 'Editor do Portal Sugar',
'LBL_SYNCPORTAL' => 'Sincronizar com Portal',
'LBL_PACKAGE_LIST' => 'Lista de Pacotes',
'LBL_HOME' => 'Tela Principal',
'LBL_NONE'=>'-Nenhum-',
'LBL_DEPLOYE_COMPLETE'=>'Instalação concluída',
'LBL_DEPLOY_FAILED'   =>'Ocorreu um erro durante o processo de instalação, o pacote pode não ter instalado corretamente',
'LBL_ADD_FIELDS'=>'Adicionar campos personalizados',
'LBL_AVAILABLE_SUBPANELS'=>'Subpainéis disponíveis',
'LBL_ADVANCED'=>'Avançada',
'LBL_ADVANCED_SEARCH'=>'Pesquisa avançada',
'LBL_BASIC'=>'Básica',
'LBL_BASIC_SEARCH'=>'Pesquisa básica',
'LBL_CURRENT_LAYOUT'=>'Layout atual',
'LBL_CURRENCY' => 'Moeda',
'LBL_CUSTOM' => 'Personalizar',
'LBL_DASHLET'=>'Sugar Dashlet',
'LBL_DASHLETLISTVIEW'=>'Visualização de lista do Sugar Dashlet',
'LBL_DASHLETSEARCH'=>'Pesquisa do Sugar Dashlet',
'LBL_POPUP'=>'Visualização do Pop-up',
'LBL_POPUPLIST'=>'Visualização de lista do pop-up',
'LBL_POPUPLISTVIEW'=>'Visualização de lista do pop-up',
'LBL_POPUPSEARCH'=>'Pesquisa do Pop-up',
'LBL_DASHLETSEARCHVIEW'=>'Pesquisa do Sugar Dashlet',
'LBL_DISPLAY_HTML'=>'Mostrar código HTML',
'LBL_DETAILVIEW'=>'Visualização de detalhe',
'LBL_DROP_HERE' => '[Solte aqui]',
'LBL_EDIT'=>'Editar',
'LBL_EDIT_LAYOUT'=>'Editar Layout',
'LBL_EDIT_ROWS'=>'Editar linhas',
'LBL_EDIT_COLUMNS'=>'Editar colunas',
'LBL_EDIT_LABELS'=>'Editar etiquetas',
'LBL_EDIT_PORTAL'=>'Editar Portal para',
'LBL_EDIT_FIELDS'=>'Editar Campos',
'LBL_EDITVIEW'=>'Visualização de edição',
'LBL_FILTER_SEARCH' => "Pesquisar",
'LBL_FILLER'=>'(preenchimento)',
'LBL_FIELDS'=>'Campos',
'LBL_FAILED_TO_SAVE' => 'Falha ao salvar',
'LBL_FAILED_PUBLISHED' => 'Falha ao publicar',
'LBL_HOMEPAGE_PREFIX' => 'Minha',
'LBL_LAYOUT_PREVIEW'=>'Pré-visualização do layout',
'LBL_LAYOUTS'=>'Layouts',
'LBL_LISTVIEW'=>'Visualização de lista',
'LBL_RECORDVIEW'=>'Visualização de registro',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Novo Pacote',
'LBL_NEW_PANEL'=>'Novo Painel',
'LBL_NEW_ROW'=>'Nova Linha',
'LBL_PACKAGE_DELETED'=>'Pacote excluído',
'LBL_PUBLISHING' => 'Publicando...',
'LBL_PUBLISHED' => 'Publicado!',
'LBL_SELECT_FILE'=> 'Selecionar Arquivo',
'LBL_SAVE_LAYOUT'=> 'Salvar layout',
'LBL_SELECT_A_SUBPANEL' => 'Selecionar um subpainel',
'LBL_SELECT_SUBPANEL' => 'Selecionar subpainel',
'LBL_SUBPANELS' => 'Subpainéis',
'LBL_SUBPANEL' => 'Subpainel',
'LBL_SUBPANEL_TITLE' => 'Título:',
'LBL_SEARCH_FORMS' => 'Pesquisar Formulários',
'LBL_STAGING_AREA' => 'Área de armazenamento (arraste e solte os itens aqui)',
'LBL_SUGAR_FIELDS_STAGE' => 'Campos do Sugar (clique nos itens para adicionar à área de armazenamento)',
'LBL_SUGAR_BIN_STAGE' => 'Lixeira do Sugar (clique nos itens para adicionar à área de armazenamento)',
'LBL_TOOLBOX' => 'Caixa de ferramentas',
'LBL_VIEW_SUGAR_FIELDS' => 'Visualizar campos do Sugar',
'LBL_VIEW_SUGAR_BIN' => 'Visualizar a lixeira do Sugar',
'LBL_QUICKCREATE' => 'Criação Rápida',
'LBL_EDIT_DROPDOWNS' => 'Editar uma lista suspensa global',
'LBL_ADD_DROPDOWN' => 'Adicionar uma nova lista suspensa global',
'LBL_BLANK' => '-vazio-',
'LBL_TAB_ORDER' => 'Ordem de guia',
'LBL_TAB_PANELS' => 'Habilitar guias',
'LBL_TAB_PANELS_HELP' => 'Quando as guias estiverem habilitadas, use a caixa suspensa "tipo"<br />para cada seção para definir como será a exibição (guia ou painel)',
'LBL_TABDEF_TYPE' => 'Tipo de exibição',
'LBL_TABDEF_TYPE_HELP' => 'Selecione como esta seção será visualizada. Esta opção terá efeito se você tiver habilitado as guias nesta visualização.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Guia',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Painel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Selecione Painel para ter essa visualização do painel dentro da visualização do layout. Selecione Guia para ter este painel exibido dentro de uma guia separada dentro do layout. Quando a Guia é especificada para um painel, painéis subsequentes definidos para exibir como o painel serão exibidos dentro da guia. <br/>Uma nova Guia será iniciada para o painel seguinte para o qual a Guia foi selecionada. Se a Guia for selecionada para um painel abaixo do primeiro painel, o primeiro painel será necessariamente uma guia.',
'LBL_TABDEF_COLLAPSE' => 'Recolher',
'LBL_TABDEF_COLLAPSE_HELP' => 'Selecione para o painel recolhido como estado padrão.',
'LBL_DROPDOWN_TITLE_NAME' => 'Nome',
'LBL_DROPDOWN_LANGUAGE' => 'Idioma',
'LBL_DROPDOWN_ITEMS' => 'Listar Itens',
'LBL_DROPDOWN_ITEM_NAME' => 'Nome do Item',
'LBL_DROPDOWN_ITEM_LABEL' => 'Exibir Rótulo',
'LBL_SYNC_TO_DETAILVIEW' => 'Sincronizar para a visualização de detalhe',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Selecione esta opção para sincronizar este layout de Visualização de edição para o layout de Visualização de detalhe correspondente. Os campos e os seus posicionamentos na Visualização de edição<br>serão sincronizados e salvos para a Visualização de detalhe automaticamente ao clicar em Salvar ou Salvar e implementar na Visualização de edição. <br>Alterações no layout não poderão ser feitas na Visualização de detalhe.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Esta Visualização de detalhe está sincronizada com a Visualização de edição correspondente.<br>Os campos e os posicionamentos nesta Visualização de detalhe refletem os campo e os posicionamentos na Visualização de edição.<br> Alterações na Visualização de detalhe não poderão ser salvas ou implementadas dentro desta página. Faça alterações ou dessincronize os layouts na Visualização de edição. ',
'LBL_COPY_FROM' => 'Copiar de',
'LBL_COPY_FROM_EDITVIEW' => 'Copiar da Visualização de edição',
'LBL_DROPDOWN_BLANK_WARNING' => 'Os valores obrigatórios são ambos para o Nome do item e para Rótulo de exibição. Para adicionar um item em branco, clique em Adicionar sem inserir qualquer valor para o Nome do Item e para o Rótulo de exibição.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Chave já existente na lista',
'LBL_DROPDOWN_LIST_EMPTY' => 'A lista deve conter pelo menos um item habilitado',
'LBL_NO_SAVE_ACTION' => 'Não foi possível localizar a ação de salvar para esta visualização.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2: establishLocation: documento mal formado',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Indica um campo de combinação. Um campo de combinação é um conjunto de campos individuais. Por exemplo, "Endereço" é um campo de combinação que contém "Rua" "Cidade", "CEP", "Estado" e "País".<br><br>Dê dois cliques em um campo de combinação para ver quais os campos que ele contém.',
'LBL_COMBO_FIELD_CONTAINS' => 'contém:',

'LBL_WIRELESSLAYOUTS'=>'Layouts móveis',
'LBL_WIRELESSEDITVIEW'=>'Visualização de edição móvel',
'LBL_WIRELESSDETAILVIEW'=>'Visualização de detalhe móvel',
'LBL_WIRELESSLISTVIEW'=>'Visualização de lista móvel',
'LBL_WIRELESSSEARCH'=>'Pesquisa móvel',

'LBL_BTN_ADD_DEPENDENCY'=>'Adicionar dependência',
'LBL_BTN_EDIT_FORMULA'=>'Editar fórmula',
'LBL_DEPENDENCY' => 'Dependência',
'LBL_DEPENDANT' => 'Dependente',
'LBL_CALCULATED' => 'Valor calculado',
'LBL_READ_ONLY' => 'Somente leitura',
'LBL_FORMULA_BUILDER' => 'Construtor de Fórmulas',
'LBL_FORMULA_INVALID' => 'Fórmula Inválida',
'LBL_FORMULA_TYPE' => 'A fórmula deve ser do tipo ',
'LBL_NO_FIELDS' => 'Nenhum campo encontrado',
'LBL_NO_FUNCS' => 'Nenhuma função encontrada',
'LBL_SEARCH_FUNCS' => 'Pesquisar funções...',
'LBL_SEARCH_FIELDS' => 'Pesquisar campos...',
'LBL_FORMULA' => 'Fórmula',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Dependente',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Arraste os itens da lista de opções disponíveis à esquerda para uma das listas da direita para tornar essa opção disponível quando a opção pai estiver selecionada. Se não houver itens sob uma opção pai, quando a opção pai for selecionada, a lista suspensa dependente não será exibida.',
'LBL_AVAILABLE_OPTIONS' => 'Opções disponíveis',
'LBL_PARENT_DROPDOWN' => 'Lista suspensa relacionada',
'LBL_VISIBILITY_EDITOR' => 'Editor de visibilidade',
'LBL_ROLLUP' => 'Valor acumulado',
'LBL_RELATED_FIELD' => 'Campo relacionado',
'LBL_CONFIG_PORTAL_URL'=>'URL para a imagem do logotipo personalizado. As dimensões recomendadas para o logotipo são 163 × 18 pixels.',
'LBL_PORTAL_ROLE_DESC' => 'Não exclua essa função. A função de portal de autoatendimento é uma função gerada pelo sistema criada durante o processo de ativação do Portal Sugar. Use os controles de acesso dentro desta função para ativar e/ou desativar módulos de Bugs, Ocorrências ou Base de Conhecimento no Portal Sugar. Não modifique quaisquer outros controles de acesso para esta função para evitar comportamento do sistema desconhecido e imprevisível. No caso de exclusão acidental desta função, crie-o novamente ao desabilitar e habilitar o Portal Sugar.',

//RELATIONSHIPS
'LBL_MODULE' => 'Módulo',
'LBL_LHS_MODULE'=>'Módulo Principal',
'LBL_CUSTOM_RELATIONSHIPS' => '* relacionamento criado no Studio',
'LBL_RELATIONSHIPS'=>'Relações',
'LBL_RELATIONSHIP_EDIT' => 'Editar Relacionamento',
'LBL_REL_NAME' => 'Nome',
'LBL_REL_LABEL' => 'Rótulo',
'LBL_REL_TYPE' => 'Tipo',
'LBL_RHS_MODULE'=>'Módulo Relacionado',
'LBL_NO_RELS' => 'Sem relações',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Condição Opcional' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Coluna',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Valor',
'LBL_SUBPANEL_FROM'=>'Subpainel de',
'LBL_RELATIONSHIP_ONLY'=>'Nenhum elemento visível será criado para este relacionamento, dado que há um relacionamento visível pré-existente entre estes dois módulos.',
'LBL_ONETOONE' => 'Um para Um',
'LBL_ONETOMANY' => 'Um para Muitos',
'LBL_MANYTOONE' => 'Muitos para Um',
'LBL_MANYTOMANY' => 'Muitos para Muitos',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Selecione uma função ou componente.',
'LBL_QUESTION_MODULE1' => 'Selecione um módulo.',
'LBL_QUESTION_EDIT' => 'Selecione um módulo para editar.',
'LBL_QUESTION_LAYOUT' => 'Selecione um layout para editar.',
'LBL_QUESTION_SUBPANEL' => 'Selecione um subpainel para editar.',
'LBL_QUESTION_SEARCH' => 'Selecione um layout de pesquisa para editar.',
'LBL_QUESTION_MODULE' => 'Selecione um componente de módulo para editar.',
'LBL_QUESTION_PACKAGE' => 'Selecione um pacote para editar, ou crie um novo pacote.',
'LBL_QUESTION_EDITOR' => 'Selecione uma ferramenta.',
'LBL_QUESTION_DROPDOWN' => 'Selecione uma lista suspensa para editar, ou crie uma nova lista suspensa.',
'LBL_QUESTION_DASHLET' => 'Selecione um layout de dashlet para editar.',
'LBL_QUESTION_POPUP' => 'Selecione o layout de um pop-up para editar.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Relacionar a',
'LBL_NAME'=>'Nome do Pacote:',
'LBL_LABELS'=>'Rótulos',
'LBL_MASS_UPDATE'=>'Atualização em massa',
'LBL_AUDITED'=>'Auditoria',
'LBL_CUSTOM_MODULE'=>'Módulo',
'LBL_DEFAULT_VALUE'=>'Valor padrão',
'LBL_REQUIRED'=>'Obrigatório',
'LBL_DATA_TYPE'=>'Tipo',
'LBL_HCUSTOM'=>'PERSONALIZADO',
'LBL_HDEFAULT'=>'PADRÃO',
'LBL_LANGUAGE'=>'Idioma:',
'LBL_CUSTOM_FIELDS' => '* campo criado no Studio',

//SECTION
'LBL_SECTION_EDLABELS' => 'Editar etiquetas',
'LBL_SECTION_PACKAGES' => 'Pacotes',
'LBL_SECTION_PACKAGE' => 'Pacote',
'LBL_SECTION_MODULES' => 'Módulos',
'LBL_SECTION_PORTAL' => 'Portal',
'LBL_SECTION_DROPDOWNS' => 'Listas suspensas',
'LBL_SECTION_PROPERTIES' => 'Propriedades',
'LBL_SECTION_DROPDOWNED' => 'Editar a lista suspensa',
'LBL_SECTION_HELP' => 'Ajuda',
'LBL_SECTION_ACTION' => 'Ação',
'LBL_SECTION_MAIN' => 'Principal',
'LBL_SECTION_EDPANELLABEL' => 'Editar Rótulo de painel',
'LBL_SECTION_FIELDEDITOR' => 'Editar Campo',
'LBL_SECTION_DEPLOY' => 'Implementar',
'LBL_SECTION_MODULE' => 'Módulo',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Editar visibilidade',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Padrão',
'LBL_HIDDEN'=>'Oculto',
'LBL_AVAILABLE'=>'Disponível',
'LBL_LISTVIEW_DESCRIPTION'=>'Existem três colunas disponíveis abaixo. A coluna <b>Padrão</b> contém campos que são exibidos em uma visualização de lista por padrão. A coluna <b>Adicional</b> contém campos que um usuário pode escolher usar para criar uma visualização personalizada. A coluna <b>Disponível</b> exibe campos disponíveis para você como administrador para adicionar às colunas Padrão ou Adicional para uso dos usuários.',
'LBL_LISTVIEW_EDIT'=>'Editor da visualização de lista',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Prever',
'LBL_MB_RESTORE'=>'Restaurar',
'LBL_MB_DELETE'=>'Excluir',
'LBL_MB_COMPARE'=>'Comparar',
'LBL_MB_DEFAULT_LAYOUT'=>'Layout padrão',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Adicionar',
'LBL_BTN_SAVE'=>'Salvar',
'LBL_BTN_SAVE_CHANGES'=>'Salvar alterações',
'LBL_BTN_DONT_SAVE'=>'Descartar alterações',
'LBL_BTN_CANCEL'=>'Cancelar',
'LBL_BTN_CLOSE'=>'Encerrar',
'LBL_BTN_SAVEPUBLISH'=>'Salvar e implementar',
'LBL_BTN_NEXT'=>'Próximo',
'LBL_BTN_BACK'=>'Anterior',
'LBL_BTN_CLONE'=>'Clonar',
'LBL_BTN_COPY' => 'Copiar',
'LBL_BTN_COPY_FROM' => 'Copiar de...',
'LBL_BTN_ADDCOLS'=>'Adicionar colunas',
'LBL_BTN_ADDROWS'=>'Adicionar linhas',
'LBL_BTN_ADDFIELD'=>'Adicionar campo',
'LBL_BTN_ADDDROPDOWN'=>'Adicionar lista suspensa',
'LBL_BTN_SORT_ASCENDING'=>'Ordenação crescente',
'LBL_BTN_SORT_DESCENDING'=>'Ordenação decrescente',
'LBL_BTN_EDLABELS'=>'Editar etiquetas',
'LBL_BTN_UNDO'=>'Desfazer',
'LBL_BTN_REDO'=>'Refazer',
'LBL_BTN_ADDCUSTOMFIELD'=>'Adicionar campo personalizado',
'LBL_BTN_EXPORT'=>'Exportar personalizações',
'LBL_BTN_DUPLICATE'=>'Duplicar',
'LBL_BTN_PUBLISH'=>'Publicar',
'LBL_BTN_DEPLOY'=>'Implementar',
'LBL_BTN_EXP'=>'Exportar',
'LBL_BTN_DELETE'=>'Excluir',
'LBL_BTN_VIEW_LAYOUTS'=>'Visualizar layouts',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Visualizar layouts móveis',
'LBL_BTN_VIEW_FIELDS'=>'Visualizar campos',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Visualizar relações',
'LBL_BTN_ADD_RELATIONSHIP'=>'Adicionar relacionamento',
'LBL_BTN_RENAME_MODULE' => 'Alterar nome do módulo',
'LBL_BTN_INSERT'=>'Inserir',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Erro: Campo já existe',
'ERROR_INVALID_KEY_VALUE'=> "Erro: Valor da chave inválido: [']",
'ERROR_NO_HISTORY' => 'Nenhuns arquivo de histórico encontrado',
'ERROR_MINIMUM_FIELDS' => 'O layout deve conter pelo menos um campo',
'ERROR_GENERIC_TITLE' => 'Ocorreu um erro',
'ERROR_REQUIRED_FIELDS' => 'Tem certeza que deseja continuar? Os seguintes campos obrigatórios estão ausentes no layout:  ',
'ERROR_ARE_YOU_SURE' => 'Tem certeza de que deseja continuar?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'O(s) seguinte(s) campo(s) têm valores calculados que não serão recalculados em tempo real na visualização de edição móvel do SugarCRM:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'O(s) seguinte(s) campo(s) têm valores calculados que não serão recalculados em tempo real na visualização de edição do portal do SugarCRM:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'O(s) seguinte(s) módulo(s) está(ão) desativado(s):',
    'LBL_PORTAL_ENABLE_MODULES' => 'Se quiser ativá-los no Portal, ative-os <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">aqui</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Configurar Portal',
    'LBL_PORTAL_THEME' => 'Tema do Portal',
    'LBL_PORTAL_ENABLE' => 'Ativar',
    'LBL_PORTAL_SITE_URL' => 'Seu site do Portal está disponível em:',
    'LBL_PORTAL_APP_NAME' => 'Nome do Portal',
    'LBL_PORTAL_LOGO_URL' => 'URL do logo',
    'LBL_PORTAL_LIST_NUMBER' => 'Número de registros para exibir na lista',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Número de campos para exibir na Visualização de detalhes',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Número de resultados para aparecer na pesquisa global',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Padrão atribuído para o registro de novos portais',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Layouts do Portal',
'LBL_SYNCP_WELCOME'=>'Insira a URL da instância do portal que deseja atualizar.',
'LBL_SP_UPLOADSTYLE'=>'Selecione uma folha de estilo para carregar do seu computador.<br>A folha de estilo será implementada no Portal Sugar da próxima vez que realizar uma sincronização.',
'LBL_SP_UPLOADED'=> 'Carregado',
'ERROR_SP_UPLOADED'=>'Certifique-se de que está carregando uma folha de estilo css.',
'LBL_SP_PREVIEW'=>'Esta é uma pré-visualização da aparência do Portal Sugar utilizando a folha de estilo.',
'LBL_PORTALSITE'=>'URL do Portal Sugar: ',
'LBL_PORTAL_GO'=>'Ir',
'LBL_UP_STYLE_SHEET'=>'Carregar folha de estilo',
'LBL_QUESTION_SUGAR_PORTAL' => 'Selecione um layout do Portal Sugar para editar.',
'LBL_QUESTION_PORTAL' => 'Selecione um layout do portal para editar.',
'LBL_SUGAR_PORTAL'=>'Editor do Portal Sugar',
'LBL_USER_SELECT' => '-- Selecionar --',

//PORTAL PREVIEW
'LBL_CASES'=>'Ocorrências',
'LBL_NEWSLETTERS'=>'Boletins informativos',
'LBL_BUG_TRACKER'=>'Rastreador de bugs',
'LBL_MY_ACCOUNT'=>'Minha Conta',
'LBL_LOGOUT'=>'Sair',
'LBL_CREATE_NEW'=>'Criar Novo',
'LBL_LOW'=>'Baixa',
'LBL_MEDIUM'=>'Média',
'LBL_HIGH'=>'Alta',
'LBL_NUMBER'=>'Número:',
'LBL_PRIORITY'=>'Prioridade:',
'LBL_SUBJECT'=>'Assunto',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Nome do Pacote:',
'LBL_MODULE_NAME'=>'Nome do Módulo:',
'LBL_MODULE_NAME_SINGULAR' => 'Nome do módulo no singular:',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Descrição:',
'LBL_KEY'=>'Chave:',
'LBL_ADD_README'=>'Leia-me',
'LBL_MODULES'=>'Módulos:',
'LBL_LAST_MODIFIED'=>'Última Modificação:',
'LBL_NEW_MODULE'=>'Novo Módulo',
'LBL_LABEL'=>'Rótulo plural',
'LBL_LABEL_TITLE'=>'Rótulo',
'LBL_SINGULAR_LABEL' => 'Rótulo singular',
'LBL_WIDTH'=>'Largura',
'LBL_PACKAGE'=>'Pacote:',
'LBL_TYPE'=>'Tipo:',
'LBL_TEAM_SECURITY'=>'Segurança de Equipe',
'LBL_ASSIGNABLE'=>'Atribuível',
'LBL_PERSON'=>'Pessoa',
'LBL_COMPANY'=>'Empresa',
'LBL_ISSUE'=>'Assunto',
'LBL_SALE'=>'Venda',
'LBL_FILE'=>'Arquivo',
'LBL_NAV_TAB'=>'Guia de navegação',
'LBL_CREATE'=>'Criar',
'LBL_LIST'=>'Lista',
'LBL_VIEW'=>'Visualização',
'LBL_LIST_VIEW'=>'Visualização de lista',
'LBL_HISTORY'=>'Ver histórico',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Restaurar o layout padrão',
'LBL_ACTIVITIES'=>'Cadeia de atividades',
'LBL_SEARCH'=>'Pesquisar',
'LBL_NEW'=>'Novo',
'LBL_TYPE_BASIC'=>'básico',
'LBL_TYPE_COMPANY'=>'empresa',
'LBL_TYPE_PERSON'=>'pessoa',
'LBL_TYPE_ISSUE'=>'assunto',
'LBL_TYPE_SALE'=>'venda',
'LBL_TYPE_FILE'=>'arquivo',
'LBL_RSUB'=>'Este é o subpainel que será exibido no seu módulo',
'LBL_MSUB'=>'Este é o subpainel que o seu módulo fornece ao módulo relacionado para exibição',
'LBL_MB_IMPORTABLE'=>'Permitir importações',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'visível',
'LBL_VE_HIDDEN'=>'oculto',
'LBL_PACKAGE_WAS_DELETED'=>'[package] foi excluído',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Exportar personalizações',
'LBL_EC_NAME'=>'Nome do Pacote:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Descrição:',
'LBL_EC_KEY'=>'Chave:',
'LBL_EC_CHECKERROR'=>'Selecione um módulo.',
'LBL_EC_CUSTOMFIELD'=>'campo(s) personalizado(s)',
'LBL_EC_CUSTOMLAYOUT'=>'layout(s) personalizado(s)',
'LBL_EC_CUSTOMDROPDOWN' => 'lista(s) suspensa(s) personalizável(is)',
'LBL_EC_NOCUSTOM'=>'Nenhum módulo foi personalizado.',
'LBL_EC_EXPORTBTN'=>'Exportar',
'LBL_MODULE_DEPLOYED' => 'Módulo foi implementado.',
'LBL_UNDEFINED' => 'indefinido',
'LBL_EC_CUSTOMLABEL'=>'rótulo(s) personalizado(s)',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Falha ao recuperar os dados',
'LBL_AJAX_TIME_DEPENDENT' => 'Uma ação dependente do tempo está em progresso. Aguarde e tente novamente daqui a alguns segundos.',
'LBL_AJAX_LOADING' => 'Carregando...',
'LBL_AJAX_DELETING' => 'Excluindo...',
'LBL_AJAX_BUILDPROGRESS' => 'Construção em andamento...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Implementação em andamento...',
'LBL_AJAX_FIELD_EXISTS' =>'O nome do campo que inseriu já existe. Insira um novo nome de campo.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Tem certeza de que deseja remover este pacote? Isso excluirá permanentemente todos os arquivos associados a este pacote.',
'LBL_JS_REMOVE_MODULE' => 'Tem certeza de que deseja remover este módulo? Isso excluirá permanentemente todos os arquivos associados a este módulo.',
'LBL_JS_DEPLOY_PACKAGE' => 'Todas as personalizações que você fez no Studio serão apagadas quando este módulo for reimplementado. Deseja continuar?',

'LBL_DEPLOY_IN_PROGRESS' => 'Implementando pacote',
'LBL_JS_VALIDATE_NAME'=>'Nome - Deve começar com uma letra e ter somente letras, números e sublinhado. Não deve ter espaço ou outros caracteres especiais.',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'A chave do pacote já existe',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'O nome do pacote já existe',
'LBL_JS_PACKAGE_NAME'=>'Nome do pacote - Deve começar com uma letra e ter somente letras, números e sublinhado. Não deve ter espaço ou outros caracteres especiais.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Chave - Deve ser um código alfanumérico e começar com uma letra.',
'LBL_JS_VALIDATE_KEY'=>'Chave - Deve ser alfanumérica, começar com uma letra e não conter espaços.',
'LBL_JS_VALIDATE_LABEL'=>'Insira um rótulo que será utilizado como Nome de exibição para este módulo',
'LBL_JS_VALIDATE_TYPE'=>'Selecione o tipo de módulo que deseja construir da lista acima',
'LBL_JS_VALIDATE_REL_NAME'=>'Nome - Deve ser alfanumérico e sem espaços',
'LBL_JS_VALIDATE_REL_LABEL'=>'Rótulo - adicione um rótulo que será exibido acima do subpainel',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Tem certeza de que deseja apagar este item da lista suspensa? Isso pode afetar a funcionalidade do seu aplicativo.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Tem certeza de que deseja apagar este item da lista suspensa? Excluir a fase Fechadas ganhas e Fechadas perdidas fará com que o módulo de Previsão não funcione corretamente',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Tem certeza de que deseja excluir o status de Nova venda? Excluir este status causará um mau funcionamento no fluxo de trabalho de item da linha de receita do módulo Oportunidades.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Tem certeza de que deseja excluir o status de Progresso de venda? Excluir este status causará um mau funcionamento no fluxo de trabalho do item da linha de receita do módulo Oportunidades.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Tem certeza de que deseja excluir a fase de vendas fechadas ganhas? Excluir esta fase fará com que o módulo Previsão não funcione corretamente',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Tem certeza de que deseja excluir a fase de vendas Fechadas perdidas? Excluir esta fase fará com que o módulo Previsão não funcione corretamente',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Tem certeza de que deseja excluir este relacionamento?<br>Nota: Esta operação pode levar alguns minutos para ser concluída.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Isso tornará este relacionamento permanente. Tem certeza de que pretende implementar este relacionamento?',
'LBL_CONFIRM_DONT_SAVE' => 'Foram feitas alterações desde o último salvamento, gostaria de salvar?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Salvar alterações?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Alguns dados podem estar truncados, e isso não pode ser desfeito, tem certeza de que deseja continuar?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Selecione o tipo de banco de dados apropriado com base no tipo de dados que será inserido no campo.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Configure o campo para que o texto completo possa ser pesquisado.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'O reforço é o processo que aumenta a relevância dos campos de um registro.<br />Os campos com um maior nível de reforço têm maior peso quando a pesquisa é executada. Quando uma pesquisa é executada, os registros correspondentes que contenham campos com um peso superior aparecerão mais acima nos resultados da pesquisa.<br />O valor padrão é 1,0, que indica um reforço neutro. Para aplicar um reforço positivo, pode-se inserir qualquer valor flutuante superior a 1. Para um reforço negativo, utilize valores inferiores a 1. Por exemplo, um valor de 1,35 reforça um campo positivamente em 135%. Se utilizar um valor de 0,60, o reforço aplicado será negativo.<br />Tenha em conta que, em versões anteriores, era obrigatório executar uma reindexação de pesquisa de texto completo. Isso não é necessário atualmente.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Sim</b>: O campo será incluído em uma operação de importação.<br><b>Não</b>: O campo não será incluído em uma importação.<br><b>Obrigatório</b>: Um valor para o campo deve ser fornecido em qualquer importação.',
'LBL_POPHELP_PII'=>'Este campo será automaticamente marcado para realizar uma auditoria e estará disponível na visualização de Informações pessoais.<br>Os campos de informações pessoais podem ser permanentemente apagados quando o registo estiver relacionado com uma solicitação de eliminação por privacidade de dados.<br>A eliminação será realizada através do módulo de Privacidade de dados e pode ser executada pelos administradores ou usuários com função de Administrador de privacidade de dados.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Insira um número para largura, medida em pixels.<br>A imagem carregada será dimensionada para esta largura.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Insira um número para altura, medida em pixels.<br>A imagem carregada será dimensionada para esta altura.',
'LBL_POPHELP_DUPLICATE_MERGE'=>'<b>Enabled</b>: The field will appear in the Merge Duplicates feature, but will not be available to use for the filter conditions in the Find Duplicates feature.<br><b>Disabled</b>: The field will not appear in the Merge Duplicates feature, and will not be available to use for the filter conditions in the Find Duplicates feature.'
. '<br><b>In Filter</b>: The field will appear in the Merge Duplicates feature, and will also be available in the Find Duplicates feature.<br><b>Filter Only</b>: The field will not appear in the Merge Duplicates feature, but will be available in the Find Duplicates feature.<br><b>Default Selected Filter</b>: The field will be used for a filter condition by default in the Find Duplicates page, and will also appear in the Merge Duplicates feature.'
,
'LBL_POPHELP_CALCULATED'=>"Create a formula to determine the value in this field.<br>"
   . "Workflow definitions containing an action that are set to update this field will no longer execute the action.<br>"
   . "Fields using formulas will not be calculated in real-time in "
   . "the Sugar Self-Service Portal or "
   . "Mobile EditView layouts.",

'LBL_POPHELP_DEPENDENT'=>"Create a formula to determine whether this field is visible in layouts.<br/>"
        . "Dependent fields will follow the dependency formula in the browser-based mobile view, <br/>"
        . "but will not follow the formula in the native applications, such as Sugar Mobile for iPhone. <br/>"
        . "They will not follow the formula in the Sugar Self-Service Portal.",
'LBL_POPHELP_GLOBAL_SEARCH'=>'Selecione para usar este campo ao pesquisar registros usando a pesquisa global neste módulo.',
//Revert Module labels
'LBL_RESET' => 'Redefinir',
'LBL_RESET_MODULE' => 'Reiniciar Módulo',
'LBL_REMOVE_CUSTOM' => 'Remover personalizações',
'LBL_CLEAR_RELATIONSHIPS' => 'Limpar relações',
'LBL_RESET_LABELS' => 'Reiniciar rótulos',
'LBL_RESET_LAYOUTS' => 'Restaurar layouts',
'LBL_REMOVE_FIELDS' => 'Remover os campos personalizados',
'LBL_CLEAR_EXTENSIONS' => 'Limpar extensões',

'LBL_HISTORY_TIMESTAMP' => 'Carimbo de data/hora',
'LBL_HISTORY_TITLE' => 'histórico',

'fieldTypes' => array(
                'varchar'=>'Campo de Texto',
                'int'=>'Número Inteiro',
                'float'=>'Flutuante',
                'bool'=>'Caixa de seleção',
                'enum'=>'Menu suspenso',
                'multienum' => 'Multisseleção',
                'date'=>'Data',
                'phone' => 'Telefone',
                'currency' => 'Moeda',
                'html' => 'HTML',
                'radioenum' => 'Rádio',
                'relate' => 'Relacionar',
                'address' => 'Endereço',
                'text' => 'Área de Texto',
                'url' => 'URL',
                'iframe' => 'IFrame',
                'image' => 'Imagem',
                'encrypt'=>'Criptografar',
                'datetimecombo' =>'Data/Hora',
                'decimal'=>'Decimal',
),
'labelTypes' => array(
    "" => "Rótulos usados frequentemente",
    "all" => "Todos os rótulos",
),

'parent' => 'Relação flexível',

'LBL_ILLEGAL_FIELD_VALUE' =>"Chaves suspensas não podem conter cotações.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Você está selecionando este item para remoção da lista suspensa. Quaisquer campos de lista suspensa que utilizam esta lista com este item como um valor não exibirão mais o valor, e o valor não poderá mais ser selecionado dos campos suspensos. Tem certeza de que deseja continuar?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Todos os módulos',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (ID {1} relacionado)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Copiar de layout',
);
