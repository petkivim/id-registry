# Identifier Registry

Identifier Registry is a system for managing identifiers for books and sheet music intended for public use, and continuously published publications. The system provides features for the management of the following identifiers:

* International Standard Book Number ([ISBN](https://en.wikipedia.org/wiki/International_Standard_Book_Number))
* International Standard Music Number ([ISMN](https://en.wikipedia.org/wiki/International_Standard_Music_Number))
* International Standard Serial Number ([ISSN](https://en.wikipedia.org/wiki/International_Standard_Serial_Number))
 
The system is based on Joomla! CMS platform. The registry includes two parts that each include a component and a content plugin. The components contain the backend features for library officers and the content plugins offer public applications forms for the users.

* ISBN & ISMN registry
  * com_isbnregistry
  * plg_content_isbnregistry_forms
* ISSN registry
  * com_issnregistry
  * plg_content_issnregistry_forms

ISBN & ISMN registry and ISSN registry are independet of each other, and it's possible to install and use both or only one of them. The backend components can be used also without the content plugins, but the use of the content plugins is not possible without the backend components.

### ISBN & ISMN Registry Features

* Publisher Registry
  * Includes a public search feature
* ISBN & ISMN application management and processing
* Messaging
  * notifications, group messages, message templates
* Statistics
* ISBN and ISMN range management 
* ACL management

### ISSN Registry Features

* Publisher Registry
* Publication Registry
* ISSN application management and processing
* Messaging
  * notifications, group messages, message templates
* Statistics
* ISSN range management
* ACL management

### Installation

Identifier Registry is installed as any other Joomla extension.

* Download the extension to your local machine as a zip file package.
* From the backend of your Joomla site (administration) select Extensions  →  Install/Uninstall.
* Click the Browse button and select the extension package on your local machine.
* Click the Upload File & Install button.

More detailed [instuctions](https://docs.joomla.org/Installing_an_extension).

### Usage

Using the content plugins it's possible to embed registrarion and application forms on articles.

ISBN & ISMN Registry registration form for publishers:

```
{mono_pub_form registration}
``` 

ISBN & ISMN Registry identifier application form for publishers:

```
{mono_pub_form application}
``` 

ISSN Registry identifier application form for publishers:

```
{serials_pub_form}
```
