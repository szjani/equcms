## Requirements

* Zend Framework 1 (`http://framework.zend.com/svn/framework/standard/branches/release-1.11`)
* Doctrine 2 (`git://github.com/doctrine/doctrine2.git`)
* Doctrine Fixtures (`git://github.com/doctrine/data-fixtures.git`)
* Doctrine Migrations (`git://github.com/doctrine/migrations.git`)
* APC
* PHP 5.3

## Installation (regarding 2.2.x branch)

1. Download and extract the mandatory libraries
2. Clone equcms: `git clone git://github.com/szjani/equcms.git`
3. Init and update submodules: `git submodule update --init`
4. Open `defines.php` in your favourite editor and set library paths
5. PROJECT_CACHE_PREFIX constant should be unique for all projects, modify it
6. Create an empty database for the project
7. Open `application/configs/production_doctrine.xml` file and modify the database connection configuration
8. Go to `scripts` directory and create database tables: `php doctrine.php orm:schema-tool:create`
9. Initialize your database with predefined fixtures: `php fixtures-loadall.php`
10. Configure your webserver (eg. create a vhost file for apache) and voila