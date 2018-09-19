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
    'LBL_LOADING' => 'A carregar' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Ocultar Opções' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Eliminar' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Desenvolvido por SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Função',
'help'=>array(
    'package'=>array(
            'create'=>'Forneça um <b>Nome</b> para o pacote. O nome tem de iniciar com uma letra e só pode conter letras, números e caracteres de sublinhado. Os espaços ou outros caracteres especiais não podem ser utilizados. (Exemplo: Gestão_RH)<br/><br/> Pode fornecer as informações de <b>Autor</b> e <b>Descrição</b> para o pacote. <br/><br/>Clique em <b>Gravar</b> para criar o pacote.',
            'modify'=>'As propriedades e ações possíveis para o <b>Pacote</b> aparecem aqui.<br><br>Poderá modificar o <b>Nome</b>, o <b> Autor</b> e a <b>Descrição</b> do pacote, assim como ver e personalizar todos os módulos contidos no pacote.<br><br>Clique em <b>Novo Módulo</b> para criar um módulo para o pacote.<br><br>Se o pacote contiver pelo menos um módulo, poderá <b>Publicar</b> e <b>Implementar</b> o pacote, assim como <b>Exportar</b> as personalizações efetuadas no pacote.',
            'name'=>'Este é o <b>Nome</b> do pacote atual. <br/><br/>O nome a inserir terá que ser alfanumérico, começar com uma letra e não pode conter espaços. (Exemplo: Recursos_Humanos)',
            'author'=>'Este é o <b>Autor</b> que é mostrado durante a instalação, como o nome da entidade que criou o pacote.<br><br>O Autor poderá ser um individuo ou uma empresa.',
            'description'=>'Esta é a <b>Descrição</b> do pacote que é mostrada durante a instalação.',
            'publishbtn'=>'Clique em <b>Publicar</b> para gravar todos os dados inseridos e criar um ficheiro .zip que é a versão de instalação do pacote.<br><br>Utilize o <b>Carregador de Módulo</b> para carregar o ficheiro .zip e instalar o pacote.',
            'deploybtn'=>'Carregar para <b>Implementar</b> para gravar todos os dados inseridos e para instalar o pacote, incluindo todos os módulos, na instância atual.',
            'duplicatebtn'=>'Clicar <b>Duplicar</b> para copiar os conteúdos do pacote para outro pacote e para mostrar o novo pacote. <br/><br/>Para o novo pacote, um novo nome será gerado automaticamente, com a adição de um número no fim do nome do pacote usado para criar o novo. Poderá renomear o novo pacote inserindo o novo <b>Nome</b> e clicando <b>Guardar</b>.',
            'exportbtn'=>'Clique em <b>Exportar</b> para criar um ficheiro .zip contendo as personalizações efetuadas no pacote.<br><br> O ficheiro gerado não será uma versão de instalação do pacote.<br><br>Utilize o <b>Carregador de Módulo</b> para importar o ficheiro .zip para ter o pacote, incluindo as suas personalizações, a aparecer no Module Builder.',
            'deletebtn'=>'Clicar <b>Eliminar</b> para eliminar este pacote e todos os ficheiros relacionados com este pacote.',
            'savebtn'=>'Clicar <b>Guardar</b> para guardar todos os dados inseridos relacionados com o pacote.',
            'existing_module'=>'Clicar no ícone do <b>Módulo</b> para editar as propriedades e personalizações dos campos, relações e layouts associados com o módulo.',
            'new_module'=>'Clicar <b>Novo Módulo</b> para criar um novo módulo para este pacote.',
            'key'=>'Esta <b>Chave</b> de 5 letras, alfanumérica será utilizada como prefixo em todos os diretórios, nomes de classes e tabelas de base de dados para todos os módulos do pacote atual.<br><br>A chave é utilizada para garantir que o nome das tabelas seja único.',
            'readme'=>'Clique para adicionar texto <b>Leia-me</b> para este pacote.<br><br>O Leia-me estará disponível na altura da instalação.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Fornece um <b>Nome</b> para o módulo. O <b>Rótulo</b> que indicou irá aparecer no separador de navegação. <br/><br/>Escolha apresentar o separador de navegação para o módulo clicando na caixa de seleção <b>Separador de Navegação</b>.<br/><br/>Verificar a caixa de seleção <b>Definições de Segurança da Equipa</b> para ter um campo de seleção de Equipa dentro dos registos do módulo. <br/><br/>Depois escolher o tipo de módulo que gostaria de criar. <br/><br/>Escolher um tipo de modelo. Cada modelo contém um conjunto de campos específico, assim como layouts pré-definidos, para usar como base para o seu módulo. <br/><br/>Clicar em <b>Guardar</b> para criar o módulo.',
        'modify'=>'Poderá alterar as propriedades do módulo ou personalizar os <b>Campos</b>, <b>Relações</b> e <b>Layouts</b> relacionados com o módulo.',
        'importable'=>'Selecionar a caixa de seleção <b>Importável</b> irá permitir importar este módulo.<br><br>Uma ligação para o Assistente de Importação irá aparecer no painel de Atalhos dentro do módulo.  O Assistente de Importação facilita a importação de dados de fontes externas para o módulo.',
        'team_security'=>'Selecionar a caixa de seleção <b>Definições de Segurança da Equipa</b> irá disponibilizar as definições de segurança da equipa para este módulo. <br/><br/>Se as definições de segurança da equipa estiverem ativadas, o campo de seleção Equipa irá aparecer nos registos do módulo ',
        'reportable'=>'Selecionar esta caixa irá permitir que este módulo tenha relatórios sobre o mesmo.',
        'assignable'=>'Selecionar esta caixa irá permitir a um registo deste módulo ser atribuído a um utilizador selecionado.',
        'has_tab'=>'Selecionar <b>Separador de Navegação</b> irá fornecer um separador de navegação para o módulo.',
        'acl'=>'Selecionar esta caixa irá permitir Controlo de Acessos neste módulo, incluindo Segurança ao Nível de Campos.',
        'studio'=>'Selecionar esta caixa irá permitir aos administradores personalizar este módulo dentro do Studio.',
        'audit'=>'Selecionar esta caixa irá permitir Auditorias para este módulo. Alterações a certos campos serão registados para que os administradores possam rever a história de alterações.',
        'viewfieldsbtn'=>'Clique em <b>Ver Campos</b> para ver os campos associados com o módulo e criar ou editar campos personalizados.',
        'viewrelsbtn'=>'Clique em <b>Ver Relações</b> para ver as relações associadas com este módulo e para criar novas relações.',
        'viewlayoutsbtn'=>'Clique em <b>Ver Layouts</b> para ver os layouts para o módulo e para personalizar a disposição dos campos dentro dos layouts.',
        'viewmobilelayoutsbtn' => 'Clique em <b>Ver Mobile Layouts</b> para ver os mobile layouts para o módulo e personalizar a disposição do campo dentro dos layouts.',
        'duplicatebtn'=>'Clique em <b>Duplicar</b> para copiar as propriedades do módulo para um novo módulo e mostrar o novo módulo. <br/><br/>Para este novo módulo, um novo nome será gerado automaticamente com a adição de um número no fim do nome do módulo usado para criar o novo.',
        'deletebtn'=>'Clique em <b>Eliminar</b> para eliminar este módulo.',
        'name'=>'Este é o <b>Nome</b> do módulo actual.<br/><br/>O nome terá que ser alfanumérico e deverá começar com uma letra e não conter espaços. (Exemplo: Gestão_RH)',
        'label'=>'Este é o <b>Rótulo</b> que irá aparecer no separador de navegação do módulo.',
        'savebtn'=>'Clique em <b>Guardar</b> para guardar todos os dados inseridos relacionados com o módulo.',
        'type_basic'=>'O tipo de modelo <b>Básico</b> fornece campos básicos, como o Nome, Atribuído a, Equipa, Data de Criação e Descrição.',
        'type_company'=>'O tipo de modelo <b>Empresa</b> fornece campos específicos de organizações, como Nome da Empresa, Indústria e Morada de Faturação.<br/><br/>Utilize este modelo para criar módulos que são similares ao módulo padrão Contas.',
        'type_issue'=>'O tipo de modelo <b>Questão</b> fornece campos específicos de Ocorrências e Erros, como por exemplo Número, Estado, Prioridade e Descrição.<br/><br/>Usar este modelo para criar módulos que são similares aos módulos padrão Ocorrências e Bug Tracker.',
        'type_person'=>'O tipo de modelo <b>Pessoa</b> fornece campos específicos de indivíduos, como a Saudação, o Título, o Nome, a Morada e o Número de Telefone.<br/><br/>Utilize este modelo para criar módulos que são similares aos módulos padrão Contactos e Clientes Potenciais.',
        'type_sale'=>'O tipo de modelo <b>Venda</b> fornece campos específicos de oportunidade, como a Origem do Cliente Potencial, Fase, Montante e Probabilidade.<br/><br/>Utilize este modelo para criar módulos que são similares ao módulo padrão Oportunidades.',
        'type_file'=>'O tipo de modelo  <b>Ficheiro</b> fornece campos específicos de Documento, como o Nome do Ficheiro, tipo de Documento e Data de Publicação.<br><br>Usar este modelo para criar módulos que são similares ao módulo padrão Documentos.',

    ),
    'dropdowns'=>array(
        'default' => 'Todas as <b>Listas Dropdown</b> para a aplicação estão listadas aqui.<br><br>As listas dropdown podem ser utilizadas em campos de listas dropdown de qualquer módulo.<br><br>Para efetuar alterações numa lista dropdown já existente, clique no nome da lista dropdown.<br><br>Clique em <b>Adicionar Lista Dropdown</b> para criar uma nova lista dropdown.',
        'editdropdown'=>'As listas dropdown podem ser utilizadas em campos de listas dropdown padrão ou personalizadas em qualquer módulo.<br><br>Forneça um <b>Nome</b> para a lista dropdown.<br><br>Se algum pacote de idioma estiver instalado na aplicação, poderá selecionar o <b>Idioma</b> para utilizar os itens da lista.<br><br>No campo <b>Nome do Item</b>, forneça um nome para a opção na lista dropdown. Este nome não irá aparecer na lista dropdown que é visível aos utilizadores.<br><br>No campo <b>Rótulo de Apresentação</b>, forneça um rótulo que será visível para os utilizadores.<br><br>Depois de fornecer o nome do item e o rótulo de apresentação, clique em <b>Adicionar</b> para adicionar o item à lista pendente.<br><br>Para reordenar os itens na lista, arraste e largue os itens na posição desejada.<br><br>Para editar um rótulo de apresentação de um item, clique no <b>ícone de Edição</b> e insira um novo rótulo. Para apagar um item da lista pendente, clique no  <b>ícone de Eliminação</b>.<br><br>Para voltar atrás na alteração efetuada num rótulo de apresentação, clique em <b>Desfazer</b>. Para voltar a fazer a alteração que foi desfeita, clique em <b>Refazer</b>.<br><br>Clique em <b>Gravar</b> para gravar a lista pendente.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Clique em <b>Guardar e Implementar</b> para guardar as alterações efetuadas e torná-las ativas dentro do módulo.',
        'historyBtn'=> 'Clique em <b>Ver Histórico</b> para ver e restaurar um layout gravado anteriormente a partir do histórico.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar layout predefinido</b> para restaurar uma vista para o seu layout original.',
        'Hidden' 	=> 'Campos <b>Ocultos</b> que não aparecem no subpainel.',
        'Default'	=> 'Campos <b>Por Defeito</b> aparecem no subpainel.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Clique em <b>Guardar e Implementar</b> para guardar as alterações efetuadas e torná-las ativas dentro do módulo.',
        'historyBtn'=> 'Clique em <b>Ver Histórico</b> para ver e restaurar um layout gravado previamente a partir do histórico.<br><br><b>Restaurar</b> em <b>Ver Histórico</b> restaura a posição do campo nos layouts gravados anteriormente. Para alterar os rótulos dos campos, clique no ícone Editar junto de cada campo.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar layout padrão</b> para restaurar o layout original de uma vista.<br><br><b>Restaurar layout padrão</b> restaura apenas a colocação de campos no layout original. Para alterar rótulos de campo, clique no ícone Editar junto de cada campo.',
        'Hidden' 	=> 'Campos <b>Ocultos</b> não disponíveis de momento aos utilizadores para visualização em Listagens.',
        'Available' => 'Campos <b>Disponíveis</b> não são mostrados por defeito, mas poderão ser adicionados às Listagens pelos utilizadores.',
        'Default'	=> 'Campos <b>Por Defeito</b> aparecem nas Listagens que não personalizáveis pelos utilizadores.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Clique em <b>Guardar e Implementar</b> para guardar as alterações efetuadas e torná-las ativas dentro do módulo.',
        'historyBtn'=> 'Clique em <b>Ver Histórico</b> para ver e restaurar um layout gravado previamente a partir do histórico.<br><br><b>Restaurar</b> em <b>Ver Histórico</b> restaura a posição do campo nos layouts gravados anteriormente. Para alterar os rótulos dos campos, clique no ícone Editar junto de cada campo.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar layout padrão</b> para restaurar o layout original de uma vista.<br><br><b>Restaurar layout padrão</b> restaura apenas a colocação de campos no layout original. Para alterar rótulos de campo, clique no ícone Editar junto de cada campo.',
        'Hidden' 	=> 'Campos <b>Ocultos</b> não disponíveis de momento aos utilizadores para visualização em Listagens.',
        'Default'	=> 'Campos <b>Por Defeito</b> aparecem nas Listagens que não personalizáveis pelos utilizadores.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Clicando <b>Guardar e Implementar</b> irá guardar todas as alterações e torná-las ativas',
        'Hidden' 	=> 'Campos <b>Ocultos</b> não aparecem na Pesquisa.',
        'historyBtn'=> 'Clique em <b>Ver Histórico</b> para ver e restaurar um layout gravado previamente a partir do histórico.<br><br><b>Restaurar</b> em <b>Ver Histórico</b> restaura a posição do campo nos layouts gravados anteriormente. Para alterar os rótulos dos campos, clique no ícone Editar junto de cada campo.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar layout padrão</b> para restaurar o layout original de uma vista.<br><br><b>Restaurar layout padrão</b> restaura apenas a colocação de campos no layout original. Para alterar rótulos de campo, clique no ícone Editar junto de cada campo.',
        'Default'	=> 'Campos <b>Por Defeito</b> aparecem na Pesquisa.'
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
        'saveBtn'	=> 'Clique em em <b>Gravar</b> para preservar as alterações efetuadas no layout desde a ultima vez que foi efetuada uma gravação.<br><br>As alterações não serão exibidas no módulo enquanto não forem Implementadas as alterações gravadas.',
        'historyBtn'=> 'Clique em <b>Ver Histórico</b> para ver e restaurar um layout gravado previamente a partir do histórico.<br><br><b>Restaurar</b> em <b>Ver Histórico</b> restaura a posição do campo nos layouts gravados anteriormente. Para alterar os rótulos dos campos, clique no ícone Editar junto de cada campo.',
        'historyRestoreDefaultLayout'=> 'Clique em <b>Restaurar layout padrão</b> para restaurar o layout original de uma vista.<br><br><b>Restaurar layout padrão</b> restaura apenas a colocação de campos no layout original. Para alterar rótulos de campo, clique no ícone Editar junto de cada campo.',
        'publishBtn'=> 'Clique em <b>Gravar e Implementar</b> para gravar todas as alterações efetuadas no layout desde a última vez que foi efetuada uma gravação, e para tornar as alterações activas no módulo.<br><br>O layout irá ser exibido imediatamente no módulo.',
        'toolbox'	=> 'A <b>Caixa de Ferramentas</b> contém a <b>Reciclagem</b>, elementos adicionais de layout e um conjunto de campos disponíveis para adicionar ao layout.<br/><br/>Os elementos de layout e os campos na Caixa de Ferramentas podem ser arrastados e largados para o layout, e os elementos de layout e os campos podem ser arrastados e largados do layout para a Caixa de Ferramentas.<br><br>O elementos de layout são os <b>Painéis</b> e as <b>Linhas</b>. Adicionar uma nova linha ou um novo painel ao layout proporciona localizações adicionais no layout para os campos.<br/><br/>Arraste e largue qualquer um dos campos na Caixa de Ferramentas ou layout para uma posição ocupada por um campo para trocar as localizações destes dois campos.<br/><br/>O campo <b>Filler</b> cria espaços em branco no layout onde este é colocado.',
        'panels'	=> 'A área <b>Layout</b> providência uma visão de como o layout irá ficar dentro do módulo quando as alterações feitas ao layout forem implementadas.<br/><br/>Poderá reposicionar campos, linhas e painéis, arrastando-os e largando-os na localização pretendida.<br/><br/>Remover elementos arrastando-os e largando-os na <b>Reciclagem</b> nas Ferramentas, ou então adicionando novos elementos e campos arrastando-os das <b>Ferramentas</b> e largando-os na localização pretendida do layout.',
        'delete'	=> 'Arrastar e largar qualquer elemento aqui para o remover do layout',
        'property'	=> 'Edite o <b>Rótulo</b> apresentado para este campo.<br><br><b>Largura</b> fornece um valor de largura em píxeis para os módulos do Sidecar e como uma percentagem da largura da tabela para módulos com compatibilidade com versões anteriores.',
    ),
    'fieldsEditor'=>array(
        'default'	=> 'Os <b>Campos</b> que estão disponíveis para o módulo estão listados aqui por Nome do Campo.<br><br>Campos personalizados criados para o módulo aparecem sobre os campos que estão disponíveis para o módulo por defeito.<br><br>Para editar um campo, clicar no <b>Nome do Campo</b>.<br/><br/>Para criar um novo campo, clicar em <b>Adicionar Campo</b>.',
        'mbDefault'=>'Os <b>Campos</b> que estão disponíveis para o módulo estão listados aqui por Nome do Campo.<br><br>Para configurar as propriedades para um campo, clicar no Nome do Campo.<br><br>Para criar um novo campo, clicar em <b>Adicionar Campo</b>. O rótulo e as outras propriedades do novo campo podem ser editados depois da criação clicando no Nome do Campo.<br><br>Depois de o módulo ser implementado, os novos campos criados no Module Builder são considerados como campos padrão no módulo implementado no Studio.',
        'addField'	=> 'Selecione um <b>Tipo de Dados</b> para o novo campo. O tipo escolhido determina que tipo de caracteres podem ser inseridos no campo. Por exemplo, apenas números inteiros poderão ser inseridos em campos que são do tipo de dados Inteiros.<br><br> Forneça um <b>Nome</b> para o campo. O nome terá que ser alfanumérico e não poderá conter nenhum espaço. Os caracteres de sublinhado são válidos.<br><br> O <b>Rótulo de Apresentação</b> é o rótulo que irá aparecer para os campos nos layouts do módulo. O <b>Rótulo de Sistema</b> é usado para referir o campo no código.<br><br> Dependendo do tipo de dados selecionado para o campo, alguma ou todas as seguintes propriedades podem ser agrupadas para o campo:<br><br> <b>Texto de Ajuda</b> aparece temporariamente enquanto o utilizador passa por cima do campo e pode ser usado para perguntar ao utilizador qual o tipo de input desejado.<br><br> <b>Texto de Comentário</b> é apenas visível dentro do Studio e/ou no Module Builder e pode ser usado para descrever o campo para administradores.<br><br> <b>Valor por Defeito</b> irá aparecer no campo. Os utilizadores poderão inserir um novo valor no campo ou usar o valor por defeito.<br><br> Selecione a caixa de seleção <b>Atualização em Massa</b> de forma a ser possível utilizar a funcionalidade de Atualização em Massa para o campo.<br><br> O valor <b>Tamanho Máximo</b> determina o número máximo de caracteres que pode ser inserido no campo.<br><br> Selecione a caixa de seleção <b>Campo Obrigatório</b> de forma a tornar o campo obrigatório. Um valor terá que ser fornecido para o campo de maneira a ser possível gravar o registo que contenha o campo.<br><br> Selecione a caixa de seleção <b>Reportável</b> de forma a permitir ao campo de ser usado por filtros e para mostrar dados em Relatórios.<br><br> Selecione a caixa de seleção <b>Auditoria</b> de forma a ser possível de controlar alterações ao campo no Registo de Alterações.<br><br>Selecione a opção no campo <b>Importável</b> para permitir, negar ou requer que o campo seja importado para o Assistente de Importação.<br><br>Selecione uma opção no campo <b>Juntar Duplicados</b> para ativar ou desativar as funcionalidades de Juntar Duplicados e Encontrar Duplicados.<br><br>Propriedades adicionais podem ser definidas para determinados tipos de dados.',
        'editField' => 'As propriedades deste campo podem ser personalizadas.<br><br>Clique em <b>Clonar</b> para criar um novo campo com as mesmas propriedades.',
        'mbeditField' => 'O <b>Rótulo de Apresentação</b> de um campo modelo pode ser personalizado. As outras propriedades do campo não poderão ser personalizadas.<br><br>Clique em <b>Clonar</b> para criar um novo campo com as mesmas propriedades.<br><br>Para remover o campo modelo para que não seja mostrado no módulo, remova o campo dos <b>Layouts</b> apropriados.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Exportar personalizações feitas no Studio criando pacotes que possam ser carregados em outra instância do Sugar através do <b>Carregador de Módulos</b>.<br><br> Primeiro, fornecer um <b>Nome do Pacote</b>.  Poderá também fornecer a informação do <b>Autor</b> e da <b>Descrição</b> para o pacote.<br><br>Selecionar o(s) módulo(s) que contenha(m) as personalizações que queira exportar. Apenas módulos que contenham personalizações irão aparecer para selecionar.<br><br>Clicar em <b>Exportar</b> para criar um ficheiro .zip para o pacote contendo as personalizações.',
        'exportCustomBtn'=>'Clique em <b>Exportar</b> para criar um ficheiro .zip para o pacote contendo as personalizações do que pretende exportar.',
        'name'=>'Este é o <b>Nome</b> do pacote. Este nome será exibido durante a instalação.',
        'author'=>'Este é o <b>Autor</b> que é exibido durante a instalação como o nome da entidade que criou o pacote. O Autor poderá ser um indivíduo ou uma empresa.',
        'description'=>'Esta é a <b>Descrição</b> do pacote que é mostrada durante a instalação.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bem-vindo à área de <b>Ferramentas de Desenvolvimento</b>. <br/><br/>Use as ferramentas desta área para criar e gerir campos e módulos padrão personalizados.',
        'studioBtn'	=> 'Use o <b>Studio</b> para personalizar módulos implementados.',
        'mbBtn'		=> 'Utilize o <b>Module Builder</b> para criar novos módulos.',
        'sugarPortalBtn' => 'Use o <b>Editor do Sugar Portal</b> para gerir e personalizar o Sugar Portal.',
        'dropDownEditorBtn' => 'Use o <b>Editor de Caixa de Seleção</b> para adicionar e editar caixas de seleção globais para campos de caixa de seleção.',
        'appBtn' 	=> 'Modo de Aplicação é onde se pode personalizar várias propriedades do programa, como por exemplo quantos relatórios TPS são exibidos na página principal',
        'backBtn'	=> 'Voltar ao passo anterior.',
        'studioHelp'=> 'Use o <b>Studio</b> para determinar qual e como a informação é mostrada nos módulos.',
        'studioBCHelp' => 'indica que o módulo é um módulo com compatibilidade retroativa',
        'moduleBtn'	=> 'Clicar para editar este módulo.',
        'moduleHelp'=> 'Os componentes do módulo que podem ser personalizados aparecem aqui.<br><br>Clicar num ícone para selecionar o componente a editar.',
        'fieldsBtn'	=> 'Criar e personalizar os <b>Campos</b> para guardar informação no módulo.',
        'labelsBtn' => 'Edite os <b>Rótulos</b> que são exibidos para os campos e outros títulos do módulo.'	,
        'relationshipsBtn' => 'Adicionar uma nova ou ver as <b>Relações</b> existentes no módulo.' ,
        'layoutsBtn'=> 'Personalizar o módulo <b>Layouts</b>. Os layouts são diferentes vistas do módulo contendo campos.<br><br>Poderá determinar quais os campos que aparecem e como são organizados em cada layout.',
        'subpanelBtn'=> 'Determine quais os campos que aparecem nos <b>Subpainéis</b> no módulo.',
        'portalBtn' =>'Personalizar o módulo <b>Layouts</b> que aparece no <b>Sugar Portal</b>.',
        'layoutsHelp'=> 'O módulo <b>Layouts</b> que pode ser personalizado aparece aqui.<br><br>Os layouts mostram campos e os dados dos campos.<br><br>Clicar num ícone para selecionar o layout a editar.',
        'subpanelHelp'=> 'Os <b>Subpainéis</b> no módulo que podem ser personalizados aparecem aqui.<br><br>Clicar no ícone para selecionar o módulo a editar.',
        'newPackage'=>'Clicar em <b>Novo Pacote</b> para criar um novo pacote.',
        'exportBtn' => 'Clique em <b>Exportar Personalizações</b> para criar e transferir o pacote contendo as personalizações efetuadas no Studio para módulos específicos.',
        'mbHelp'    => 'Utilize o <b>Module Builder</b> para criar pacotes contendo módulos personalizados baseados em módulos padrão ou objetos personalizados.',
        'viewBtnEditView' => 'Personalizar o layout de <b>EditView
</b> do módulo.<br><br>O EditView é um formulário que contem os campos para guardar os dados inseridos pelo utilizador.',
        'viewBtnDetailView' => 'Personalizar o layout de <b>DetailView
</b> do módulo.<br><br>O DetailView exibe os dados inseridos pelo utilizador.',
        'viewBtnDashlet' => 'Personalizar o <b>Sugar Dashlet</b> do módulo, incluindo a Listagem do Sugar Dashlet e a Pesquisa.<br><br>O Sugar Dashlet estará disponível para adicionar a páginas no módulo Principal.',
        'viewBtnListView' => 'Personalizar o layout da <b>Listagem</b> do layout.<br><br>Os resultados da Pesquisa aparecem na Listagem.',
        'searchBtn' => 'Personalizar o layout da <b>Pesquisa</b>.<br><br>Determinar quais os campos que podem ser usados para filtrar registos que aparecem na Listagem.',
        'viewBtnQuickCreate' =>  'Personalizar o layout de <b>Criar Rápido</b> do módulo.<br><br>O formulário de Criar Rápido aparece em subpainéis e no módulo de E-mails.',

        'searchHelp'=> 'Os formulários de <b>Pesquisa</b> que podem ser personalizados aparecem aqui.<br><br>Os formulários de Pesquisa contêm campos para filtrar registos.<br><br>Clicar num ícone para escolher o layout de pesquisa a editar.',
        'dashletHelp' =>'Os layouts de <b>Sugar Dashlet</b> que podem ser personalizados aparecem aqui.<br><br>O Sugar Dashlet irá estar disponível para adicionar a páginas no módulo Principal.',
        'DashletListViewBtn' =>'A <b>Listagem do Sugar Dashlet</b> exibe registos baseados nos filtros de pesquisa do Sugar Dashlet.',
        'DashletSearchViewBtn' =>'A <b>Pesquisa do Sugar Dashlet</b> filtra registos para a listagem do Sugar Dashlet.',
        'popupHelp' =>'Os layouts de <b>Popup</b> que podem ser personalizados aparecem aqui.<br>',
        'PopupListViewBtn' => 'A <b>Listagem do Popup</b> mostra registos baseados na pesquisa do Popup.',
        'PopupSearchViewBtn' => 'A <b>Pesquisa do Popup</b> mostra registos para a listagem do Popup.',
        'BasicSearchBtn' => 'Personalizar o formulário da <b>Pesquisa Básica</b> que aparece no separador da Pesquisa Básica na área de Pesquisa para o módulo.',
        'AdvancedSearchBtn' => 'Personalizar o formulário da <b>Pesquisa Avançada</b> que aparece no separador da Pesquisa Avançada na área de Pesquisa para o módulo.',
        'portalHelp' => 'Gerir e personalizar o <b>Sugar Portal</b>.',
        'SPUploadCSS' => 'Carregar uma <b>Folha de Estilos</b> para o Sugar Portal.',
        'SPSync' => '<b>Sincronizar</b> personalizações com a instância do Sugar Portal.',
        'Layouts' => 'Personalizar os <b>Layouts</b> dos módulos do Sugar Portal.',
        'portalLayoutHelp' => 'Os módulos dentro do Sugar Portal aparecem nesta área.<br><br>Selecione o módulo para editar os <b>Layouts</b>.',
        'relationshipsHelp' => 'Todas as <b>Relações</b> que existem entre os módulos e outros módulos implementados aparecem aqui.<br><br>O <b>Nome</b> da relação é um nome gerado pelo sistema para esta relação.<br><br>O <b>Módulo Primário</b> é o módulo que detém as relações. Por exemplo, todas as propriedades das relações cujo módulo Contas é o módulo primário estão guardadas nas tabelas da base de dados das Contas.<br><br>O <b>Tipo</b> é o tipo de relação que existe entre o módulo Primário e o <b>Módulo Relacionado</b>.<br><br>Clique num título da coluna para ordenar pela coluna.<br><br>Clique numa linha da tabela de relações para ver as propriedades associadas à relação.<br><br>Clique em <b>Adicionar Relação</b> para criar uma nova relação.<br><br>As relações podem ser criadas entre quaisquer dois módulos implementados.',
        'relationshipHelp'=>'<b>Relações</b> podem ser criadas entre o módulo e outro módulo implementado.<br><br> As relações são expressas visualmente através de subpainéis e campos relacionados nos registos do módulo.<br><br>Selecione um dos seguintes <b>Tipos</b> de relações para o módulo:<br><br> <b>Um-para-Um</b> - Ambos os registos dos módulos irão ter campos relacionados.<br><br> <b>Um-para-Muitos</b> - Os registos do Módulo Primário irão ter um subpainel e os registos do Módulo Relacionado irão ter um campo relacionado.<br><br> <b>Muitos-para-Muitos</b> - Ambos os registos dos módulos irão mostrar subpainéis.<br><br> Selecione o <b>Módulo Relacionado</b> para a relação.<br><br> Se o tipo de relação envolver subpainéis, escolha uma visualização de subpainel para os módulos apropriados.<br><br> Clique em <b>Gravar</b> para criar a relação.',
        'convertLeadHelp' => "Aqui pode-se adicionar módulos ao ecrã de layouts de conversão e modificar os layouts existentes.<br/><br/>
<b>Ordenação:</b><br/>
Contactos, Contas e Oportunidades devem manter a sua ordem. Pode ordenar novamente qualquer outro módulo arrastando a respetiva linha na tabela.<br/><br/>
<b>Dependência:</b><br/>
Se as Oportunidades estiverem Incluídas, as Contas devem ser obrigatórias ou removidas do layout de conversão.<br/><br/>
<b>Módulo:</b>O nome do módulo.<br/><br/>
<b>Obrigatório:</b> Os módulos obrigatórios terão que ser criados ou selecionados antes que o cliente potencial possa ser convertido.<br/><br/>
<b>Copiar Dados:</b> Se for selecionado, os campos do cliente potencial serão copiados para os campos com o mesmo nome nos registos criados recentemente.<br/><br/>
<b>Eliminar:</b> Remova este módulo do layout de conversão.<br/><br/>        ",
        'editDropDownBtn' => 'Editar um Menu de Seleção global',
        'addDropDownBtn' => 'Adicionar um novo Menu de Seleção global',
    ),
    'fieldsHelp'=>array(
        'default'=>'Os <b>Campos</b> do módulo são listados aqui por Nome de Campo.<br><br>O modelo do módulo inclui um conjunto pré-determinado de campos.<br><br>Para criar um novo campo, clicar <b>Adicionar Campo</b>.<br><br>Para editar um campo, clicar no <b>Nome do Campo</b>.<br/><br/>Depois do módulo ser implementado, os novos campos criados no Module Builder, juntamente com os campos do modelo, são considerados como campos padrão no Studio.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'As <b>Relações</b> que foram criadas entre o módulo e outros módulos aparecem aqui.<br><br>O <b>Nome</b> da relação é o nome gerado pelo sistema para a relação.<br><br>O <b>Módulo Primário</b> é o módulo que detém as relações. As propriedades da relação são guardadas nas tabelas da base de dados pertencente ao módulo primário.<br><br>O <b>Tipo</b> é o tipo de relação que existe entre o módulo Primário e o <b>Módulo Relacionado</b>.<br><br>Clicar no título da coluna para ordenar pela coluna.<br><br>Clicar numa linha da tabela da relação para ver e editar as propriedades associadas à relação.<br><br>Clicar <b>Adicionar Relação</b> para criar uma nova relação.',
        'addrelbtn'=>'ajuda para adicionar relações.',
        'addRelationship'=>'<b>Relações</b> podem ser criadas entre o módulo e outro módulo personalizado ou um módulo implementado.<br><br> Relações são expressadas visualmente através de subpainéis e relaciona campos nos registos do módulo.<br><br>Selecionar um dos seguintes <b>Tipos</b> de relações para o módulo:<br><br> <b>Um-para-Um</b> - Ambos os registos dos módulos irão ter campos relacionados.<br><br> <b>Um-para-Muitos</b> - Os registos do Módulo Primário irão ter um subpainel e os registos do Módulo Relacionado irão ter um campo relacionado.<br><br> <b>Muitos-para-Muitos</b> - Ambos os registos dos módulos irão mostrar subpainéis.<br><br> Selecionar o <b>Módulo Relacionado</b> para a relação.<br><br>Se o tipo de relação envolver subpainéis, escolher uma visualização de subpainel para os módulos apropriados.<br><br> Clicar <b>Guardar</b> para criar a relação.',
    ),
    'labelsHelp'=>array(
        'default'=> 'Os <b>Rótulos</b> para os campos e outros títulos no módulo podem ser alterados.<br><br>Editar o rótulo clicando dentro do campo, inserir o novo rótulo e clicar <b>Guardar</b>.<br><br>Se algum pacote de idioma estiver instalado na aplicação, poderá selecionar o <b>Idioma</b> a utilizar para os rótulos.',
        'saveBtn'=>'Clickar <b>Guardar</b> para guardar todas as alterações.',
        'publishBtn'=>'Clique em <b>Guardar e Implementar</b> para gravar todas as alterações e torná-las ativas.',
    ),
    'portalSync'=>array(
        'default' => 'Introduza o <b>URL do Sugar Portal</b> da instância do portal a atualizar, e clicar <b>Ir</b>.<br><br>Depois introduza um nome de utilizador Sugar válido e palavra-passe e clique em <b>Começar Sincronização</b>.<br><br>As personalizações efetuadas nos <b>Layouts</b> do Sugar Portal, juntamente com a <b>Folha de Estilos</b> se alguma foi carregada, serão transferidas para a instância especificada.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Pode personalizar o aspeto do Sugar Portal usando uma folha de estilos.<br><br>Selecione uma <b>Folha de Estilos</b> para carregar.<br><br>A folha de estilos será implementada no Sugar Portal da próxima vez que uma sincronização for efetuada.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Para começar um projeto, clique em <b>Novo Pacote</b> para criar um novo pacote para albergar o(s) seu(s) módulo(s) personalizado(s). <br/><br/>Cada pacote pode conter um ou mais módulos.<br/><br/>Por exemplo, poderá querer criar um pacote contendo um módulo personalizado que esteja relacionado com o módulo padrão Contas. Ou poderá querer criar um pacote contendo vários módulos novos que trabalhem em conjunto como um projeto e que estejam relacionados entre si e com outros módulos já existentes na aplicação.',
            'somepackages'=>'Um <b>pacote</b> funciona como um recipiente para módulos personalizados que fazem parte de um projecto. O pacote pode conter um ou mais <b>módulos</b> personalizados que podem estar relacionados entre si ou com outros módulos da aplicação.<br/><br/>Depois de criar um pacote para o projecto, pode criar módulos para o pacote logo de seguida, ou poderá voltar ao Module Builder mais tarde para completar o projecto.<br><br>Quando o projecto estiver concluído, pode <b>Implementar</b> o pacote para instalar os módulos personalizados na aplicação.',
            'afterSave'=>'O novo pacote deverá conter pelo menos um módulo. Poderá criar um ou mais módulos personalizados para o pacote.<br/><br/>Clicar <b>Novo Módulo</b> para criar um módulo personalizado para este pacote.<br/><br/>Depois de criar pelo menos um módulo, poderá publicar ou implementar o pacote para o tornar disponível para a sua instância e/ou para as instâncias de outros utilizadores.<br/><br/> Para implementar o pacote em apenas um passo dentro da sua instância do Sugar, clicar <b>Implementar</b>.<br><br>Clicar <b>Publicar</b> para guardar o pacote como um ficheiro .zip. Depois do ficheiro .zip ser gravado no seu sistema, use o <b>Carregador do Módulo</b> para carregar e instalar o pacote dentro da sua instância do Sugar. <br/><br/>Poderá distribuir o ficheiro por outros utilizadores para carregarem e instalarem nas suas próprias instâncias de Sugar.',
            'create'=>'Um <b>pacote</b> funciona como um recipiente para módulos personalizados que fazem parte de um projecto. O pacote pode conter um ou mais <b>módulos</b> personalizados que podem estar relacionados entre si ou com outros módulos da aplicação.<br/><br/>Depois de criar um pacote para o projecto, pode criar módulos para o pacote logo de seguida, ou poderá voltar ao Module Builder mais tarde para completar o projecto.',
            ),
    'main'=>array(
        'welcome'=>'Utilize as <b>Ferramentas de Desenvolvimento</b> para criar e gerir campos e módulos padrão personalizados. <br/><br/>Para gerir módulos na aplicação, clicar em <b>Studio</b>. <br/><br/>Para criar módulos personalizados, clicar em <b>Module Builder</b>.',
        'studioWelcome'=>'Todos os módulos instalados, incluindo os padrão e objectos carregados por módulos, são personalizáveis dentro do Studio.'
    ),
    'module'=>array(
        'somemodules'=>"Como o pacote actual contem pelo menos um módulo, pode <b>Implementar</b> os módulos do pacote dentro da sua instância do Sugar ou <b>Publicar</b> o pacote a ser instalado na instância actual do Sugar ou em outra instância usando o <b>Carregador de Módulo</b>.<br/><br/>Para instalar o pacote directamente dentro da sua instância do Sugar, clicar <b>Implementar</b>.<br><br>Para criar um ficheiro .zip para o pacote que possa ser carregado e instalado na instância actual de Sugar e outras instâncias usando o <b>Carregador de Módulo</b>, clicar em <b>Publicar</b>.<br/><br/> Poderá construir os módulos para este pacote em etapas, e publicar ou implementar quanto estiver pronto para tal. <br/><br/>Depois de publicar ou implementar o pacote, poderá fazer alterações às propriedades do pacote e personalizar mais os módulos. Em seguida, voltar a publicar ou a implementar o pacote para aplicar as alterações." ,
        'editView'=> 'Aqui pode editar os campos existentes. Pode remover qualquer dos campos existentes ou adicionar campos disponíveis no painel esquerdo.',
        'create'=>'Ao escolher o tipo de <b>Tipo</b> de módulo que queira criar, tenha em mente que tipos de campos que gostaria de ter dentro do módulo. <br/><br/>Cada modelo de módulo contém um conjunto de campos pertencentes ao tipo de módulos descrito pelo título.<br/><br/><b>Básico</b> - Fornece campos básicos que aparecem em módulos padrão, como o campo Nome, Atribuído a, Equipa, Data de Criação e Descrição.<br/><br/> <b>Empresa</b> - Fornece campos específicos de organizações, como Nome da Empresa, Indústria e Morada de Faturação. Utilize este modelo para criar módulos que são similares ao módulo padrão de Contas.<br/><br/> <b>Pessoal</b> - Fornece campos específicos de indivíduos, como Saudação, Título, Nome, Morada e Número de Telefone. Utilize este modelo para criar módulos que são similares aos módulos padrão Contactos e Clientes Potenciais.<br/><br/><b>Questão</b> - Fornece campos relacionados com erros, como Número, Estado, Prioridade e Descrição. Utilize este modelo para criar módulos que são similares aos módulos padrão de Ocorrências e Bug Tracker.<br/><br/>Nota: Depois de criar o módulo, poderá editar os rótulos dos campos fornecidos pelo modelo, assim como criar campos personalizados para adicionar aos layouts dos módulos.',
        'afterSave'=>'Personalize o módulo para servir as necessidades editando e criando campos, estabelecendo relações com outros módulos e dispondo os campos dentro dos layouts.<br/><br/>Para ver os campos do modelo e gerir os campos personalizados dentro do módulo, clicar <b>Ver Campos</b>.<br/><br/>Para criar e gerir relações entre o módulo e outros módulos, quer módulos já na aplicação ou outros módulos personalizados dentro do mesmo pacote, clicar <b>Ver Relações</b>.<br/><br/>Para editar os layouts dos módulos, clicar <b>Ver Layouts</b>. Poderá alterar os layouts dos Detalhes, Edição e Listagem do módulo tal como faria com módulos já na aplicação dentro do Studio.<br/><br/> Para criar um módulo com as mesmas propriedades que o módulo actual, clicar <b>Duplicar</b>.  Depois poderá personalizar mais o novo módulo.',
        'viewfields'=>'Os campos no módulo podem ser personalizados para servir as necessidades.<br/><br/>Não é possível apagar os campos padrão, mas é possível remove-los dos layouts adequados dentro das páginas de Layout.<br/><br/>Pode criar rapidamente novos campos que têm propriedades similares dos campos existentes clicando em <b>Clonar</b> no formulário das <b>Propriedades</b>. Inserir quaisquer novas propriedades e clicar <b>Guardar</b>.<br/><br/>É recomendado que se defina todas as propriedades para os campos padrão e os campos personalizados antes de publicar e instalar o pacote que contém o módulo personalizado.',
        'viewrelationships'=>'Pode criar relações muitos-para-muitos entre o módulo atual e outros módulos do pacote, e/ou entre o módulo atual e módulos já instalados na aplicação.<br><br> Para criar relações um-para-muitos e um-para-um, crie campos <b>Relacionado</b> e <b>Relacionado Flexível</b> para os módulos.',
        'viewlayouts'=>'Pode controlar quais os campos que estão disponíveis para capturar dados dentro de <b>Ecrã de Edição</b>.  Pode também controlar que dados são exibidos dentro dos <b>Detalhes</b>.  As vistas não precisam de coincidir. <br/><br/>O formulário de Criar Rápido é mostrado quando <b>Criar</b> é clicado num subpainel de um módulo. Por defeito, o layout do formulário <b>Criar Rápido </b> é o mesmo que o layout de <b>Edição</b> por defeito. Pode personalizar o formulário Criar Rápido para que contenha menos campos e/ou campos diferentes que o layout de Edição.<br><br>Pode determinar a segurança do módulo utilizando a personalização do Layout, juntamente com a <b>Gestão de Funções</b>.<br><br>',
        'existingModule' =>'Depois de criar e personalizar este módulo, poderá criar módulos adicionais ou voltar ao pacote para <b>Publicar</b> ou <b>Implementar</b> o pacote.<br><br>Para criar módulos adicionais, clicar <b>Duplicar</b> para criar um módulo com as mesmas propriedades que o módulo actual, ou voltar ao pacote e clicar <b>Novo Módulo</b>.<br><br>Se já está pronto para <b>Publicar</b> ou <b>Implementar</b> o pacote que contém este módulo, voltar ao pacote para realizar estas funções. Pode publicar e implementar pacotes que contenham pelo menos um módulo.',
        'labels'=> 'Os rótulos dos campos padrão assim com os dos campos personalizados podem ser alterados. Alterar os rótulos dos campos não afecta os dados guardados nos campos.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Existem três colunas exibidas à esquerda. A coluna "Por Defeito" contêm os campos que são mostrados na listagem por defeito, a coluna "Disponíveis" contém campos que um utilizador poder escolher usar quando está a criar uma listagem personalizada e a coluna "Ocultos" contém campos ocultos mas que estão disponíveis ao administrador para adicionar às colunas por defeito ou disponíveis para serem utilizados pelos utilizadores.',
        'savebtn'	=> 'Clicar em <b>Gravar</b> irá guardar todas as alterações e torná-las ativas.',
        'Hidden' 	=> 'Campos ocultos são campos que não estão visíveis aos utilizadores para utilização nas listagens.',
        'Available' => 'Campos disponíveis são campos que não são exibidos por defeito, mas podem ser ativados pelos utilizadores.',
        'Default'	=> 'Os campos por defeito são exibidos aos utilizadores que não criaram definições de visualização da lista personalizadas.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Existem duas colunas exibidas à esquerda. A coluna "Por Defeito" contêm os campos que são mostrados na pesquisa e a coluna "Ocultos" contém campos ocultos mas que estão disponíveis ao administrador para adicionar à vista.',
        'savebtn'	=> 'Clicar em <b>Gravar e Implementar</b> irá gravar todas as alterações e torná-las ativas.',
        'Hidden' 	=> 'Campos ocultos são campos que não estão visíveis na pesquisa.',
        'Default'	=> 'Campos por defeito serão exibidos na pesquisa.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Existem duas colunas exibidas à esquerda. A coluna da direita, rotulada de Layout Actual ou Pré-visualização de Layout é onde pode alterar o layout do módulo. A coluna da esquerda, intitulada Ferramentas, contém elementos úteis e ferramentas para utilizar aquando a edição do layout.<br/><br/>Se a área do layout tem o título de Layout Atual então está a trabalhar numa cópia do layout utilizado atualmente pelo módulo para exibição.<br/><br/>Se é intitulada Pré-visualização de Layout então está a trabalhar numa cópia criada anteriormente quando o botão Guardar foi clicado, que poderia já ter sido mudada da versão visualizada pelos utilizadores deste módulo.',
        'saveBtn'	=> 'Clicar neste botão grava o layout para que possa preservar as suas alterações. Quando voltar a este módulo irá começar a partir deste layout alterado. No entanto o seu layout não será mostrado aos utilizadores do módulo enquanto não carregar no botão Guardar e Publicar.',
        'publishBtn'=> 'Clicar este botão para implementar o layout. Isto significa que este layout será exibido aos utilizadores deste módulo imediatamente.',
        'toolbox'	=> 'A caixa de ferramentas contém vários recursos úteis para a edição de layouts, incluindo uma área de reciclagem, um conjunto de elementos adicionais e um conjunto de campos disponíveis. Qualquer um destes podem ser arrastados e largados no layout.',
        'panels'	=> 'Esta área mostra como o layout irá ser exibido aos utilizadores deste módulo quando implementado.<br/><br/>Poderá reposicionar elementos como campos, linhas e painéis arrastando e largando-os; apagar elementos arrastando e largando-os na área de reciclagem na caixa de ferramentas, ou adicionar novos elementos arrastando e largando-os da caixa de ferramentas para o layout na posição pretendida.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Existem duas colunas exibidas à esquerda. A coluna da direita, rotulada de Layout Actual ou Pré-visualização de Layout é onde pode alterar o layout do módulo. A coluna da esquerda, intitulada Ferramentas, contém elementos úteis e ferramentas para utilizar aquando a edição do layout.<br/><br/>Se a área do layout tem o título de Layout Atual então está a trabalhar numa cópia do layout utilizado atualmente pelo módulo para exibição.<br/><br/>Se é intitulada Pré-visualização de Layout então está a trabalhar numa cópia criada anteriormente quando o botão Guardar foi clicado, que poderia já ter sido mudada da versão visualizada pelos utilizadores deste módulo.',
        'dropdownaddbtn'=> 'Clicando neste botão adiciona um novo item no menu de seleção.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Personalizações feitas no Studio dentro desta instância podem ser empacotados e implementados em outra instância.  <br><br>Forneça um <b>Nome de Pacote</b>. Pode fornecer a informação do <b>Autor</b> e da <b>Descrição</b> para o pacote.<br><br>Selecione o(s) módulo(s) que contenham as personalizações a exportar. (Apenas módulos que contenham personalizações irão aparecer para seleção.)<br><br>Clique em <b>Exportar</b> para criar um ficheiro .zip para o pacote contendo as personalizações. O ficheiro .zip pode ser carregado para outra instância através do  <b>Carregador de Módulo</b>.',
        'exportCustomBtn'=>'Clique em <b>Exportar</b> para criar um ficheiro .zip para o pacote contendo as personalizações do que pretende exportar.
',
        'name'=>'Este é o <b>Nome</b> do pacote a ser exibido no Carregador de Módulo depois do pacote ser carregado para instalação no Studio.',
        'author'=>'O <b>Autor</b> é o nome da entidade que criou o pacote. O Autor poderá ser um indivíduo ou uma empresa.<br><br>O Autor será exibido no Carregador de Módulo depois do pacote ser carregado para instalação no Studio.
',
        'description'=>'A <b>Descrição</b> do pacote que é exibida no Carregador de Módulo depois do pacote ser carregado para instalação no Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bem-vindo à área de <b>Ferramentas de Desenvolvimento</b1>. <br/><br/>Use as ferramentas desta área para criar e gerir campos e módulos padrão personalizados.',
        'studioBtn'	=> 'Use o <b>Studio</b> para personalizar módulos instalados alterando a disposição dos campos, escolhendo quais os campos que estão disponíveis e criando novos campos de dados personalizados.',
        'mbBtn'		=> 'Utilize o <b>Module Builder</b> para criar novos módulos.',
        'appBtn' 	=> 'Use o modo de Aplicação para personalizar várias propriedades do programa, como por exemplo quantos relatórios TPS são exibidos na página principal',
        'backBtn'	=> 'Voltar ao passo anterior.',
        'studioHelp'=> 'Use o <b>Studio</b> para personalizar módulos instalados.',
        'moduleBtn'	=> 'Clicar para editar este módulo.',
        'moduleHelp'=> 'Selecionar o componente do módulo que pretende editar',
        'fieldsBtn'	=> 'Edite a informação que é gravada no módulo controlando os <b>Campos</b> no módulo.<br/><br/>Pode editar e criar campos personalizados aqui.',
        'layoutsBtn'=> 'Personalize os <b>Layouts</b> da Edição, Detalhe, Listagem e pesquisa.',
        'subpanelBtn'=> 'Edite qual a informação que é exibida nos subpainéis deste módulo.',
        'layoutsHelp'=> 'Selecione o <b>Layout a editar</b>.<br/<br/>Para alterar o layout que contenha campos de dados para inserir dados, clique em <b>Edição</b>.<br/><br/>Para alterar o layout que exiba os dados inseridos nos campos da Edição, clique em <b>Detalhes</b>.<br/><br/>Para alterar as colunas que aparecem na lista por defeito, clique na <b>Listagem</b>.<br/><br/>Para alterar o layout do formulário de pesquisa Básica e Avançada, clique <b>Pesquisa</b>.',
        'subpanelHelp'=> 'Selecione um <b>Subpainel</b> para editar.',
        'searchHelp' => 'Selecione um layout de <b>Pesquisa</b> para editar.',
        'labelsBtn'	=> 'Edite as <b>Etiquetas</b> para exibir os valores neste módulo.',
        'newPackage'=>'Clicar em <b>Novo Pacote</b> para criar um novo pacote.',
        'mbHelp'    => '<b>Bem-vindo ao Module Builder.</b><br/><br/>Utilize o <b>Module Builder</b> para criar pacotes que contenham módulos personalizados baseados em objetos padrão ou personalizados. <br/><br/>Para começar, clique em <b>Novo Pacote</b> para criar um novo pacote, ou selecione um pacote a editar.<br/><br/> Um <b>pacote</b> funciona como um recipiente para módulos personalizados, que fazem parte de todo um projeto. O pacote pode conter um ou mais módulos personalizados que podem estar relacionados um com os outros ou com módulos da aplicação. <br/><br/>Exemplos: Poderá querer criar um pacote que contenha um módulo personalizado que está relacionado com o módulo padrão de Contas. Ou poderá querer criar um pacote que contenha novos módulos que funcionem juntos como um projeto que estão relacionados entre si e com outros módulos da aplicação.',
        'exportBtn' => 'Clicar <b>Exportar Personalizações</b> para criar o pacote contendo as personalizações feitas no Studio para módulos específicos.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Editor da Lista Dropdown',

//ASSISTANT
'LBL_AS_SHOW' => 'Mostrar Assistente no futuro.',
'LBL_AS_IGNORE' => 'Ignorar Assistente no futuro.',
'LBL_AS_SAYS' => 'Assistente diz:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Module Builder',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Editor da Lista Dropdown',
'LBL_EDIT_DROPDOWN'=>'Editar Lista Dropdown',
'LBL_DEVELOPER_TOOLS' => 'Ferramentas de Desenvolvimento',
'LBL_SUGARPORTAL' => 'Editor Sugar Portal',
'LBL_SYNCPORTAL' => 'Sincronizar com Portal',
'LBL_PACKAGE_LIST' => 'Lista de Pacotes',
'LBL_HOME' => 'Ecrã Principal',
'LBL_NONE'=>'-Nenhum-',
'LBL_DEPLOYE_COMPLETE'=>'Instalação Completa',
'LBL_DEPLOY_FAILED'   =>'Ocorreu um erro durante o processo de instalação, o pacote pode não ter instalado correctamente',
'LBL_ADD_FIELDS'=>'Adicionar Campos Personalizados',
'LBL_AVAILABLE_SUBPANELS'=>'Subpainéis Disponíveis',
'LBL_ADVANCED'=>'Avançada',
'LBL_ADVANCED_SEARCH'=>'Pesquisa Avançada',
'LBL_BASIC'=>'Básica',
'LBL_BASIC_SEARCH'=>'Pesquisa Básica',
'LBL_CURRENT_LAYOUT'=>'Layout Actual',
'LBL_CURRENCY' => 'Moeda',
'LBL_CUSTOM' => 'Personalizar',
'LBL_DASHLET'=>'Sugar Dashlet',
'LBL_DASHLETLISTVIEW'=>'Listagem Sugar Dashlet',
'LBL_DASHLETSEARCH'=>'Pesquisa Sugar Dashlet',
'LBL_POPUP'=>'Popup',
'LBL_POPUPLIST'=>'Listagem do Popup',
'LBL_POPUPLISTVIEW'=>'Listagem do Popup',
'LBL_POPUPSEARCH'=>'Pesquisa do Popup',
'LBL_DASHLETSEARCHVIEW'=>'Pesquisa Sugar Dashlet',
'LBL_DISPLAY_HTML'=>'Exibir Código HTML',
'LBL_DETAILVIEW'=>'DetailView',
'LBL_DROP_HERE' => '[Largue Aqui]',
'LBL_EDIT'=>'Editar',
'LBL_EDIT_LAYOUT'=>'Editar Layout',
'LBL_EDIT_ROWS'=>'Editar Linhas',
'LBL_EDIT_COLUMNS'=>'Editar Colunas',
'LBL_EDIT_LABELS'=>'Editar Rótulos',
'LBL_EDIT_PORTAL'=>'Editar Portal para',
'LBL_EDIT_FIELDS'=>'Editar Campos',
'LBL_EDITVIEW'=>'EditView',
'LBL_FILTER_SEARCH' => "Pesquisar",
'LBL_FILLER'=>'(filtro)',
'LBL_FIELDS'=>'Campos',
'LBL_FAILED_TO_SAVE' => 'Falhou ao Gravar',
'LBL_FAILED_PUBLISHED' => 'Falhou ao Publicar',
'LBL_HOMEPAGE_PREFIX' => 'Minha',
'LBL_LAYOUT_PREVIEW'=>'Prever Layout',
'LBL_LAYOUTS'=>'Layouts',
'LBL_LISTVIEW'=>'Listagem',
'LBL_RECORDVIEW'=>'Visualização do Registo',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Novo Pacote',
'LBL_NEW_PANEL'=>'Novo Painel',
'LBL_NEW_ROW'=>'Nova Linha',
'LBL_PACKAGE_DELETED'=>'Pacote Eliminado',
'LBL_PUBLISHING' => 'A publicar ...',
'LBL_PUBLISHED' => 'Publicado',
'LBL_SELECT_FILE'=> 'Selecionar um Ficheiro',
'LBL_SAVE_LAYOUT'=> 'Gravar Layout',
'LBL_SELECT_A_SUBPANEL' => 'Selecionar um Subpainel',
'LBL_SELECT_SUBPANEL' => 'Selecionar Subpainel',
'LBL_SUBPANELS' => 'Subpainéis',
'LBL_SUBPANEL' => 'Subpainel',
'LBL_SUBPANEL_TITLE' => 'Título:',
'LBL_SEARCH_FORMS' => 'Pesquisar Formulários',
'LBL_STAGING_AREA' => 'Área de Testes (arraste e largue os itens aqui)',
'LBL_SUGAR_FIELDS_STAGE' => 'Campos Sugar (clique nos itens para adicionar à área de testes)',
'LBL_SUGAR_BIN_STAGE' => 'Sugar Bin (clique nos itens para adicionar à área de testes)',
'LBL_TOOLBOX' => 'Caixa de Ferramentas',
'LBL_VIEW_SUGAR_FIELDS' => 'Ver Campos Sugar',
'LBL_VIEW_SUGAR_BIN' => 'Ver Recipiente Sugar',
'LBL_QUICKCREATE' => 'Criação Rápida',
'LBL_EDIT_DROPDOWNS' => 'Editar um Menu de Seleção Global',
'LBL_ADD_DROPDOWN' => 'Adicionar um novo Menu de Seleção Global',
'LBL_BLANK' => '-vazio-',
'LBL_TAB_ORDER' => 'Ordem de Tabulador',
'LBL_TAB_PANELS' => 'Exibir painéis como tabuladores',
'LBL_TAB_PANELS_HELP' => 'Quando os separadores estiverem ativados, utilize a caixa do "tipo" dropdown<br />para cada secção para definir a forma de apresentação (separador ou painel)',
'LBL_TABDEF_TYPE' => 'Tipo de Tela:',
'LBL_TABDEF_TYPE_HELP' => 'Selecione a forma como esta secção será exibida. Esta opção apenas terá efeito se tiver ativado tabuladores nesta vista.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Tabulador',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Painel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Selecione Painel para ter este painel exibido na tela de layout. Selecione Tabulador para ter este painel exibido num tabulador dentro do layout. Quanto for especificado Tabulador para um painel, painéis subsequentes também serão exibidos no tabulador<br/>Por cada painel para o qual for escolhido Tabulador será criado um novo tabulador. Se tabulador for selecionado para um painel abaixo do primeiro, o primeiro será necessariamente um tabulador.',
'LBL_TABDEF_COLLAPSE' => 'Colapsar',
'LBL_TABDEF_COLLAPSE_HELP' => 'Colapsar esta secção por defeito quando estiver definida como painel',
'LBL_DROPDOWN_TITLE_NAME' => 'Nome',
'LBL_DROPDOWN_LANGUAGE' => 'Idioma',
'LBL_DROPDOWN_ITEMS' => 'Listar Itens',
'LBL_DROPDOWN_ITEM_NAME' => 'Nome do Item',
'LBL_DROPDOWN_ITEM_LABEL' => 'Rótulo de Apresentação',
'LBL_SYNC_TO_DETAILVIEW' => 'Sincronizar para DetailView',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Escolher esta opção para sincronizar este layout EditView para o layout DetailView correspondente. Os campos e os seus posicionamentos em EditView<br>serão sincronizados e gravados para o DetailView automaticamente quando clicar em Gravar ou Gravar e Implementar em EditView. <br>Não será possível efetuar alterações em DetailView.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Este DetailView foi sincronizado com o EditView correspondente.<br> Os campos e os seus posicionamentos neste DetailView reflectem os campos e os seus posicionamentos em EditView.<br> Alterações ao DetailView não poderão ser gravados ou implementados nesta página. Efectue alterações ou anule a sincronização dos layouts em EditView. ',
'LBL_COPY_FROM' => 'Copiar Valor de:',
'LBL_COPY_FROM_EDITVIEW' => 'Copiar de EditView',
'LBL_DROPDOWN_BLANK_WARNING' => 'Os valores são necessários para o Nome do Item e para Rótulo de Apresentação. Para adicionar um item em branco, clique em Adicionar sem introduzir qualquer valor para o Nome do Item e para o Rótulo de Apresentação.',
'LBL_DROPDOWN_KEY_EXISTS' => 'A chave já existe na lista',
'LBL_DROPDOWN_LIST_EMPTY' => 'A lista deve conter pelo menos um item activo',
'LBL_NO_SAVE_ACTION' => 'Não foi possível encontrar a ação de gravação para esta visualização.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: documento incorretamente formado',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Indica um campo combinado. Um campo combinado é uma colecção de campos individuais. Por exemplo "Morada" é um campo combinado que contém "Endereço", "Cidade", "Código Postal", "Estado" e "País".<br><br>Carregar duas vezes num campo combinado para ver quais os campos que este contém.',
'LBL_COMBO_FIELD_CONTAINS' => 'contém:',

'LBL_WIRELESSLAYOUTS'=>'Layouts para dispositivos móveis',
'LBL_WIRELESSEDITVIEW'=>'EditView Móvel',
'LBL_WIRELESSDETAILVIEW'=>'DetailView Móvel',
'LBL_WIRELESSLISTVIEW'=>'Listagem Móvel',
'LBL_WIRELESSSEARCH'=>'Pesquisa',

'LBL_BTN_ADD_DEPENDENCY'=>'Adicionar Dependência',
'LBL_BTN_EDIT_FORMULA'=>'Editar Fórmula',
'LBL_DEPENDENCY' => 'Dependência',
'LBL_DEPENDANT' => 'Dependente',
'LBL_CALCULATED' => 'Calculado',
'LBL_READ_ONLY' => 'Somente leitura',
'LBL_FORMULA_BUILDER' => 'Construtor de Fórmulas',
'LBL_FORMULA_INVALID' => 'Fórmula Inválida',
'LBL_FORMULA_TYPE' => 'A fórmula deverá ser do tipo',
'LBL_NO_FIELDS' => 'Nenhum campo encontrado',
'LBL_NO_FUNCS' => 'Nenhuma função encontrada',
'LBL_SEARCH_FUNCS' => 'Funções de busca...',
'LBL_SEARCH_FIELDS' => 'Campos de pesquisa...',
'LBL_FORMULA' => 'Fórmula',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Dependente',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Arrastar itens da lista de opções disponíveis da esquerda para uma das listas na direita para tornar a opção disponível quando um determinado parente é selecionado.',
'LBL_AVAILABLE_OPTIONS' => 'Opções de Disponibilidade',
'LBL_PARENT_DROPDOWN' => 'Dropdown do Parente',
'LBL_VISIBILITY_EDITOR' => 'Editor de Visibilidade',
'LBL_ROLLUP' => 'Agregado',
'LBL_RELATED_FIELD' => 'Campo Relacionado',
'LBL_CONFIG_PORTAL_URL'=>'URL para a imagem do logo personalizado. As dimensões recomendadas são 163 × 18 píxeis.',
'LBL_PORTAL_ROLE_DESC' => 'Não elimine esta função. A Função de Cliente de Self-Service do Portal é uma função gerada pelo sistema durante a ativação do Sugar Portal. Utilize os controlos de Acesso dentro desta Função para ativar e/ou desativar os módulos Erros, Ocorrências ou Base de Conhecimento no Sugar Portal. Não modifique outros controlos de acesso para esta função para evitar comportamentos desconhecidos e imprevisíveis do sistema. Em caso de uma eliminação acidental desta função, poderá criá-la de novo desativando e ativando o Sugar Portal.',

//RELATIONSHIPS
'LBL_MODULE' => 'Módulo',
'LBL_LHS_MODULE'=>'Módulo Principal',
'LBL_CUSTOM_RELATIONSHIPS' => '* relacionamento criado no Studio ou no Construtor de Módulos',
'LBL_RELATIONSHIPS'=>'Relacionamentos',
'LBL_RELATIONSHIP_EDIT' => 'Editar Relacionamento',
'LBL_REL_NAME' => 'Nome',
'LBL_REL_LABEL' => 'Rótulo',
'LBL_REL_TYPE' => 'Tipo',
'LBL_RHS_MODULE'=>'Módulo Relacionado',
'LBL_NO_RELS' => 'Sem Relacionamentos',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Condição Opcional' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Coluna',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Valor',
'LBL_SUBPANEL_FROM'=>'Subpainel de',
'LBL_RELATIONSHIP_ONLY'=>'Nenhuns elementos visíveis serão criados para este relacionamento, dado que há um relacionamento visível pré-existente entre estes dois módulos.',
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
'LBL_QUESTION_DROPDOWN' => 'Selecione uma lista dropdown para editar, ou crie uma nova lista dropdown.',
'LBL_QUESTION_DASHLET' => 'Selecione um layout de dashlet para editar.',
'LBL_QUESTION_POPUP' => 'Selecione o layout de um popup para editar.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Relacionar Com',
'LBL_NAME'=>'Nome do Pacote:',
'LBL_LABELS'=>'Rótulos',
'LBL_MASS_UPDATE'=>'Actualização Massiva',
'LBL_AUDITED'=>'Auditoria',
'LBL_CUSTOM_MODULE'=>'Módulo',
'LBL_DEFAULT_VALUE'=>'Valor Padrão',
'LBL_REQUIRED'=>'Obrigatório',
'LBL_DATA_TYPE'=>'Tipo',
'LBL_HCUSTOM'=>'PERSONALIZADO',
'LBL_HDEFAULT'=>'PADRÃO',
'LBL_LANGUAGE'=>'Idioma:',
'LBL_CUSTOM_FIELDS' => '* campo criado no Studio',

//SECTION
'LBL_SECTION_EDLABELS' => 'Editar Rótulos',
'LBL_SECTION_PACKAGES' => 'Pacotes',
'LBL_SECTION_PACKAGE' => 'Pacote',
'LBL_SECTION_MODULES' => 'Módulos',
'LBL_SECTION_PORTAL' => 'Portal',
'LBL_SECTION_DROPDOWNS' => 'Listas Dropdown',
'LBL_SECTION_PROPERTIES' => 'Propriedades',
'LBL_SECTION_DROPDOWNED' => 'Editar Lista Dropdown',
'LBL_SECTION_HELP' => 'Ajuda',
'LBL_SECTION_ACTION' => 'Ação',
'LBL_SECTION_MAIN' => 'Principal',
'LBL_SECTION_EDPANELLABEL' => 'Editar Rótulo de Painel',
'LBL_SECTION_FIELDEDITOR' => 'Editar Campo',
'LBL_SECTION_DEPLOY' => 'Implementar',
'LBL_SECTION_MODULE' => 'Módulo',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Editar Visibilidade',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Padrão',
'LBL_HIDDEN'=>'Oculto',
'LBL_AVAILABLE'=>'Disponível',
'LBL_LISTVIEW_DESCRIPTION'=>'Existem três colunas disponíveis abaixo. A coluna <b>Padrão</b> contém campos que são exibidos numa visualização de lista por defeito. A coluna <b>Adicional</b> contém campos que um utilizador pode escolher usar para criar uma visualização personalizada. A coluna <b>Disponível</b> exibe campos disponíveis para si como administrador para adicionar às colunas Padrão ou Adicional para uso dos utilizadores.',
'LBL_LISTVIEW_EDIT'=>'Editor de Visualização de Lista',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Prever',
'LBL_MB_RESTORE'=>'Restaurar',
'LBL_MB_DELETE'=>'Eliminar',
'LBL_MB_COMPARE'=>'Comparar',
'LBL_MB_DEFAULT_LAYOUT'=>'Layout Padrão',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Adicionar',
'LBL_BTN_SAVE'=>'Gravar',
'LBL_BTN_SAVE_CHANGES'=>'Gravar Alterações',
'LBL_BTN_DONT_SAVE'=>'Descartar Alterações',
'LBL_BTN_CANCEL'=>'Cancelar',
'LBL_BTN_CLOSE'=>'Encerrar',
'LBL_BTN_SAVEPUBLISH'=>'Gravar & Implementar',
'LBL_BTN_NEXT'=>'Próximo',
'LBL_BTN_BACK'=>'Anterior',
'LBL_BTN_CLONE'=>'Duplicar',
'LBL_BTN_COPY' => 'Copiar',
'LBL_BTN_COPY_FROM' => 'Copiar de...',
'LBL_BTN_ADDCOLS'=>'Adicionar Colunas',
'LBL_BTN_ADDROWS'=>'Adicionar Linhas',
'LBL_BTN_ADDFIELD'=>'Adicionar Campo',
'LBL_BTN_ADDDROPDOWN'=>'Adicionar Lista Dropdown',
'LBL_BTN_SORT_ASCENDING'=>'Ordenação Ascendente',
'LBL_BTN_SORT_DESCENDING'=>'Ordenação Descendente',
'LBL_BTN_EDLABELS'=>'Editar Rótulos',
'LBL_BTN_UNDO'=>'Desfazer',
'LBL_BTN_REDO'=>'Refazer',
'LBL_BTN_ADDCUSTOMFIELD'=>'Adicionar Campo Personalizado',
'LBL_BTN_EXPORT'=>'Exportar Personalizações',
'LBL_BTN_DUPLICATE'=>'Duplicar',
'LBL_BTN_PUBLISH'=>'Publicar',
'LBL_BTN_DEPLOY'=>'Implementar',
'LBL_BTN_EXP'=>'Exportar',
'LBL_BTN_DELETE'=>'Eliminar',
'LBL_BTN_VIEW_LAYOUTS'=>'Ver Layouts',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Ver Mobile Layouts',
'LBL_BTN_VIEW_FIELDS'=>'Ver Campos',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Ver Relacionamentos',
'LBL_BTN_ADD_RELATIONSHIP'=>'Adicionar Relacionamento',
'LBL_BTN_RENAME_MODULE' => 'Mudar o Nome do Módulo',
'LBL_BTN_INSERT'=>'Introduzir',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Erro: Campo Já Existente',
'ERROR_INVALID_KEY_VALUE'=> "Erro: Valor-Chave Inválido: [&#39;]",
'ERROR_NO_HISTORY' => 'Nenhuns ficheiros de histórico encontrados',
'ERROR_MINIMUM_FIELDS' => 'Este layout deve conter pelo menos um campo',
'ERROR_GENERIC_TITLE' => 'Ocorreu um erro',
'ERROR_REQUIRED_FIELDS' => 'Tem certeza que deseja continuar? Os seguintes campos obrigatórios não estão no layout:',
'ERROR_ARE_YOU_SURE' => 'De certeza que deseja continuar?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'O(s) seguinte(s) campo(s) têm valores calculados que não serão re-calculados em tempo real na Edição do SugarCRM Mobile:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'O(s) seguinte(s) campo(s) têm valores calculados que não serão re-calculados em tempo real na Edição do SugarCRM Portal:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'O(s) seguinte(s) módulo(s) esta(ão) desativado(s):',
    'LBL_PORTAL_ENABLE_MODULES' => 'Se quer activa-los no portal, por favor activa-los <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">aqui</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Configurar Portal',
    'LBL_PORTAL_THEME' => 'Tema do portal',
    'LBL_PORTAL_ENABLE' => 'Activar',
    'LBL_PORTAL_SITE_URL' => 'O seu Portal está disponível em:',
    'LBL_PORTAL_APP_NAME' => 'Nome da Aplicação',
    'LBL_PORTAL_LOGO_URL' => 'URL do Logo',
    'LBL_PORTAL_LIST_NUMBER' => 'Número de registos a serem mostrados na lista',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Número de campos a serem mostrados na Vista de Detalhe',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Número de resultados a mostrar na Pesquisa Global',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Assignação por defeito para novos registos no portal',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Layouts do Portal',
'LBL_SYNCP_WELCOME'=>'Por favor introduza o URL da instância do portal que deseja actualizar.',
'LBL_SP_UPLOADSTYLE'=>'Selecione uma folha de estilo para carregar do seu computador.<br>A folha de estilo será implementada no Portal Sugar da próxima vez que desempenhar uma sincronização.',
'LBL_SP_UPLOADED'=> 'Carregado',
'ERROR_SP_UPLOADED'=>'Por favor certifique-se que está a carregar uma folha de estilo css.',
'LBL_SP_PREVIEW'=>'Eis uma previsão do aspecto do Sugar Portal utilizando a folha de estilo.',
'LBL_PORTALSITE'=>'URL Sugar Portal:',
'LBL_PORTAL_GO'=>'Ir',
'LBL_UP_STYLE_SHEET'=>'Carregar Folha de Estilo',
'LBL_QUESTION_SUGAR_PORTAL' => 'Selecione um layout Sugar Portal para editar.',
'LBL_QUESTION_PORTAL' => 'Selecione um layout de portal para editar.',
'LBL_SUGAR_PORTAL'=>'Editor Sugar Portal',
'LBL_USER_SELECT' => '-- Selecionar --',

//PORTAL PREVIEW
'LBL_CASES'=>'Ocorrências',
'LBL_NEWSLETTERS'=>'Newsletters',
'LBL_BUG_TRACKER'=>'Bug Tracker',
'LBL_MY_ACCOUNT'=>'A Minha Conta',
'LBL_LOGOUT'=>'Sair',
'LBL_CREATE_NEW'=>'Criar Novo',
'LBL_LOW'=>'Baixo',
'LBL_MEDIUM'=>'Média',
'LBL_HIGH'=>'Alto',
'LBL_NUMBER'=>'Número:',
'LBL_PRIORITY'=>'Prioridade:',
'LBL_SUBJECT'=>'Assunto',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Nome do Pacote',
'LBL_MODULE_NAME'=>'Nome do Módulo:',
'LBL_MODULE_NAME_SINGULAR' => 'Nome Singular do Módulo:',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Descrição:',
'LBL_KEY'=>'Chave:',
'LBL_ADD_README'=>'Leia-me',
'LBL_MODULES'=>'Módulos:',
'LBL_LAST_MODIFIED'=>'Última Modificação:',
'LBL_NEW_MODULE'=>'Novo Módulo',
'LBL_LABEL'=>'Rótulo Plural',
'LBL_LABEL_TITLE'=>'Rótulo',
'LBL_SINGULAR_LABEL' => 'Rótulo Singular',
'LBL_WIDTH'=>'Largura',
'LBL_PACKAGE'=>'Pacote:',
'LBL_TYPE'=>'Tipo:',
'LBL_TEAM_SECURITY'=>'Definições de Segurança da Equipa',
'LBL_ASSIGNABLE'=>'Atribuível',
'LBL_PERSON'=>'Pessoa',
'LBL_COMPANY'=>'Empresa',
'LBL_ISSUE'=>'Assunto',
'LBL_SALE'=>'Venda',
'LBL_FILE'=>'Ficheiro',
'LBL_NAV_TAB'=>'Tabulador de Navegação',
'LBL_CREATE'=>'Criar',
'LBL_LIST'=>'Lista',
'LBL_VIEW'=>'Vista',
'LBL_LIST_VIEW'=>'Listagem',
'LBL_HISTORY'=>'Ver Histórico',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Restaurar o layout padrão',
'LBL_ACTIVITIES'=>'Fluxo de Atividades',
'LBL_SEARCH'=>'Pesquisar',
'LBL_NEW'=>'Novo',
'LBL_TYPE_BASIC'=>'básico',
'LBL_TYPE_COMPANY'=>'empresa',
'LBL_TYPE_PERSON'=>'pessoa',
'LBL_TYPE_ISSUE'=>'assunto',
'LBL_TYPE_SALE'=>'venda',
'LBL_TYPE_FILE'=>'ficheiro',
'LBL_RSUB'=>'Este é o subpainel que será exibido no seu módulo',
'LBL_MSUB'=>'Este é o subpainel que o seu módulo fornece ao módulo relacionado para exibir',
'LBL_MB_IMPORTABLE'=>'Permitir Importações',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'visível',
'LBL_VE_HIDDEN'=>'oculto',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] foi eliminado',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Exportar Personalizações',
'LBL_EC_NAME'=>'Nome do Pacote:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Descrição:',
'LBL_EC_KEY'=>'Chave:',
'LBL_EC_CHECKERROR'=>'Selecione um módulo.',
'LBL_EC_CUSTOMFIELD'=>'campo(s) personalizado(s)',
'LBL_EC_CUSTOMLAYOUT'=>'layout(s) personalizado(s)',
'LBL_EC_CUSTOMDROPDOWN' => 'dropdown(s) personalizadas',
'LBL_EC_NOCUSTOM'=>'Nenhuns módulos foram personalizados.',
'LBL_EC_EXPORTBTN'=>'Exportar',
'LBL_MODULE_DEPLOYED' => 'Módulo foi implementado.',
'LBL_UNDEFINED' => 'indefinido',
'LBL_EC_CUSTOMLABEL'=>'rótulo(s) personalizado(s)',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Falhou ao recuperar dados',
'LBL_AJAX_TIME_DEPENDENT' => 'Uma ação dependente do tempo está em progresso. Por favor aguarde e tente de novo daqui a alguns segundos.',
'LBL_AJAX_LOADING' => 'A carregar...',
'LBL_AJAX_DELETING' => 'A eliminar...',
'LBL_AJAX_BUILDPROGRESS' => 'Construção em Progresso...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Implementação em Progresso...',
'LBL_AJAX_FIELD_EXISTS' =>'O nome do campo que introduziu já existe. Por favor insira um novo nome de campo.',
//JS
'LBL_JS_REMOVE_PACKAGE' => 'Tem a certeza que pretende remover este pacote? Isto irá eliminar permanentemente todos os ficheiros associados a este pacote.',
'LBL_JS_REMOVE_MODULE' => 'Tem a certeza que pretende remover este módulo? Isto irá eliminar permanentemente todos os ficheiros associados a este módulo.',
'LBL_JS_DEPLOY_PACKAGE' => 'Todas as personalizações que você fez no Studio serão apagadas quando este módulo for re-instalado. Tem certeza que deseja continuar?',

'LBL_DEPLOY_IN_PROGRESS' => 'Implementando Pacote',
'LBL_JS_VALIDATE_NAME'=>'Nome - Deve ser alfanumérico, sem espaços e começando por uma letra',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'A chave de pacote já existe',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'O Nome do Pacote já existe',
'LBL_JS_PACKAGE_NAME'=>'Nome do pacote - Deve começar com uma letra e só deve conter letras, números e linhas. Não devem ser usados espaços ou outros caracteres especiais.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Chave - Tem que ser alfanumérica e começar com uma letra.',
'LBL_JS_VALIDATE_KEY'=>'Chave - Deve ser alfanumérica, sem espaços e começando por uma letra',
'LBL_JS_VALIDATE_LABEL'=>'Introduza um rótulo que será utilizado como Nome de Apresentação para este módulo',
'LBL_JS_VALIDATE_TYPE'=>'Selecione o tipo de módulo que pretende construir da lista acima',
'LBL_JS_VALIDATE_REL_NAME'=>'Nome - Deve ser alfanumérico e sem espaços',
'LBL_JS_VALIDATE_REL_LABEL'=>'Rótulo - adicione um rótulo que será exibido acima do subpainel',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => 'Tem a certeza que quer apagar o item da lista de seleção de preenchimento obrigatório? Isto poderá afetar a funcionalidade da sua aplicação.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => 'Tem a certeza que quer apagar o item da lista de seleção? Apagar as fases de Ganha e Perdida irão fazer com que o módulo de Previsão deixe de funcionar',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => 'Tem a certeza que quer apagar o estado de Nova venda? Apagar este estado irá fazer com que o workflow do item de Linha de Receita do módulo das Oportunidades deixe de trabalhar correctamente.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => 'Tem a certeza que quer apagar o estado de venda Em Progresso? Apagar este estado irá fazer com que o workflow do Item de Linha de Receita do módulo das Oportunidades deixe de trabalhar correctamente.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => 'Tem a certeza que quer apagar a fase de vendas Ganha? Apagar esta fase irá fazer com que o módulo de Previsão não funcione correctamente',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => 'Tem a certeza que quer apagar a fase de vendas Perdida? Apagar esta fase irá fazer com que o módulo de Previsão não funcione bem',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'Tem a certeza de que pretende eliminar esta relação?<br>Nota: É possível que esta operação só esteja concluída após alguns minutos.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Isto irá tornar este relacionamento permanente. Tem a certeza que pretende implementar este relacionamento?',
'LBL_CONFIRM_DONT_SAVE' => 'Foram feitas alterações desde a sua última gravação, pretende gravar?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => 'Gravar Alterações?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Há dados que podem ser eliminados, e isso não pode ser desfeito, tem certeza que deseja continuar?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Selecione o tipo de base de dados apropriado baseado no tipo de dados que será introduzido no campo.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Configure o campo para que o texto seja totalmente pesquisável.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Reforçar é o processo de aumentar a relevância dos campos de um registo.<br />Aos campos com um maior nível de reforço é dado maior peso quando a pesquisa é executada. Quando uma pesquisa é executada, os registos correspondentes que contenham campos com um peso superior surgem mais acima nos resultados da pesquisa.<br />O valor padrão é 1.0, que significa um reforço neutro. Aplicar um reforço positivo, é aceite qualquer valor flutuante superior a 1. Para um reforço negativo, utilize valores inferiores a 1. Por exemplo, um valor de 1,35 reforça um campo positivamente em 135%. Se utilizar um valor de 0,60, o reforço aplicado será negativo.<br />Tenha em conta de que, em versões anteriores, era obrigatório executar uma reindexação de pesquisa de texto completo. Tal já não é necessário.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Sim</b>: O campo será incluído numa operação de importação.<br><b>Não</b>: O campo não será incluído numa importação.<br><b>Obrigatório</b>: Um valor para o campo deve ser fornecido em qualquer importação.',
'LBL_POPHELP_PII'=>'Este campo será automaticamente marcado para realizar uma auditoria e estará disponível na visualização de Informações pessoais.<br>Os campos de informações pessoais podem ser permanentemente apagados quando o registo estiver relacionado com uma solicitação de eliminação por privacidade de dados.<br>A eliminação será realizada através do módulo de Privacidade de dados e pode ser executada pelos administradores ou utilizadores com função de Administrador de privacidade de dados.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Introduza um número para a Largura, tal como é medido em píxeis.<br> A imagem carregada vai ser dimensionada para esta Largura.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Introduza um número para a Altura, tal como é medido em píxeis.<br> A imagem carregada vai ser dimensionada para esta Altura.',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'Selecione para utilizar este campo quando pesquisa por registos utilizando a Pesquisa Global neste módulo.',
//Revert Module labels
'LBL_RESET' => 'Reiniciar',
'LBL_RESET_MODULE' => 'Reiniciar Módulo',
'LBL_REMOVE_CUSTOM' => 'Remover personalizações',
'LBL_CLEAR_RELATIONSHIPS' => 'Limpar Relacionamentos',
'LBL_RESET_LABELS' => 'Reiniciar Rótulos',
'LBL_RESET_LAYOUTS' => 'Repor Layouts',
'LBL_REMOVE_FIELDS' => 'Remover os Campos Personalizados',
'LBL_CLEAR_EXTENSIONS' => 'Limpar Extensões',

'LBL_HISTORY_TIMESTAMP' => 'Marca de hora',
'LBL_HISTORY_TITLE' => 'histórico',

'fieldTypes' => array(
                'varchar'=>'Campo de Texto',
                'int'=>'Número Inteiro',
                'float'=>'Flutuante',
                'bool'=>'Caixa de Seleção',
                'enum'=>'Lista Pendente',
                'multienum' => 'Multiseleção',
                'date'=>'Data',
                'phone' => 'Telefone:',
                'currency' => 'Moeda',
                'html' => 'HTML',
                'radioenum' => 'Botão de Opção',
                'relate' => 'Relacionado',
                'address' => 'Endereço',
                'text' => 'Área de Texto',
                'url' => 'URL',
                'iframe' => 'IFrame',
                'image' => 'Imagem',
                'encrypt'=>'Encriptar',
                'datetimecombo' =>'Data/Hora',
                'decimal'=>'Decimal',
),
'labelTypes' => array(
    "" => "Rótulos usados frequentemente",
    "all" => "Todos os rótulos",
),

'parent' => 'Relacionado Flexível',

'LBL_ILLEGAL_FIELD_VALUE' =>"Chaves de dropdowns não podem conter plicas.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Quaisquer dropdowns que utilizem tenham este item como valor vão deixar de o exibir, e o valor vai deixar de ser selecionável nas dropdowns. Tem a certeza que quer continuar?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Todos os Módulos',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (ID {1} relacionado)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Copiar do layout',
);
