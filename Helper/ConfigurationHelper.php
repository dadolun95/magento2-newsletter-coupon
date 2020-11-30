<?php
/**
 * @category    Magento 2
 * @package     Dadolun_NewsletterCoupon
 * @copyright   Copyright (c) 2020 Dadolun (https://github.com/dadolun95)
 */

namespace Dadolun\NewsletterCoupon\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigurationHelper
 * @package Dadolun\NewsletterCoupon\Helper
 */
class ConfigurationHelper
{
    /**
     * Module section
     */
    const SECTION_MODULE = 'dadolun_newsletter';
    /**
     * Configuration section
     */
    const GROUP_CONFIGURATION = self::SECTION_MODULE . '/configuration';
    /**
     * Enabled feature config path
     */
    const PATH_COUPON_GENERATION_ENABLED = self::GROUP_CONFIGURATION . '/coupon_generation_enabled';
    /**
     * Sales rule used for coupon generation config path
     */
    const PATH_USED_SALESRULE_ID = self::GROUP_CONFIGURATION . '/salesrule_id';
    /**
     * Coupon expiration delay expression config path
     */
    const PATH_COUPON_EXPIRATION_DELAY_EXPRESSION = self::GROUP_CONFIGURATION . '/expiration_delay_expression';

    /**
     * Newsletter Module section
     */
    const NEWSLETTER_SECTION_MODULE = 'newsletter';
    /**
     * Configuration section
     */
    const NEWSLETTER_GROUP_CONFIGURATION = self::NEWSLETTER_SECTION_MODULE . '/subscription';
    /**
     * Disable email sending config path
     */
    const PATH_DISABLE_NEWSLETTER_CONFIRMATION_REQUEST_EMAIL = self::NEWSLETTER_GROUP_CONFIGURATION . '/disable_confirmation_request_email';
    /**
     * Disable email sending config path
     */
    const PATH_DISABLE_NEWSLETTER_CONFIRMATION_SUCCESS_EMAIL = self::NEWSLETTER_GROUP_CONFIGURATION . '/disable_confirmation_success_email';
    /**
     * Disable email sending config path
     */
    const PATH_DISABLE_NEWSLETTER_UNSUBSCRIPTION_EMAIL = self::NEWSLETTER_GROUP_CONFIGURATION . '/disable_unsubscription_email';

    /**
     * Core store config
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * ConfigurationHelper constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param null $store
     * @param string $scope
     * @return mixed
     */
    public function isCouponGenerationEnabled($store = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->isSetFlag(
            self::PATH_COUPON_GENERATION_ENABLED,
            $scope,
            $store
        );
    }

    /**
     * @param null $store
     * @param string $scope
     * @return mixed
     */
    public function getUsedSalesRuleId($store = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(
            self::PATH_USED_SALESRULE_ID,
            $scope,
            $store
        );
    }

    /**
     * @param null $store
     * @param string $scope
     * @return mixed
     */
    public function getDelayExpression($store = null, $scope = ScopeInterface::SCOPE_STORE) {
        return $this->scopeConfig->getValue(
            self::PATH_COUPON_EXPIRATION_DELAY_EXPRESSION,
            $scope,
            $store
        );
    }

    /**
     * @param null $store
     * @param string $scope
     * @return mixed
     */
    public function disableConfirmationRequestEmail($store = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->isSetFlag(
            self::PATH_DISABLE_NEWSLETTER_CONFIRMATION_REQUEST_EMAIL,
            $scope,
            $store
        );
    }

    /**
     * @param null $store
     * @param string $scope
     * @return mixed
     */
    public function disableConfirmationSuccessEmail($store = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->isSetFlag(
            self::PATH_DISABLE_NEWSLETTER_CONFIRMATION_SUCCESS_EMAIL,
            $scope,
            $store
        );
    }

    /**
     * @param null $store
     * @param string $scope
     * @return mixed
     */
    public function disableUnsubscriptionEmail($store = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->isSetFlag(
            self::PATH_DISABLE_NEWSLETTER_UNSUBSCRIPTION_EMAIL,
            $scope,
            $store
        );
    }
}
