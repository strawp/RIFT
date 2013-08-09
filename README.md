RIFT
====

Rapid Intranet Framework Template

Create flexible web-based relational databases inside your organisation with the minimum development time.

## Installation

Clone / download the code:

`cd /var/www/`

`$ git clone https://github.com/strawp/RIFT.git`

Configure apache to accept htaccess files called "_htaccess":

`$ sudo vim /etc/apache2/htaccess.conf`

`AccessFileName .htaccess _htaccess`

..and also hide files starting "_ht" or ".ht"

```
<Files ~ "^(\.|_)ht">
    Order allow,deny
    Deny from all
    Satisfy all
</Files>

```

Then set the document root for this vhost as any other site:

```
<VirtualHost *:80>
  ServerName your.domain.name
  DocumentRoot /var/www/RIFT/application/
</VirtualHost>
```

Restart Apache:

`sudo apache2ctl restart`

Create a database for it to connect to:

`$ mysql -u root -p`

`mysql> create schema rift;`

Add a user to that database:

`mysql> GRANT ALL ON rift.* TO riftuser IDENTIFIED BY 'Am4z1ngP4$sw0rd';`

`mysql> FLUSH PRIVILEGES;`

`mysql> exit`

Initialise the database structure, noting the password for the admin user that is created for you as part of this process.

`cd RIFT/application/scripts`

`php sync_db.php`

Then log in at whatever URL you deployed it to using the admin username / password the `sync_db.php` script created. If you missed that bit, 
at this stage you can just drop and recreate the schema for it to do it all again using `mysql> DROP SCHEMA rift`.

## Working with the RIFT code

### Creating new classes / tables

In RIFT, models and tables are closely associated. All tables are automatically created by their associated class and we can quickly create
classes by using the `create_class.php` script. For example, creating a new class `Cake` looks like this:

```
$ php create_class.php
Interactive mode class creator (ctrl-c to exit)

1.  Create a new class
2.  Remove a class
3.  Save and exit
4.  Save, sync with DB and exit

Enter an option: 1

Class name: Cake

Add a field: strName
"strName"

Add a field: strIcing
"strIcing"

Add a field: cnfVegan
"cnfVegan"

Add a field:

Class created

1.  Create a new class
2.  Remove a class
3.  Save and exit
4.  Save, sync with DB and exit
Edit one of the following:
  5. Cake

Enter an option: 4
Syncing database with framework models...
Checking table options...
Database sync'd. Did the following things:
 - Created table cake
 - Added cake to meta table
Took 0:00:00
```

This has created the file `models/cake.model.class.php` and then created a corresponding database table, `cake`. 
Fields are specified in the manner we would want them to appear on the web form and are denoted by the first 3 characters
of their name. For example, `strName` and `strIcing` are string fields, rendered as text boxes on the web and stored as varchars in the database. 
`cnfVegan` is a "Confirm" box, rendered as a single checkbox on the web and stored as a 0/1 in a tinyint in the database.

The full list of available field types can be found in the application under `Reports -> System -> List field types` when you are logged in
as an admin user. A demonstration of how these fields are rendered is also available in the `test` class.

If you log into your instance of RIFT you will see how this class is rendered by going to `http://your.site/cake/`.

### Linking between classes

One powerful feature of RIFT is that it is very easy to link between two classes. 
For example, say we want to record the favourite type of cake of each of our users. Open up `application/models/user.model.class.php` and
under the line that looks like:

`$this->addField( Field::create( "strTitle" ) );`

add the line:

`$this->addField( Field::create( "lstCakeId" ) );`

Save the file, then run `application/scripts/sync_db.php` again. This picks up the fact that `lstCakeId` has been added and creates a foreign key
in the table `user` called `cake_id`. On the web this is rendered as a drop down box of all available cakes on the system. 

We can go back to the user file and make the name more friendly. Add the optional `displayname` parameter so that the line now reads:

`$this->addField( Field::create( "lstCakeId", "displayname=Favourite Cake" ) );`

You will see this now reflected in the label for that field.

Now that users are linked to cakes, we can reflect this link on the cake page by showing all users who like that cake. 
In `application/models/cake.model.class.php`, under the line:

`$this->addField( Field::create( "cnfVegan" ) );`

add the line:

`$this->addField( Field::create( "chdUser", "displayname=Fans" ) );`

This is "child" field. It automatically picks all users that are pointing to cake and displays them in a new tab called "Fans". 
We can click through to any of the users associated with that cake from the list.


### Setting access privileges

RIFT has a user group based access control system. You can specify that users of any groups have *C*reate, *R*ead, *U*pdate or *D*elete 
access to a model. By default, RIFT has two user groups: Editors and Reviewers but currently only admin users can see the delicious cake records. 
We can assign full access to the cake to our editors group by adding this line to the top of the constructor of the `Cake` class:

`$this->addAuthGroup( "EDIT" );`

and we can grant read access to the reviewers group by adding this line:

`$this->addAuthGroup( "REVI", "r" );`

If we also wanted to grant the reviewers update access we could change the `"r"` to `"ru"` or `"cru"` for create privileges and `"crud"` for 
full create, read, update and delete privileges.

## License

This code is published under a GPL-2.0 license.