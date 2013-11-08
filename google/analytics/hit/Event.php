<?php

namespace Google\Analytics\MeasurementProtocol;

class Event extends Hit {
	private $category;
	private $action;
	private $label;
	private $value;

	public function __construct($category, $action, $label = null, $value = null) {
		$this->category = $category;
		$this->action = $action;
		$this->label = $label;
		$this->value = $value;
	}

	public function getCategory() {
		return $this->category;
	}

	public function getAction() {
		return $this->action;
	}

	public function hasLabel() {
		return $this->label !== null;
	}

	public function getLabel() {
		return $this->label;
	}

	public function hasValue() {
		return $this->value !== null;
	}

	public function getValue() {
		return $this->value;
	}

	public function getParameters() {
		return array(
			't' => 'event',
			'ec' => $this->category,
			'ea' => $this->action,
			'el' => $this->label
		);
	}
}

?>