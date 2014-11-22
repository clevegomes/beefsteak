<?php

namespace Hydra\Widgets;

/**
 * Class DateRangeWidget
 * @package Hydra\Widgets
 */
class DateRangeWidget extends Widget {
    public function __construct($fieldDefinition = NULL, $fieldRecord = NULL, $parentElement = NULL) {
        parent::__construct($fieldDefinition, $fieldRecord, $parentElement);
        $this->type = 'date_range';
    }

    public function createItem($itemSet, $wrapperSet, $index) {
        $type = $this->fieldRecord->field_type;

        if ($type == 'datetime_range') {
            $from = $itemSet->addField('datetime', array('date_start', __('Date start', 'hydraforms')));
            $to = $itemSet->addField('datetime', array('date_end', __('Date end', 'hydraforms')));
        }
        else {
            $from = $itemSet->addField('date', array('date_start', __('Date start', 'hydraforms')));
            $to = $itemSet->addField('date', array('date_end', __('Date end', 'hydraforms')));
        }

        // required validator for field
        if($this->isRequired() && $index == 0) {
            $from->addValidator('required');
            $to->addValidator('required');
        }
    }

    public function processValuesBeforeSave(\HydraFieldRecord $fieldRecord, $items = array(), $post = NULL) {
        if (!count($items['items'])) {
            return array();
        }

        foreach ($items['items'] as $key => $item) {
            if (empty($item['date_start']) || empty($item['date_end'])) {
                unset($items['items'][$key]);
            }
        }

        if (!count($items['items'])) {
            return array();
        }

        return $items;
    }

    public function allowedConditions() {
        // range slider support only in between condition - obviously
        return array(
            'value' => array(
                'in_between' => __('In Between', 'hydraforms'),
            ),
        );
    }

    public function composeCondition($filterField, $conditionField, $condition, $value) {
        $metaArgs = array();

        if(isset($value['date_start']) && isset($value['date_end'])) {
            $metaArgs = array(
                'key' => $filterField->field_name . '_%_' . $condition->col,
                'compare' => 'BETWEEN',
                'value' => array($value['date_start'], $value['date_end']),
            );
        }

        return $metaArgs;
    }
}

/**
 * Class DateWidget
 * @package Hydra\Widgets
 */
class DateWidget extends Widget {
    public function __construct($fieldDefinition = NULL, $fieldRecord = NULL, $parentElement = NULL) {
        parent::__construct($fieldDefinition, $fieldRecord, $parentElement);
        $this->type = 'date';
    }

    public function createItem($itemSet, $wrapperSet, $index) {
        $type = $this->fieldRecord->field_type;

        if ($type == 'datetime') {
            $field = $itemSet->addField('datetime', array('date', __($this->fieldRecord->getLabel(), 'hydraforms')));
        }
        else {
            $field = $itemSet->addField('date', array('date', __($this->fieldRecord->getLabel(), 'hydraforms')));
        }

        // required validator for field
        if($this->isRequired() && $index == 0) {
            $field->addValidator('required');
        }
    }

    public function processValuesBeforeSave(\HydraFieldRecord $fieldRecord, $items = array(), $post = NULL) {
        if (!count($items['items'])) {
            return array();
        }

        foreach ($items['items'] as $key => $item) {
            if (empty($item['date'])) {
                unset($items['items'][$key]);
            }
        }

        if (!count($items['items'])) {
            return array();
        }

        return $items;
    }

    public function allowedConditions() {
        return array(
            'date' => array(
                'equals' => __('Equals', 'hydraforms'),
                'greater' => __('Greater', 'hydraforms'),
                'greater-equals' => __('Greater or Equals', 'hydraforms'),
                'lower' => __('Lower', 'hydraforms'),
                'lower-equals' => __('Lower or Equals', 'hydraforms'),
            )
        );
    }
}
