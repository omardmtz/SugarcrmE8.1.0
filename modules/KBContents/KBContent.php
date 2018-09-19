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

class KBContent extends SugarBean {

    const DEFAULT_STATUS = 'draft';
    const ST_DRAFT = 'draft';
    const ST_IN_REVIEW = 'in-review';
    const ST_APPROVED = 'approved';
    const ST_PUBLISHED = 'published';
    const ST_EXPIRED = 'expired';

    public $table_name = "kbcontents";
    public $object_name = "KBContent";
    public $new_schema = true;
    public $module_dir = 'KBContents';

    // Import should be disabled in 7.7
    // public $importable = true;

    public $status;
    public $active_rev;
    public $is_external;
    public $active_date;
    public $exp_date;
    public $kbsapprover_id;

    /**
     * {@inheritDoc}
     * Add new type 'nestedset' that works like relate field.
     */
    public static $relateFieldTypes = array(
        'relate',
        'nestedset',
    );

    /**
     * Return root id for KB categories.
     * @return string for root node of KB categories.
     */
    public function getCategoryRoot()
    {
        $admin = BeanFactory::newBean('Administration');
        $config = $admin->getConfigForModule('KBContents');
        $category = BeanFactory::newBean('Categories');

        if (empty($config['category_root']) || !$category->retrieve($config['category_root'])) {
            $this->setupCategoryRoot();
            $config = $admin->getConfigForModule('KBContents');
        }

        return $config['category_root'];
    }

    /**
     * Setup root for KBContents categories.
     */
    public function setupCategoryRoot()
    {

        $categoryRoot = BeanFactory::newBean('Categories');
        $categoryRoot->name = 'KBContentCategory';

        $apiUser = new User();
        $apiUser->is_admin = '1';
        $api = new RestService();
        $api->user = $apiUser;
        $api->platform = 'base';
        $client = new ConfigModuleApi();
        $client->configSave(
            $api,
            array(
                'category_root' => $categoryRoot->saveAsRoot(),
                'module' => 'KBContents',
            )
        );
    }

    /**
     * Return primary language for KB.
     * @return array Key and label for primary language.
     */
    public function getPrimaryLanguage()
    {
        $langs = $this->getLanguages();
        $default = null;
        foreach ($langs as $lang) {
            if ($lang['primary'] === true) {
                $default = $lang;
                unset($default['primary']);
                $default = array(
                    'label' => reset($default),
                    'key' => key($default)
                );
                break;
            }
        }
        if ($default === null) {
            $this->setupPrimaryLanguage();
            $default = $this->getPrimaryLanguage();
        }
        return $default;
    }

    /**
     * Return available languages for KB.
     * @return array
     */
    public function getLanguages()
    {
        $admin = BeanFactory::newBean('Administration');
        $config = $admin->getConfigForModule('KBContents');
        return isset($config['languages']) ? $config['languages'] : array();
    }

    /**
     * Return pairs `key` => `value` of available languages.
     * @return array
     */
    public function getLanguageOptions()
    {
        $data = $this->getLanguages();
        $result = array();
        foreach ($data as $value) {
            reset($value);
            $result[key($value)] = current($value);
        }
        return $result;
    }

    /**
     * Setup Default Languages for KBContents.
     */
    public function setupPrimaryLanguage()
    {

        $apiUser = new User();
        $apiUser->is_admin = '1';
        $api = new RestService();
        $api->user = $apiUser;
        $api->platform = 'base';
        $client = new ConfigModuleApi();
        $client->configSave(
            $api,
            array(
                'languages' => array(
                    array(
                        'en' => 'English',
                        'primary' => true,
                    ),
                ),
                'module' => 'KBContents',
            )
        );
    }

    /**
     * Overriding parent method just for turn off
     * date formatting in loading because this leads
     * to lost seconds in datetime fields.
     */
    public function check_date_relationships_load()
    {
        global $disable_date_format;
        $old_disable_date_format  = $disable_date_format;
        $disable_date_format = true;
        parent::check_date_relationships_load();
        $disable_date_format = $old_disable_date_format;
    }

