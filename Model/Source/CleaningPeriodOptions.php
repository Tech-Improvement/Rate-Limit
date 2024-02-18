<?php

namespace TechImprovement\RateLimit\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CleaningPeriodOptions implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '0 * * * *', 'label' => __('Every Hour')],
            ['value' => '0 0 * * *', 'label' => __('Daily')],
            ['value' => '0 0 * * 1', 'label' => __('Weekly')],
            ['value' => '0 0 1 * *', 'label' => __('Monthly')],
        ];
    }
}
