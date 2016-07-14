<?php

declare(strict_types=1);

namespace Rs\XmlFilter\Exception;

use Rs\XmlFilter\Document\Element;

class Exception extends \Exception
{
    /**
     * @var Element
     */
    private $element;
    /**
     * @var array
     */
    private $options;
    /**
     * @var mixed
     */
    private $value;

    public function __construct($message, Element $element, array $options, $value = null)
    {
        parent::__construct($message);

        $this->element = $element;
        $this->options = $options;
        $this->message = $message;
        $this->value = $value;
    }

    public function getOptions() : array
    {
        return $this->options;
    }

    public function getElement() : Element
    {
        return $this->element;
    }

    public function getValue()
    {
        return $this->value;
    }
}
