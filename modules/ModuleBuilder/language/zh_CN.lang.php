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
    'LBL_LOADING' => '加载' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => '隐藏可选项' /*for 508 compliance fix*/,
    'LBL_DELETE' => '删除' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'SugarCRM 提供动力' /*for 508 compliance fix*/,
    'LBL_ROLE' => '角色',
'help'=>array(
    'package'=>array(
            'create'=>'为文件包提供<b>名称</b>。名称必须以字母开头且仅包含字母、数字和下划线。不得使用空格或其他特殊符号。（例如：HR_Management）<br/><br/> 您可以为文件包提供<b>作者</b> 和 <b>说明</b> 信息。 <br/><br/>单击 <b>保存</b> 来创建新文件包。',
            'modify'=>'<b>文件包</b>的属性和可能的操作在此处显示。<br><br>您可以修改文件包的<b>名称</b>、<b>作者</b>和<b>说明</b> ，也可以查看和自定义文件包中包含的所有模块。<br><br>单击<b>新模块</b> 来给文件包新增模块。<br><br> 如果文件包包含至少一个模块，您可以<b>发布</b> 和<b>部署</b> 文件包并 <b>导出</b> 文件包中的定制项。',
            'name'=>'这是当前文件包的<b>名称</b> 。<br/><br/>名称必须以字母开头且仅包含字母、数字和下划线。不得使用空格或其他特殊符号。（例如：HR_Management）',
            'author'=>'这是在安装期间中显示的<b>作者</b>，作为创建文件包的实体名称。<br><br>作者可以是个人或者公司。',
            'description'=>'这是在安装期间显示的文件包的 <b>说明</b> 。',
            'publishbtn'=>'单击<b>发布</b> 保存输入的所有数据并创建一个 .zip 文件，作为文件包的可安装版本。<br><br>用<b>模块安装器</b>上传 .zip 文件并安装文件包。',
            'deploybtn'=>'单击<b>部署</b>保存输入的所有数据并在当前实例中安装文件包，包括所有模块 。',
            'duplicatebtn'=>'单击<b>复制</b>将文件包的内容复制到一个新文件夹并显示此新文件包。 <br/><br/>对于此新文件包，其名称将通过在创建该文件包的文件包名称末尾添加数字来自动生成。 您可以通过输入新<b>名称</b> 并单击 <b>保存</b>对新文件包进行重命名。',
            'exportbtn'=>'单击<b>导出</b>创建 一个 .zip 文件，其中包含在文件包中创建的定制项。<br><br> 生成的文件不是文件包的可安装版本。<br><br>用 <b>模块安装器</b>导入 .zip 文件并在模块生成器中显示文件包，包括定制项。',
            'deletebtn'=>'单击<b>删除</b>以删除此文件包以及与其相关的所有文件。',
            'savebtn'=>'单击<b>保存</b>以保存与文件包中相关的所有输入数据。',
            'existing_module'=>'单击<b>模块</b> 图标以编辑属性和自定义与模块相关的字段、关系和布局。',
            'new_module'=>'单击<b>新模块</b>为文件包创建一个新模块。',
            'key'=>'这个五个字母的字母数字<b>关键值</b>将用作当期那文件包中所有模块的所有目录、类名称和数据库表的前缀。<br><br>该关键值用于创建唯一的表名称。',
            'readme'=>'单击为文件包添加<b>自述文件</b>文本。<br><br>自述文件在安装期间可用。',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'为模块提供一个<b>名称</b>。您提供的<b>标签</b>将出现在导航标签中。<br/><br/>通过选中<b>导航标签</b>复选框来为模块选择要显示的导航标签。<br/><br/>选中<b>团队安全</b>复选框以在模板记录中包含一个团队选择字段。<br/><br/>然后选择想要创建的模块类型。<br/><br/>选择一个模板类型。每个模板包含一套指定字段和预定义布局， 作为模块的基础。<br/><br/>单击<b>保存</b> 新建模块。',
        'modify'=>'您可以更改模块属性或自定义与模块相关的<b>字段</b>、<b>关系</b>和<b>布局</b> 。',
        'importable'=>'选中<b>可导入的</b>复选框将为本模块启用导入功能。<br><br>模块的快捷键面板中将显示“导入向导”的链接。导入向导将说明如何将数据从外部资源导入自定义模块。',
        'team_security'=>'选中<b>团队安全</b>复选框将为本模块启用团队安全。<br/><br/>如果团队安全已启用，团队选择字段将会出现在模块的记录中。',
        'reportable'=>'选中此框将使本模块拥有关于其自身的报表。',
        'assignable'=>'选中此框可将本模块的记录分配至选定的用户。',
        'has_tab'=>'选中<b>导航标签</b>将为模块提供一个导航标签。',
        'acl'=>'选中此框将对本模块启用问控制，包括字段级安全。',
        'studio'=>'选中此框可使管理员在工作室内自定义本模块。',
        'audit'=>'选中此框将为本模块启用审计功能。某些字段的更改将会被记录以便管理员可以回顾更改历史记录。',
        'viewfieldsbtn'=>'单击<b>查看字段</b>来查看与模块相关的字段，并创建和编辑自定义字段。',
        'viewrelsbtn'=>'单击<b>查看关系</b>来查看与模块相关的关系并创建新关系。',
        'viewlayoutsbtn'=>'单击<b>查看布局</b>来查看模块的布局并自定义布局内的字段排列。',
        'viewmobilelayoutsbtn' => '单击<b>查看手机布局</b>来查看模块的手机布局并定制布局内的字段排列。',
        'duplicatebtn'=>'单击<b>复制</b>将模块的属性复制到新模块中并显示新模块。<br/><br/>对于此新模块，其名称将通过在创建该模块的模块名称末尾添加数字来自动生成。',
        'deletebtn'=>'单击<b>删除</b>来删除此模块。',
        'name'=>'这是当前模块的<b>名称</b>。<br/><br/>名称必须为字母数字组合，以字母开头且不包含空格。（例如：HR_Management）',
        'label'=>'这是将出现在模块导航标签中的<b>标签</b> 。',
        'savebtn'=>'单击<b>保存</b>以保存与模块相关的所有输入数据。',
        'type_basic'=>'<b>基础</b>模板类型提供基本字段，例如姓名、负责人、团队、创建日期和说明字段。',
        'type_company'=>'<b>公司</b>模板类型提供特定于组织的字段，例如公司名称、行业和账单地址。<br/><br/>使用此模板来创建与标准账户模块类似的模块。',
        'type_issue'=>'<b>发行</b>模板类型将提供特定于客户反馈和缺陷的字段，例如数量、状态、优先次序和说明。<br/><br/>使用此模板来创建与标准客户反馈和缺陷跟踪系统模块类似的模块。',
        'type_person'=>'<b>个人</b>模板类型提供特定于个人的字段，例如称谓、头衔、姓名、地址和电话号码。<br/><br/>使用此模板来创建与标准联系人和潜在客户模块类似的模块。',
        'type_sale'=>'<b>销售</b>模板提供特定于商业机会的字段，例如潜在客户来源、阶段、金额和概率。<br/><br/>使用此模板来创建与标准商业机会模块类似的模块。',
        'type_file'=>'<b>文件</b>模板提供特定于文档的字段，例如文件名、文档类型和公布日期。<br><br>使用此模板来创建与标准文档模块类似的模块。',

    ),
    'dropdowns'=>array(
        'default' => '所有 应用程序的<b>下拉列表</b>均列示在此处。<br><br>下拉列表可用于任何模块中的下拉列表字段。<br><br>单击下拉列表名称对现有下拉列表执行更改。<br><br>单击<b>新增下拉列表</b>来新建下拉列表。',
        'editdropdown'=>'下拉列表可用于任何模块中的标准和自定义的下拉列表字段。<br><br>为下拉列表提供一个<b>名称</b>。<br><br>如果应用程序中安装了任何语言包，您可以选择 <b>语言</b> 用于列表项目。<br><br>在<b>项目名称</b> 字段，为下拉列表中的选项提供名称。该名称将不会出现在对用户可见的下拉列表中。<br><br>在<b>显示标签</b> 字段中，提供对用户可见的标签。<br><br>在提供项目名称和显示标签之后，单击<b>添加</b>向下拉列表中添加项目。 <br><br>如需对列表中的项目重新排序，请将项目拖放所需位置。<br><br>如需编辑项目的显示标签，请单击<b>编辑图标</b>并输入新标签。 如需从下拉列表中删除项目，请单击<b>删除图标</b><br><br>如需撤销对显示标签所做的更改，请单击<b>撤销</b>。如需恢复撤销的更改，请单击<b>恢复</b>。<br><br>单击<b>保存</b>来保存下拉列表。',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> '单击<b>保存并部署</b>来保存您所做的更改并在模块中应用更改。',
        'historyBtn'=> '单击<b>查看历史记录</b>从历史记录中来查看和恢复先前保存的布局。',
        'historyRestoreDefaultLayout'=> '单击<b>恢复默认布局</b>来恢复初始布局视图。',
        'Hidden' 	=> '<b>隐藏</b> 字段不会出现在子面板中。',
        'Default'	=> '<b>默认</b>字段出现在子面板中。',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> '单击<b>保存并部署</b>来保存您所做的更改并在模块中应用更改。',
        'historyBtn'=> '单击<b>查看历史记录</b>从历史记录中来查看和恢复先前保存的布局。<br><br><b>查看历史记录</b>中的<b>恢复</b>可恢复以往保存的布局中的字段排列。如需更改字段标签，请单击每个字段旁边的“编辑”图标。',
        'historyRestoreDefaultLayout'=> '单击<b>恢复默认布局</b>来恢复初始布局视图。<br><br><b>恢复默认布局</b>仅能恢复初始布局中的字段排列。如需更改字段标签，请单击每个字段旁边的“编辑”图标。',
        'Hidden' 	=> '目前用户尚无法在列表视图中查看<b>隐藏</b>字段。',
        'Available' => '默认情况下<b>可用</b>字段不显示，但用户可将其添加到列表视图中。',
        'Default'	=> '出现在列表视图中的<b>默认</b>字段不是用户自定义的。'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> '单击<b>保存并部署</b>来保存您所做的更改并在模块中应用更改。',
        'historyBtn'=> '单击<b>查看历史记录</b>从历史记录中来查看和恢复先前保存的布局。<br><br><b>查看历史记录</b>中的<b>恢复</b>可恢复以往保存的布局中的字段排列。如需更改字段标签，请单击每个字段旁边的“编辑”图标。',
        'historyRestoreDefaultLayout'=> '单击<b>恢复默认布局</b>来恢复初始布局视图。<br><br><b>恢复默认布局</b>仅能恢复初始布局中的字段排列。如需更改字段标签，请单击每个字段旁边的“编辑”图标。',
        'Hidden' 	=> '目前用户尚无法在列表视图中查看<b>隐藏</b>字段。',
        'Default'	=> '出现在列表视图中的<b>默认</b>字段不是用户自定义的。'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> '单击<b>保存并部署</b>将会保存并应用所有更改。',
        'Hidden' 	=> '<b>隐藏</b>字段不出现在“查找”结果中。',
        'historyBtn'=> '单击<b>查看历史记录</b>从历史记录中来查看和恢复先前保存的布局。<br><br><b>查看历史记录</b>中的<b>恢复</b>可恢复以往保存的布局中的字段排列。如需更改字段标签，请单击每个字段旁边的“编辑”图标。',
        'historyRestoreDefaultLayout'=> '单击<b>恢复默认布局</b>来恢复初始布局视图。<br><br><b>恢复默认布局</b>仅能恢复初始布局中的字段排列。如需更改字段标签，请单击每个字段旁边的“编辑”图标。',
        'Default'	=> '<b>默认</b>字段出现在“查找”结果中。'
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
        'saveBtn'	=> '单击<b>保存</b>来保存自从上次保存以来您对布局所做的所有更改。<br><br>在您部署所做更改之前，这些更改将不会显示到模块中。',
        'historyBtn'=> '单击<b>查看历史记录</b>从历史记录中来查看和恢复先前保存的布局。<br><br><b>查看历史记录</b>中的<b>恢复</b>可恢复以往保存的布局中的字段排列。如需更改字段标签，请单击每个字段旁边的“编辑”图标。',
        'historyRestoreDefaultLayout'=> '单击<b>恢复默认布局</b>来恢复初始布局视图。<br><br><b>恢复默认布局</b>仅能恢复初始布局中的字段排列。如需更改字段标签，请单击每个字段旁边的“编辑”图标。',
        'publishBtn'=> '单击<b>保存并部署</b>来保存自从上次保存以来您对布局所做的所有更改并在模块中应用更改。<br><br>布局将会立即显示在模块中。',
        'toolbox'	=> '<b>工具箱</b>包含<b>回收站</b>、额外布局元素和一系列可添加到布局中的可用字段。<br/><br/>工具箱中的布局元素和字段可拖拽至布局中，布局中的布局元素和字段可拖拽到工具箱中。<br><br>布局元素是<b>面板</b>和<b>行</b>。在布局中添加一个新的行或面板可为字段提供额外的位置。<br/><br/>拖拽工具箱或布局中的任意字段到已占用的字段位置可交换两个字段的位置。<br/><br/><b>填充器</b>字段将在其所放置位置创建空白区。',
        'panels'	=> '部署对布局所做的更改之后，在<b>布局</b>区域可查看布局在模块中的显示视图。<br/><br/>您可以通过拖拽至所需位置来重置字段，行和面板。<br/><br/>如将各元素拖拽到工具箱中的<b>回收站</b>，则可删除元素，或者如需增加新元素和字段，可从<b>工具箱</b>中拖拽出来并放到布局中的所需位置。',
        'delete'	=> '将任何元素拖拽至此处，即可将其从布局中删除',
        'property'	=> '编辑为此字段显示的<b>标签</b>。 <br><br><b>宽度</b>可为 Sidecar 提供像素宽度值，并作为向后兼容模块的表格百分比。',
    ),
    'fieldsEditor'=>array(
        'default'	=> '模块的可用<b>字段</b>按字段名称列示在此处。<br><br>模块自定义字段显示在默认的可用字段上方。<br><br>单击<b>字段名称</b>来编辑字段。<br/><br/>单击 <b>添加字段</b>来新建字段。',
        'mbDefault'=>'模块的可用<b>字段</b>按字段名称列示在此处。<br><br>如需配置字段属性，请单击字段名称。<br><br>如需创建新字段，请单击<b>添加字段</b>。新字段创建之后，可通过单击字段名称来编辑标签和其他属性。 <br><br>部署模块之后，在模块生成器中创建的新字段将在工作室部署的模块中被视为标准字段。',
        'addField'	=> '为新字段选择<b>日期类型</b>。您选择的类型决定可在字段中输入的字符种类。 例如，整数数据型字段中只能输入整数。<br><br> 为字段提供一个 <b>名称</b>。名称必须由字母数字组成且不包含空格。 下划线是有效的。<br><br> <b>显示标签</b> 是在模块布局中标示字段的标签 。<b>系统标签</b> 用于指代码中的字段。<br><br> 根据为字段所选的数据类型，可为字段设置以下部分或全部属性：<br><br>当用户将鼠标悬停在字段上方时，将暂时显示<b>帮助文本</b>，用于向用户提示输入类型。<br><br> <b>批注文本</b>仅在工作室和/或模块生成器中可见，管理员可将其用来描述字段。<br><br><b>默认值</b>将出现在字段中。用户可以在字段中输入新值或使用默认值。<br><br>选择<b>批量更新</b>复选框以便能够为字段使用批量更新功能。<br><br><b>最大尺寸</b>值决定了字段中可输入的最大字符数量。<br><br> 选择<b>必需字段</b>复选框将字段设置为必需。必须为字段提供一个值以便能够保存包含字段的记录。<br><br> 选择<b>可报告</b> 复选框，以使字段能用于过滤器和用于显示记录中的数据。<br><br> 选择<b>审计</b>复选框，以便在变更日志中追踪字段的变更。<br><br>在<b>可导入</b>字段中选择一个选项来允许、禁止或者要求将字段导入到导入向导。<br><br>在<b>副本合并</b>字段中选择一个选项，来允许或禁止副本合并和查找副本功能。<br><br>其他属性设置适用于某些特定数据类型。',
        'editField' => '此字段的属性可以自定义。<br><br>单击<b>复制</b> 来创建一个具有相同属性的新字段。',
        'mbeditField' => '模板字段的<b>显示标签</b> 可以自定义 。字段的其他属性无法自定义。<br><br>单击<b>复制</b> 来创建一个具有相同属性的新字段。<br><br>如需移除一个模板字段以便其不会显示在模块中，请从适当的<b>布局</b>中移除字段。'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'如需导出工作室中创建的定制项，请创建可通过 <b>Module Loader</b> 上传到另外一个 Sugar 实例的文件包 。<br><br> 首先，提供一个<b>文件包名称</b>。您还可为文件包提供 <b>作者</b> 和 <b>说明</b> 信息。<br><br>选择包含您想要导出的定制项的模块。仅显示包含定制项的模块，供您选择。<br><br>然后单击 <b>导出</b> 来为文件包创建一个包含定制项的 .zip 文件。',
        'exportCustomBtn'=>'点击<b>导出</b>为包含想要导出的定制项的文件包创建 .zip 文件。',
        'name'=>'这是文件包的<b>名称</b>。这个名称将在安装期间显示。',
        'author'=>'这是安装期间显示的<b>作者</b>，作为创建文件包的实体的名称。 作者可以是个人或公司。',
        'description'=>'这是在安装期间显示的文件包的 <b>说明</b> 。',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> '欢迎来到<b>开发工具</b> 区域。 <br/><br/>使用本区域内的工具来创建和管理标准和自定义的模块和字段。',
        'studioBtn'	=> '使用 <b>工作室</b> 来自定义部署的模块。',
        'mbBtn'		=> '使用 <b>模块生成器</b> 来新建模块。',
        'sugarPortalBtn' => '使用 <b>Sugar 门户编辑器</b>来管理和自定义 Sugar 门户。',
        'dropDownEditorBtn' => '使用<b>下拉编辑器</b> 来为下拉字段新增和编辑所有下拉列表项 。',
        'appBtn' 	=> '应用模式是您可以自定义程序的不同属性的地方，例如在首页中显示多少 TPS 报表。',
        'backBtn'	=> '返回上一步。',
        'studioHelp'=> '使用 <b>工作室</b>来决定显示在模块中的信息及其显示方式。',
        'studioBCHelp' => '指示模块为向后兼容模块',
        'moduleBtn'	=> '单击以编辑此模块。',
        'moduleHelp'=> '您可以为模块自定义的组件显示在此处。<br><br>单击一个图标来选择需要编辑的组件。',
        'fieldsBtn'	=> '创建并自定义<b>字段</b>，以便在模块内储存信息。',
        'labelsBtn' => '编辑为字段和模块中其他标题显示的<b>标签</b> 。'	,
        'relationshipsBtn' => '为模块添加新的或查看现有<b>关系</b> 。' ,
        'layoutsBtn'=> '自定义模块<b>布局</b>。布局是包含字段的模块的不同视图。<br><br>您可以决定显示哪些模块以及它们在每个布局中的组织方式。',
        'subpanelBtn'=> '决定哪些字段显示在模块的<b>子面板</b> 中。',
        'portalBtn' =>'自定义显示在 <b>Sugar 门户</b>的模块<b>布局</b> 。',
        'layoutsHelp'=> '可以进行自定义的模块<b>布局</b>显示在此处。<br><br>布局显示字段和字段数据。<br><br>单击一个图标来选择需要编辑的布局。',
        'subpanelHelp'=> '模块中可以进行自定义的<b>子面板</b>显示在此处。<br><br>单击一个图标来选择需要编辑的模块。',
        'newPackage'=>'单击<b>新文件包</b>来创建一个新文件包。',
        'exportBtn' => '单击<b>导出自定义</b>来创建和下载包含在工作室中为指定模块制定的定制项的文件包。',
        'mbHelp'    => '使用 <b>模块生成器</b> 来创建文件包，其中包含基于标准或自定义对象的自定义模块。',
        'viewBtnEditView' => '自定义模块的<b>编辑视图</b>布局。<br><br>编辑视图是包含输入字段以捕获用户输入数据的表格。',
        'viewBtnDetailView' => '自定义模块的<b>细节视图</b> 布局。<br><br>细节视图显示了用户输入的字段数据。',
        'viewBtnDashlet' => '自定义模块的 <b>Sugar Dashlet</b>，包含 Sugar 仪表的列表视图和查找。<br><br> Sugar 仪表将用于添加至首页模块的页面中。',
        'viewBtnListView' => '自定义模块的 <b>列表视图</b>布局。<br><br>查找结果显示在列表视图中。',
        'searchBtn' => '自定义模块的 <b>查找</b> 布局。<br><br>决定什么字段可用于筛选在列表视图中显示的记录。',
        'viewBtnQuickCreate' =>  '自定义模块的 <b>快速创建</b> 布局。<br><br>快速创建表格显示在子面板和电子邮件模块中。',

        'searchHelp'=> '可自定义的<b>查找</b> 表格显示在此处。<br><br>查找表格包含用于筛选记录的字段。<br><br>单击图标来选择需要编辑的查找布局。',
        'dashletHelp' =>'可自定义的 <b>Sugar 仪表</b> 布局显示在此处。<br><br>Sugar 仪表可用于添加至主页模块的页面中。',
        'DashletListViewBtn' =>'<b>Sugar 仪表列表视图</b>基于Sugar 仪表查找过滤器显示记录。',
        'DashletSearchViewBtn' =>'<b>Sugar 仪表查找</b> 为 Sugar 仪表列表视图过滤记录。',
        'popupHelp' =>'可自定义的<b>弹窗</b>布局显示在此处。<br>',
        'PopupListViewBtn' => '当选择一个或多个与当前记录相关的记录时，<b>弹窗列表视图</b>布局可用于显示记录列表。',
        'PopupSearchViewBtn' => '<b>弹窗查找</b>布局允许用户查找需关联至当前记录的记录，在同一个窗口中显示在弹窗列表视图上方。为弹窗列表视图查看记录。当 Sidecar 模块使用查找 layoutâs 配置时， 遗留模块将此布局用于弹窗查找。',
        'BasicSearchBtn' => '自定义<b>基本查找</b>表格，位于模块查找区域的基本查找标签中。',
        'AdvancedSearchBtn' => '自定义<b>高级查找</b>表格，位于模块查找区域的高级查找标签中。',
        'portalHelp' => '管理和自定义<b>Sugar 门户</b>。',
        'SPUploadCSS' => '为 Sugar 门户上传一个<b>样式表</b>。',
        'SPSync' => '<b>同步</b>定制项至 Sugar 门户实例。',
        'Layouts' => '自定义 Sugar 门户模块的<b>布局</b> 。',
        'portalLayoutHelp' => 'Sugar 门户内的模块出现在此区域。<br><br>选择一个模块来编辑<b>布局</b>。',
        'relationshipsHelp' => '该模块与其他部署模块之间<b>关系</b>均显示在此处。<br><br>关系<b>名称</b>是系统为关系生成的名称。<br><br> <b>主要模块</b> 是拥有关系的模块。 例如，关系的所有属性（账户模块为主要模块）储存在账户数据库表格中。<br><br><b>类型</b>是指主要模块和<b>相关模块</b>之间的关系类型。<br><br>单击列标题对列进行排序。<br><br>单击关系表格中的一行来查看与关系相关的属性。<br><br>单击 <b>添加关系</b> 来创建一个新关系。<br><br>可在任何两个部署模块之间创建关系。',
        'relationshipHelp'=>'<b>关系</b> 可在模块和其他部署的模块之间创建。<br><br> 关系通过模块记录中的子面板和相关字段直观地表示。<br><br>为模块选择以下关系<b>类型</b>之一：<br><br> <b>一对一</b> - 两个模块的记录均会包含相关字段 。<br><br> <b>一对多</b> - 主要模块的记录将包含一个子面板， 且相关模块的记录将包含相关字段。<br><br> <b>多对多</b> - 两个模块的记录都会显示子面板。<br><br>为关系选择 <b>相关模块</b> 。<br><br>如果关系类型包含子面板，请为合适的模块选择子面板视图。<br><br> 单击<b>保存</b> 创建关系。',
        'convertLeadHelp' => "您可在此处将模块添加至转换布局屏幕，并修改现有模块的设置。<br/><br />
<b>排序：</b><br/>
联系人、账户和商业机会必须维持其次序。您可以通过在表中拖动模块的行来对任何模块进行重新排序。<br/><br/>
<b>依赖性：</b><br/>
如果包含商业机会，则必须在转换布局中包含或删除账户。<br/><br/>
<b>模块：</b> 模块名称。<br/><br/>	
<b>必需：</b> 在转换潜在客户之前必须创建或选择必需模块。<br/><br/>
<b>复制数据：</b>如选中此项，则来自潜在客户的字段将会被复制到新建记录中名字相同的字段中。<br/><br/>
<b>删除：</b> 从转换布局中删除此模块。<br/><br/>",
        'editDropDownBtn' => '编辑全局下拉列表',
        'addDropDownBtn' => '添加新的全局下拉列表',
    ),
    'fieldsHelp'=>array(
        'default'=>'模块中的<b>字段</b> 按字段名称列示在此处。<br><br>模块模板包含预先决定的一系列字段。<br><br>如需创建新字段，请单击 <b>添加字段</b>。<br><br>如需编辑字段，请单击 <b>字段名称</b>。<br/><br/>在模块部署之后，在模块生成器中创建的新字段以及模板字段，将被视为工作室中的标准字段。',
    ),
    'relationshipsHelp'=>array(
        'default'=>'在模块和其他模块之间创建的<b>关系</b> 将显示在此处。<br><br>关系 <b>名称</b> 是系统为关系生成的名称。<br><br> <b>主要模块</b>是拥有关系的模块。关系属性储存在属于主要模块的数据库表格中。<br><br><b>类型</b> 是指主要模块和<b>相关模块</b>之间的关系类型。 <br><br>单击列标题，对列进行排序。<br><br>单击关系表格中的行来查看和编辑关系的相关属性。<br><br>单击 <b>添加关系</b> 来新建关系。',
        'addrelbtn'=>'将鼠标悬停在“新增关系帮助…”上方',
        'addRelationship'=>'<b>关系</b> 可以在模块和其他自定义模块之间活在部署模块中被创建。<br><br> 关系通过模块记录中的子面板和相关字段直观地表示。<br><br>为模块选择以下关系<b>中</b> 之一：<br><br> <b>一对一</b> - 两个模块的记录均会包含相关字段 。<br><br> <b>一对多</b> - 主要模块的记录将包含一个子面板， 且相关模块的记录将包含相关字段。<br><br> <b>多对多</b> -两个模块的记录都会显示子面板。<br><br>为关系选择 <b>相关模块</b> 。 <br><br>如果关系类型包含子面板，请为合适的模块选择子面板视图。<br><br> 点击<b>保存</b> 创建关系。',
    ),
    'labelsHelp'=>array(
        'default'=> '模块中字段和其他标题的<b>标签</b> 可以更改。<br><br>在字段内点击以编辑标签，输入新的标签并单击 <b>保存</b>。<br><br>如果应用程序中安装了任何语言包，您可以选择标签使用的<b>语言</b> 。',
        'saveBtn'=>'单击 <b>保存</b> 来保存所有更改。',
        'publishBtn'=>'单击 <b>保存并部署</b> 来保存和应用所有更改。',
    ),
    'portalSync'=>array(
        'default' => '进入门户实例的 <b>Sugar 门户 URL</b>来更新，并单击 <b>开始</b>。<br><br>然后输入有效的 Sugar 用户名和密码，再单击 <b>开始同步</b>。<br><br> 对 Sugar 门户 <b>布局</b>制定的定制项以及 <b>样式表</b> 一起，如果一个被上传了，将会被转移到指定的门户实例中。',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => '您可以通过使用样式表来自定义 Sugar 门户的外观。<br><br>选择一个<b>样式表</b> 来上传。<br><br>在下次执行同步时，样式表会在 Sugar 门户中应用。',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'如需开始项目，请单击<b>新文件包</b>来新建用于托管自定义模块的文件包。 <br/><br/>每个文件包可以包含一个或多个模块。<br/><br/>例如，您可能想要创建一个文件包，其中包含与标准账户模块相关的自定义模块。或者，您可能想要创建一个文件包，其中包含多个新模块，联合发挥项目作用且相互关联，并与应用程序中现有的其他模块相关联。',
            'somepackages'=>'<b>文件包</b>作为自定义模块的容器，其所有部分都是项目的一部分。文件包可以包含一个或多个自定义 <b>模块</b> ，这些模块相互关联，或与应用程序中现有的其他模块相关联。<br/><br/>在为项目创建文件包之后，您可以立即为文件包创建模块，或稍后返回模块生成器来完成此项目。<br><br>当项目完成时，您可以 <b>部署</b>文件包，在应用程序内安装自定义模块。',
            'afterSave'=>'您的新文件包应至少包含一个模块。您可以为文件包创建一个或多个自定义模块。<br/><br/>单击<b>新模块</b>来为次文件包创建自定义模块。<br/><br/>在创建至少一个模块之后，您可以发布或部署文件包，使其可用于您的实例和/或其他用户的实例。<br/><br/>如需在您 Sugar 实例中一步部署文件包，请单击 <b>部署</b>。<br><br>单击 <b>发布</b>，将文件包保存为 zip 文件。 .zip 文件保存到您的系统之后，使用<b>模块安装器</b>在您 Sugar 实例中上传和安装文件包。 <br/><br/>您可以将此文件分发给其他用户，以便在他们的 Sugar 实例中上传和安装。',
            'create'=>'<b>文件包</b> 作为包含自定义模块的容器，其所有都是项目的一部分。文件包可以包含一个或多个自定义<b>模块</b>，这些模块相互关联，或与应用程序中现有的其他模块相关联。<br/><br/>在为项目创建了文件包之后，您可以立即为文件包创建模块，或稍后返回模块生成器来完成此项目。',
            ),
    'main'=>array(
        'welcome'=>'使用 <b>开发工具</b> 来创建和管理标准和自定义模块和字段。 <br/><br/>如需管理应用程序中的模块，请单击 <b>工作室</b>。 <br/><br/>如需创建自定义模块，请单击 <b>模块生成器</b>。',
        'studioWelcome'=>'所有现在已安装的模块（包括标准的和模块加载对象）均可在工作室内进行自定义。'
    ),
    'module'=>array(
        'somemodules'=>"由于当前文件包包含至少一个模块，您可以在 Sugar 实例中<b>部署</b> 文件包中的模块，或者使用<b>模块加载器</b> <b>公布</b>即将安装到当前实例或其他实例中的文件包。<br/><br/>如需在您的 Sugar 实例中直接安装文件包，请单击<b>部署</b>。<br><br>如需为文件包创建 .zip 文件，该文件包可使用<b>模块加载器</b>加载和安装在当前 Sugar 实例和其他实例中，请单击 <b>发布</b>。<br/><br/> 您可以分阶段为文件包构建模块，当您准备好之后执行发布或部署。<br/><br/>在发布或部署文件包之后，您可以更改文件包的属性并进一步自定义模块。然后再次发布或再次部署文件包来应用更改。" ,
        'editView'=> '您可以在此处编辑现有字段。您可以在左侧面板中删除任何现有字段或添加可用字段。',
        'create'=>'当选择您想要创建的模块<b>类型</b>时，请牢记您希望模块中包含的字段类型。<br/><br/>每个模块模板包含一系列属于相应模块类型的字段，使用标题做相关描述。<br/><br/><b>基本</b> - 提供标准模块中的基本字段，例如姓名，负责人，团队，创建日期和说明字段。<br/><br/> <b>公司</b> - 提供特定于组织的字段，例如公司名称，行业，账单地址。使用此模板来创建与标准模块类似的模块。<br/><br/> <b>个人</b> - 提供特定于个人的字段，例如称谓，职称，姓名，地址和电话号码。使用此模板来创建与标准联系人和潜在客户模块类似的模块。<br/><br/><b>发行</b> - 提供特定于客户反馈和缺陷的字段，例如数量，状态，优先次序和说明。使用此模板来创建与标准客户反馈和缺陷跟踪模块类似的模块。<br/><br/>注意：创建模块之后，您可以编辑模板提供的字段的标签，也可以创建自定义字段并添加至模块布局中。',
        'afterSave'=>'通过编辑和创建字段、与其他模块创建关系和在布局内排列字段来自定义满足您需要的模块。<br/><br/>如需在模块内查看模板字段和管理自定义字段，请单击<b>查看字段</b>。<br/><br/>如需创建和管理模块和其他模块之间的关系，无论是应用程序中现有的或同一个文件包中的其他自定义模块，请单击 <b>查看关系</b>。<br/><br/>如需编辑模块布局，请单击 <b>查看布局</b>。您可以更改模块的细节视图、编辑视图和列表视图布局，与对工作室内应用程序中的模块执行的操作相同。<br/><br/> 如需创建与当前模块属性相同的模块，请单击<b>复制</b>。您可以进一步自定义新模块。',
        'viewfields'=>'模块中的字段可以自定义以满足您的需要。<br/><br/>您无法删除标准字段，但是您可以从布局页面中适当的布局中移除它们。<br/><br/>您可以快速创建与现有字段属性相同是新字段，请在<b>属性</b> 表格中单击<b>复制</b> 。输入任何新属性，然后点击 <b>保存</b>。<br/><br/>建议您在发布和安装包含自定义模块的文件包之前，为标准字段和自定义字段设置所有属性。',
        'viewrelationships'=>'您可以在当前模块与文件包中其他模块之间，和/或在当前模块与已安装在应用程序中的模块之间创建多对多关系。<br><br> 如需创建一对多和一对一关系，请为模块创建 <b>关联</b> 和<b>弹性关联</b>字段。',
        'viewlayouts'=>'您可以控制<b>编辑视图</b>中用于捕获数据的字段。 您也可以控制显示在<b>细节视图</b>中的数据。视图不需要匹配。 <br/><br/>在子面板中点击<b>创建</b> 时，快速创建表格将会显示。默认情况下，<b>快速创建</b> 表格布局与默认 <b>编辑视图</b> 布局相同。您可以自定义快速创建表格使它比编辑视图布局包含更少和/或不同的字段。<br><br>您可以使用布局自定义和<b>角色管理</b>来决定模块安全性。<br><br>',
        'existingModule' =>'在创建和自定义此模块之后，您可以创建其他模块或返回文件包来<b>公布</b> 或 <b>部署</b> 文件包。<br><br>如需创建其他模块，请单击<b>复制</b> 来创建与当前模块属性相同的模块，或返回文件包并点击 <b>新模块</b>。<br><br> 如果您准备好<b>发布</b> 或 <b>部署</b> 包含此模块的文件包，请返回文件包来执行这些功能。您可以发布或部署包含至少一个模块的文件包。',
        'labels'=> '标准字段和自定义字段的标签可更改。 更改字段标签不会影响储存在字段中的数据。',
    ),
    'listViewEditor'=>array(
        'modify'	=> '左侧显示三列。  "默认"列包含默认情况下显示在列表视图中的字段，"可用" 列包含用户可选择用于创建自定义列表视图的字段，"隐藏" 列包含了管理员可为添加到默认或可用列，供用户使用但目前被禁用的字段。',
        'savebtn'	=> '单击<b>保存</b> 将保存并应用所有更改。',
        'Hidden' 	=> '隐藏字段是列表视图中用户暂时不同使用的字段。',
        'Available' => '可用字段不是默认显示的字段，但用户可以启用的字段。',
        'Default'	=> '默认字段为没有创建自定义列表视图设置的用户而显示。'
    ),

    'searchViewEditor'=>array(
        'modify'	=> '左侧显示两列。 "默认"列包含将会显示在查找视图中的字段， "隐藏"列包含您作为管理员可添加到视图的字段。',
        'savebtn'	=> '单击 <b>保存并部署</b>会保存并应用所有更改。',
        'Hidden' 	=> '隐藏字段是不会出现在查找视图中的字段。',
        'Default'	=> '默认字段将显示在查找视图中。'
    ),
    'layoutEditor'=>array(
        'default'	=> '左侧显示两列。 标为“当前布局”或“布局预览”的右列，是您修改模块布局的地方。标为“工具箱”的左列，包含编辑布局时可用的元素和工具。<br/><br/>如果布局区域标为“当前布局”，则表明您正在处理的是当前被模块用于显示的布局的副本。<br/><br/>如果标为“布局预览”，则表明您正在处理的是之前通过点击“保存”按钮而创建的副本，很可能与模块用户查看的版本不同。',
        'saveBtn'	=> '点击此按钮保存布局，以便保存您的修改。返回此模块时，您将从这个已经被修改了的布局开始。 在点击保存和发布按钮之前，模块的使用者不会看到您的布局。',
        'publishBtn'=> '点击此按钮来部署布局。这意味着模块用户将立即看到此布局。',
        'toolbox'	=> '工具箱包含了编辑布局时各种有用的功能，包括一个垃圾区域，一套额外的元素和一套可用字段。这些都可以拖拽到布局中。',
        'panels'	=> '这个区域显示了部署时，模块用户将看到的布局外观。<br/><br/>您可以通过拖拽来重新排列元素，例如字段，行和面板；通过拖拽到工具箱中的垃圾箱来删除元素， 或通过从工具箱拖到布局中的理想位置来新增元素。'
    ),
    'dropdownEditor'=>array(
        'default'	=> '左侧显示两列。 标为“当前布局”或“布局预览”的右列，是您修改模块布局的地方。标为“工具箱”的左列，包含编辑布局时可用的元素和工具。<br/><br/>如果布局区域标为“当前布局”，则表明您正在处理的是当前被模块用于显示的布局的副本。<br/><br/>如果标为“布局预览”，则表明您正在处理的是之前通过点击“保存”按钮而创建的副本，很可能与模块用户查看的版本不同。',
        'dropdownaddbtn'=> '点击此按钮向下拉列表中添加新项目。',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'在这个实例中的工作室中创建的定制项可打包并部署至另一个案例。<br><br>提供<b>文件包名称</b>。您可以为文件包提供<b>作者</b> 和 <b>描述</b> 信息。<br><br>选择包含要导出的定制项的模块。（仅包含可供您选择的定制项的模块。）<br><br>点击 <b>导出</b> ，为包含定制项的文件包创建 .zip 文件。 .zip 文件可以通过<b>模块安装器</b>上传到另一个实例中。',
        'exportCustomBtn'=>'点击 <b>导出</b>为包含想要导出的定制项的文件包创建 .zip 文件 。',
        'name'=>'上传文件包以在工作室中安装之后，文件包的<b>名称</b>将显示在模块安装器中。',
        'author'=>'<b>作者</b>是创建文件包的实体的名称。 作者可以是个人或公司。<br><br>上传文件包以在工作室中安装之后，作者将显示在模块安装器中。',
        'description'=>'上传文件包以在工作室中安装之后，文件包<b>说明</b> 将显示在模块安装器中。',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> '欢迎来到<b>开发工具</b1> 区域。 <br/><br/>使用此区域中的工具来创建和管理标准和自定义模块和字段。',
        'studioBtn'	=> '使用 <b>工作室</b>更改字段排列、选择可用字段和创建自定义数据字段，以对已安装的模块进行自定义。',
        'mbBtn'		=> '使用 <b>模块生成器</b> 来新建模块。',
        'appBtn' 	=> '使用应用模式来自定义程序的不同属性，例如首页中显示多少个 TPS 报表',
        'backBtn'	=> '返回上一步。',
        'studioHelp'=> '使用 <b>工作室</b> 来自定义已安装的模块。',
        'moduleBtn'	=> '单击以编辑此模块。',
        'moduleHelp'=> '选择您想要编辑的模块组件',
        'fieldsBtn'	=> '通过控制模块中的 <b>字段</b>来编辑模块中储存的信息。<br/><br/>您可以在此处编辑和创建自定义字段。',
        'layoutsBtn'=> '自定义编辑，细节，列表和查找视图的<b>布局</b> 。',
        'subpanelBtn'=> '编辑模块子面板中显示的信息。',
        'layoutsHelp'=> '选择一个 <b>布局来编辑</b>。<br/<br/>如需更改包含数据输入字段的布局，请点击<b>编辑视图</b>。<br/><br/>如需更改显示在编辑视图中输入字段的数据的布局， 请点击<b>细节视图</b>。<br/><br/>如需更改显示在默认列表中的列，情点击 <b>列表视图</b>。<br/><br/> 如需更改基本和高级查找表格布局，请点击<b>查找</b>。',
        'subpanelHelp'=> '选择一个<b>子面板</b> 来编辑。',
        'searchHelp' => '选择一个<b>查找</b> 布局来编辑。',
        'labelsBtn'	=> '编辑<b>标签</b>，显示该模块的数值。',
        'newPackage'=>'单击<b>新文件包</b>来创建一个新文件包。',
        'mbHelp'    => '<b>欢迎来到模块生成器。</b><br/><br/>用 <b>模块生成器</b> 来创建文件包，其中包含基于标准或自定义对象的自定义模块。<br/><br/>如需开始 ，请点击<b>新文件包</b> 来创建新文件包或者选择一个文件包来编辑。<br/><br/> <b>文件包</b> 作为自定义模块的容器，其所有部分都是项目的一部分。文件包可以包含一个或多个自定义模块，这些模块相互关联，或与应用程序中现有的其他模块相关联。 <br/><br/>例如：您可能想要创建一个文件包，其中包含与标准账户模块相关的自定义模块。或者，您可能想要创建一个文件包，其中包含多个新模块，联合发挥项目作用且相互关联，并与应用程序中现有的其他模块相关联。',
        'exportBtn' => '点击 <b>导出定制项</b> 来创建一个包含在工作室中为指定模块创建的定制项的文件包。',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'下拉列表编辑器',

//ASSISTANT
'LBL_AS_SHOW' => '今后显示助手。',
'LBL_AS_IGNORE' => '今后忽略助手。',
'LBL_AS_SAYS' => '助手说：',

//STUDIO2
'LBL_MODULEBUILDER'=>'模块生成器',
'LBL_STUDIO' => '工作室',
'LBL_DROPDOWNEDITOR' => '下拉列表编辑器',
'LBL_EDIT_DROPDOWN'=>'编辑下拉列表',
'LBL_DEVELOPER_TOOLS' => '开发工具',
'LBL_SUGARPORTAL' => 'Sugar 门户编辑器',
'LBL_SYNCPORTAL' => '同步门户',
'LBL_PACKAGE_LIST' => '文件包列表',
'LBL_HOME' => '首页',
'LBL_NONE'=>'-无-',
'LBL_DEPLOYE_COMPLETE'=>'部署完成',
'LBL_DEPLOY_FAILED'   =>'部署时发生错误，您的文件包可能没有正确安装。',
'LBL_ADD_FIELDS'=>'添加自定义字段',
'LBL_AVAILABLE_SUBPANELS'=>'可用子面板',
'LBL_ADVANCED'=>'高级',
'LBL_ADVANCED_SEARCH'=>'高级查找',
'LBL_BASIC'=>'基本',
'LBL_BASIC_SEARCH'=>'基本查找',
'LBL_CURRENT_LAYOUT'=>'布局',
'LBL_CURRENCY' => '货币',
'LBL_CUSTOM' => '自定义',
'LBL_DASHLET'=>'Sugar 仪表',
'LBL_DASHLETLISTVIEW'=>'Sugar 仪表列表视图',
'LBL_DASHLETSEARCH'=>'Sugar 仪表查找',
'LBL_POPUP'=>'弹窗视图',
'LBL_POPUPLIST'=>'弹窗列表视图',
'LBL_POPUPLISTVIEW'=>'弹窗列表视图',
'LBL_POPUPSEARCH'=>'弹窗查找',
'LBL_DASHLETSEARCHVIEW'=>'Sugar 仪表查找',
'LBL_DISPLAY_HTML'=>'显示 HTML 代码',
'LBL_DETAILVIEW'=>'细节视图',
'LBL_DROP_HERE' => '[拽到这里]',
'LBL_EDIT'=>'编辑',
'LBL_EDIT_LAYOUT'=>'编辑部局',
'LBL_EDIT_ROWS'=>'编辑行',
'LBL_EDIT_COLUMNS'=>'编辑列',
'LBL_EDIT_LABELS'=>'编辑标签',
'LBL_EDIT_PORTAL'=>'编辑门户',
'LBL_EDIT_FIELDS'=>'编辑字段',
'LBL_EDITVIEW'=>'编辑视图',
'LBL_FILTER_SEARCH' => "查找",
'LBL_FILLER'=>'（填充器）',
'LBL_FIELDS'=>'字段',
'LBL_FAILED_TO_SAVE' => '保存失败',
'LBL_FAILED_PUBLISHED' => '发布失败',
'LBL_HOMEPAGE_PREFIX' => '我的',
'LBL_LAYOUT_PREVIEW'=>'预览布局',
'LBL_LAYOUTS'=>'布局',
'LBL_LISTVIEW'=>'列表视图',
'LBL_RECORDVIEW'=>'记录视图',
'LBL_MODULE_TITLE' => '工作室',
'LBL_NEW_PACKAGE' => '新文件包',
'LBL_NEW_PANEL'=>'新面板',
'LBL_NEW_ROW'=>'新行',
'LBL_PACKAGE_DELETED'=>'已删除的文件包',
'LBL_PUBLISHING' => '发布中 ...',
'LBL_PUBLISHED' => '已发布',
'LBL_SELECT_FILE'=> '选择文件',
'LBL_SAVE_LAYOUT'=> '保存布局',
'LBL_SELECT_A_SUBPANEL' => '选择一个子面板',
'LBL_SELECT_SUBPANEL' => '选择一个子面板',
'LBL_SUBPANELS' => '子面板',
'LBL_SUBPANEL' => '子面板',
'LBL_SUBPANEL_TITLE' => '职称：',
'LBL_SEARCH_FORMS' => '查找',
'LBL_STAGING_AREA' => '准备区 (在此处拖拉项目)',
'LBL_SUGAR_FIELDS_STAGE' => 'Sugar 字段（点击项目添加到准备区）',
'LBL_SUGAR_BIN_STAGE' => 'Sugar 指令 (点击项目添加到准备区)',
'LBL_TOOLBOX' => '工具箱',
'LBL_VIEW_SUGAR_FIELDS' => '查看Sugar 字段',
'LBL_VIEW_SUGAR_BIN' => '查看Sugar Bin',
'LBL_QUICKCREATE' => '快速创建',
'LBL_EDIT_DROPDOWNS' => '编辑全局下拉列表',
'LBL_ADD_DROPDOWN' => '添加新的全局下拉列表',
'LBL_BLANK' => '-空白-',
'LBL_TAB_ORDER' => '标签顺序',
'LBL_TAB_PANELS' => '启用标签',
'LBL_TAB_PANELS_HELP' => '启用标签后，对每个部分使用“类型”下拉列表框<br />来定义其显示方式（标签或面板）',
'LBL_TABDEF_TYPE' => '显示类型',
'LBL_TABDEF_TYPE_HELP' => '选择这个区域的显示方式。仅当您在此视图中启用标签之后，本选项才有效。',
'LBL_TABDEF_TYPE_OPTION_TAB' => '标签',
'LBL_TABDEF_TYPE_OPTION_PANEL' => '面板',
'LBL_TABDEF_TYPE_OPTION_HELP' => '选择面板将此面板显示在视图中。选择标签将此面板限制在视图内的单独标签中。为面板指定标签时，被设置为以面板形式显示的后续面板将显示在标签内。<br/>对于选中的标签，将对其下一个面板启用新的标签。如果为第一个面板之下的面板选择了标签，则第一个面板将必须成为标签。',
'LBL_TABDEF_COLLAPSE' => '折叠',
'LBL_TABDEF_COLLAPSE_HELP' => '选择以将此面板的默认状态设置折叠。',
'LBL_DROPDOWN_TITLE_NAME' => '名称',
'LBL_DROPDOWN_LANGUAGE' => '语言',
'LBL_DROPDOWN_ITEMS' => '列表项目',
'LBL_DROPDOWN_ITEM_NAME' => '项目名称',
'LBL_DROPDOWN_ITEM_LABEL' => '显示标签',
'LBL_SYNC_TO_DETAILVIEW' => '同步到细节视图',
'LBL_SYNC_TO_DETAILVIEW_HELP' => '选择此选项将此编辑视图布局同步到相应的细节视图布局。在编辑视图中点击保存并部署之后，编辑视图中的字段和字段位置<br>将自动同步并保存到细节视图中。<br>细节视图中将无法对布局进行更改。',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => '此细节视图与相应的编辑视图同步。<br> 细节视图中字段和字段位置反映了编辑视图中字段和字段的位置。<br> 在此页中对细节视图所做的更改将无法保存或部署。请在编辑视图中做更改或取消同步。',
'LBL_COPY_FROM' => '复制源：',
'LBL_COPY_FROM_EDITVIEW' => '从编辑视图复制',
'LBL_DROPDOWN_BLANK_WARNING' => '项目名称和显示标签都需要值。 如需增加一个空白项，请点击添加但不为项目名称和显示标签输入任何值。',
'LBL_DROPDOWN_KEY_EXISTS' => '密匙已经存在于列表中',
'LBL_DROPDOWN_LIST_EMPTY' => '此列表必须至少包含一个启用项目',
'LBL_NO_SAVE_ACTION' => '找不到此视图的保存操作。',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation：文件格式错误',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '**表示组合字段。组合字段由多个字段集成。例如，“地址”是一个组合字段，其中包含“街道”、“城市”、“邮编”、“省市”和“国家/地区”。<br><br>双击打开一个组合字段查看其中包含的字段。',
'LBL_COMBO_FIELD_CONTAINS' => '包括：',

'LBL_WIRELESSLAYOUTS'=>'手机布局',
'LBL_WIRELESSEDITVIEW'=>'手机编辑视图',
'LBL_WIRELESSDETAILVIEW'=>'手机细节视图',
'LBL_WIRELESSLISTVIEW'=>'手机列表视图',
'LBL_WIRELESSSEARCH'=>'手机查找',

'LBL_BTN_ADD_DEPENDENCY'=>'添加依赖性',
'LBL_BTN_EDIT_FORMULA'=>'编辑公式',
'LBL_DEPENDENCY' => '依赖性',
'LBL_DEPENDANT' => '附属',
'LBL_CALCULATED' => '计算值',
'LBL_READ_ONLY' => '只读',
'LBL_FORMULA_BUILDER' => '公式生成器',
'LBL_FORMULA_INVALID' => '无效公式',
'LBL_FORMULA_TYPE' => '公式的类型必须为',
'LBL_NO_FIELDS' => '找不到字段',
'LBL_NO_FUNCS' => '找不到相关函数',
'LBL_SEARCH_FUNCS' => '查找函数...',
'LBL_SEARCH_FIELDS' => '查找字段...',
'LBL_FORMULA' => '公式',
'LBL_DYNAMIC_VALUES_CHECKBOX' => '附属',
'LBL_DEPENDENT_DROPDOWN_HELP' => '当父选项被选中时，从左侧附属下拉表的可选选项中将选项拖动到右边以使选项生效。如果当父选项被选中时，其下没有项目，则附属下拉表无法显示。',
'LBL_AVAILABLE_OPTIONS' => '可用选项',
'LBL_PARENT_DROPDOWN' => '父级下拉列表',
'LBL_VISIBILITY_EDITOR' => '能见度编辑器',
'LBL_ROLLUP' => '汇总',
'LBL_RELATED_FIELD' => '相关字段',
'LBL_CONFIG_PORTAL_URL'=>'自定义标识图像的 URL。推荐的标识尺寸为 163 × 18 像素。',
'LBL_PORTAL_ROLE_DESC' => '不要删除此角色。客户自助门户角色是一个系统在 Sugar 门户激活过程中生成的角色。用此角色中的访问控制来启用和/或禁用 Sugar 门户中的错误、客户反馈或知识库模块。请不要修改此角色的任何其他访问控制，以避免未知的与不可预测的系统行为。如不慎删除此角色，请通过禁用和启用 Sugar 门户来重新创建。',

//RELATIONSHIPS
'LBL_MODULE' => '模块',
'LBL_LHS_MODULE'=>'主模块',
'LBL_CUSTOM_RELATIONSHIPS' => '* 工作室中创建的关系',
'LBL_RELATIONSHIPS'=>'关系',
'LBL_RELATIONSHIP_EDIT' => '编辑关系',
'LBL_REL_NAME' => '姓名',
'LBL_REL_LABEL' => '标签',
'LBL_REL_TYPE' => '类型',
'LBL_RHS_MODULE'=>'关联模块',
'LBL_NO_RELS' => '无关系',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'可选条件' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'列',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'值',
'LBL_SUBPANEL_FROM'=>'子面板来自',
'LBL_RELATIONSHIP_ONLY'=>'没有创建该关系的可见元素，因为这两个模块之间已存在可见关系。',
'LBL_ONETOONE' => '一对一',
'LBL_ONETOMANY' => '一对多',
'LBL_MANYTOONE' => '多对一',
'LBL_MANYTOMANY' => '多对多',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => '选择一项功能或组件。',
'LBL_QUESTION_MODULE1' => '选择一个模块。',
'LBL_QUESTION_EDIT' => '选择一个模块来编辑。',
'LBL_QUESTION_LAYOUT' => '选择一个布局来编辑。',
'LBL_QUESTION_SUBPANEL' => '选择一个子面板来编辑。',
'LBL_QUESTION_SEARCH' => '选择一个查找布局来编辑',
'LBL_QUESTION_MODULE' => '选择一个模块组件来编辑。',
'LBL_QUESTION_PACKAGE' => '选择一个文件包来编辑，或创建一个新文件包。',
'LBL_QUESTION_EDITOR' => '选择一工具。',
'LBL_QUESTION_DROPDOWN' => '选择一个下拉列表来编辑，或创建一个新下拉列表。',
'LBL_QUESTION_DASHLET' => '选择一个 Dashlet 布局来编辑。',
'LBL_QUESTION_POPUP' => '选择一个弹窗布局来编辑。',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'相关',
'LBL_NAME'=>'名称',
'LBL_LABELS'=>'标签',
'LBL_MASS_UPDATE'=>'批量更新',
'LBL_AUDITED'=>'审计',
'LBL_CUSTOM_MODULE'=>'模块',
'LBL_DEFAULT_VALUE'=>'默认值',
'LBL_REQUIRED'=>'需要',
'LBL_DATA_TYPE'=>'类型',
'LBL_HCUSTOM'=>'自定义',
'LBL_HDEFAULT'=>'默认',
'LBL_LANGUAGE'=>'语言：',
'LBL_CUSTOM_FIELDS' => '* 工作室中创建的字段',

//SECTION
'LBL_SECTION_EDLABELS' => '编辑标签',
'LBL_SECTION_PACKAGES' => '文件包',
'LBL_SECTION_PACKAGE' => '文件包',
'LBL_SECTION_MODULES' => '模块',
'LBL_SECTION_PORTAL' => '门户',
'LBL_SECTION_DROPDOWNS' => '下拉列表',
'LBL_SECTION_PROPERTIES' => '属性',
'LBL_SECTION_DROPDOWNED' => '下拉列表编辑器',
'LBL_SECTION_HELP' => '帮助',
'LBL_SECTION_ACTION' => '动作',
'LBL_SECTION_MAIN' => '主要',
'LBL_SECTION_EDPANELLABEL' => '编辑面板标签',
'LBL_SECTION_FIELDEDITOR' => '编辑字段',
'LBL_SECTION_DEPLOY' => '部署',
'LBL_SECTION_MODULE' => '模块',
'LBL_SECTION_VISIBILITY_EDITOR'=>'编辑可见度',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'默认',
'LBL_HIDDEN'=>'隐藏',
'LBL_AVAILABLE'=>'可用',
'LBL_LISTVIEW_DESCRIPTION'=>'下面显示了三列。 <b>默认</b> 列包含的字段默认显示在列表视图中。 <b>附加</b> 列包含用户可以选择用来创建客户视图的字段。<b>可用</b> 行显示您作为管理员可添加到默认或附加行，以供用户使用的字段。',
'LBL_LISTVIEW_EDIT'=>'列表视图编辑器',

//Manager Backups History
'LBL_MB_PREVIEW'=>'预览',
'LBL_MB_RESTORE'=>'还原',
'LBL_MB_DELETE'=>'删除',
'LBL_MB_COMPARE'=>'比较',
'LBL_MB_DEFAULT_LAYOUT'=>'默认布局',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'添加',
'LBL_BTN_SAVE'=>'保存',
'LBL_BTN_SAVE_CHANGES'=>'保存更改',
'LBL_BTN_DONT_SAVE'=>'放弃更改',
'LBL_BTN_CANCEL'=>'取消',
'LBL_BTN_CLOSE'=>'关闭',
'LBL_BTN_SAVEPUBLISH'=>'保存与部署',
'LBL_BTN_NEXT'=>'下一条',
'LBL_BTN_BACK'=>'后退',
'LBL_BTN_CLONE'=>'复制',
'LBL_BTN_COPY' => '复制',
'LBL_BTN_COPY_FROM' => '复制自…',
'LBL_BTN_ADDCOLS'=>'添加列',
'LBL_BTN_ADDROWS'=>'添加行',
'LBL_BTN_ADDFIELD'=>'添加字段',
'LBL_BTN_ADDDROPDOWN'=>'添加下拉列表',
'LBL_BTN_SORT_ASCENDING'=>'升序排序',
'LBL_BTN_SORT_DESCENDING'=>'降序排序',
'LBL_BTN_EDLABELS'=>'编辑标签',
'LBL_BTN_UNDO'=>'撤销',
'LBL_BTN_REDO'=>'重做',
'LBL_BTN_ADDCUSTOMFIELD'=>'添加自定义字段',
'LBL_BTN_EXPORT'=>'导出定制项',
'LBL_BTN_DUPLICATE'=>'复制',
'LBL_BTN_PUBLISH'=>'发布',
'LBL_BTN_DEPLOY'=>'部署',
'LBL_BTN_EXP'=>'导出',
'LBL_BTN_DELETE'=>'删除',
'LBL_BTN_VIEW_LAYOUTS'=>'查看布局',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'查看手机布局',
'LBL_BTN_VIEW_FIELDS'=>'查看字段',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'查看关系',
'LBL_BTN_ADD_RELATIONSHIP'=>'添加关系',
'LBL_BTN_RENAME_MODULE' => '更改模块名称',
'LBL_BTN_INSERT'=>'安装',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> '错误：字段已经存在',
'ERROR_INVALID_KEY_VALUE'=> "错误：无效关键值： [']",
'ERROR_NO_HISTORY' => '没有找到历史文件',
'ERROR_MINIMUM_FIELDS' => '布局中必须至少包含一个字段',
'ERROR_GENERIC_TITLE' => '发生了一个错误',
'ERROR_REQUIRED_FIELDS' => '您确定要继续吗？布局中缺少以下必需字段：',
'ERROR_ARE_YOU_SURE' => '您确定要继续么？',

'ERROR_CALCULATED_MOBILE_FIELDS' => '以下字段中含有已计算的值，这些值将不会在 SugarCRM 手机编辑视图中进行实时重算：',
'ERROR_CALCULATED_PORTAL_FIELDS' => '以下字段中含有已计算的值，这些值将不会在 SugarCRM 门户编辑视图中进行实时重算：',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => '以下模块已禁用：',
    'LBL_PORTAL_ENABLE_MODULES' => '如果您希望在门户内启用它们，请在<a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">此处</a>启用。',
    'LBL_PORTAL_CONFIGURE' => '配置门户',
    'LBL_PORTAL_THEME' => '主题门户',
    'LBL_PORTAL_ENABLE' => '启用',
    'LBL_PORTAL_SITE_URL' => '您的门户站点可访问：',
    'LBL_PORTAL_APP_NAME' => '应用名称',
    'LBL_PORTAL_LOGO_URL' => '标识 URL',
    'LBL_PORTAL_LIST_NUMBER' => '列表中显示的记录数量',
    'LBL_PORTAL_DETAIL_NUMBER' => '细节视图中显示的字段数量',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => '全局查找时显示的结果数量',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => '默认分配为新门户登记',

'LBL_PORTAL'=>'门户',
'LBL_PORTAL_LAYOUTS'=>'门户布局',
'LBL_SYNCP_WELCOME'=>'请输入您想要更新的门户实例的 URL。',
'LBL_SP_UPLOADSTYLE'=>'选择一个样式表并从您的计算机上传。<br>下次实施同步时，样式表将在 Sugar 门户中应用。',
'LBL_SP_UPLOADED'=> '上传',
'ERROR_SP_UPLOADED'=>'请确定您将上传一个 CSS 样式表。',
'LBL_SP_PREVIEW'=>'此处是使用样式表对 Sugar 门户外观进行预览的视图。',
'LBL_PORTALSITE'=>'Sugar 门户的 URL：',
'LBL_PORTAL_GO'=>'开始',
'LBL_UP_STYLE_SHEET'=>'上传样式表',
'LBL_QUESTION_SUGAR_PORTAL' => '选择一个 Sugar 门户布局来编辑。',
'LBL_QUESTION_PORTAL' => '选择一个门户布局来编辑。',
'LBL_SUGAR_PORTAL'=>'Sugar 门户编辑器',
'LBL_USER_SELECT' => '-- 选择 --',

//PORTAL PREVIEW
'LBL_CASES'=>'客户反馈',
'LBL_NEWSLETTERS'=>'新闻邮件',
'LBL_BUG_TRACKER'=>'缺陷跟踪',
'LBL_MY_ACCOUNT'=>'我的帐户',
'LBL_LOGOUT'=>'版面布局',
'LBL_CREATE_NEW'=>'创建新的',
'LBL_LOW'=>'低',
'LBL_MEDIUM'=>'中',
'LBL_HIGH'=>'高',
'LBL_NUMBER'=>'数量：',
'LBL_PRIORITY'=>'优先级：',
'LBL_SUBJECT'=>'主题',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'文件包名称：',
'LBL_MODULE_NAME'=>'模块名称：',
'LBL_MODULE_NAME_SINGULAR' => '单一模块名称：',
'LBL_AUTHOR'=>'作者：',
'LBL_DESCRIPTION'=>'说明:',
'LBL_KEY'=>'关键值：',
'LBL_ADD_README'=>'自述文件',
'LBL_MODULES'=>'模块：',
'LBL_LAST_MODIFIED'=>'最新修改：',
'LBL_NEW_MODULE'=>'新模块',
'LBL_LABEL'=>'标签:',
'LBL_LABEL_TITLE'=>'标签',
'LBL_SINGULAR_LABEL' => '单数标签',
'LBL_WIDTH'=>'宽度',
'LBL_PACKAGE'=>'文件包：',
'LBL_TYPE'=>'类型:',
'LBL_TEAM_SECURITY'=>'团队安全',
'LBL_ASSIGNABLE'=>'可分配',
'LBL_PERSON'=>'个人',
'LBL_COMPANY'=>'公司',
'LBL_ISSUE'=>'发行',
'LBL_SALE'=>'销售',
'LBL_FILE'=>'文件',
'LBL_NAV_TAB'=>'导航标签',
'LBL_CREATE'=>'创建',
'LBL_LIST'=>'列表',
'LBL_VIEW'=>'查看',
'LBL_LIST_VIEW'=>'列表视图',
'LBL_HISTORY'=>'查看历史记录',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'恢复默认布局',
'LBL_ACTIVITIES'=>'活动流',
'LBL_SEARCH'=>'查找',
'LBL_NEW'=>'新建',
'LBL_TYPE_BASIC'=>'基本',
'LBL_TYPE_COMPANY'=>'公司',
'LBL_TYPE_PERSON'=>'个人',
'LBL_TYPE_ISSUE'=>'发行',
'LBL_TYPE_SALE'=>'销售',
'LBL_TYPE_FILE'=>'文件',
'LBL_RSUB'=>'这是将显示在您模块中的子面板',
'LBL_MSUB'=>'这是您的模块为其他相关模块提供的用于显示的子面板',
'LBL_MB_IMPORTABLE'=>'允许导入',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'可见',
'LBL_VE_HIDDEN'=>'隐藏',
'LBL_PACKAGE_WAS_DELETED'=>'已删除 [[package]]',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'导出定制项',
'LBL_EC_NAME'=>'文件包名称：',
'LBL_EC_AUTHOR'=>'作者：',
'LBL_EC_DESCRIPTION'=>'描述:',
'LBL_EC_KEY'=>'关键值：',
'LBL_EC_CHECKERROR'=>'请选择一个模块。',
'LBL_EC_CUSTOMFIELD'=>'自定义的字段',
'LBL_EC_CUSTOMLAYOUT'=>'自定义的布局',
'LBL_EC_CUSTOMDROPDOWN' => '自定义的下拉列表',
'LBL_EC_NOCUSTOM'=>'没有自定义任何模块。',
'LBL_EC_EXPORTBTN'=>'导出',
'LBL_MODULE_DEPLOYED' => '已部署模块。',
'LBL_UNDEFINED' => '未定义',
'LBL_EC_CUSTOMLABEL'=>'自定义标签',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => '检索数据失败',
'LBL_AJAX_TIME_DEPENDENT' => '动作正在进行中，请耐心等待并在几秒钟后重试。',
'LBL_AJAX_LOADING' => '加载中...',
'LBL_AJAX_DELETING' => '删除中...',
'LBL_AJAX_BUILDPROGRESS' => '建设过程中...',
'LBL_AJAX_DEPLOYPROGRESS' => '部署过程中...',
'LBL_AJAX_FIELD_EXISTS' =>'您输入的字段名已存在。请输入新字段名。',
//JS
'LBL_JS_REMOVE_PACKAGE' => '您确定要删除此程序包吗？这会永久删除这个程序包相关的所有文件。',
'LBL_JS_REMOVE_MODULE' => '您确定要删除此模块吗？这会永久删除这个模块相关的所有文件。',
'LBL_JS_DEPLOY_PACKAGE' => '重新部署该模块将覆盖所有您在工作室中针对该模块所做的任何自定义。您确定要继续吗？',

'LBL_DEPLOY_IN_PROGRESS' => '正部署程序包',
'LBL_JS_VALIDATE_NAME'=>'名称-必须以字母开头并可能只能包含字母、 数字和下划线。不能使用空格或其他特殊字符。',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'程序包密匙已存在',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'程序包名称已存在',
'LBL_JS_PACKAGE_NAME'=>'文件包名称-必须以字母开头并可能只能包含字母、 数字和下划线。不能使用空格或其他特殊字符。',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'关键值-必须是含有字母数字，且以字母开头。',
'LBL_JS_VALIDATE_KEY'=>'关键值 - 必须为字母数字且以字母开头，并无空格。',
'LBL_JS_VALIDATE_LABEL'=>'请输入将用作此模块的显示名称的一个标签',
'LBL_JS_VALIDATE_TYPE'=>'请选择您想从上面的列表中建立的模块的类型',
'LBL_JS_VALIDATE_REL_NAME'=>'名称 - 必须为字母数字且无空格',
'LBL_JS_VALIDATE_REL_LABEL'=>'标签 - 请添加一个可显示于子面板上的标签',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => '您确定要删除此所需的下拉列表项吗？这可能会影响您的应用程序的功能。',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => '您确定要删除此下拉列表项吗？删除谈成结束或丢单结束这两个阶段，将导致预测模块不能正常工作',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => '您确定要删除新的销售状态吗？删除此状态将导致商业机会模块营收单项工作流不能正常工作。',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => '您确定要删除正在进行中的销售状态吗？删除此状态将导致商业机会模块营收单项工作流不能正常工作。',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => '您确定要删除谈成结束销售阶段吗？删除这一阶段将会导致预测模块不能正常工作',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => '您确定要删除丢单结束销售阶段吗？删除这一阶段将会导致预测模块不能正常工作',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'您确定要删除这种关系？<br>注意：此操作可能无法几分钟内完成。',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'将使这种关系永久化，您确定要部署这种关系吗？',
'LBL_CONFIRM_DONT_SAVE' => '自上次保存已做更改，您想要保存吗？',
'LBL_CONFIRM_DONT_SAVE_TITLE' => '保存更改?',
'LBL_CONFIRM_LOWER_LENGTH' => '数据可能被截断并且该操作不能被撤消，您确定要继续吗?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'请按照输入至该字段的数据类型选择合适的数据类型。',
'LBL_POPHELP_FTS_FIELD_CONFIG' => '配置字段为可全文搜索。',
'LBL_POPHELP_FTS_FIELD_BOOST' => '增强 (Boosting) 是加强记录的字段关联的过程。<br />进行搜索时，增强水平较高的字段将获得更大的权重。当执行搜索时，将含有字段的记录与更大的权重匹配，则显示更高的搜索结果。<br />默认值为 1.0，代表中性增强。如要应用正增强，可接受任何高于 1 的浮点值。对于负增强，则使用低于 1 的值。例如，数值 1.35 会将字段增强 135%。使用数值 0.60，则应用负增强。<br />请注意，在以前的版本中，要求执行全文搜索索引命令。这不再是必需的。',
'LBL_POPHELP_IMPORTABLE'=>'<b>是</b> ：导入操作将加入该字段。<br><b>否</b> ：导入操作不会加入该字段。<br><b>必要</b>：任何导入操作必须提供该字段数值。',
'LBL_POPHELP_PII'=>'此字段将自动标记用于审计，可在“个人信息”视图中查看。<br>如果记录与“数据隐私”删除请求相关，还可以永久删除“个人信息”字段。<br>删除操作将通过“数据隐私”模块进行，可由角色为“数据隐私管理员”的管理员或用户执行。',
'LBL_POPHELP_IMAGE_WIDTH'=>'输入一个以像素为单位的宽度值。<br> 上传的图像将被缩放到这个宽度。',
'LBL_POPHELP_IMAGE_HEIGHT'=>'输入一个以像素为单位的高度值。<br> 上传的图像将被缩放到这个高度。',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'当在这个模块上使用全球查询功能来搜索记录时选择使用这个字段。',
//Revert Module labels
'LBL_RESET' => '重置',
'LBL_RESET_MODULE' => '复位模块',
'LBL_REMOVE_CUSTOM' => '删除自定义',
'LBL_CLEAR_RELATIONSHIPS' => '消除关系',
'LBL_RESET_LABELS' => '复位标签',
'LBL_RESET_LAYOUTS' => '重置布局',
'LBL_REMOVE_FIELDS' => '删除自定义字段',
'LBL_CLEAR_EXTENSIONS' => '清除的扩展名',

'LBL_HISTORY_TIMESTAMP' => '时间戳',
'LBL_HISTORY_TITLE' => '历史记录',

'fieldTypes' => array(
                'varchar'=>'文本字段',
                'int'=>'整数',
                'float'=>'浮动',
                'bool'=>'复选框',
                'enum'=>'下拉表单',
                'multienum' => '多选',
                'date'=>'日期',
                'phone' => '电话',
                'currency' => '货币',
                'html' => 'HTML',
                'radioenum' => '单选框',
                'relate' => '关系',
                'address' => '地址',
                'text' => '文本区域',
                'url' => 'URL',
                'iframe' => 'IFrame',
                'image' => '图片',
                'encrypt'=>'加密',
                'datetimecombo' =>'日期时间',
                'decimal'=>'十进制',
),
'labelTypes' => array(
    "" => "经常使用的标签",
    "all" => "所有标签",
),

'parent' => '弹性关联',

'LBL_ILLEGAL_FIELD_VALUE' =>"下拉列表的键中不能含有引号。",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"从下拉列表中选择删除该项目。使用此列表与此项目作为值的任何下拉字段将不再显示值，且将不能从下拉字段中选择该值。您确定要继续吗?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'所有模块',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} （相关 {1} ID）',
'LBL_HEADER_COPY_FROM_LAYOUT' => '从布局复制',
);