    /**
     * {@inheritDoc}
     */
    public function save_relationship_changes($is_update, $exclude = array())
    {
        parent::save_relationship_changes($is_update, $exclude);

        if ($is_update) {
            return;
        }

        $doc = $article = null;
        $this->load_relationship('kbdocuments_kbcontents');
        if (empty($this->kbdocument_id)) {
            $doc = BeanFactory::newBean('KBDocuments');
            $doc->new_with_id = true;
            $doc->id = create_guid();
            $doc->name = $this->name;
            $doc->team_set_id = $this->team_set_id;
            $doc->team_id = $this->team_id;
            $doc->save();
            $this->kbdocuments_kbcontents->add($doc);
        } else {
            $doc = $this->kbdocuments_kbcontents->getBeans();
            if (is_array($doc)) {
                $doc = array_shift($doc);
            }
        }

        if (empty($this->kbarticle_id)) {
            $article = BeanFactory::newBean('KBArticles');
            $article->new_with_id = true;
            $article->id = create_guid();
            $article->name = $this->name;
            $article->team_set_id = $this->team_set_id;
            $article->team_id = $this->team_id;
            $article->save();
            $this->load_relationship('kbarticles_kbcontents');
            $this->kbarticles_kbcontents->add($article);
        }

        if (!empty($article) && !empty($doc)) {
            $article->load_relationship('kbdocuments_kbarticles');
            $article->kbdocuments_kbarticles->add($doc);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function save($check_notify = false)
    {
        $dataChanges = empty($this->fetched_row) ? array() : $this->db->getDataChanges($this);
        if(empty($this->id) || !empty($this->new_with_id)) {
            if (empty($this->language)) {
                $lang = $this->getNextAvailableLanguage();
                if ($lang === null) {
                    throw new SugarApiException('There is no available languages for localization');
                }
                $this->language = $lang;
            }
            if (empty($this->revision)) {
                $this->revision = 1;
                if (!empty($this->kbdocument_id) && !empty($this->kbarticle_id)) {
                    $query = new SugarQuery();
                    $query->from(BeanFactory::newBean('KBContents'));
                    $query->select()->fieldRaw('MAX(revision)', 'max_revision');
                    $query->where()
                        ->equals('kbdocument_id', $this->kbdocument_id)
                        ->equals('kbarticle_id', $this->kbarticle_id);

                    $result = $query->execute();
                    if (!empty($result[0]['max_revision'])) {
                        $this->revision = $result[0]['max_revision'] + 1;
                    }
                }
            }
            if (empty($this->status)) {
                $this->status = self::DEFAULT_STATUS;
            }
            $this->active_rev = (int) empty($this->kbarticle_id);
        }

        $this->skipUsefulnessChanges($dataChanges);

        if (isset($dataChanges['status'])) {
            switch ($dataChanges['status']['after']) {
                // automatically set ApprovedBy if status was changed to Approved
                case self::ST_APPROVED:
                    $user = $GLOBALS['current_user'];
                    $this->kbsapprover_id = $user->id;
                    break;
            }
            switch ($dataChanges['status']['before']) {
                case self::ST_PUBLISHED:
                    if (!in_array($dataChanges['status']['after'], array(self::ST_APPROVED, self::ST_EXPIRED))) {
                        $this->active_date = '';
                    }
                    break;
            }
        }

        // automatically set ApprovedBy for new record with Approved status
        if ($this->new_with_id && $this->status === self::ST_APPROVED) {
            $this->load_relationship('kbsapprovers_kbcontents');
            $user = $GLOBALS['current_user'];
            $this->kbsapprover_id = $user->id;
        }

        $this->checkActiveRev();

        $beanId = parent::save($check_notify);
        if (!empty($this->category_id)) {
            $this->updateCategoryExternalVisibility($this->category_id);
        }
        if (isset($dataChanges['category_id'])) {
            if ($dataChanges['category_id']['before']
                    && ($dataChanges['category_id']['before'] != $dataChanges['category_id']['after'])) {
                $this->updateCategoryExternalVisibility($dataChanges['category_id']['before']);
            }
        }
        return $beanId;
    }

    /**
     * Returns next available language for localization for current bean.
     *
     * @return string|null Language key for next available language. Null returned if there is no available.
     */
    private function getNextAvailableLanguage()
    {
        $languages = $this->getLanguages();
        $localizationLanguages = $this->getLocalizationLanguages();

        $availableLanguages = array();
        foreach ($languages as $language) {
            $alreadyUsed = false;
            foreach ($localizationLanguages as $localizationLanguage) {
                if (array_key_exists($localizationLanguage, $language)) {
                    $alreadyUsed = true;
                    break;
                }
            }

            if (!$alreadyUsed) {
                // If primary language has not been used for localization, then using it.
                if ($language['primary'] === true) {
                    // language key is the first key argument in language definition array;
                    unset($language['primary']);
                    $languageKeys = array_keys($language);
                    return reset($languageKeys);
                }
                $availableLanguages[] = $language;
            }
        }

        if (count($availableLanguages) === 0) {
            return null;
        }

        $nextAvailable = reset($availableLanguages);
        unset($nextAvailable['primary']);
    
        $keys = array_keys($nextAvailable);
        return reset($keys);
    }

    /**
     * Gets languages used for localization of bean
     *
     * @return string[] List of languages used for localization
     * @throws SugarQueryException
     */
    private function getLocalizationLanguages()
    {
        $query = new SugarQuery();
        $query->select(array('language'));
        $query->distinct(true);
        $query->from(BeanFactory::newBean('KBContents'));
        $query->where()->equals('kbdocument_id', $this->kbdocument_id);
        $languages = $query->execute();

        return array_map(function ($lang) {
            return $lang['language'];
        }, $languages);
    }
    
    /**
     * Mute changes in kb voting (need when
     * save kb article).
     *
     * @param array $dataChanges
     */
    protected function skipUsefulnessChanges(array $dataChanges)
    {
        if (empty($this->id) || $this->new_with_id) {
            $this->useful = 0;
            $this->notuseful = 0;
            return;
        }
        if (array_key_exists('useful', $dataChanges)) {
            $this->useful = $dataChanges['useful']['before'];
        }
        if (array_key_exists('notuseful', $dataChanges)) {
            $this->notuseful = $dataChanges['notuseful']['before'];
        }
    }

    /**
     * Special save for users votes save.
     * @throws SugarApiException
     */
    public function saveUsefulness()
    {
        if(empty($this->id) || $this->new_with_id) {
            throw new SugarApiException('Bean must be saved before vote');
        }

        $this->update_date_modified = false;
        $this->update_modified_by = false;
        parent::save();
    }

    /**
     * {@inheritDoc}
     */
    public function mark_deleted($id)
    {
        $deletedBean = BeanFactory::getBean('KBContents', $id);
        if ($this->active_rev == 1) {
            $query = new SugarQuery();
            $query->from(BeanFactory::newBean('KBContents'));
            $query->select(array('id'));
            $whereAnd = $query->where();
            if ($this->id) {
                $whereAnd->notEquals('id', $this->id);
            }
            $whereAnd
                ->equals('kbdocument_id', $this->kbdocument_id)
                ->equals('kbarticle_id', $this->kbarticle_id);
            $query->orderBy('date_entered', 'DESC');
            $query->limit(1);

            $result = $query->execute();

            if ($result) {
                $bean = BeanFactory::getBean('KBContents', $result[0]['id']);
                if ($bean->id) {
                    $this->resetActiveRev();

                    $bean->active_rev = 1;
                    $bean->save();
                }
            }
        }
        parent::mark_deleted($id);
        if (!empty($deletedBean->category_id)) {
            $this->updateCategoryExternalVisibility($deletedBean->category_id);
        }
    }

    /**
     * Checks if current article was published.
     * @return bool
     */
    protected function isPublished()
    {
        $published = static::getPublishedStatuses();
        if(empty($this->id) || !empty($this->new_with_id)) {
            return in_array($this->status, $published);
        } else {
            $dataChanges = empty($this->fetched_row) ? array() : $this->db->getDataChanges($this);
            if (!isset($dataChanges['status'])) {
                return false;
            }
            return in_array($dataChanges['status']['after'], $published) &&
            !in_array($dataChanges['status']['before'], $published);
        }
    }

    public static function getPublishedStatuses()
    {
        return array(static::ST_PUBLISHED);
    }

    /**
     * Check is current document active revision or not.
     * Marks all previous revisions as non-active.
     * Marks all previous published revisions as expired.
     */
    protected function checkActiveRev()
    {
        if (empty($this->kbarticle_id)) {
            $this->active_rev = 1;
            return;
        }
        if ($this->isPublished()) {
            $this->resetActiveRev();
            $this->active_rev = 1;
            $this->expirePublished();
            if (empty($this->active_date)) {
                $this->active_date = $GLOBALS['timedate']->nowDbDate();
            }
        } else {
            $activeRevisionStatus = $this->getActiveRevisionStatus();
            if ($activeRevisionStatus &&
                !in_array($activeRevisionStatus['status'], static::getPublishedStatuses())
            ) {
                $this->resetActiveRev();
                $this->active_rev = 1;
                if (empty($this->active_date)) {
                    $this->active_date = $GLOBALS['timedate']->nowDbDate();
                }
            }
        }
    }

    /**
     * Get status for document with active revision.
     * @return bool
     */
    protected function getActiveRevisionStatus()
    {
        if ($this->kbdocument_id && $this->kbarticle_id) {
            $query = new SugarQuery();
            $query->from(BeanFactory::newBean('KBContents'));
            $query->select(array('id', 'status'));
            $whereAnd = $query->where();
            if ($this->id) {
                $whereAnd->notEquals('id', $this->id);
            }
            $whereAnd
                ->equals('active_rev', 1)
                ->equals('kbdocument_id', $this->kbdocument_id)
                ->equals('kbarticle_id', $this->kbarticle_id);
            $query->orderBy('revision', 'DESC');
            $query->limit(1);

            $result = $query->execute();

            if ($result) {
                return $result[0];
            }
        }
        return false;
    }

    /**
     * Reset active revision status for all revisions in article except this.
     */
    protected function resetActiveRev()
    {
        $query = new SugarQuery();
        $query->from($this);
        $query->select(array('id'));
        $whereAnd = $query->where();
        if ($this->id) {
            $whereAnd->notEquals('id', $this->id);
        }
        $whereAnd
            ->equals('kbdocument_id', $this->kbdocument_id)
            ->equals('kbarticle_id', $this->kbarticle_id)
            ->equals('active_rev', 1);

        $result = $query->execute();
        foreach ($result as $row) {
            $oldRevBean = BeanFactory::getBean($this->module_name, $row['id']);
            $oldRevBean->active_rev = 0;
            $oldRevBean->save();
        }
    }

    /**
     * Expire all published articles.
     */
    protected function expirePublished()
    {
        $expDate = $GLOBALS['timedate']->nowDb();
        $statuses = static::getPublishedStatuses();

        $query = new SugarQuery();
        $query->from($this);
        $query->select(array('id'));
        $whereAnd = $query->where();
        if ($this->id) {
            $whereAnd->notEquals('id', $this->id);
        }
        $whereAnd
            ->equals('kbdocument_id', $this->kbdocument_id)
            ->equals('kbarticle_id', $this->kbarticle_id)
            ->in('status', $statuses);

        $result = $query->execute();
        foreach ($result as $row) {
            $oldStatusBean = BeanFactory::getBean($this->module_name, $row['id']);
            $oldStatusBean->exp_date = $expDate;
            $oldStatusBean->status = static::ST_EXPIRED;
            $oldStatusBean->save();
        }
    }

    /**
     * {@inheritdoc}
     **/
    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }
        return false;
    }

