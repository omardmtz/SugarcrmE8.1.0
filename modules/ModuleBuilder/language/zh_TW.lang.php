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
    'LBL_LOADING' => '載入中' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => '隱藏選項' /*for 508 compliance fix*/,
    'LBL_DELETE' => '刪除' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => '由 SugarCRM 提供技術支援' /*for 508 compliance fix*/,
    'LBL_ROLE' => '角色',
'help'=>array(
    'package'=>array(
            'create'=>'為封裝提供<b>名稱</b>。名稱必須以字母開頭，且只能包含字母、數字和底線。不得使用空格或其他特殊字元。（例如：HR_Management）<br/><br/>您可為此封裝提供<b>作者</b>和<b>描述</b>資訊。<br/><br/>按一下 <b>儲存</b>以建立封裝。',
            'modify'=>'<b>封裝</b>的屬性和可能的操作顯示在此處。<br><br>您可以修改封裝的<b>名稱</b>、<b>作者</b>和<b>描述</b>  並可檢視和自訂此封裝包含的所有模組。<br><br>按一下<b>新增模組</b>為此封裝建立一個模組。<br><br>如果此封裝包含一個或以上的模組，您可<b>發佈</b>和<b>部署</b>封裝，亦可<b>匯出</b>封裝的自訂配置。',
            'name'=>'此為目前封裝的<b>名稱</b><br/><br/>。名稱必須以字母開頭，且只能包含字母、數字和底線。不得使用空格或其他特殊字元。（例如：HR_Management）',
            'author'=>'此為<b>作者</b>，它將在安裝期間顯示為建立此封裝的實體名稱。<br><br>作者可以是個人或公司。',
            'description'=>'此為安裝期間顯示的封裝<b>描述</b>。',
            'publishbtn'=>'按一下<b>發佈</b>儲存所有已輸入資料，以建立一個 .zip 檔案，作為封裝的可安裝版本。<br><br>使用<b>模組載入器</b>上載 .zip 檔案並安裝封裝。',
            'deploybtn'=>'按一下<b>部署</b>儲存所有已輸入資料並在目前實例中安裝封裝，包括所有模組。',
            'duplicatebtn'=>'按一下<b>複製</b>將封裝的所有內容複製到新封裝，並在新封裝中顯示。<br/><br/>對於新封裝，可在舊的封裝名稱末尾附加一個數字，自動產生一個新名稱。您可輸入一個新<b>名稱</b>並按一下<b>儲存</b>，對新封裝進行重命名。',
            'exportbtn'=>'按一下<b>匯出</b>以建立一個包含封裝自訂設定的 .zip 檔案。<br><br>產生的檔案非封裝的可安裝版本。<br><br>使用<b>模組載入器</b>匯入 .zip 檔案，讓封裝及其自訂設定都出現在模組建立器中。',
            'deletebtn'=>'按一下<b>刪除</b>以刪除此封裝及所有關聯檔案。',
            'savebtn'=>'按一下<b>儲存</b>以儲所有輸入的與封裝關聯的資料。',
            'existing_module'=>'按一下<b>模組</b>圖示以編輯屬性和自訂與模組相關的欄位、關係和版面配置。',
            'new_module'=>'按一下<b>新模組</b>，為此封裝建立新模組。',
            'key'=>'這個由 5 個字母組成的英數<b>關鍵值</b>將用於目前封裝包含的所有模組的所有目錄的前綴、類名稱和資料庫表格。<br><br>此關鍵值用於確保表格名稱唯一性。',
            'readme'=>'按一下為此封裝新增<b>讀我檔案</b>文字。<br><br>此讀我檔案將在安裝時可用。',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'為模組提供<b>名稱</b>。您提供的<b>標籤</b>將出現在瀏覽索引標籤。<br/><br/>選取<b>瀏覽索引標籤</b>核取方塊以便為模組顯示瀏覽索引標籤。<br/><br/>選取<b>小組安全</b>核取方塊以在模組記錄中包含小組選擇欄位。<br/><br/>然後選取您要建立的模組的類型。<br/><br/>選取一個模組類型。每個範本包含一組指定的欄位，以及預先定義的版面配置，作為模組的基礎設定。<br/><br/>按一下<b>儲存</b>以建立模組。',
        'modify'=>'您可以更改模組屬性或自訂與模組關聯的<b>欄位</b>、<b>關係</b>和<b>版面配置</b>。',
        'importable'=>'選取<b>可匯入</b>核取方塊將在此模組中啟用匯入。<br><br>模組的捷徑面板將顯示一個指向匯入精靈的連結。匯入精靈可提高資料從外部來源匯入自訂模組的速度。',
        'team_security'=>'按一下<b>小組安全</b>核取方塊將為此模組啟用小組安全。<br/><br/>若啟用小組安全，小組選擇欄位將出現在模組的記錄中。',
        'reportable'=>'核取此方塊將允許模組運行與自身相關的報表。',
        'assignable'=>'核取此方塊將允許將此模組中的記錄指派至選定的使用者。',
        'has_tab'=>'核取<b>瀏覽索引標籤</b>將為此模組提供一個瀏覽索引標籤。',
        'acl'=>'核取此方塊將在此模組中啟用存取控制，包括欄位層級安全。',
        'studio'=>'核取此方塊將允許管理員在工作室中自訂此模組。',
        'audit'=>'核取此方塊將為此模組啟用稽核。將記錄特定欄位的變更，以便讓管理員檢閱變更歷史。',
        'viewfieldsbtn'=>'按一下<b>檢視欄位</b>以檢視與模組相關的欄位，以及建立和編輯自訂欄位。',
        'viewrelsbtn'=>'按一下<b>檢視關係</b>以檢視與此模組相關的關係，以及建立新關係。',
        'viewlayoutsbtn'=>'按一下<b>檢視版面配置</b>以檢視此模組的版面配置，並可在版面配置內自訂欄位排列。',
        'viewmobilelayoutsbtn' => '按一下<b>檢視活動版面配置</b>以檢視此模組的活動版面配置，並可在版面配置內自訂欄位排列。',
        'duplicatebtn'=>'按一下<b>複製</b>以將模組的屬性複製到新模組，並在新模組中顯示。<br/><br/>對於新模組，可在舊的模組名稱末尾附加一個數字，自動產生一個新名稱。',
        'deletebtn'=>'按一下<b>刪除</b>以刪除此模組。',
        'name'=>'這是目前模組的<b>名稱</b>。<br/><br/>名稱必須為英數字元，必須以字母開頭，且不得包含空格。（例如：HR_Management）',
        'label'=>'這是即將顯示在此模組瀏覽索引標籤中的<b>標籤</b>。',
        'savebtn'=>'按一下<b>儲存</b>以儲存所有輸入的與此模組關聯的資料。',
        'type_basic'=>'<b>基礎</b>範本類型提供基礎欄位，如名稱、指派對象、小組、建立日期和描述欄位。',
        'type_company'=>'<b>公司</b>範本類型提供適用於組織的欄位，如公司名稱、行業和發票地址。<br/><br/>使用此範本可建立與標準「帳戶」模組類似的模組。',
        'type_issue'=>'<b>問題</b>範本類型提供針對實例和錯誤的欄位，如數字、狀態、優先順序和描述。<br/><br/>使用此範本可建立與標準「實例和錯誤追蹤」模組類似的模組。',
        'type_person'=>'<b>個人</b>範本類型提供針對個人的欄位，如稱呼、職位、姓名、地址和電話號碼。<br/><br/>使用此範本可建立與標準「連絡人」模組類似的模組。',
        'type_sale'=>'<b>銷售</b>範本類型提供針對商機的欄位，如潛在客戶來源、銷售階段、金額和可能性。<br/><br/>使用此範本可建立與標準「商機」模組類似的模組。',
        'type_file'=>'<b>檔案</b>範本提供針對文件的欄位，如檔案名、文件類型和發布日期。<br><br>使用此範本可建立與標準「文件」模組類似的模組。',

    ),
    'dropdowns'=>array(
        'default' => '此應用程式的所有<b>下拉式清單</b>均在此處列出。<br><br>這些下拉式清單可用於任何模組的下拉式欄位。<br><br>如需變更現有下拉式清單，按一下下拉式清單名稱。<br><br>按一下<b>新增下拉式清單</b>即可建立新的下拉式清單。',
        'editdropdown'=>'下拉式清單可用於任意模組的標準或自訂下拉式欄位。<br><br>為下拉式清單提供一個<b>名稱</b>。<br><br>如果此應用程式已安裝任何語言封裝，您可選取<b>語言</b>供清單項目使用。<br><br>在<b>項目名稱</b>欄位內，為下拉式清單中的選項提供一個名稱。此名稱將不會顯示在對使用者可見的下拉式清單中。<br><br>在<b>顯示標籤</b>欄位，提供一個對使用者可見的標籤。<br><br>提供項目名稱和顯示標籤後，按一下<b>新增</b>在下拉式清單中新增項目。<br><br>如需重新排列清單中的項目，將項目拖放至想要的位置。<br><br>如需編輯項目的顯示標籤，按一下<b>編輯圖示</b>，並輸入新標籤。如需從下拉式清單中刪除項目，按一下<b>刪除圖示</b>。<br><br>如需撤銷顯示標籤中做出的變更，按一下<b>撤銷</b>。如需重做未完成的變更，按一下<b>重做</b>。<br><br>按一下<b>儲存</b>以儲存下拉式清單。',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> '按一下<b>儲存和部署</b>以儲存並在模組中啟用您的變更。',
        'historyBtn'=> '按一下<b>檢視歷史</b>以檢視並從歷史中還原之前儲存的版面配置。',
        'historyRestoreDefaultLayout'=> '按一下<b>還原預設版面配置</b>以還原檢視表的原始版面配置。',
        'Hidden' 	=> '<b>已隱藏</b>欄位不會顯示在子面板中。',
        'Default'	=> '<b>預設</b>欄位顯示在子面板中。',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> '按一下<b>儲存和部署</b>以儲存並在模組中啟用您的變更。',
        'historyBtn'=> '按一下<b>檢視歷史</b>以檢視並從歷史中還原先前儲存的版面配置。<br><br>在<b>檢視歷史</b>內<b>還原</b>將還原先前已儲存版面配置中的欄位佈置。如需變更欄位標籤，按一下每個欄位旁邊的編輯圖示。',
        'historyRestoreDefaultLayout'=> '按一下<b>還原預設版面配置</b>以還原檢視表的原始版面配置。<br><br><b>還原預設版面配置</b>只會還原原始版面配置中的欄位佈置。如需變更欄位標籤，按一下每個欄位旁邊的編輯圖示。',
        'Hidden' 	=> '<b>已隱藏</b>欄位目前在 ListViews 中對使用者不可見。',
        'Available' => '<b>可用</b>欄位預設為不顯示，但可由使用者新增至 ListViews。',
        'Default'	=> 'ListViews 中出現的<b>預設</b>欄位不可由使用者自訂。'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> '按一下<b>儲存和部署</b>以儲存並在模組中啟用您的變更。',
        'historyBtn'=> '按一下<b>檢視歷史</b>以檢視並從歷史中還原先前儲存的版面配置。<br><br>在<b>檢視歷史</b>內<b>還原</b>將還原先前已儲存版面配置中的欄位佈置。如需變更欄位標籤，按一下每個欄位旁邊的編輯圖示。',
        'historyRestoreDefaultLayout'=> '按一下<b>還原預設版面配置</b>以還原檢視表的原始版面配置。<br><br><b>還原預設版面配置</b>只會還原原始版面配置中的欄位佈置。如需變更欄位標籤，按一下每個欄位旁邊的編輯圖示。',
        'Hidden' 	=> '<b>已隱藏</b>欄位目前在 ListViews 中對使用者不可見。',
        'Default'	=> 'ListViews 中出現的<b>預設</b>欄位不可由使用者自訂。'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> '按一下<b>儲存和部署</b>將儲存並啟用所有變更。',
        'Hidden' 	=> '<b>已隱藏</b>欄位不會出現在搜尋中。',
        'historyBtn'=> '按一下<b>檢視歷史</b>以檢視並從歷史中還原先前儲存的版面配置。<br><br>在<b>檢視歷史</b>內<b>還原</b>將還原先前已儲存版面配置中的欄位佈置。如需變更欄位標籤，按一下每個欄位旁邊的編輯圖示。',
        'historyRestoreDefaultLayout'=> '按一下<b>還原預設版面配置</b>以還原檢視表的原始版面配置。<br><br><b>還原預設版面配置</b>只會還原原始版面配置中的欄位佈置。如需變更欄位標籤，按一下每個欄位旁邊的編輯圖示。',
        'Default'	=> '<b>預設</b>欄位會出現在搜尋中。'
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
        'saveBtn'	=> '按一下<b>儲存</b>以保留您自上次儲存模組以來對版面配置做出的變更。<br><br>除非您部署和儲存變更，否則此類變更不會在模組中顯示。',
        'historyBtn'=> '按一下<b>檢視歷史</b>以檢視並從歷史中還原先前儲存的版面配置。<br><br>在<b>檢視歷史</b>內<b>還原</b>將還原先前已儲存版面配置中的欄位佈置。如需變更欄位標籤，按一下每個欄位旁邊的編輯圖示。',
        'historyRestoreDefaultLayout'=> '按一下<b>還原預設版面配置</b>以還原檢視表的原始版面配置。<br><br><b>還原預設版面配置</b>只會還原原始版面配置中的欄位佈置。如需變更欄位標籤，按一下每個欄位旁邊的編輯圖示。',
        'publishBtn'=> '按一下<b>儲存和部署</b>以保留您自上次儲存模組以來對版面配置作出的變更，並在模組中啟用這些變更。<br><br>版面配置會立即在模組中顯示。',
        'toolbox'	=> '<b>工具箱</b>包含<b>資源回收筒</b>、其他版面配置元素和一組可新增至版面配置的可用欄位。<br/><br/>工具箱中的版面配置元素和欄位可拖放至版面配置，版面配置元素和欄位可從版面配置拖放至工具箱。<br><br>版面配置元素為<b>面板</b>和<b>列</b>。為版面配置新增列或面板可在版面配置中為欄位提供附加位置。<br/><br/>將工具箱或版面配置中的任意欄位拖放至已使用的欄位，將切換這兩欄的位置。<br/><br/><b>篩選</b>欄位會在所在的版面配置中建立空白。',
        'panels'	=> '<b>版面配置</b>區域提供一個檢視表，可供檢視在版面配置部署變更之後，版面配置的顯示方式。<br/><br/>您可以直接將欄位、列、子面板拖放至想要的位置，對其進行重新排列。<br/><br/>將元素拖放至工具箱中的<b>資源回收筒</b>即可刪除元素，將元素和欄位從<b>工具箱</b>拖放至版面配置中想要的位置即可新增元素和欄位。',
        'delete'	=> '將任何元素拖放至此處即可將其從版面配置中刪除。',
        'property'	=> '編輯此欄位顯示的<b>標籤</b>。<br><br><b>寬度</b>是供 Sidecar 模組使用的以像素為單位的寬度值，亦可作為回溯相容性模組表格寬度的百分比。',
    ),
    'fieldsEditor'=>array(
        'default'	=> '模組可用的<b>欄位</b>已按欄位名稱在此處列出。<br><br>為模組建立的自訂欄位將顯示在此模組預設可用的欄位之上。<br><br>如需編輯欄位，按一下<b>欄位名稱</b>。<br/><br/>如需建立新欄位，按一下<b>新增欄位</b>。',
        'mbDefault'=>'模組可用的<b>欄位</b>已按欄位名稱在此處列出。<br><br>如需配置欄位屬性，按一下欄位名稱。<br><br>如需建立新欄位，按一下<b>新增欄位</b>。建立之後，按一下欄位名稱即可編輯新欄位的標籤和其他屬性。<br><br>部署模組之後，模組建立器中建立的新欄位將被當作工作室已部署模組的標準欄位。',
        'addField'	=> '為新欄位選取<b>資料類型</b>。您選取的類型將決定此欄位可以輸入的字元類型。例如：選取整數資料類型的欄位只能輸入整數數字。<br><br>為欄位提供<b>名稱</b>。名稱必須為英數字元且不得包含空格。可以使用底線。<br><br><b>顯示標籤</b>是為模組版面配置中的欄位顯示的標籤。<b>系統標籤</b>用來參照到程式碼中的欄位。<br><br>根據為欄位選取的資料類型，可為欄位設定部分或所有以下屬性：<br><br><b>說明文字</b>將在滑鼠暫留欄位時顯示，並可用於提示使用者輸入所要求的類型。<br><br><b>註解文字</b>只有在工作時和/或模組建立器中看到，它可用於為管理員描述欄位。<br><br><b>預設值</b>將顯示在欄位中。使用者可在欄位中輸入新值或使用預設值。<br><br>選取<b>大規模更新</b>核取方塊將可以在該欄位應用大規模更新。<br><br><b>最大大小</b>值將決定可以在欄位中輸入字元的最大數量。<br><br>選取<b>必要欄位</b>核取方塊以便將欄位設定為必填。必須為此欄位設定一個值，以便能夠儲存包含此欄位的記錄。<br><br>選取<b>可報告</b>核取方塊以允許欄位用於篩選器以及在報表中顯示資料。<br><br>選取<b>稽核</b>核取方塊以便能夠在變更記錄中追蹤欄位變更歷史。<br><br>在<b>可匯入</b>欄位選取一個選項，以便允許、禁止、或要求欄位必須將資料匯入至匯入精靈。 <br><br>在<b>重複項合併</b>欄位中選取一個選項以允許或禁止合併重複項和查照重複項功能。<br><br>某些資料類型可設定其他屬性。',
        'editField' => '此欄位的屬性可自訂。<br><br>按一下<b>再製</b>，以使用相同的屬性建立新欄位。',
        'mbeditField' => '範本欄位的<b>顯示</b>標籤可自訂。欄位的其他屬性不可自訂。<br><br>按一下<b>再製</b>以使用相同屬性建立新欄位。<br><br>如需移除範本欄位並讓其不在模組中顯示，從響應的<b>版面配置</b>中刪除欄位即可。'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'建立另一個可透過<b>模組載入器</b>上載至另一個 Sugar 實例的封裝，匯出在工作室做出的自訂設定。 <br><br> 首先請提供<b>封裝名稱</b>。您亦可為封裝提供<b>作者</b>和<b>描述</b>資訊。<br><br>選取包含您想要匯出的自訂設定的模組。您將看到只包含自訂設定的模組，並從中選取。<br><br>然後按一下<b>匯出</b>，為包含自訂設定的封裝建立一個 .zip 檔案。',
        'exportCustomBtn'=>'按一下<b>匯出</b>為包含您想要匯出之自訂的封裝建立一個 .zip 檔案。',
        'name'=>'這是封裝的<b>名稱</b>。此名稱將在安裝時顯示。',
        'author'=>'此為<b>作者</b>，它將在安裝期間顯示為建立此封裝的實體名稱。作者可以是個人或公司。',
        'description'=>'此為安裝期間顯示的封裝<b>描述</b>。',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> '歡迎來到<b>開發者工具</b>區。<br/><br/>使用此區域的工具建立和管理標準及自訂的模組和欄位。',
        'studioBtn'	=> '使用<b>工作室</b>自訂已部署模組。',
        'mbBtn'		=> '使用<b>模組建立器</b>建立新模組。',
        'sugarPortalBtn' => '使用<b> Sugar 入口網站編輯器</b>管理和自訂 Sugar 門戶網站。',
        'dropDownEditorBtn' => '使用<b>下拉式編輯器</b>為下拉式欄位新增和編輯全域下拉式清單。',
        'appBtn' 	=> '您可以透過應用程式模式自訂專案的不同屬性，如首頁顯示的 TPS 報表的數量。',
        'backBtn'	=> '返回上一步。',
        'studioHelp'=> '使用<b>工作室</b>確定模組中顯示的資訊及其顯示方式。',
        'studioBCHelp' => ' 表示此模組是回溯相容性模組',
        'moduleBtn'	=> '按一下以編輯此模組。',
        'moduleHelp'=> '您可為模組自訂的元件出現在此處。<br><br>按一下圖示以選取需要編輯的元件。',
        'fieldsBtn'	=> '建立和自訂<b>欄位</b>以在模組中儲存資訊。',
        'labelsBtn' => '編輯為欄位和模組中的其他標題顯示的<b>標籤</b>。'	,
        'relationshipsBtn' => '新增或檢視模組的現有<b>關係</b>。' ,
        'layoutsBtn'=> '自訂模組<b>版面配置</b>。此版面配置是包含欄位的模組的不同檢視表。<br><br>您可以確定顯示的欄位以及它們在每個版面配置中的排列方式。',
        'subpanelBtn'=> '確定哪些欄位出現在模組的<b>子面板</b>。',
        'portalBtn' =>'自訂在 <b>Sugar 入口網站</b>顯示的模組<b>版面配置</b>。',
        'layoutsHelp'=> '可自訂的模組<b>版面配置</b>會在此處顯示。<br><br>版面配置顯示欄位和欄位資料。<br><br>按一下圖示以選取要編輯的版面配置。',
        'subpanelHelp'=> '模組中可自訂的<b>子面板</b>會在此處顯示。<br><br>按一下圖示以選取要編輯的模組。',
        'newPackage'=>'按一下<b>新封裝</b>以建立新的封裝。',
        'exportBtn' => '按一下<b>匯出自訂</b>以建立和下載封裝，此封裝包含工作室中設定的適用於指定模組的自訂。',
        'mbHelp'    => '使用<b>模組建立器</b>以建立封裝，此封裝包含基於標準和自訂對象的自訂模組。',
        'viewBtnEditView' => '自訂模組的<b> EditView</b> 版面配置。<br><br>EditView 是包含輸入欄位的表格，以獲取使用者輸入的資料。',
        'viewBtnDetailView' => '自訂模組的 <b>DetailView</b> 版面配置。<br><br>DetailView 顯示使用者輸入的欄位資料。',
        'viewBtnDashlet' => '自訂模組的 <b>Sugar Dashlet</b>，包括 Sugar Dashlet 的 ListView 和搜尋。<br><br>Sugar Dashlet 可用於將頁面新增至首頁模組。',
        'viewBtnListView' => '自訂模組的 <b>ListView</b> 版面配置。<br><br>搜尋結果顯示在 ListView 中。',
        'searchBtn' => '自訂模組的 <b>搜尋</b>版面配置。<br><br>確定哪些欄位用於篩選在 ListView 中顯示的記錄。',
        'viewBtnQuickCreate' =>  '自訂模組的 <b>QuickCreate</b> 版面配置。<br><br>QuickCreate 表格顯示在子面板和電子郵件模組。',

        'searchHelp'=> '可自訂的<b>搜尋</b>表單在此處顯示。<br><br>搜尋表單包含欄位以篩選記錄。<br><br>按一下圖示以選取要編輯的搜尋版面配置。',
        'dashletHelp' =>'可自訂的 <b>Sugar Dashlet</b> 版面配置會在此處顯示。<br><br>Sugar Dashlet 可將頁面新增至首頁模組。',
        'DashletListViewBtn' =>'<b>Sugar Dashlet ListView</b> 根據 Sugar Dashlet 搜尋篩選結果顯示記錄。',
        'DashletSearchViewBtn' =>'<b>Sugar Dashlet 搜尋</b>為 Sugar Dashlet Listview 篩選記錄。',
        'popupHelp' =>'可自訂的<b>快顯</b>版面配置會在此處顯示。<br>',
        'PopupListViewBtn' => '<b>快顯 ListView</b>版面配置可在選取一條或多條與目前記錄相關的記錄時，用於檢視記錄清單。',
        'PopupSearchViewBtn' => '<b>快顯搜尋</b>版面配置允許使用者搜尋與目前記錄相關的記錄，並在同一視窗的快顯 Listview 上方顯示。傳統模組使用此版面配置進行快顯搜尋，Sidecar 模組則使用搜尋版面配置的設定。',
        'BasicSearchBtn' => '自訂<b>基礎搜尋</b>表單，此表單將顯示在模組搜尋區的基礎搜需標籤中。',
        'AdvancedSearchBtn' => '自訂<b>進階搜尋</b>表單，此表單將出現在模組搜尋區的進階搜尋標籤中。',
        'portalHelp' => '管理和自訂<b> Sugar 入口網站</b>。',
        'SPUploadCSS' => '上載用於 Sugar 入口網站的<b>樣式表</b>。',
        'SPSync' => '將自訂<b>同步</b>至 Sugar 入口網站實例。',
        'Layouts' => '自訂 Sugar 入口網站模組的<b>版面配置</b>。',
        'portalLayoutHelp' => 'Sugar 入口網站內的模組將在此區域顯示。<br><br>選取一個模組以編輯<b>版面配置</b>。',
        'relationshipsHelp' => '模組和其他已部署模組之間存在的所有<b>關係</b>會在此處顯示。<br><br>關係<b>名稱</b>是系統產生的關係名稱。<br><br><b>主要模組</b>是擁有關係的模組。例如：對於所有以帳戶模組為主要模組的關係，其所有屬性都儲存在帳戶資料庫表格中。<br><br><b>類型</b>是主要模組和<b>相關模組</b>之間存在的關係的類型。<br><br>按一下欄標題對欄進行分類。<br><br>按一下關係表格中的列檢視與關係相關的屬性。<br><br>按一下<b>新增關係</b>以建立一個新關係。<br><br>可在任意兩個已部署模組之間建立關係。',
        'relationshipHelp'=>'可在一個模組和另外一個已部署模組之間建立<b>關係</b>。<br><br>關係透過子面板和模組記錄中的相關欄位進行視覺表達。<br><br>為模組選取下列一種關係<b>類型</b>：<br><br> <b>一對一</b> - 兩個模組記錄均包含相關欄位。.<br><br> <b>一對多</b> - 主要模組的記錄包含子面板，相關模組的記錄包含相關欄位。<br><br> <b>多對多</b> - 兩個模組記錄均都會顯示子面板。<br><br>為關係選取<b>相關模組</b>。<br><br>如果關係類型涉及子面板，請為相應的模組選取子面板檢視表。<br><br>按一下<b>儲存</b>以建立關係。',
        'convertLeadHelp' => "您可在此處將模組新增至轉換版面配置螢幕，以及修改現有模組的設定。<br/><br/>
        <b>排序：</b><br/>
        連絡人、帳戶和商機必須保持此順序。您可以將模組的列拖放至表格，對任何其他模組進行重新排序。<br/><br/>
        <b>相依性：</b><br/>
        若包含商機，則必須在轉換版面配置中包含或移除帳戶。<br/><br/>
        <b>模組：</b> 模組名稱。
<br/><br/>
        <b>必要：</b>轉換潛在客戶前必須建立或選取必要的模組。<br/><br/>
        <b>複製資料：</b>若核取，潛在客戶的欄位將被複製到新建立記錄中名稱相同的欄位。<br/><br/>
        <b>刪除：</b>從轉換版面配置中移除此模組。<br/><br/>
        ",
        'editDropDownBtn' => '編輯全域下拉式清單',
        'addDropDownBtn' => '新增全域下拉式清單',
    ),
    'fieldsHelp'=>array(
        'default'=>'模組中的<b>欄位</b>已按欄位名稱在此處列出。<br><br>模組範本中包含一組預先設定的欄位。<br><br>如需建立新欄位，按一下<b>新增欄位</b>。<br><br>如需編輯欄位，按一下<b>欄位名稱</b>。<br/><br/>部署模組後，模組建立器中建立的新欄位以及範本欄位，都將被視為工作室中的標準欄位。',
    ),
    'relationshipsHelp'=>array(
        'default'=>'在模組和其他模組之間建立的所有<b>關係</b>會在此處顯示。<br><br>關係<b>名稱</b>是系統產生的關係名稱。<br><br><b>主要模組</b>是擁有關係的模組。關係屬性儲存在屬主要模組所有的資料庫表格中。<br><br><b>類型</b>是主要模組和<b>相關模組</b>之間存在的關係類型。<br><br>按一下欄標題對欄進行分類。<br><br>按一下關係表格中的列檢視與關係相關的屬性。<br><br>按一下<b>新增關係</b>以建立一個新關係。',
        'addrelbtn'=>'滑鼠懸停取得新增關係的說明..',
        'addRelationship'=>'可在一個模組和另外一個自訂模組或已部署模組之間建立<b>關係</b>。<br><br>關係透過子面板和模組記錄中的相關欄位進行視覺表達。<br><br>為模組選取下列一種關係<b>類型</b>：<br><br> <b>一對一</b> - 兩個模組記錄均包含相關欄位。<br><br> <b>一對多</b> - 主要模組的記錄包含子面板，相關模組的記錄包含相關欄位。<br><br> <b>多對多</b> - 兩個模組記錄均都會顯示子面板。<br><br>為關係選取<b>相關模組</b>。<br><br>如果關係類型涉及子面板，請為相應的模組選取子面板檢視表。<br><br>按一下<b>儲存</b>以建立關係。',
    ),
    'labelsHelp'=>array(
        'default'=> '可對欄位和模組中其他標題的<b>標籤</b>進行變更。<br><br>在欄位內部按一下即可編輯標籤，輸入新標籤並按一下<b>儲存</b>。<br><br>如果此應用程式已安裝任何語言封裝，您可選取<b>語言</b>供清單項目使用。',
        'saveBtn'=>'按一下<b>儲存</b>以儲存所有變更。',
        'publishBtn'=>'按一下<b>儲存和部署</b>以儲存並啟用所有變更。',
    ),
    'portalSync'=>array(
        'default' => '輸入要更新的門戶網站實例的 <b> Sugar 入口網站 URL</b>，並按一下<b>前往</b>。<br><br>然後輸入有效的 Sugar 使用者名稱和密碼，並按一下<b>開始同步</b>。<br><br>對 Sugar 入口網站<b>版面配置</b>設定的自訂，連同<b>樣式表</b>（若已上載），都將轉移至特定的門戶網站實例。',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => '您可使用樣式表自訂 Sugar 入口網站的外觀。<br><br>選取一個<b>樣式表</b>以上載。<br><br>樣式表將在下次執行同步時實作至 Sugar 入口網站。',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'如需開始使用專案，按一下<b>新增封裝</b>建立一個新封裝以容納您的所有模組。<br/><br/>每個封裝可包含一個或多個模組。<br/><br/>例如，您可能要建立一個封裝，其中包含一個與標準帳戶模組相關的自訂模組。或者，您可能要建立一個包含多個模組的封裝，其中的模組作為一個專案相互協作，並且互相關聯並與應用程式中已經存在的其他模組關聯。',
            'somepackages'=>'<b>封裝</b>可作為自訂模組的容器，所有自訂個模組都屬於同一專案。封裝可包含一個或多個自訂<b>模組</b>，它們互相關聯並與應用程式中的其他模組關聯。<br/><br/>為專案建立封裝後，您可以立即為封裝建立模組，或者稍後返回模組建立器，完成此專案。<br><br>完成專案後，您可以<b>部署</b>封裝以便在應用程式內安裝自訂模組。',
            'afterSave'=>'您的新封裝應至少包含一個模組。您可以為此封裝建立一個或多個自訂模組。<br/><br/>按一下<b>新增模組</b>為此封裝建立一個自訂個模組。<br/><br/>在建立一個或以上模組後，您可以發布或部署封裝，供您的實例和/或其他使用者的實例使用。<br/><br/>如需在 Sugar 實例內一步完成封裝的部署，按一下<b>部署</b>。<br><br>按一下<b>發布</b>將封裝儲存為 .zip 檔案。在系統中儲存 .zip 檔案後，使用<b>模組載入器</b>上載並在 Sugar 實例內安裝此封裝。<br/><br/>您可以將檔案分發至其他使用者，以便在他們自己的 Sugar 實例內上載和安裝。',
            'create'=>'<b>封裝</b>可作為自訂模組的容器，所有自訂個模組都屬於同一專案。封裝可包含一個或多個自訂<b>模組</b>，它們互相關聯並與應用程式中的其他模組關聯。<br/><br/>為專案建立封裝後，您可以立即為封裝建立模組，或者稍後返回模組建立器，完成此專案。',
            ),
    'main'=>array(
        'welcome'=>'使用<b>開發者工具</b>建立和管理標準及自訂的模組和欄位。<br/><br/>如需管理應用程式中的模組，按一下<b>工作室</b>。<br/><br/>如需建立自訂模組，按一下<b>模組建立器</b>。',
        'studioWelcome'=>'所有目前已安裝的模組，包括標準和載入模組的對象，均可在工作室內自訂。'
    ),
    'module'=>array(
        'somemodules'=>"由於目前封裝包含至少一個模組，您可以在 Sugar 實例內部<b>部署</b>封裝內的模組，或<b>發佈</b>封裝以使用<b>模組載入器</b>在目前 Sugar 實例或另一個實例中安裝。<br/><br/>如需直接從 Sugar 實例安裝封裝，按一下<b>部署</b>。<br><br>如需為封裝建立 .zip 檔案以便使用<b>模組載入器</b>在目前Sugar 實例或另一個實例中載入病安裝，按一下<b>發佈</b>。<br/><br/>您可以分階段為此封裝建立模組，並在準備充分時發佈或部署。<br/><br/>發佈或部署後，您可以對封裝屬性做出變更，並進一步自訂模組。然後重新發佈或重新部署封裝以應用所有變更。" ,
        'editView'=> '您可在此處編輯現有欄位。您可以在左側面板中移除任何現有欄位或新增可用欄位。',
        'create'=>'選取您要建立的模組的<b>類型</b>後，請記住您想要在模組內附加的欄位類型。<br/><br/>每個模組範本包含一組屬於標題所描述之模組類型的欄位。<br/><br/><b>基礎</b> －提供標準模組中顯示的基礎欄位，如名稱、指派對象、小組、建立日期和描述欄位。<br/><br/><b>公司</b>範本類型提供適用於組織的欄位，如公司名稱、行業和發票地址。使用此範本可建立與標準「帳戶」模組類似的模組。<br/><br/><b>個人</b> －提供針對個人的欄位，如稱呼、職位、姓名、地址和電話號碼。使用此範本可建立與標準「連絡人」模組類似的模組。<br/><br/><b>問題</b> －提供針對實例和錯誤的欄位，如數字、狀態、優先順序和描述。使用此範本可建立與標準「實例和錯誤追蹤」模組類似的模組。<br/><br/>注意：建立模組後，您可以編輯範本所提供欄位的標籤，並可建立自訂欄位以新增至模組版面設置。',
        'afterSave'=>'透過編輯和建立欄位、與其他模組建立關係，以及在版面配置中排列欄位，對模組進行自訂以滿足您的需求。<br/><br/>如需檢視範本欄位和在模組內管理自訂欄位，按一下<b>檢視欄位</b>。<br/><br/>如需建立和管理模組和其他模組之間的關係，無論是應用程式內的已存在模組還是同一封裝內的其他自訂模組，按一下<b>檢視關係</b>。<br/><br/>如需編輯模組版面配置，按一下<b>檢視版面配置</b>。您可以為此模組更改詳情檢視表、編輯檢視表和清單檢視表版面配置，就像工作室內部已經存在的其他模組一樣。<br/><br/>如需建立一個與目前模組具有相同屬性的模組，按一下<b>複製</b>。您可以進一步自訂新模組。',
        'viewfields'=>'可自訂模組內的欄位以滿足您的需求。<br/><br/>您無法刪除標準欄位，但您可在版面配置頁面移除它們已實現合適的版面配置。<br/><br/>您可以按一下<b>屬性</b>表單中的<b>再製</b>，快速建立與現有欄位擁有相同屬性的新欄位。輸入任意新屬性，然後按一下<b>儲存</b>。<br/><br/>建議您在發佈和安裝包含自訂模組的封裝之前，為標準和自訂欄位設定所有屬性。',
        'viewrelationships'=>'您可在目前模組和封裝內的其他模組之間，和/或在目前模組和應用程式內已安裝模組之間建立多對多關係。<br><br>如需建立一對多和一對一關係，請為模組建立<b>相關</b>和<b> Flex 相關</b>欄位。',
        'viewlayouts'=>'您可在<b>編輯檢視表</b>中控制可以捕獲資料的欄位。您亦可在<b>詳情檢視表</b>中控制顯示的資料。這些檢視表不一定要匹配。<br/><br/>在模組子面板按一下<b>建立</b>按鈕，將顯示「快速建立」表單。預設情況下，<b>快速建立</b>表單版面配置與預設<b>編輯檢視表</b>版面配置相同。您可以自訂快速建立表單，讓它包含的欄位比編輯檢視表版面配置更少和/或不同。<br><br>您可以使用版面配置自訂和<b>角色管理</b>來決定<br><br>模組安全性。',
        'existingModule' =>'建立和自訂此模組後，您可以建立其他模組，或返回封裝以<b>發佈</b>或<b>部署</b>此封裝。<br><br>如需建立其他模組，按一下<b>複製</b>以建立與目前模組具有相同屬性的新模組，或向後巡覽至封裝，並按一下<b>新建模組</b>。<br><br>如果您已經準備好<b>發佈</b>或<b>部署</b>包含此模組的封裝，向後巡覽至封裝以執行此類功能。您可發佈和部署至少包含一個模組的封裝。',
        'labels'=> '可變更標準欄位和自訂欄位的標籤。變更欄位標籤不會影響儲存在欄位中的資料。',
    ),
    'listViewEditor'=>array(
        'modify'	=> '左邊顯示三欄。「預設」欄包含預設以清單檢視表顯示的欄位；「可用」欄包含使用者可選取使用以建立自訂清單檢視表的欄位；「已隱藏」欄包含您作為管理員可使用的欄位，它們可新增至預設或可用欄供使用者使用，但目前已停用此功能。',
        'savebtn'	=> '按一下<b>儲存</b>將儲存並啟用所有變更。',
        'Hidden' 	=> '已隱藏欄位是目前在清單檢視表中對使用者不可見的欄位。',
        'Available' => '可用欄位是預設不顯示，但可由使用者啟用的欄位。',
        'Default'	=> '未建立自訂清單檢視表設定的使用者可看到預設欄位。'
    ),

    'searchViewEditor'=>array(
        'modify'	=> '左邊顯示兩欄。「預設」欄包含將在搜尋檢視表中顯示的欄位，「已隱藏」欄包含您作為管理員可新增至檢視表的欄位。',
        'savebtn'	=> '按一下<b>儲存和部署</b>將儲存並啟用所有變更。',
        'Hidden' 	=> '已隱藏欄位是不會在搜尋檢視表中顯示的欄位。',
        'Default'	=> '預設欄位將在搜尋檢視表中顯示。'
    ),
    'layoutEditor'=>array(
        'default'	=> '左側顯示兩欄。右邊的欄標記為目前版面配置或版面配置預覽，您可在此處變更模組版面配置。左邊的欄標題為工具箱，其中包含編輯版面配置時使用的有用元素和工具。<br/><br/>如果版面配置區標題為目前版面配置，則您處理的是目前由模組用於顯示的版面配置副本。<br/><br/>如果標題為版面配置預覽，您處理的是先前按一下「儲存」按鈕建立的副本，它可能與此模組使用者看到的版本有所不同。',
        'saveBtn'	=> '按一下此按鈕儲存版面配置，以保留變更。返回此模組後，您將首先看到此比變更的版面配置。但使用者不會看到您的版面配置，除非您按一下「儲存和發佈」按鈕。',
        'publishBtn'=> '按一下此按鈕以部署版面配置。這意味著這個模組使用者立即可看到此版面配置。',
        'toolbox'	=> '工具箱包含各種用於編輯版面配置的有用功能，包括垃圾區、一組其他元素和一組可用欄位。其中任意功能均可拖放至版面配置。',
        'panels'	=> '此區域顯示部署模組時，此模組使用者看到的版面配置。<br/><br/>您可以透過拖放操作重新設定元素的位置，如欄位、列和面板；將其拖放到工具箱的垃圾區即可刪除；將其從工具箱拖放至版面配置的預期位置即可新增元素。'
    ),
    'dropdownEditor'=>array(
        'default'	=> '左側顯示兩欄。右邊的欄標記為目前版面配置或版面配置預覽，您可在此處變更模組版面配置。左邊的欄標題為工具箱，其中包含編輯版面配置時使用的有用元素和工具。<br/><br/>如果版面配置區標題為目前版面配置，則您處理的是目前由模組用於顯示的版面配置副本。<br/><br/>如果標題為版面配置預覽，您處理的是先前按一下「儲存」按鈕建立的副本，它可能與此模組使用者看到的版本有所不同。',
        'dropdownaddbtn'=> '按一下此按鈕在下拉式清單中新增項目。',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'在實例的工作室中進行的自訂設定，可在另一個實例內封裝和部署。<br><br>請提供<b>封裝名稱</b>。您可以為封裝提供<b>作者</b>和<b>描述</b>資訊。<br><br>選取包含此自訂的模組以匯出。（只會顯示包含自訂的模組供您選取。）<br><br>按一下<b>匯出</b>以便為包含自訂的封裝建立一個 .zip 檔案。該 .zip 檔案可透過<b>模組載入器</b>上載至另一個實例。',
        'exportCustomBtn'=>'按一下<b>匯出</b>為包含您想要匯出之自訂的封裝建立一個 .zip 檔案。',
        'name'=>'封裝上載至工作時等待安裝後，封裝<b>名稱</b>將顯示在模組載入器中。',
        'author'=>'<b>作者</b>是建立此封裝的實體名稱。作者可以是個人或公司。<br><br>封裝完成上載準備在工作室安裝之後，作者將在模組載入器中顯示。',
        'description'=>'封裝上載至工作時等待安裝後，封裝<b>描述</b>將顯示在模組載入器中。',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> '歡迎來到<b>開發者工具</b1>區域。<br/><br/>使用此區域的工具建立和管理標準及自訂的模組和欄位。',
        'studioBtn'	=> '使用<b>工作室</b>，透過變更欄位排列、選取可用欄位和建立自訂資料欄位來自訂已安裝模組。',
        'mbBtn'		=> '使用<b>模組建立器</b>建立新模組。',
        'appBtn' 	=> '使用應用程式模式自訂程式的不同屬性，如首頁顯示的 TPS 報表的數量。',
        'backBtn'	=> '返回上一步。',
        'studioHelp'=> '使用<b>工作室</b>自訂已安裝模組。',
        'moduleBtn'	=> '按一下以編輯此模組。',
        'moduleHelp'=> '選取您想要編輯的模組元件。',
        'fieldsBtn'	=> '控制模組中的<b>欄位</b>，以編輯模組中儲存的資訊。<br/><br/>您可在此處編輯和建立自訂欄位。',
        'layoutsBtn'=> '自訂編輯、詳情、清單和搜尋檢視表的<b>版面配置</b>。',
        'subpanelBtn'=> '編輯此模組的子面板顯示的資訊。',
        'layoutsHelp'=> '選取<b>需要編輯的版面配置</b>。<br/<br/>如需變更包含可用於輸入資料之資料欄位的版面配置，按一下<b>編輯檢視表</b>。<br/><br/>如需變更編輯檢視表欄位中所輸入資料的版面配置，按一下<b>詳情檢視表</b>。<br/><br/>如需變更預設清單中顯示的欄，按一下<b>清單檢視表</b>。<br/><br/>如需變更基礎和進階搜尋表單版面配置，按一下<b>搜尋</b>。',
        'subpanelHelp'=> '選取需要編輯的<b>子面板</b>。',
        'searchHelp' => '選取需要編輯的<b>搜尋</b>版面配置。',
        'labelsBtn'	=> '編輯<b>標籤</b>，顯示該模組的值。',
        'newPackage'=>'按一下<b>新封裝</b>以建立新的封裝。',
        'mbHelp'    => '<b>歡迎來到模組建立器。</b><br/><br/>使用<b>模組建立器</b>建立封裝，其中包含基於標準和自訂對象的自訂模組<br/><br/>首先按一下<b>新增封裝</b>以建立一個新封裝，或選取一個封裝進行編輯。<br/><br/><b>封裝</b>可作為自訂模組的容器，所有自訂個模組都屬於同一專案。封裝可包含一個或多個自訂模組，它們互相關聯並與應用程式中的其他模組關聯。 <br/><br/>例如，您可能要建立一個封裝，其中包含一個與標準帳戶模組關聯的自訂模組。或者，您可能要建立一個包含多個模組的封裝，其中的模組作為一個專案相互協作，並且互相關聯並與應用程式中的其他模組關聯。',
        'exportBtn' => '按一下<b>匯出自訂</b>以建立一個封裝，此封裝包含工作室中設定的適用於指定模組的自訂。',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'下拉式清單編輯器',

//ASSISTANT
'LBL_AS_SHOW' => '未來顯示助理。',
'LBL_AS_IGNORE' => '未來忽略助理',
'LBL_AS_SAYS' => '助理說：',

//STUDIO2
'LBL_MODULEBUILDER'=>'模組建立器',
'LBL_STUDIO' => '工作室',
'LBL_DROPDOWNEDITOR' => '下拉式清單編輯器',
'LBL_EDIT_DROPDOWN'=>'編輯下拉式清單',
'LBL_DEVELOPER_TOOLS' => '開發者工具',
'LBL_SUGARPORTAL' => 'Sugar 入口網站編輯器',
'LBL_SYNCPORTAL' => '同步入口網站',
'LBL_PACKAGE_LIST' => '封裝清單',
'LBL_HOME' => '首頁',
'LBL_NONE'=>'-無-',
'LBL_DEPLOYE_COMPLETE'=>'部署完成',
'LBL_DEPLOY_FAILED'   =>'部署流程中出錯，您的封裝可能未正確安裝。',
'LBL_ADD_FIELDS'=>'新增自訂欄位',
'LBL_AVAILABLE_SUBPANELS'=>'可用子面板',
'LBL_ADVANCED'=>'進階',
'LBL_ADVANCED_SEARCH'=>'進階搜尋',
'LBL_BASIC'=>'基礎',
'LBL_BASIC_SEARCH'=>'基礎搜尋',
'LBL_CURRENT_LAYOUT'=>'版面配置',
'LBL_CURRENCY' => '貨幣',
'LBL_CUSTOM' => '自訂',
'LBL_DASHLET'=>'Sugar Dashlet',
'LBL_DASHLETLISTVIEW'=>'Sugar Dashlet 清單檢視表',
'LBL_DASHLETSEARCH'=>'Sugar Dashlet 搜尋',
'LBL_POPUP'=>'快顯檢視表',
'LBL_POPUPLIST'=>'快顯清單檢視表',
'LBL_POPUPLISTVIEW'=>'快顯清單檢視表',
'LBL_POPUPSEARCH'=>'快顯搜尋',
'LBL_DASHLETSEARCHVIEW'=>'Sugar Dashlet 搜尋',
'LBL_DISPLAY_HTML'=>'顯示 HTML 程式碼',
'LBL_DETAILVIEW'=>'詳情檢視表',
'LBL_DROP_HERE' => '[拖放於此處]',
'LBL_EDIT'=>'編輯',
'LBL_EDIT_LAYOUT'=>'編輯配置',
'LBL_EDIT_ROWS'=>'編輯列',
'LBL_EDIT_COLUMNS'=>'編輯欄',
'LBL_EDIT_LABELS'=>'編輯標籤',
'LBL_EDIT_PORTAL'=>'編輯入口網站',
'LBL_EDIT_FIELDS'=>'編輯欄位',
'LBL_EDITVIEW'=>'編輯檢視表',
'LBL_FILTER_SEARCH' => "搜尋",
'LBL_FILLER'=>'（篩選）',
'LBL_FIELDS'=>'欄位',
'LBL_FAILED_TO_SAVE' => '儲存失敗',
'LBL_FAILED_PUBLISHED' => '發佈失敗',
'LBL_HOMEPAGE_PREFIX' => '我的',
'LBL_LAYOUT_PREVIEW'=>'版面配置預覽',
'LBL_LAYOUTS'=>'版面配置',
'LBL_LISTVIEW'=>'清單檢視表',
'LBL_RECORDVIEW'=>'記錄檢視表',
'LBL_MODULE_TITLE' => '工作室',
'LBL_NEW_PACKAGE' => '新封裝',
'LBL_NEW_PANEL'=>'新面板',
'LBL_NEW_ROW'=>'新列',
'LBL_PACKAGE_DELETED'=>'已刪除封裝',
'LBL_PUBLISHING' => '正在發佈...',
'LBL_PUBLISHED' => '已發佈',
'LBL_SELECT_FILE'=> '選取檔案',
'LBL_SAVE_LAYOUT'=> '儲存版面配置',
'LBL_SELECT_A_SUBPANEL' => '選取一個子面板',
'LBL_SELECT_SUBPANEL' => '選取子面板',
'LBL_SUBPANELS' => '子面板',
'LBL_SUBPANEL' => '子面板',
'LBL_SUBPANEL_TITLE' => '標題：',
'LBL_SEARCH_FORMS' => '搜尋',
'LBL_STAGING_AREA' => '臨時區域（拖放項目至此處）',
'LBL_SUGAR_FIELDS_STAGE' => 'Sugar 欄位（按一下項目以新增至臨時區域）',
'LBL_SUGAR_BIN_STAGE' => 'Sugar 桶（按一下項目以新增至臨時區域）',
'LBL_TOOLBOX' => '工具箱',
'LBL_VIEW_SUGAR_FIELDS' => '檢視 Sugar 欄位',
'LBL_VIEW_SUGAR_BIN' => '檢視 Sugar 桶',
'LBL_QUICKCREATE' => '快速建立',
'LBL_EDIT_DROPDOWNS' => '編輯全域下拉式清單',
'LBL_ADD_DROPDOWN' => '新增全域下拉式清單',
'LBL_BLANK' => '-空白-',
'LBL_TAB_ORDER' => '標籤順序',
'LBL_TAB_PANELS' => '啟用標籤',
'LBL_TAB_PANELS_HELP' => '啟用標籤後，在每個區段使用「類型」下拉式方塊以定義其顯示方式（標籤或面板）。<br />',
'LBL_TABDEF_TYPE' => '顯示類型',
'LBL_TABDEF_TYPE_HELP' => '選取此區段的顯示方式。只有在此檢視表中啟用標籤時，此選項才會生效。',
'LBL_TABDEF_TYPE_OPTION_TAB' => '標籤',
'LBL_TABDEF_TYPE_OPTION_PANEL' => '面板',
'LBL_TABDEF_TYPE_OPTION_HELP' => '選取面板，以讓此面板在版面配置檢視表中顯示。選取標籤，以讓此標籤在版面配置內的獨立標籤內顯示。為面板指定標籤後，所有設定為作為面板顯示的後續面板都將在標籤內顯示。<br/>對於選中的標籤，將對其下一個面板使用新標籤。若第一個面板下的面板選取了標籤，第一個面板必須是標籤。',
'LBL_TABDEF_COLLAPSE' => '摺疊',
'LBL_TABDEF_COLLAPSE_HELP' => '選取以將此面板的預設狀態設定為摺疊。',
'LBL_DROPDOWN_TITLE_NAME' => '名稱',
'LBL_DROPDOWN_LANGUAGE' => '語言',
'LBL_DROPDOWN_ITEMS' => '清單項目',
'LBL_DROPDOWN_ITEM_NAME' => '項目名稱',
'LBL_DROPDOWN_ITEM_LABEL' => '顯示標籤',
'LBL_SYNC_TO_DETAILVIEW' => '同步至詳情檢視表',
'LBL_SYNC_TO_DETAILVIEW_HELP' => '選取此選項以將編輯檢視表同步至相應的詳情檢視表版面配置。在編輯檢視表中按一下「儲存」或「儲存和部署」按鈕後，編輯檢視表中的欄位和欄位排列<br>將自動同步並儲存至詳情檢視表。<br>無法在詳細資料檢視表中變更版面配置。',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => '此詳細資料檢視表將同步至相應的編輯檢視表。<br>詳情檢視表中的欄位和欄位排列將對應至編輯檢視表。<br>無法在此頁面儲存或部署詳細資料檢視表的變更。請在編輯檢視表做出變更或取消版面配置同步。',
'LBL_COPY_FROM' => '複製自',
'LBL_COPY_FROM_EDITVIEW' => '從編輯檢視表複製',
'LBL_DROPDOWN_BLANK_WARNING' => '項目名稱和顯示標籤都需要值。如需新增空白項目，請按一下「新增」而無須為項目名稱和顯示標籤輸入任何值。',
'LBL_DROPDOWN_KEY_EXISTS' => '金鑰在清單中已存在',
'LBL_DROPDOWN_LIST_EMPTY' => '清單必須包含至少一個啟用項目',
'LBL_NO_SAVE_ACTION' => '找不到此檢視表的儲存行動。',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation：錯誤的文件格式',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** 表示組合欄位。組合欄位由多個欄位組成。例如，「地址」是一個組合欄位，其中包含「街道地址」、「城市」、「郵遞區號」、「省」和「國家」。<br><br>按兩下組合欄位，查看其中包含的欄位。',
'LBL_COMBO_FIELD_CONTAINS' => '包含：',

'LBL_WIRELESSLAYOUTS'=>'行動版面配置',
'LBL_WIRELESSEDITVIEW'=>'行動編輯檢視表',
'LBL_WIRELESSDETAILVIEW'=>'行動詳情檢視表',
'LBL_WIRELESSLISTVIEW'=>'行動清單檢視表',
'LBL_WIRELESSSEARCH'=>'行動搜尋',

'LBL_BTN_ADD_DEPENDENCY'=>'新增相依性',
'LBL_BTN_EDIT_FORMULA'=>'編輯計算公式',
'LBL_DEPENDENCY' => '相依性',
'LBL_DEPENDANT' => '相依',
'LBL_CALCULATED' => '計算值',
'LBL_READ_ONLY' => '唯讀',
'LBL_FORMULA_BUILDER' => '計算公式建立器',
'LBL_FORMULA_INVALID' => '無效計算公式',
'LBL_FORMULA_TYPE' => '計算公式類型必須為',
'LBL_NO_FIELDS' => '找不到欄位',
'LBL_NO_FUNCS' => '找不到函數',
'LBL_SEARCH_FUNCS' => '搜尋函數...',
'LBL_SEARCH_FIELDS' => '搜尋欄位...',
'LBL_FORMULA' => '計算公式',
'LBL_DYNAMIC_VALUES_CHECKBOX' => '相依',
'LBL_DEPENDENT_DROPDOWN_HELP' => '選取父項時，從左側從屬下拉式清單中的可用選項中將選項拖動至右邊，以使選項生效。如果父項下沒有任何項目，選取父項時，將不會顯示附屬下拉式清單。',
'LBL_AVAILABLE_OPTIONS' => '可用選項',
'LBL_PARENT_DROPDOWN' => '父代下拉式清單',
'LBL_VISIBILITY_EDITOR' => '可見度編輯器',
'LBL_ROLLUP' => '彙總',
'LBL_RELATED_FIELD' => '相關欄位',
'LBL_CONFIG_PORTAL_URL'=>'連結至自訂標誌圖像的 URL。建議標誌尺寸為 163 Ã 18 像素。',
'LBL_PORTAL_ROLE_DESC' => '切勿刪除此角色。自訂自助入口網站角色是 Sugar 入口網站激活流程中建立的系統產生角色。在該角色內使用存取控制可在 Sugar 入口網站啟用和/或停用錯誤、實例或知識庫模組。切勿修改此角色的任何其他存取控制，以避免未知和不可預測的系統行為。如意外刪除此角色，請透過停用並啟用 Sugar 入口網站，重新建立此角色。',

//RELATIONSHIPS
'LBL_MODULE' => '模組',
'LBL_LHS_MODULE'=>'主模組',
'LBL_CUSTOM_RELATIONSHIPS' => '* Studio 中建立的關係',
'LBL_RELATIONSHIPS'=>'關係',
'LBL_RELATIONSHIP_EDIT' => '編輯關係',
'LBL_REL_NAME' => '名稱',
'LBL_REL_LABEL' => '標籤',
'LBL_REL_TYPE' => '類型',
'LBL_RHS_MODULE'=>'相關模組',
'LBL_NO_RELS' => '沒有關係',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'選用條件' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'欄',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'值',
'LBL_SUBPANEL_FROM'=>'子面板來自',
'LBL_RELATIONSHIP_ONLY'=>'不會為此關係建立可見元素，因為這兩個模組之間有既存的可見關係。',
'LBL_ONETOONE' => '一對一',
'LBL_ONETOMANY' => '一對多',
'LBL_MANYTOONE' => '多對一',
'LBL_MANYTOMANY' => '多對多',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => '選取一項功能或元件。',
'LBL_QUESTION_MODULE1' => '選取一個模組。',
'LBL_QUESTION_EDIT' => '選取一個模組來編輯。',
'LBL_QUESTION_LAYOUT' => '選取一個版面配置來編輯。',
'LBL_QUESTION_SUBPANEL' => '選取一個子面板來編輯。',
'LBL_QUESTION_SEARCH' => '選取一個搜尋版面配置來編輯。',
'LBL_QUESTION_MODULE' => '選取一個模組元件來編輯。',
'LBL_QUESTION_PACKAGE' => '選取一個封裝來編輯，或建立新封裝。',
'LBL_QUESTION_EDITOR' => '選取一個工具。',
'LBL_QUESTION_DROPDOWN' => '選取一個下拉式清單來編輯，或建立新的下拉式清單。',
'LBL_QUESTION_DASHLET' => '選取一個 Dashlet 版面配置來編輯。',
'LBL_QUESTION_POPUP' => '選取一個快顯版面配置來編輯。',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'關聯至',
'LBL_NAME'=>'名稱',
'LBL_LABELS'=>'標籤',
'LBL_MASS_UPDATE'=>'大規模更新',
'LBL_AUDITED'=>'稽核',
'LBL_CUSTOM_MODULE'=>'模組',
'LBL_DEFAULT_VALUE'=>'預設值',
'LBL_REQUIRED'=>'必填',
'LBL_DATA_TYPE'=>'類型',
'LBL_HCUSTOM'=>'自訂',
'LBL_HDEFAULT'=>'預設',
'LBL_LANGUAGE'=>'語言：',
'LBL_CUSTOM_FIELDS' => 'Studio 已建立 * 欄位',

//SECTION
'LBL_SECTION_EDLABELS' => '編輯標籤',
'LBL_SECTION_PACKAGES' => '封裝',
'LBL_SECTION_PACKAGE' => '封裝',
'LBL_SECTION_MODULES' => '模組',
'LBL_SECTION_PORTAL' => '入口網站',
'LBL_SECTION_DROPDOWNS' => '下拉式清單',
'LBL_SECTION_PROPERTIES' => '屬性',
'LBL_SECTION_DROPDOWNED' => '編輯下拉式清單',
'LBL_SECTION_HELP' => '說明',
'LBL_SECTION_ACTION' => '動作',
'LBL_SECTION_MAIN' => '主要',
'LBL_SECTION_EDPANELLABEL' => '編輯面板標籤',
'LBL_SECTION_FIELDEDITOR' => '編輯欄位',
'LBL_SECTION_DEPLOY' => '部署',
'LBL_SECTION_MODULE' => '模組',
'LBL_SECTION_VISIBILITY_EDITOR'=>'編輯可見度',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'預設',
'LBL_HIDDEN'=>'已隱藏',
'LBL_AVAILABLE'=>'可用',
'LBL_LISTVIEW_DESCRIPTION'=>'下方顯示三欄。<b>預設</b>欄包含預設在清單檢視表顯示的欄位；<b>其他</b>欄包含使用者可選取使用以建立自訂清單檢視表的欄位；<b>可用</b>欄包含您作為管理員可使用的欄位，它們可新增至預設或其他欄供使用者使用。',
'LBL_LISTVIEW_EDIT'=>'清單檢視編輯器',

//Manager Backups History
'LBL_MB_PREVIEW'=>'預覽',
'LBL_MB_RESTORE'=>'還原',
'LBL_MB_DELETE'=>'刪除',
'LBL_MB_COMPARE'=>'比較',
'LBL_MB_DEFAULT_LAYOUT'=>'預設版面配置',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'新增',
'LBL_BTN_SAVE'=>'儲存',
'LBL_BTN_SAVE_CHANGES'=>'儲存變更',
'LBL_BTN_DONT_SAVE'=>'捨棄變更',
'LBL_BTN_CANCEL'=>'取消',
'LBL_BTN_CLOSE'=>'關閉',
'LBL_BTN_SAVEPUBLISH'=>'儲存與部署',
'LBL_BTN_NEXT'=>'下一步',
'LBL_BTN_BACK'=>'返回',
'LBL_BTN_CLONE'=>'再製',
'LBL_BTN_COPY' => '複製',
'LBL_BTN_COPY_FROM' => '複製自…',
'LBL_BTN_ADDCOLS'=>'新增欄',
'LBL_BTN_ADDROWS'=>'新增列',
'LBL_BTN_ADDFIELD'=>'新增欄位',
'LBL_BTN_ADDDROPDOWN'=>'新增下拉式清單',
'LBL_BTN_SORT_ASCENDING'=>'遞增排序',
'LBL_BTN_SORT_DESCENDING'=>'遞減排序',
'LBL_BTN_EDLABELS'=>'編輯標籤',
'LBL_BTN_UNDO'=>'復原',
'LBL_BTN_REDO'=>'取消復原',
'LBL_BTN_ADDCUSTOMFIELD'=>'新增自訂欄位',
'LBL_BTN_EXPORT'=>'匯出自訂',
'LBL_BTN_DUPLICATE'=>'複製',
'LBL_BTN_PUBLISH'=>'發佈',
'LBL_BTN_DEPLOY'=>'部署',
'LBL_BTN_EXP'=>'匯出',
'LBL_BTN_DELETE'=>'刪除',
'LBL_BTN_VIEW_LAYOUTS'=>'檢視版面配置',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'檢視行動版面配置',
'LBL_BTN_VIEW_FIELDS'=>'檢視欄位',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'檢視關係',
'LBL_BTN_ADD_RELATIONSHIP'=>'新增關係',
'LBL_BTN_RENAME_MODULE' => '變更模組名稱',
'LBL_BTN_INSERT'=>'插入',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> '錯誤：欄位已存在',
'ERROR_INVALID_KEY_VALUE'=> "錯誤：無效金鑰值：[']",
'ERROR_NO_HISTORY' => '找不到歷史檔案',
'ERROR_MINIMUM_FIELDS' => '版面配置必須至少包含一個欄位',
'ERROR_GENERIC_TITLE' => '出錯',
'ERROR_REQUIRED_FIELDS' => '您確定要繼續嗎？版面配置缺乏下列必要欄位：',
'ERROR_ARE_YOU_SURE' => '您確定要繼續嗎？',

'ERROR_CALCULATED_MOBILE_FIELDS' => '下列欄位含有已計算的值，這些值將不會在 SugarCRM Mobile 編輯檢視表中進行實時重算：',
'ERROR_CALCULATED_PORTAL_FIELDS' => '下列欄位含有已計算的值，這些值將不會在 SugarCRM 入口網站編輯檢視表中實時重算：',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => '下列模組已停用：',
    'LBL_PORTAL_ENABLE_MODULES' => '若想在入口網站啟用它們，請在<a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">此處</a>啟用。',
    'LBL_PORTAL_CONFIGURE' => '設定入口網站',
    'LBL_PORTAL_THEME' => '主題入口網站',
    'LBL_PORTAL_ENABLE' => '啟用',
    'LBL_PORTAL_SITE_URL' => '您的入口網站：',
    'LBL_PORTAL_APP_NAME' => '應用程式名稱',
    'LBL_PORTAL_LOGO_URL' => '標誌 URL',
    'LBL_PORTAL_LIST_NUMBER' => '清單中顯示的記錄數量',
    'LBL_PORTAL_DETAIL_NUMBER' => '詳情檢視表中顯示的欄位數量',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => '全域搜尋顯示的結果數量',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => '預設指派為新入口網站註冊｜',

'LBL_PORTAL'=>'入口網站',
'LBL_PORTAL_LAYOUTS'=>'入口網站版面配置',
'LBL_SYNCP_WELCOME'=>'請輸入您想要更新的入口網站實例 URL。',
'LBL_SP_UPLOADSTYLE'=>'請選取一個樣式表從您的電腦上載。<br>下次執行同步時，此樣式表將在 Sugar 入口網站實施。',
'LBL_SP_UPLOADED'=> '已上載',
'ERROR_SP_UPLOADED'=>'請確保您正在上載一個 css 樣式表。',
'LBL_SP_PREVIEW'=>'此處是使用樣式表後 Sugar 入口網站的預覽圖。',
'LBL_PORTALSITE'=>'Sugar 入口網站 URL：',
'LBL_PORTAL_GO'=>'前往',
'LBL_UP_STYLE_SHEET'=>'上載樣式表',
'LBL_QUESTION_SUGAR_PORTAL' => '選取 Sugar 入口網站版面配置來編輯。',
'LBL_QUESTION_PORTAL' => '選取一個入口網站版面配置來編輯。',
'LBL_SUGAR_PORTAL'=>'Sugar 入口網站編輯器',
'LBL_USER_SELECT' => '-- 選取 --',

//PORTAL PREVIEW
'LBL_CASES'=>'實例',
'LBL_NEWSLETTERS'=>'新聞稿',
'LBL_BUG_TRACKER'=>'錯誤追蹤器',
'LBL_MY_ACCOUNT'=>'我的帳戶',
'LBL_LOGOUT'=>'登出',
'LBL_CREATE_NEW'=>'建立新',
'LBL_LOW'=>'低',
'LBL_MEDIUM'=>'媒體',
'LBL_HIGH'=>'高',
'LBL_NUMBER'=>'編號：',
'LBL_PRIORITY'=>'優先順序：',
'LBL_SUBJECT'=>'主題',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'封裝名稱：',
'LBL_MODULE_NAME'=>'模組名稱：',
'LBL_MODULE_NAME_SINGULAR' => '單個模組名稱：',
'LBL_AUTHOR'=>'作者：',
'LBL_DESCRIPTION'=>'描述：',
'LBL_KEY'=>'金鑰：',
'LBL_ADD_README'=>'讀我',
'LBL_MODULES'=>'模組：',
'LBL_LAST_MODIFIED'=>'上次修改：',
'LBL_NEW_MODULE'=>'新模組',
'LBL_LABEL'=>'複數標籤',
'LBL_LABEL_TITLE'=>'標籤',
'LBL_SINGULAR_LABEL' => '單數標籤',
'LBL_WIDTH'=>'寬度',
'LBL_PACKAGE'=>'封裝：',
'LBL_TYPE'=>'類型：',
'LBL_TEAM_SECURITY'=>'小組安全',
'LBL_ASSIGNABLE'=>'可指派的',
'LBL_PERSON'=>'個人',
'LBL_COMPANY'=>'公司',
'LBL_ISSUE'=>'問題',
'LBL_SALE'=>'銷售',
'LBL_FILE'=>'檔案',
'LBL_NAV_TAB'=>'瀏覽索引標籤',
'LBL_CREATE'=>'建立',
'LBL_LIST'=>'清單',
'LBL_VIEW'=>'檢視',
'LBL_LIST_VIEW'=>'清單檢視表',
'LBL_HISTORY'=>'檢視歷史',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'還原預設版面配置',
'LBL_ACTIVITIES'=>'活動流',
'LBL_SEARCH'=>'搜尋',
'LBL_NEW'=>'新',
'LBL_TYPE_BASIC'=>'基礎',
'LBL_TYPE_COMPANY'=>'公司',
'LBL_TYPE_PERSON'=>'個人',
'LBL_TYPE_ISSUE'=>'問題',
'LBL_TYPE_SALE'=>'銷售',
'LBL_TYPE_FILE'=>'檔案',
'LBL_RSUB'=>'此為將在模組中顯示的子面板',
'LBL_MSUB'=>'此為您的模組為其他關聯模組提供的用於顯示的子面板',
'LBL_MB_IMPORTABLE'=>'允許匯入',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'可見',
'LBL_VE_HIDDEN'=>'已隱藏',
'LBL_PACKAGE_WAS_DELETED'=>'已刪除[[package]]',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'匯出自訂',
'LBL_EC_NAME'=>'封裝名稱：',
'LBL_EC_AUTHOR'=>'作者：',
'LBL_EC_DESCRIPTION'=>'描述：',
'LBL_EC_KEY'=>'金鑰：',
'LBL_EC_CHECKERROR'=>'請選取一個模組。',
'LBL_EC_CUSTOMFIELD'=>'已自訂欄位',
'LBL_EC_CUSTOMLAYOUT'=>'已自訂版面配置',
'LBL_EC_CUSTOMDROPDOWN' => '已自訂下拉式清單',
'LBL_EC_NOCUSTOM'=>'沒有任何已自訂模組。',
'LBL_EC_EXPORTBTN'=>'匯出',
'LBL_MODULE_DEPLOYED' => '已部署模組。',
'LBL_UNDEFINED' => '未定義',
'LBL_EC_CUSTOMLABEL'=>'已自訂標籤',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => '無法抓取資料',
'LBL_AJAX_TIME_DEPENDENT' => '正在執行時間相依行動。請稍等並在數秒後再試一次。',
'LBL_AJAX_LOADING' => '載入中...',
'LBL_AJAX_DELETING' => '正在刪除...',
'LBL_AJAX_BUILDPROGRESS' => '建立進行中...',
'LBL_AJAX_DEPLOYPROGRESS' => '部署進行中...',
'LBL_AJAX_FIELD_EXISTS' =>'您輸入的欄位名稱已存在。請輸入新的欄位名稱。',
//JS
'LBL_JS_REMOVE_PACKAGE' => '您確定要移除這個封裝嗎？這項操作將永久刪除與此封裝相關的所有檔案。',
'LBL_JS_REMOVE_MODULE' => '您確定要移除這個模組嗎？這項操作將永久刪除與此模組相關的所有檔案。',
'LBL_JS_DEPLOY_PACKAGE' => '重新部署此模組將複寫您在工作時中設定的任何自訂。您確定要繼續嗎？',

'LBL_DEPLOY_IN_PROGRESS' => '正在部署封裝',
'LBL_JS_VALIDATE_NAME'=>'名稱－必須以字母開頭，且只能包含字母、數字和底線。不得使用空格或其他特殊字元。',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'封裝金鑰已存在',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'封裝名稱已存在',
'LBL_JS_PACKAGE_NAME'=>'封裝名稱－必須以字母開頭，且只能包含字母、數字和底線。不得使用空格或其他特殊字元。',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'金鑰－必須是英數字元且必須以字母開頭。',
'LBL_JS_VALIDATE_KEY'=>'金鑰－必須是英數字元，必須以字母開頭且不得包含空格。',
'LBL_JS_VALIDATE_LABEL'=>'請輸入將用作模組顯示名稱的標籤',
'LBL_JS_VALIDATE_TYPE'=>'請選取您想要從上述清單建立的模組的類型',
'LBL_JS_VALIDATE_REL_NAME'=>'名稱－必須為不包含空格的英數字元',
'LBL_JS_VALIDATE_REL_LABEL'=>'標籤－請新增一個可顯示於子面板上的標籤',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => '您確定要刪除此必要的下拉式清單項目嗎？這可能影響應用程式的功能。',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => '您確定要刪除此下拉式清單項目嗎？刪除「結束並贏得客戶」或「結束但客戶流失」階段將導致預測模組無法正常工作。',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => '您確定要刪除此新銷售狀態嗎？刪除此狀態將導致商機模組的營收項目工作流程無法正常工作。',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => '您確定要刪除「進行中」銷售狀態嗎？刪除此狀態將導致商機模組的營收項目工作流程無法正常工作。',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => '您確定要刪除「已結束並贏得客戶」銷售階段嗎？刪除此階段將導致預測模組無法正常工作。',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => '您確定要刪除「已結束但客戶流失」銷售階段嗎？刪除此階段將導致預測模組無法正常工作。',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'您確定要刪除這個關係嗎？<br>注意：此作業可能無法在數分鐘內完成。',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'這將使這個關係永久化。您確定要部署此關係嗎？',
'LBL_CONFIRM_DONT_SAVE' => '自上次儲存以來已進行變更，您想要儲存嗎？',
'LBL_CONFIRM_DONT_SAVE_TITLE' => '儲存變更？',
'LBL_CONFIRM_LOWER_LENGTH' => '資料可能已截斷，且該作業 不能被撤銷，您確定要繼續嗎？',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'請按照您將在欄位輸入的資料，選取適當的資料類型。',
'LBL_POPHELP_FTS_FIELD_CONFIG' => '設定欄位為可全文字搜尋。',
'LBL_POPHELP_FTS_FIELD_BOOST' => '增強是強化記錄欄位相關性的過程。<br />在執行搜尋時，擁有更高增強等級的欄位將擁有更大的權數。執行搜尋時，包含擁有權數更高的欄位的匹配記錄，將顯示在搜尋結果中較前的位置。<br />預設值為 1.0，表示中性增強。如需應用正增強，可接受任何高於 1 的浮點值。負增強使用低於 1 的值。例如，數值 1.35 會將欄位增強 135%。使用數值 0.60 將應用負增強。<br />注意︰之前版本要求您必須執行全文字搜尋重新索引。這不再必要。',
'LBL_POPHELP_IMPORTABLE'=>'<b>是</b>：欄位將包含在匯入作業中。<br><b>否</b>：欄位將不會包含在匯入中。<br><b>必要</b>：必須為任何匯入提供欄位值。',
'LBL_POPHELP_PII'=>'此欄位將自動標記審核，可在個人資訊視圖中查看。<br>如果紀錄與資訊隱私擦除請求相關時，還可以永久删除個人訊欄位。<br>擦除操作將通過資訊隱私模組進行，可由管理員或使用者在資訊隱私管理器角色中執行。',
'LBL_POPHELP_IMAGE_WIDTH'=>'輸入一個寬度值，以像素為單位。<br>上載圖像將自動調整為此寬度。',
'LBL_POPHELP_IMAGE_HEIGHT'=>'輸入一個高度值，以像素為單位。<br>上載圖像將自動調整為此高度。',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'當在此模組上使用全域搜尋功能來搜尋記錄時使用此欄位。',
//Revert Module labels
'LBL_RESET' => '重設',
'LBL_RESET_MODULE' => '重設模組',
'LBL_REMOVE_CUSTOM' => '移除自訂',
'LBL_CLEAR_RELATIONSHIPS' => '清除關係',
'LBL_RESET_LABELS' => '重設標籤',
'LBL_RESET_LAYOUTS' => '重設版面配置',
'LBL_REMOVE_FIELDS' => '移除自訂欄位',
'LBL_CLEAR_EXTENSIONS' => '清除擴充',

'LBL_HISTORY_TIMESTAMP' => '時間戳記',
'LBL_HISTORY_TITLE' => '歷史',

'fieldTypes' => array(
                'varchar'=>'文字欄位',
                'int'=>'整數',
                'float'=>'浮點',
                'bool'=>'核取方塊',
                'enum'=>'下拉式清單',
                'multienum' => '多選',
                'date'=>'日期',
                'phone' => '電話',
                'currency' => '貨幣',
                'html' => 'HTML',
                'radioenum' => '選項按鈕',
                'relate' => '相關',
                'address' => '地址',
                'text' => '文字區域',
                'url' => 'URL',
                'iframe' => 'IFrame',
                'image' => '圖像',
                'encrypt'=>'加密',
                'datetimecombo' =>'日期時間',
                'decimal'=>'小數點',
),
'labelTypes' => array(
    "" => "最常使用標籤",
    "all" => "所有標籤",
),

'parent' => 'Flex 相關',

'LBL_ILLEGAL_FIELD_VALUE' =>"下拉式清單金鑰不得包含引號。",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"您正選取從下拉式清單中移除此項目。任何使用此清單並將此項目作為值的下拉式欄位都不再顯示值，並將無法從下拉式欄位中選取此值。您確定要繼續嗎？",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'所有模組',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0}（相關 {1} ID）',
'LBL_HEADER_COPY_FROM_LAYOUT' => '複製版面配置',
);
