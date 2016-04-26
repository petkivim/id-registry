<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author      Petteri Kivimäki
 * @copyright   Copyright (C) 2016 Petteri Kivim?ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
if ($this->item->id > 0) :
    ?>
    <iframe id="publications_iframe" src="index.php?option=com_issnregistry&view=publicationsembed&formId=<?php echo $this->item->id; ?>&tmpl=component&layout=embed_form" frameborder="0" height="400" width="80%"></iframe>
<?php endif; ?>