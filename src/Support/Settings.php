<?php

namespace CodeDistortion\Adapt\Support;

/**
 * Common Adapt settings.
 */
class Settings
{
    /**
     * The name of the Adapt config file.
     *
     * @var string
     */
    public const LARAVEL_CONFIG_NAME = 'code-distortion.adapt';

    /**
     * The name of the table that contains the reuse information.
     *
     * The version in this name will change if the structure of the table ever changes.
     *
     * @const string
     */
    public const REUSE_TABLE = '____adapt____';

    /**
     * A version representing the way the reuse-table is structured and used.
     *
     * @const string
     */
    public const REUSE_TABLE_VERSION = '2';
}
