<?php

namespace VIVOMEDIA\JsShortcutTarget\Aspect;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 * @Flow\Aspect
 */
class JsShortcutTargetAspect
{
    /**
     * @Flow\Around("method(Neos\Flow\Mvc\Routing\UriBuilder->uriFor())")
     * @param \Neos\Flow\Aop\JoinPointInterface $joinPoint
     * @return string
     */
    public function rewritePluginViewUris(JoinPointInterface $joinPoint)
    {
        $arguments = $joinPoint->getMethodArguments();

        $node = $arguments['controllerArguments']['node'] ?? null;
        if ($node && $node instanceof Node && $node->getNodeType()->isOfType('Neos.Neos:Shortcut')) {
            $target = $node->getProperty('target');
            if (strpos($target, 'javascript:') !== false) {
                return $target;
            }
        }
        return $joinPoint->getAdviceChain()->proceed($joinPoint);
    }
}