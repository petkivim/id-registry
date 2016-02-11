<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Load styles
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_isbnregistry/css/searchpublisher.css");
?>

<h3 class="article-title"><?php echo $this->publisher->official_name; ?></h3>
<div>
    <table>
        <tr>
            <td><?php echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_ADDRESS'); ?> :</td>
            <td><?php echo $this->publisher->address; ?></td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_ZIP'); ?> :</td>
            <td><?php echo $this->publisher->zip; ?></td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_CITY'); ?> :</td>
            <td><?php echo $this->publisher->city; ?></td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_PHONE'); ?> :</td>
            <td><?php echo $this->publisher->phone; ?></td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_WWW'); ?> :</td>
            <td><?php echo $this->publisher->www; ?></td>
        </tr>
        <?php if ($this->publisher->has_quitted) : ?>
            <tr>
                <td><?php echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_STATE'); ?> :</td>
                <td><?php echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_QUITTED'); ?></td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<h4 class="subtitle"><?php echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_ISBN_IDENTIFIERS'); ?></h4>
<?php
if (empty($this->isbns)) {
    echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_ISBN_NO_IDENTIFIERS');
} else {
    ?>
    <table class="identifiers">     
        <?php
        foreach ($this->isbns as $isbn) {
            $row = '<tr><td class="identifier">';
            $row .= $isbn->publisher_identifier;
            $row .= '</td><td class="description">';
            if ($isbn->is_active && !$this->publisher->has_quitted) {
                $row .= JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_IDENTIFIER_STATE_ACTIVE');
            } else if ($isbn->is_closed) {
                $row .= JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_IDENTIFIER_STATE_CLOSED');
            }
            $row .= '</td></tr>';
            echo $row;
        }
    }
    ?>
</table>

<h4 class="subtitle"><?php echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_ISMN_IDENTIFIERS'); ?></h4>
<?php
if (empty($this->ismns)) {
    echo JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_ISMN_NO_IDENTIFIERS');
} else {
    ?>
    <table class="identifiers">     
        <?php
        foreach ($this->ismns as $ismn) {
            $row = '<tr><td class="identifier">';
            $row .= $ismn->publisher_identifier;
            $row .= '</td><td class="description">';
            if ($ismn->is_active && !$this->publisher->has_quitted) {
                $row .= JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_IDENTIFIER_STATE_ACTIVE');
            } else if ($ismn->is_closed) {
                $row .= JText::_('COM_ISBNREGISTRY_VIEW_PUBLISHER_IDENTIFIER_STATE_CLOSED');
            }
            $row .= '</td></tr>';
            echo $row;
        }
    }
    ?>
</table>
