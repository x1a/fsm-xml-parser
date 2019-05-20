<?php

namespace Tests\X1a\Xml\FSM;

use X1a\Xml\FSM\Reader;

class Sample1Reader extends Reader
{
    public $something;
    public $rows = [];

    protected $rowText;

    protected function attachEventHandlers()
    {
        $this->parser
                ->attachHandler(Sample1Parser::ST_INITIAL, Sample1Parser::EV_ROWS_START, [$this, 'onRowsStart'])
                ->attachHandler(Sample1Parser::ST_ROWS, Sample1Parser::EV_ROW_START, [$this, 'onRowStart'])
                ->attachHandler(Sample1Parser::ST_ROW, Sample1Parser::EV_TEXT, [$this, 'onRowText'])
                ->attachHandler([Sample1Parser::ST_ROW, Sample1Parser::ST_ROW_TEXT],
                        Sample1Parser::EV_ROW_END, [$this, 'onRowEnd']);
    }

    public function onRowsStart()
    {
        $this->something = $this->xml->getAttribute('something');
    }

    public function onRowStart()
    {
        $this->rowText = '';
    }

    public function onRowText()
    {
        $this->rowText = $this->xml->value;
    }

    public function onRowEnd()
    {
        $this->rows[] = $this->rowText;
    }
}