    public function get_summary_text()
    {
        return $this->name;
    }

    /**
     * Need to load votes for usefulness relationship.
     * {@inheritdoc}
     */
    public function fill_in_relationship_fields()
    {
        parent::fill_in_relationship_fields();
        $user = $GLOBALS['current_user'];
        $this->usefulness_user_vote = 0;
        $this->load_relationship('usefulness');
        $validUser = $this->usefulness->isValidSugarUser($user);
        $contact_id = null;
        $params = array();
        if (!$validUser && $contact = $this->usefulness->getPortalContact()) {
            $contact_id = $contact->id;
            $params['where'] = 'contact_id = ' . DBManagerFactory::getInstance()->quoted($contact_id);
        }
        $this->usefulness->load($params);
        foreach ($this->usefulness->rows as $row) {
            if ($validUser && $row['id'] == $user->id) {
                $this->usefulness_user_vote = $row['vote'];
            } elseif (!$validUser && $row['contact_id'] == $contact_id) {
                $this->usefulness_user_vote = $row['vote'];
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    function get_notification_recipients()
    {
        $notify_user = BeanFactory::newBean('Users');
        if ($this->status == self::ST_IN_REVIEW) {
            $notify_user->retrieve($this->kbsapprover_id);
        } else {
            $notify_user->retrieve($this->assigned_user_id);
        }
        $this->new_assigned_user_name = $notify_user->full_name;

        LoggerManager::getLogger()->info("Notifications: recipient is {$this->new_assigned_user_name}");

        return array($notify_user);
    }

    /**
     * {@inheritdoc}
     */
    public function set_notification_body($xtpl, $bean)
    {
        global $app_list_strings, $current_user, $locale;
        $user = BeanFactory::getBean('Users', $bean->created_by);
        $status = isset($bean->status) ? $app_list_strings['kbdocument_status_dom'][$bean->status] : '';
        $timedate = TimeDate::getInstance();
        $dateCreated = $bean->date_entered ?
            $timedate->to_display_date_time($bean->date_entered) :
            $timedate->to_display_date_time($bean->fetched_row['date_entered']);
        $messageLbl = '';
        $preMessage = '';

        if ($bean->status == self::ST_IN_REVIEW) {
            $preMessage = "$current_user->name ";
            $messageLbl = 'LBL_KB_PUBLISHED_REQUEST';

        } elseif (in_array($bean->status, KBContent::getPublishedStatuses())) {
            $messageLbl = 'LBL_KB_NOTIFICATION';

        } elseif ($bean->status == self::ST_DRAFT) {
            $messageLbl = 'LBL_KB_STATUS_BACK_TO_DRAFT';
        }

        $xtpl->assign('KBDOCUMENT_NAME', $bean->name);
        $xtpl->assign('KBDOCUMENT_STATUS', $status);
        $xtpl->assign('KBDOCUMENT_DATE_CREATED', $dateCreated);
        $xtpl->assign('KBDOCUMENT_CREATED_BY', $locale->formatName($user));
        $xtpl->assign('KBDOCUMENT_DESCRIPTION', $bean->description);
        $xtpl->assign('NOTIFICATION_MESSAGE', $preMessage . translate($messageLbl, $this->module_dir));

        return $xtpl;
    }

    /**
     * Update category visibility for external use (portal, etc.).
     * @param int $categoryId
     */
    protected function updateCategoryExternalVisibility($categoryId)
    {
        $isUpdated = false;
        $documentExternalFlag = false;
        $category = BeanFactory::retrieveBean('Categories', $categoryId);
        if ($category instanceof Category) {
            if (!$category->isRoot()) {
                $documentExternalFlag = $this->_isExternal($category);
                $isUpdated = $this->_updateCategory($category, $documentExternalFlag);
            }
            if ($isUpdated) {
                $parentExternalFlag = false;
                foreach ($category->getParents(null, true) as $node) {
                    $seed = BeanFactory::retrieveBean('Categories', $node['id']);
                    if (!$seed->isRoot()) {
                        if ($documentExternalFlag) {
                            $this->_updateCategory($seed, $documentExternalFlag);
                            continue;
                        }
                        if ($parentExternalFlag) {
                            continue;
                        }
                        $parentExternalFlag = $this->_isExternal($seed);
                        $this->_updateCategory($seed, $parentExternalFlag);
                    }
                }
            }
        }
    }

    /**
     * Check if category is external (Has external articles within or has external children).
     * @param Category $category
     * @return bool
     */
    private function _isExternal(Category $category)
    {
        return $this->_countExternalArticlesInCategory($category) || $this->_countExternalCategories($category);
    }

    /**
     * Calculate if category has external articles with active revision.
     * @param Category $bean
     * @return bool
     * @throws SugarQueryException
     */
    private function _countExternalArticlesInCategory(Category $bean)
    {
        $query = new SugarQuery();
        $query->select()->setCountQuery();
        $query->from(new KBContent)
            ->where()
            ->equals('category_id', $bean->id)
            ->equals('is_external', 1)
            ->equals('status', KBContent::ST_PUBLISHED)
            ->equals('active_rev', 1);

        $data = $query->execute();
        $row = array_shift($data);
        $count = array_shift($row);
        return (boolean) $count > 0;
    }

    /**
     * Count if category has external children.
     * @param Category $bean
     * @return bool
     * @throws SugarQueryException
     */
    private function _countExternalCategories(Category $bean) {
        $ids = array();
        foreach ($bean->getChildren() as $child) {
            $ids[] = $child['id'];
        }

        if (count($ids)) {
            $query = new SugarQuery();
            $query->select()->setCountQuery();
            $query->from(new Category)
                ->where()
                ->equals('is_external', 1);

            $query->where()->in('id', $ids);

            $data = $query->execute();
            $row = array_shift($data);
            $count = array_shift($row);
            return (boolean) $count > 0;
        } else {
            return false;
        }
    }

    /**
     * Update category if external flag has been changed.
     * @param Category $category
     * @param $isExternal
     * @return boolean
     */
    private function _updateCategory(Category $category, $isExternal)
    {
        if ($category->is_external != $isExternal) {
            $category->is_external = $isExternal;
            $category->save();
            return true;
        }
        return false;
    }
}
