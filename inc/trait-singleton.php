<?php
namespace WPAIToolbox;

//Used from https://wp-helpers.com/2021/03/23/using-the-singleton-pattern-to-deal-with-constraints/
trait Singleton {

    /**
     * @var Singleton reference to singleton instance
     */
    private static $instance;

    /**
     * Creates a new instance of a singleton class (via late static binding),
     * accepting a variable-length argument list.
     *
     * @return self
     */
    final public static function instance()
    {

        if(!self::$instance) {

            self::$instance = new self;

        }

        return self::$instance;
    }

    /**
     * Prevents cloning the singleton instance.
     *
     * @return void
     */
    private function __clone() {}

    /**
     * Prevents unserializing the singleton instance.
     *
     * @return void
     */
    public function __wakeup() {}
}