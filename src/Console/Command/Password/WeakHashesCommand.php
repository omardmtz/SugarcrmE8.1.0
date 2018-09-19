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

namespace Sugarcrm\Sugarcrm\Console\Command\Password;

use Sugarcrm\Sugarcrm\Console\CommandRegistry\Mode\InstanceModeInterface;
use Sugarcrm\Sugarcrm\Security\Password\Hash;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use SugarQuery;
use SugarQuery_Builder_Andwhere;
use BeanFactory;
use ReflectionMethod;

/**
 *
 * List users with weak password hashes or password which need rehashing
 *
 */
class WeakHashesCommand extends Command implements InstanceModeInterface
{
    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('password:weak')
            ->setDescription('Show users having weak or non-compliant password hashes')
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $list = array();

        $hash = $this->getHashInstance();
        $users = $this->getUserHashes();

        foreach ($users as $user) {
            $user['is_weak'] = false;
            $user['needs_rehash'] = false;

            if ($this->isWeak($hash, $user['user_hash'])) {
                $user['is_weak'] = true;
            }

            if ($hash->needsRehash($user['user_hash'])) {
                $user['needs_rehash'] = true;
            }

            if ($user['is_weak'] ||$user['needs_rehash']) {
                $list[] = $user;
            }
        }

        if (empty($list)) {
            $output->writeln("No users found with weak hashes or hashes in need of rehashing.");
            return;
        }

        $table = new Table($output);
        $table->setHeaders(array(
            'Id',
            'Weak',
            'Needs rehash',
            'Username',
            'First name',
            'Last name',
            'Status',
        ));

        foreach ($list as $user) {
            $table->addRow(array(
                $user['id'],
                $user['is_weak'] ? '<error>yes</error>' : 'no',
                $user['needs_rehash'] ? '<comment>yes</comment>' : 'no',
                $user['user_name'],
                $user['first_name'],
                $user['last_name'],
                $user['employee_status'],
            ));
        }

        $table->render();
    }

    /**
     * Get password hash instance
     * @return Hash
     */
    protected function getHashInstance()
    {
        return Hash::getInstance();
    }

    /**
     * Get list of local user info with their hashes
     * @return array
     */
    protected function getUserHashes()
    {
        $q = new SugarQuery();
        $q->from(BeanFactory::newBean('Users'));
        $q->select(array(
            'id',
            'user_name',
            'user_hash',
            'first_name',
            'last_name',
            'employee_status',
        ));

        $where = new SugarQuery_Builder_Andwhere($q);
        $where->equals('external_auth_only', 0);
        $where->equals('is_group', 0);

        $q->where($where);

        return $q->execute();
    }

    /**
     * Verify if given string is a weak md5 hash
     * @param Hash $hash
     * @param string $string
     * @return boolean
     */
    protected function isWeak(Hash $hash, $string)
    {
        $rm = new ReflectionMethod($hash, 'isLegacyHash');
        $rm->setAccessible(true);
        return $rm->invokeArgs($hash, array($string));
    }
}
