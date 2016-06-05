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

### Architecture

The system is based on Joomla! CMS 3.X Platform and the architecture follows the rules and guidelines for Joomla [components](https://docs.joomla.org/Absolute_Basics_of_How_a_Component_Functions) and [plugins](https://docs.joomla.org/J3.x:Creating_a_Plugin_for_Joomla). 

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
