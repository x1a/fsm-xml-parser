<?php

namespace X1a\Xml\FSM;

abstract class Reader
{

    /**
     * @var \XMLReader
     */
    protected $xml;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * Reader constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
        $this->attachEventHandlers();
    }

    abstract protected function attachEventHandlers();

    /**
     * @param string $filepath
     * @throws \RuntimeException
     */
    public function read($filepath)
    {
        if (!file_exists($filepath)) {
            throw new \RuntimeException("File not found: '{$filepath}'");
        }

        $this->xml = new \XMLReader;

        if (false === $this->xml->open($filepath, null, LIBXML_COMPACT)) {
            throw new \RuntimeException("Can not open XML file: '{$filepath}'");
        }

        while ($this->xml->read()) {
            if ('#text' == $this->xml->name && \XMLReader::SIGNIFICANT_WHITESPACE == $this->xml->nodeType) {
                continue; // skip newlines and indents
            }

            if ('#comment' == $this->xml->name) {
                continue; // skip comments
            }

            $event = sprintf("%s-%s", $this->xml->name, $this->xml->nodeType);
            $this->parser->triggerEvent($event);
        }

        $this->xml->close();
    }


}