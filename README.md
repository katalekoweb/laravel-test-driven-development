# Laravel Test Driven Development with PHPUnit and Pest 

This repository contains examples and best practices for implementing Test Driven Development (TDD) in Laravel applications using PHPUnit and Pest testing frameworks. It covers various aspects of TDD, including writing tests, creating features, and ensuring code quality.

## Features
- Comprehensive examples of TDD in Laravel
- Usage of PHPUnit and Pest for testing
- Best practices for writing maintainable and effective tests
- Step-by-step guides for implementing TDD in your projects

## How to run test command
To run the test suite, use the following command in your terminal:

```bash
php artisan test
``` 

## How to run an spefic test file
To run a specific test file, use the following command in your terminal:
```bash
php artisan test --filter=NameOfTheTestFile
```

## Test Mantra 
1. Write a failing test
2. Write the minimum code to make the test pass
3. Refactor the code while keeping the tests green  

## The three AAAs of testing
1. Arrange: Set up the conditions for the test
2. Act: Execute the code being tested
3. Assert: Verify that the outcome is as expected 

## Project installation locally using Docker with Sail