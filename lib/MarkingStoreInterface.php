<?php

namespace JBJ\Workflow\SymfonyBridge;

use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface as BaseInterface;

interface MarkingStoreInterface extends BaseInterface
{
    /**
     * @return string $storeId
     */
    public function getName();
}
