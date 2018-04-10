<?php declare(strict_types=1);

namespace Project;

/**
 * Class ConfigurationInterface
 * @package     Project
 * @copyright   Copyright (c) 2018 Maik Schößler
 */
interface ConfigurationInterface
{
    /** @var string PASS */
    public const PASS = 'password';

    /** @var string USER */
    public const USER = 'user';

    /** @var string DEFAULT_SERVER */
    public const DEFAULT_SERVER = 'localhost';
}