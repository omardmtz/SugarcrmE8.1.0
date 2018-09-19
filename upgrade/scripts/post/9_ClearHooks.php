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

require_once 'ModuleInstall/ModuleInstaller.php';

/**
 * Upgrade script to clear hooks with wrong references.
 */
class SugarUpgradeClearHooks extends UpgradeScript
{
    public $order = 9400;

    public $type = self::UPGRADE_CUSTOM;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $hooksModules = $this->findHookFiles();
        //Check modules hooks.
        foreach ($hooksModules['hooks'] as $file) {
            $this->testHooks($file);
        }
        //Check extension hooks.
        foreach ($hooksModules['ext'] as $file) {
            $this->testHooks($file);
        }
        if (!empty($hooksModules['ext'])) {
            $mi = new ModuleInstaller();
            $mi->rebuild_logichooks();
        }
    }

    /**
     * Find all hooks files.
     * @return array
     */
    protected function findHookFiles()
    {
        $modules = array(
            'hooks' => array(),
            'ext' => array(),
        );
        $path1 = "custom/modules/*/logic_hooks.php";
        $path2 = "custom/modules/logic_hooks.php";
        $path3 = "custom/Extension/modules/*/Ext/LogicHooks/*.php";
        $path4 = "custom/Extension/application/Ext/LogicHooks/*.php";

        $modules['hooks'] = array_merge(glob($path1), glob($path2));
        $modules['ext'] = array_merge(glob($path3), glob($path4));

        return $modules;
    }

    /**
     * Rewrite logic hook's file with new hooks.
     * @param String $hook_file
     * @param array $hooks
     */
    protected function rewriteHookFile($hook_file, $hooks)
    {
        $this->log("**** Rewrite hooks for {$hook_file}");
        $this->upgrader->backupFile($hook_file);
        if (empty($hooks)) {
            unlink($hook_file);
        } else {
            $out = "<?php\n";
            foreach ($hooks as $event_array => $event) {
                foreach ($event as $elements) {
                    $out .= "\$hook_array['{$event_array}'][] = array(";
                    foreach ($elements as $el) {
                        $out .= var_export($el, true) . ',';
                    }
                    $out .= ");\n";
                }
            }
            file_put_contents($hook_file, $out);
        }
    }

    /**
     * Check logic hook file for bad definitions.
     * @param String $hook_file
     */
    protected function testHooks($hook_file)
    {
        $needRewrite = false;
        $hook_array = array();
        include $hook_file;
        foreach ($hook_array as $k => $hooks) {
            foreach ($hooks as $j => $hook) {
                $validHooks = false;
                if (count($hook) >= 5 && file_exists($hook[2])) {
                    $validHooks = $this->checkClassMethodInFile($hook[2], $hook[3], $hook[4]);
                }
                if (!$validHooks) {
                    $this->log("DELETE bad hook '{$hook[1]}' in '{$hook_file}'");
                    $needRewrite = true;
                    unset($hook_array[$k][$j]);
                }
            }
            if (empty($hook_array[$k])) {
                unset($hook_array[$k]);
            }
        }
        if ($needRewrite) {
            $this->rewriteHookFile($hook_file, $hook_array);
        }
    }

    /**
     * Check whether file contains a class with provided method.
     * @param string $file
     * @param string $class
     * @param string $method
     * @return bool
     */
    protected function checkClassMethodInFile($file, $class, $method)
    {
        if (empty($class) || empty ($method)) {
            return false;
        }
        $source = file_get_contents($file);
        $tokens = token_get_all($source);
        $classes = array();
        $current = '';
        $level = 0;
        $namespace = '';
        foreach ($tokens as $ind => $token) {
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_NAMESPACE:
                        $namespace = $this->findNamespace($tokens, $ind);
                        break;
                    case T_CLASS:
                        if (isset($tokens[$ind - 1])) {
                            $previousToken = $tokens[$ind - 1];
                            if (is_array($previousToken) && $previousToken[0] === T_DOUBLE_COLON) {
                                break;
                            }
                        }
                        $current = $this->findName($tokens, $ind, T_STRING);
                        if ($namespace != '') {
                            $current = $namespace.'\\'.$current;
                        }
                        $classes[$current] = array(
                            'level' => $level,
                            'methods' => array()
                        );
                        break;
                    case T_FUNCTION:
                        if (!empty($current)) {
                            $classes[$current]['methods'][] = $this->findName($tokens, $ind, T_STRING);
                        }
                        break;
                    case T_CURLY_OPEN:
                    case T_DOLLAR_OPEN_CURLY_BRACES:
                    case T_STRING_VARNAME:
                        $level++;
                        break;
                }
            } else {
                switch($token) {
                    case '{':
                        $level++;
                        break;
                    case '}':
                        $level--;
                        if (!empty($current) && $classes[$current]['level'] == $level) {
                            $current = '';
                        }
                        break;
                }
            }
        }
        if (!empty($classes[$class]) && (in_array($method, $classes[$class]['methods'])
            || (($method == $class) && in_array('__construct', $classes[$class]['methods'])))) {
            return true;
        }
        return false;
    }

    /**
     * Find next token with provided type.
     * @param array $tokens
     * @param int $start
     * @param int $type
     * @return string|bool
     */
    protected function findName($tokens, $start, $type)
    {
        $count = count($tokens);
        for ($i = $start + 1; $i < $count; $i++) {
            if (is_array($tokens[$i]) && $tokens[$i][0] == $type) {
                return $tokens[$i][1];
            }
        }
        return false;
    }

    /**
     * Find namespace.
     * @param array $tokens
     * @param int $start
     * @return string
     */
    protected function findNamespace($tokens, $start)
    {
        $count = count($tokens);
        $namespace = '';
        for ($i = $start + 1; $i < $count; $i++) {
            if (is_array($tokens[$i]) && $tokens[$i][0] == T_STRING) {
                $namespace = $tokens[$i][1];
                break;
            }
        }
        if ($namespace != '') {
            $i++;
            while (is_array($tokens[$i]) && $tokens[$i][0] == T_NS_SEPARATOR) {
                $namespace .= '\\'.$this->findName($tokens, $i++, T_STRING);
            }
        }
        return $namespace;
    }
}
