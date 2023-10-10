# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

- Add support for both `monolog/monolog` version `^2.0` and `^3.0`.
- Drop support for OctoberCMS `^2.0`.
- Add support for Laravel `^10.0` (keeping compatibility with ^9.0`).

## [2.2.0] - 2023-08-11

- Modify `composer.json` constraint from `october/system` to `october/rain`.
- Modify `composer.json` to allow `composer/installers` plugin.
- Modify `Vdlp\AmqpLogging\Classes\AmqpLogger.php` fallback formatter to append new line.

## [2.1.0] - 2022-05-27

- Add support for October CMS `^3.0`.

## [2.0.0] - 2021-07-09

- Drop support for October CMS `^1.0`.
