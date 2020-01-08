<?php

namespace Medalink\Reflects;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

/**
 * Trait Constants
 *
 * @package Medalink\Reflects
 */
trait Constants
{
    /**
     * Get constants for our class, supports a prefix limitation and a blacklist
     *
     * @param  string|null  $prefix
     * @param  bool  $asHumanReadable
     * @param  bool  $withoutPrefix
     * @return array
     */
    public static function getReflectedConstants(?string $prefix = null, bool $asHumanReadable = true, bool $withoutPrefix = true): array
    {
        // Holders
        $matches = [];
        $constants = [];
        $humanFormattedRoles = [];

        // Attempt to reflect our class and grab our role names from our constants
        try {
            $reflect = new ReflectionClass(static::class);
            $constants = array_keys($reflect->getConstants());

            // If we have a blacklist on the upstream let's use it
            if (isset(static::$reflectedConstantsBlacklist)) {
                $constants = array_diff($constants, static::$reflectedConstantsBlacklist);
            }

            // Determine if we need to filter by prefix
            if ($prefix) {
                // Loop our data and only use results starting with our prefix
                foreach ($constants as $constant) {
                    if (Str::startsWith($constant, $prefix)) {
                        // We also remove our prefix
                        if ($withoutPrefix) {
                            $matches[] = str_replace($prefix, '', $constant);

                            continue;
                        }

                        $matches = $constant;
                    }
                }

                // Override our list with our new filtered list
                $constants = $matches;
            }

        } catch (ReflectionException $e) {
            abort(500);
        }

        // Return results if we do not need to treat them
        if (!$asHumanReadable) {
            return $constants;
        }

        // Loop our roles and make them human readable if requested
        foreach ($constants as $constant) {
            $humanFormattedRoles[$constant] = ucwords(strtolower(str_replace('_', ' ', $constant)));
        }


        // Return our human formatted roles
        return $humanFormattedRoles;
    }

    /**
     * @param  string  $constant
     * @return string
     */
    public static function lookupConstant(string $constant): string
    {
        $constantName = strtoupper(str_replace(' ', '_', $constant));

        return constant('static::'.$constantName);
    }
}
