<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author              Petteri Kivim?ki
 * @copyright   Copyright (C) 2015 Petteri Kivim?ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
if($this->item->id > 0) :
?>
<iframe src="index.php?option=com_isbnregistry&view=publications&publisherId=<?php echo $this->item->id; ?>&tmpl=component&layout=embed" frameborder="0" height="400" width="80%"></iframe>
<?php endif; ?>