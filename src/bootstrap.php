<?php
/**
 *
 * @author d.morozov
 */

require __DIR__.'/../vendor/autoload.php';

// Doctrine don't use autoload PHP
// @see https://stackoverflow.com/questions/12456938/cant-read-annotation-when-use-symfony-validator-as-standalone
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(function($class) {
    return class_exists($class);
});