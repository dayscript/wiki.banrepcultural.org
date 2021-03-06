<?php

namespace OOUI;

/**
 * Base class for input widgets.
 *
 * @abstract
 */
class InputWidget extends Widget {
	use FlaggedElement;
	use TabIndexedElement;
	use TitledElement;
	use AccessKeyedElement;

	/* Static Properties */

	public static $supportsSimpleLabel = true;

	/* Properties */

	/**
	 * Input element.
	 *
	 * @var Tag
	 */
	protected $input;

	/**
	 * Input value.
	 *
	 * @var string
	 */
	protected $value = '';

	/**
	 * @param array $config Configuration options
	 * @param string $config['name'] HTML input name (default: '')
	 * @param string $config['value'] Input value (default: '')
	 * @param string $config['dir'] The directionality of the input (ltr/rtl)
	 */
	public function __construct( array $config = [] ) {
		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->input = $this->getInputElement( $config );

		// Traits
		$this->initializeFlaggedElement( array_merge( $config, [ 'flagged' => $this ] ) );
		$this->initializeTabIndexedElement(
			array_merge( $config, [ 'tabIndexed' => $this->input ] ) );
		$this->initializeTitledElement(
			array_merge( $config, [ 'titled' => $this->input ] ) );
		$this->initializeAccessKeyedElement(
			array_merge( $config, [ 'accessKeyed' => $this->input ] ) );

		// Initialization
		if ( isset( $config['name'] ) ) {
			$this->input->setAttributes( [ 'name' => $config['name'] ] );
		}
		if ( $this->isDisabled() ) {
			$this->input->setAttributes( [ 'disabled' => 'disabled' ] );
		}
		$this
			->addClasses( [ 'oo-ui-inputWidget' ] )
			->appendContent( $this->input );
		$this->input->addClasses( [ 'oo-ui-inputWidget-input' ] );
		$this->setValue( isset( $config['value'] ) ? $config['value'] : null );
		if ( isset( $config['dir'] ) ) {
			$this->setDir( $config['dir'] );
		}
	}

	/**
	 * Get input element.
	 *
	 * @param array $config Configuration options
	 * @return Tag Input element
	 */
	protected function getInputElement( $config ) {
		return new Tag( 'input' );
	}

	/**
	 * Get input element's ID.
	 *
	 * If the element already has an ID then that is returned, otherwise unique ID is
	 * generated, set on the element, and returned.
	 *
	 * @return {string} The ID of the element
	 */
	public function getInputId() {
		$id = $this->input->getAttribute( 'id' );

		if ( $id === null ) {
			$id = Tag::generateElementId();
			$this->input->setAttributes( [ 'id' => $id ] );
		}

		return $id;
	}

	/**
	 * Get the value of the input.
	 *
	 * @return string Input value
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Set the directionality of the input.
	 *
	 * @param string $dir Text directionality: 'ltr', 'rtl' or 'auto'
	 * @return $this
	 */
	public function setDir( $dir ) {
		$this->input->setAttributes( [ 'dir' => $dir ] );
		return $this;
	}

	/**
	 * Set the value of the input.
	 *
	 * @param string $value New value
	 * @return $this
	 */
	public function setValue( $value ) {
		$this->value = $this->cleanUpValue( $value );
		$this->input->setValue( $this->value );
		return $this;
	}

	/**
	 * Clean up incoming value.
	 *
	 * Ensures value is a string, and converts null to empty string.
	 *
	 * @param string $value Original value
	 * @return string Cleaned up value
	 */
	protected function cleanUpValue( $value ) {
		if ( $value === null ) {
			return '';
		} else {
			return (string)$value;
		}
	}

	public function setDisabled( $state ) {
		parent::setDisabled( $state );
		if ( isset( $this->input ) ) {
			if ( $this->isDisabled() ) {
				$this->input->setAttributes( [ 'disabled' => 'disabled' ] );
			} else {
				$this->input->removeAttributes( [ 'disabled' ] );
			}
		}
		return $this;
	}

	public function getConfig( &$config ) {
		$name = $this->input->getAttribute( 'name' );
		if ( $name !== null ) {
			$config['name'] = $name;
		}
		if ( $this->value !== '' ) {
			$config['value'] = $this->value;
		}
		return parent::getConfig( $config );
	}
}
