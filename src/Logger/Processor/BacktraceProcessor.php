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

namespace Sugarcrm\Sugarcrm\Logger\Processor;

use Psr\Log\LoggerInterface;

/**
 * Appends request-specific details to the message
 */
class BacktraceProcessor
{
    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['message'] .= sprintf('; Call Trace: %s', $this->getFormatedBacktrace());

        return $record;
    }

    private function getFormatedBacktrace($includeArgs = false)
    {
        $trace = "";
        $stack = $this->trimStack(
            debug_backtrace(
                DEBUG_BACKTRACE_PROVIDE_OBJECT | (!$includeArgs ? DEBUG_BACKTRACE_IGNORE_ARGS : 0)
            )
        );

        foreach ($stack as $pos => $call) {
            if (isset($call['file']) && isset($call['line'])) {
                $trace .= "\n{$pos}: {$call['file']}:{$call['line']}";
                if (isset($call['class']) && isset($call['function'])) {
                    $trace .= " - {$call['class']}::{$call['function']}()";
                }
                if (!empty($call['args'])) {
                    $trace .= "(";
                    foreach ($call['args'] as $i => $a) {
                        $trace .= "\n    $i: " . $this->print_var($a, 1, true);
                    }
                    $trace .= "\n)";
                }
            }
        }

        return $trace;
    }

    private function trimStack($stack)
    {
        for ($i = count($stack) - 1; $i >= 0; $i--) {
            $call = $stack[$i];
            if (!empty($call['class']) && in_array(LoggerInterface::class, class_implements($call['class']))) {
                $stack = array_slice($stack, $i);
                break;
            }
        }

        return $stack;
    }

    private function print_var($o, $indent = 0, $includeProps = false)
    {
        $ret = '';
        if (is_object($o)) {
            $ret .= "Object - {" . get_class($o) . "}";
        } elseif (is_array($o)) {
            $ret .= "Array[" . sizeof($o) . "]";
        } else {
            return var_export($o, true);
        }

        if ($includeProps) {
            foreach ($o as $k => $v) {
                $ret .= "\n" . str_repeat("    ", $indent + 1) . "$k => " . $this->print_var($v, $indent + 1);
            }
        }

        return $ret;
    }
}
