<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Model\Config\Source;

use \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;

/**
 * Class AutoTypeSalesRule
 * @package Dadolun\NewsletterCoupon\Model\Source
 */
class AutoTypeSalesRule implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var RuleCollectionFactory
     */
    protected $ruleCollectionFactory;

    /**
     * AutoTypeSalesRules constructor.
     * @param RuleCollectionFactory $ruleCollectionFactory
     */
    public function __construct(
        RuleCollectionFactory $ruleCollectionFactory
    )
    {
        $this->ruleCollectionFactory = $ruleCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        /**
         * @var \Magento\SalesRule\Model\ResourceModel\Rule\Collection $ruleCollection
         */
        $ruleCollection = $this->ruleCollectionFactory->create();
        $ruleCollection->addFieldToFilter('coupon_type', \Magento\SalesRule\Model\RULE::COUPON_TYPE_SPECIFIC)
            ->addFieldToFilter('use_auto_generation', 1);
        $options = [];
        /**
         * @var \Magento\SalesRule\Model\Data\Rule $rule
         */
        foreach ($ruleCollection->getItems() as $rule) {
            $options[] = array(
                'value' => $rule->getRuleId(),
                'label' => $rule->getName()
            );
        }
        return $options;
    }
}
