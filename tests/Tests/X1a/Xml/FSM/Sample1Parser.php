<?php

namespace Tests\X1a\Xml\FSM;

use X1a\Xml\FSM\Parser;

class Sample1Parser extends Parser
{

    /**
     * FSM States
     */
    const ST_INITIAL = Parser::STATUS_INITIAL;
    const ST_ROWS = 'rows';
    const ST_ROW = 'row';
    const ST_ROW_TEXT = 'rowText';

    /**
     * Events
     */
    const EV_ROWS_START = 'rows-1';
    const EV_ROWS_END = 'rows-15';
    const EV_ROW_START = 'row-1';
    const EV_ROW_END = 'row-15';
    const EV_TEXT = '#text-3';

    protected $transitions = [
            [self::ST_INITIAL, self::ST_ROWS, self::EV_ROWS_START],
            [self::ST_ROWS, self::ST_INITIAL, self::EV_ROWS_END],

            [self::ST_ROWS, self::ST_ROW, self::EV_ROW_START],
            [self::ST_ROW, self::ST_ROWS, self::EV_ROW_END],
            [self::ST_ROW, self::ST_ROW_TEXT, self::EV_TEXT],
            [self::ST_ROW_TEXT, self::ST_ROWS, self::EV_ROW_END],
    ];

    /**
     * Sample1Parser constructor.
     */
    public function __construct()
    {
        $states = [static::ST_ROWS, static::ST_ROW, static::ST_ROW_TEXT];
        parent::__construct($states, $this->transitions);
    }


}