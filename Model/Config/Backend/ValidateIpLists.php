<?php

namespace TechImprovement\RateLimit\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Exception\ValidatorException;

class ValidateIpLists extends Value
{
    /**
     * Validate data before save
     *
     * @return $this
     * @throws ValidatorException
     */
    public function beforeSave(): static
    {
        $this->validateNoDuplicates($this->getValue(), 'current list');

        $fieldId = $this->getData('field_config/id');
        if ($fieldId == 'whitelist') {
            $otherFieldValue = $this->getFieldsetDataValue('blacklist');
            $otherFieldLabel = 'blacklist';
        } elseif ($fieldId == 'blacklist') {
            $otherFieldValue = $this->getFieldsetDataValue('whitelist');
            $otherFieldLabel = 'whitelist';
        } else {
            return parent::beforeSave();
        }

        $this->validateNoOverlap($this->getValue(), $otherFieldValue, $fieldId, $otherFieldLabel);

        return parent::beforeSave();
    }

    /**
     * Validates that there are no duplicate IPs in the list.
     *
     * @param string $value The value from the textarea.
     * @param string $listLabel Label for the list being validated.
     * @throws ValidatorException
     */
    protected function validateNoDuplicates(string $value, string $listLabel): void
    {
        $ips = array_filter(preg_split('/\r\n|\r|\n/', $value));
        $duplicateIps = array_unique(array_diff_assoc($ips, array_unique($ips)));

        if (!empty($duplicateIps)) {
            throw new ValidatorException(
                __('Duplicate IPs detected in the %1: %2', $listLabel, implode(', ', $duplicateIps))
            );
        }
    }

    /**
     * Validates that there's no overlap between two IP lists.
     *
     * @param string $whitelistValue Value of the first list.
     * @param string $blacklistValue Value of the second list.
     * @param string $whitelistLabel Label for the first list being validated.
     * @param string $blacklistLabel Label for the second list.
     * @throws ValidatorException
     */
    protected function validateNoOverlap(string $whitelistValue, string $blacklistValue, string $whitelistLabel, string $blacklistLabel): void
    {
        $whitelistIps = array_filter(preg_split('/\r\n|\r|\n/', $whitelistValue));
        $blacklistIps = array_filter(preg_split('/\r\n|\r|\n/', $blacklistValue));

        $overlap = array_intersect($whitelistIps, $blacklistIps);

        if (!empty($overlap)) {
            throw new ValidatorException(
                __('IPs cannot be in both %1 and %2: %3', $whitelistLabel, $blacklistLabel, implode(', ', $overlap))
            );
        }
    }
}
