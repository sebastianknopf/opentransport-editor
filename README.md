# OpenTransport GTFS Editor

A web-based [GTFS](https://www.gtfs.org) editor built with [CakePHP](https://github.com/cakephp/cakephp) 3.7 and the [AdminLTE](https://github.com/maiconpinto/cakephp-adminlte-theme) theme.

## General

This app called *OpenTransport* is a completely web-based multi-tenant editor for GTFS data with a REST API to access the data in structured an machine-readable way.

The main intent of this app is to enable especially little transit agencies offering tourism or historic transport services to create valid GTFS feeds for embedding in Google Maps.
In general this project is intended for little transport agencies looking for a simple way of passenger information where money is a limiting factor. Due to the ability to add
tenants to the data, it can also be used from more than one transport agency at one time.

Need some preview? [Look at the set of screenshots](docs/SCREENSHOT.md)!

All you need to run is a standard web server and a MySQL database where you can upload and run the app.

## Installation

The installation process for users is kept as simple as possible. What you need is the dependency manager [Composer], a basic FTP client and
your credentials for the FTP access and your database. To get an actual package there're the following options.

1.  Download the required package by using composer. Run the command `php composer.phar create-project --prefer-dist opentransport/editor`
    Composer will install create a ready-to-use package for you. 
    
2.  If you don't want to use Composer, you also can download the whole package from [php-download.com](https://www.php-download.com) searching for `opentransport/editor`. Take a
    look into their user guide, to see how it works in general.

Once you've created the PHP package for *OpenTransport* Editor, you can upload the whole chunk of files to your webserver using the FTP client.
After that, type your desired address in the browser to start the web installer. See the [installation guide](/docs/INSTALL.md) for more information.

## Beginner Guide

Before using this editor, you should inform about general structure of GTFS data and the structure of common timetable data formats. You can find a highly recommended lecture about GTFS in general on [https://www.gtfs.org](https://www.gtfs.org) including some best-practices.

Once you are familiar with GTFS and the timetable structure in general you can start using the *OpenTransport* editor. There're three important URLs for you:

*   The frontend an testing view on `https://{yourdomain}` - Provides a very basic demo of how you can use the REST API
    to create a simple information system.
    
*   The admin backend on `https://{yourdomain}/admin` - This is your main working place. Here all the data management
    and background work is done.
    
*   The REST API endpoint on `https://{yourdomain}/api` - The interface to access the data in your database from public. You
    can find a documentation about the REST API by opening this URL.

## Planned Improvements

Of course there're several improvements planned to this editor. You can find a small overview here. If you're missing something, feel free to open an issue of type encancement for this.

### Usability

For the next few versions there're plans to improve the usability. In particular there're for e.g. plans for a special 'Network Editor' to add stops and shapes
in a simple intuitive way. 

### Functional Enhancements

*   **Data-Export** - In order to provide a simple and effective way to export your timetable data
    to various timetable data formats, the data export will be finished in *Version 1.0.0*.
   
*   **Realtime Data** - It is really no secret, that consistent realtime data could push the value of your passenger information to a higher level. For this
    purpose, we'll extend the REST API do receive realtime data directly out of your vehicles (for e.g. by using a special Android app) and also
    to include these realtime information into the basic outputs.
    
*   **Multimodal Routing** - To provide a basic way to compute transport routes in between your network, a simple routing engine based on the [Connection Scan Algorithm](https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=2&cad=rja&uact=8&ved=2ahUKEwil6u2vpdTnAhUdQEEAHXbkClYQFjABegQIAhAB&url=https%3A%2F%2Fi11www.iti.kit.edu%2Fextra%2Fpublications%2Fdpsw-isftr-13.pdf&usg=AOvVaw2KEie3XrKwsclPLJfGxm9E) [download original PDF paper] is planned.
    This should be no replacement for a professional routing engine, since it is more experimental.    
   
*   **Vehicle Data** - The data format GTFS on which the app is working does not support vehicle data in general. One 
    improvement and benefit of *OpenTransport* should be a simple way to provide also vehicle information to your timetable data.
    
*   **Fare Data** - Fare modelling in GTFS is quite basic: It only supports one standard fare, no fare classes like adults, children or reduced prices. 
    One goal for the next few versions will be a complete fare modelling, which enables a detailled fare information with
    all important fare models which are used in Germany.

### Manual

The currently used system manual is very basic. It will be improved within the next view versions.

### Localisation

Sadly the app is only available in German today. If you want to create a translation for another language, you can use the GetText under [src/Locale/default.pot](src/Locale/default.pot) file which contains the whole text snippets to 
translate in a new language.

## More Information

### Contributing

You feel like coding in PHP is yours and you're familiar with CakePHP and general transport information, feel free to contribute this project! There're only a few requirements, you should be aware of:

*   Please try to use as much PHP coding standard as possibly anytime
*   Document your code - a method at least with a PHP-Doc comment and so on
*   Coding language should be English
*   Try to localize as much as possible using the `__(...)` functions

If you've created a new feature, open a PR to contribute the project.

### License

This project is licensed under the MIT License. See [LICENSE](/LICENSE.md) for more information.