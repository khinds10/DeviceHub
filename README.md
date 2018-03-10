# Device Hub

#### Generalized data hub to persist RaspberryPi project generated data for reporting and analysis

## Installation

Copy the files from this project to a PHP enabled webhost of your choice with a MySQL instance available for use

### Create the MySQL DB (having connectivity to your PHP webhost)

>
>    CREATE DATABASE my_devices;
>
>    USE my_devices;
>
>    CREATE TABLE devices (
>        entry INT NOT NULL AUTO_INCREMENT,
>        time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
>        device VARCHAR(50),
>        value1 VARCHAR(500),
>        value2 VARCHAR(500),
>        value3 VARCHAR(500),
>        value4 VARCHAR(500),
>        value5 VARCHAR(500),
>        value6 VARCHAR(500),
>        value7 VARCHAR(500),
>        value8 VARCHAR(500),
>        value9 VARCHAR(500),
>        value10 VARCHAR(500),
>        PRIMARY KEY (entry)
>    );
>
>    ALTER TABLE `devices` ADD INDEX `device` (`device`);
>    ALTER TABLE `devices` ADD INDEX `time` (`time`);
>

### Configure Site Settings

Copy the `settings.shadow.php` file to a file named `settings.php` with the correct credentials for your MySQL instance

## RPi Project python example to upload data

Python dependancy

> sudo apt-get install python-requests

Code Snippet

>    import requests
>
>    r = requests.post("http://yourweb.com/api/log/", data={'device': 'weather-clock', 'value1': '78', 'value2': '56', 'value3': '', 'value4': '', 'value5': '', 'value6': '', 'value7': '', 'value8': '', 'value9': '', 'value10': ''})
>    print(r.status_code, r.reason)
>    print(r.text)
>

`weather-clock` indentified device persisting `value1 = 78` and `value2 = 56`
(value1 is *F and value2 is humidity % implied in this case)

#### Read Device Data Back
http://yourweb.com/api/read/?device=weather-clock

#### Additional API usage examples

http://yourweb.com/api/hello
    Output: HELLO WORLD

http://yourweb.com/api/test?var=testing_get_method
    Output: testing_get_method
