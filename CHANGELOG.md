# Changelog

All notable changes to `laravel-mtn-momo-ai` will be documented in this file.

# v1.2.0 - 2025-01-23

## New Features
- Added support for DeepSeek AI model alongside existing ChatGPT, Claude, and Gemini models
- Implemented DeepSeek class with full LLMInterface compatibility
- Updated configuration to include DeepSeek API settings

## Changes
- Modified LLMFactory to create DeepSeek instances
- Enhanced InstallCommand to prompt for DeepSeek API key during setup

## Developer Notes
- Requires DeepSeek PHP client: "deepseek-php/deepseek-php-client": "^1.0"
- Update your .env file with DEEPSEEK_API_KEY for full functionality


## v1.1.0 - 2025-01-12

### Fixed

- issues with the installation command
- issues with the NLP (Natural Language Processing) not working
- removed unnecessary code
- some functionality with the help of AI models not working

### Added

- proper PHPDoc standard comments for clearer understanding of use
- automated AI-prompted notification feature for alerting users of transaction anomalies

## 1.0.1 - 2025-01-10

### Fixed

- minor bug fixes

## 1.0.0 - 2025-01-08

Initial Release ✨
