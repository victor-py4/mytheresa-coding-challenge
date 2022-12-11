## About
MyTheresa Coding Challenge is a project made with Laravel which uses Docker as an infrastructure to ensure it works in any machine.
It implements a REST API endpoint that given a list of products, applies some discounts to them and can be filtered.

It uses **PHP 8.1.13** and **MYSQL 8.0**, each one has an individual Docker container connected to the same network.

All the required information is set at the env.example, if you prefer to change the domain or the basic credentials feel free to change it before the setup.
Also the project starts at the default ports, 80 and 3306, if you have any other services that uses these ports please stop the services or change it a **docker-compose.yml** file.

## Requirements
You'll need to have Docker and Docker Compose both installed and running to initialize the project.

Fortunatelly Docker Dekstop is availabe for all systems and provides everything you need to run it.

Here you have how to install it to different OS systems:

- [Docker Desktop for Linux](https://docs.docker.com/desktop/install/linux-install/)
- [Docker Desktop for Mac OS](https://docs.docker.com/desktop/install/mac-install/)
- [Docker Desktop for Windows](https://docs.docker.com/desktop/install/windows-install/)

If you have already installed and running Docker, you need to ensure that your shell runs bash by default or you could execute bash scripts.

For windows users you should ensure that Windows Subsystem for Linux 2 (WSL2) is installed and enabled. 
WSL allows you to run Linux binary files in Windows, if you need to install here's the [information how to install WSL](https://learn.microsoft.com/en-us/windows/wsl/install).

Before WSL installation you need to enable it for Docker. [Enable WSL for Docker Desktop](https://docs.docker.com/desktop/windows/wsl/)

## Setup
In this projects you will find some bash scripts that helps you run specific commands.
> setup.sh // Initializes the project and installs everything you need
> 
> run_tests.sh // Runs the tests

Both of them are shell scripts, depending on your system you could execute them in different ways.
To ensure you can run them, change the file permissions to be executed like this:
```bash
chmod +xr setup.sh
```
Now you have to execute **setup.sh** to setup the project.
```
bash setup.sh
```
This tiny script will:
- Setup the environment.
- Download and setup all the docker images.
- Install composer dependencies if the project needs.
- Run the DB migration with all the sctructure and data.

## Project
This projects provides you three entities: **Product**, **Category** and **Discount**. Each one has it's endpoint to retrive individually the information.
For example, if you need to get the first product information, you could use:
```
http://mytheresa-coding-challenge.test/api/product/1
```
And it will return a JSON with all the product information.

It works with all the entities, so you just need to replace the entity name with the other ones and also the ID to get another one.
Here are some examples:
```
http://mytheresa-coding-challenge.test/api/product/3
http://mytheresa-coding-challenge.test/api/category/2
http://mytheresa-coding-challenge.test/api/discount/1
```

Also there's another endpoint that retrieves all the products, filtered by category and optional by price 
less than a price you provide (all the prices are in a min unit, multiplied by 100 to avoid float numbers).

It has two filters as described above as query parameters, **category** will return all the products filtered by category and **priceLessThan** will return all the products that have a lower or equal price you provided.

You could combine them or use them individually, if you don't provide any of them it will return all the products.
Here's an example:
```
http://mytheresa-coding-challenge.test/api/products?category=sandals&priceLessThan=80000
```

