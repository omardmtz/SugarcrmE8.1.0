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

$connector_strings = array(
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
Sugar kurulumunuzu yeni bir uygulama olarak kaydederek Google\'dan bir Müşteri Anahtarı ve şifresi alın.
<br/><br>Kurulumunuzu kaydetmeniz için gereken adımlar:
<br/><br/>
<ol>
<li>Google Developers sitesine gidin:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Uygulamayı kaydetmek istediğiniz Google hesabını kullanarak Oturum Açın.</li>
<li>Yeni bir proje oluşturun</li>
<li>Bir Proje Adı girerek oluştur düğmesine tıklayın.</li>
<li>Proje oluşturulduğunda Google Drive ve Google Contacts API\'yi etkinleştirin</li>
<li>APIs & Auth > Kimlik Bilgileri bölümü altında yeni bir müşteri kimliği oluşturun </li>
<li>Web Uygulamasını seçerek Yapılandırma onay ekranına tıklayın</li>
<li>Bir ürün adına girerek Kaydet düğmesine tıklayın</li>
<li>Onaylı yönlendirme URI\'leri bölümüne şu url\'yi girin: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Müşteri kimliği oluştur düğmesine tıklayın</li>
<li>Aşağıdaki kutucuklara müşteri kimliği ve şifresini kopyalayın</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Müşteri Kimliği',
    'oauth2_client_secret' => 'Müşteri şifresi',
);
