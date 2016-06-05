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

### Architecture

The system is based on Joomla! CMS 3.X Platform and the architecture follows the rules and guidelines for Joomla [components](https://docs.joomla.org/Absolute_Basics_of_How_a_Component_Functions) and [plugins](https://docs.joomla.org/J3.x:Creating_a_Plugin_for_Joomla). 

#### Ajax Calls

In general the system is based on Joomla MVC architecture, but there are some backend features that use asynchronous Ajax calls instead.

* get list of ISBN/ISMN identifers that belong to a publisher ([jQuery](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publisher.js#L144))
* get list of publications without ISBN/ISMN identifier related to a publisher ([show](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publisher.js#L409))
* create new publisher ISBN/ISMN identifier ([show](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publisher.js#L68))
* create new ISBN/ISMN identifier ([show](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publisher.js#L293))
* create new ISBN/ISMN identifiers ([show](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publisher.js#L352))
* update active publisher ISBN/ISMN identifier ([show](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publisher.js#L112))
* delete publisher ISBN/ISMN identifier ([show](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publisher.js#L252))
* cancel publication ISBN/ISMN identifier ([show](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publication.js#L67))
* delete publication ISBN/ISMN identifier ([show](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publication.js#L102))

Ajax calls are implemented using ```jQuery``` and responses are returned in ```JSON``` format. Below there's an example that shows the execution chain when a list of ISBN/ISMN identifers belonging to a publisher is fetched.

[jQuery](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/scripts/publisher.js#L144) => [controller](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/controllers/abstractpublisheridentifierrange.php#L76) => [model](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/models/abstractpublisheridentifierrange.php#L566) => [table](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/tables/abstractpublisheridentifierrange.php#L443)

#### Database Operations

Transactions are used in business operations that consist of multiple database queries that must all be succesful, e.g. create new publication ISBN identifier. Transactions are always implemented on model level and functions on table level classes must be called from the same model only. All the other parts of the system (e.g. controller, view, other models) must use the functions through model classes. Database queries are placed on table level, not on model, view or controller level. However, there are some exceptions because of Joomla's conventions, e.g. ```getListQuery``` function in ```mymodels``` class that returns a list of database items for the list view.

#### UI

Under the ```/views/classname/``` directory there are number of files. There is almost always a file called view.html.php. It is called the view file, but there can be more than one depending on the type of output to produce. It has a specific naming convention, view.output_type.php, where the output type can be html, feed, pdf, raw or error. What this means is when HTML output for this particular view is wanted, the view.html.php file is used. When the RSS output is wanted, the view.feed.php file is used. This feature is used when publication Marc21 [preview](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/views/publication/view.preview.php) is showed and when Marc21 file is [downloaded](https://github.com/petkivim/id-registry/blob/master/src/monograph-publishers/com_isbnregistry/admin/views/publication/view.raw.php). Therefore, the publication has three view files: ```view.html.php```, ```view.preview.php``` and ```view.raw.php```. When accessing the page the format can be specified using ```format``` URL parameter, e.g. ```format=preview```, ```format=raw```.

```
https://{SITE}/administrator/index.php?option=com_isbnregistry&view=publication&layout=edit&id=84&format=preview
```

The fields of the backend forms are defined in XML files located in ```com_xxxx/admin/models/forms``` folder, e.g. ISBN/ISMN registry [forms](https://github.com/petkivim/id-registry/tree/master/src/monograph-publishers/com_isbnregistry/admin/models/forms). Most of the fields are standard Joomla field types, but in addition there are some custom fields that are located in ```com_xxxx/admin/models/fields``` folder. Field validation is implemented using Joomla's front end validation framework. Validation rules are defined using jQuery and ```js``` files containing the rules are located in the ```com_xxxx/admin/scripts``` folder. For example:

Definition of publisher's ```official_name``` field in ```com_xxxx/admin/models/forms/publisher.xml``` file.

```
<field
    name="official_name"
    type="text"
    label="COM_ISBNREGISTRY_PUBLISHER_FIELD_OFFICIAL_NAME_LABEL"
    description="COM_ISBNREGISTRY_PUBLISHER_FIELD_OFFICIAL_NAME_DESC"
    size="20"
    class="inputbox validate-officialname"
    default=""
    required="true"
/>
```

Validation rules for the field in ```com_xxxx/admin/scripts/publisher_validators.js``` file.

```
jQuery(document).ready(function () {
    document.formvalidator.setHandler("officialname", function (value) {
        regex = /^.{1,100}$/;
        return regex.test(value);
    });
});
```

Embedded ```iframe```s and popup windows are also heavily used in the UI. Popup windows are implemented using Joomla's own popup system. 
