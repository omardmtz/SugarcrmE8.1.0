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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\Question;
use BeanFactory;
use Exception;
use User;

/**
 *
 * Interactive password reset command for local users.
 *
 */
class PasswordResetCommand extends Command implements InstanceModeInterface
{
    /**
     * {inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('password:reset')
            ->setDescription('Reset user password for local user authentication.')
            ->addArgument(
                'userid',
                InputArgument::REQUIRED,
                'User id'
            )
            ->addOption(
                'skip-rules',
                '',
                InputOption::VALUE_NONE,
                'Skip password rules'
            )
        ;
    }

    /**
     * {inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // interaction is required
        if ($this->isNotInteractive($input)) {
            throw new Exception(
                'This command is not designed to be run in non-interactive mode'
            );
        }

        $user = $this->getUser($input->getArgument('userid'));
        $output->writeln('User found, ready to reset password (CTRL+C to abort)');

        $table = new Table($output);
        $table
            ->addRow(array('Username', $user->user_name))
            ->addRow(array('First name', $user->first_name))
            ->addRow(array('Last name', $user->last_name))
            ->addRow(array('Status', $user->employee_status))
            ->render()
        ;

        $password = $this->getPassword($input, $output);

        if (!$input->getOption('skip-rules') && !$this->isPasswordCompliant($password)) {
            throw new Exception("Password doesn't meet complexity criteria");
        }

        $user->setNewPassword($password);
        $output->writeln('<info>New password set</info>');
    }

    /**
     * Check if running in non-interactive mode
     * @param InputInterface $input
     * @return boolean
     */
    protected function isNotInteractive(InputInterface $input)
    {
        if ($input->hasOption('no-interaction') && $input->getOption('no-interaction')) {
            return true;
        } elseif ($input->hasOption('quiet') && $input->getOption('quiet')) {
            return true;
        }
        return false;
    }

    /**
     * Get password interactively
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    protected function getPassword(InputInterface $input, OutputInterface $output)
    {
        $qh = $this->getHelper('question');

        $q1 = new Question('New password:');
        $q1->setHidden(true);
        $q1->setHiddenFallback(false);
        $pwd1 = $qh->ask($input, $output, $q1);

        if (empty($pwd1)) {
            throw new Exception("Password cannot be empty");
        }

        $q2 = new Question('Confirm new password:');
        $q2->setHidden(true);
        $q2->setHiddenFallback(false);
        $pwd2 = $qh->ask($input, $output, $q2);

        if (0 !== strcmp($pwd1, $pwd2)) {
            throw new Exception("Passwords do not match");
        }

        return $pwd1;
    }

    /**
     * Get User bean
     * @param string $id
     * @return User
     * @throws Exception
     */
    protected function getUser($id)
    {
        $user = $this->loadUserBean($id);

        if (empty($user->id)) {
            throw new Exception("User not found");
        }

        if ($user->external_auth_only != 0) {
            throw new Exception("Cannot set password for external authenticated users");
        }

        if ($user->is_group != 0) {
            throw new Exception("Cannot set password for group users");
        }

        return $user;
    }

    /**
     * Attempt to load user through BeanFactory
     * @param string $id
     * @return User|null
     */
    protected function loadUserBean($id)
    {
        return BeanFactory::getBean('Users', $id);
    }

    /**
     * Check if given password complies with password rules
     * @param string $password
     * @return boolean
     */
    protected function isPasswordCompliant($password)
    {
        return BeanFactory::newBean('Users')->check_password_rules($password);
    }
}
