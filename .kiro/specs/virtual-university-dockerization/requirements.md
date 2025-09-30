# Requirements Document

## Introduction

This document outlines the requirements for dockerizing the Virtual University project, a comprehensive educational management system built on CodeIgniter 3.1. The goal is to create a containerized environment that allows developers and users to run the application locally without manual LAMP stack configuration, ensuring consistent deployment across different environments.

## Requirements

### Requirement 1

**User Story:** As a developer, I want to run the Virtual University application using Docker containers, so that I can quickly set up a development environment without manually configuring Apache, MySQL, and PHP.

#### Acceptance Criteria

1. WHEN a developer runs `docker-compose up` THEN the system SHALL start all required services (Apache, MySQL, PHP)
2. WHEN the containers are running THEN the application SHALL be accessible via http://localhost:8080
3. WHEN the application starts THEN the database SHALL be automatically initialized with the provided SQL schema
4. WHEN the containers are stopped and restarted THEN the database data SHALL persist between sessions

### Requirement 2

**User Story:** As a developer, I want the Docker environment to match the production requirements, so that I can ensure compatibility and avoid environment-specific issues.

#### Acceptance Criteria

1. WHEN the Docker environment is created THEN it SHALL use PHP version 7.4 or compatible with CodeIgniter 3.1 requirements
2. WHEN the Docker environment is created THEN it SHALL use MySQL 5.7 or higher for database compatibility
3. WHEN the Docker environment is created THEN it SHALL use Apache 2.4 with mod_rewrite enabled
4. WHEN the application runs THEN it SHALL support URL rewriting as configured in the .htaccess file

### Requirement 3

**User Story:** As a developer, I want the database to be automatically configured, so that I don't need to manually set up database credentials and schema.

#### Acceptance Criteria

1. WHEN the MySQL container starts THEN it SHALL automatically create the `sw15_update` database
2. WHEN the database is created THEN it SHALL execute the provided SQL schema from `Sql/Update_proyect.sql`
3. WHEN the application connects to the database THEN it SHALL use environment variables for database credentials
4. WHEN the database is initialized THEN it SHALL include the default admin user with credentials from the SQL file

### Requirement 4

**User Story:** As a developer, I want the application files to be properly mounted and configured, so that I can make changes during development and see them reflected immediately.

#### Acceptance Criteria

1. WHEN the Docker containers are running THEN the Learning folder SHALL be mounted to the Apache document root
2. WHEN a file is modified in the Learning directory THEN the changes SHALL be immediately visible in the running application
3. WHEN the Apache container starts THEN it SHALL have proper permissions to read and execute PHP files
4. WHEN the application runs THEN the CodeIgniter base_url SHALL be automatically configured for the Docker environment

### Requirement 5

**User Story:** As a user, I want clear documentation on how to use the Docker setup, so that I can easily get the application running without technical expertise.

#### Acceptance Criteria

1. WHEN the Docker setup is complete THEN there SHALL be a README with clear setup instructions
2. WHEN following the documentation THEN a user SHALL be able to start the application with a single command
3. WHEN the documentation is provided THEN it SHALL include default login credentials
4. WHEN troubleshooting is needed THEN the documentation SHALL include common issues and solutions

### Requirement 6

**User Story:** As a developer, I want the Docker environment to be production-ready, so that I can deploy the same configuration to different environments.

#### Acceptance Criteria

1. WHEN the Docker configuration is created THEN it SHALL support environment-specific configurations
2. WHEN deploying to production THEN the database credentials SHALL be configurable via environment variables
3. WHEN the application runs in production mode THEN it SHALL have appropriate security settings
4. WHEN scaling is needed THEN the Docker setup SHALL support horizontal scaling of the web application