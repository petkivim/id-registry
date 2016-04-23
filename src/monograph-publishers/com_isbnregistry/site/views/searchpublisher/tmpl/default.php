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
JHTML::_('behavior.modal');

// Load styles
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_isbnregistry/css/searchpublisher.css");
?>

<h1 class="article-title"><?php echo JText::_('COM_ISBNREGISTRY_SEARCH_PUBLISHER_VIEW_TITLE'); ?></h1>

<div class="intro"><?php echo JText::_('COM_ISBNREGISTRY_SEARCH_PUBLISHER_INTRO'); ?></div>

<form action="<?php echo JURI::current(); ?>" method="get">
    <div>
        <table>
            <tr>
                <td><?php echo JText::_('COM_ISBNREGISTRY_SEARCH_PUBLISHER_SEARCH_FIELD_LABEL'); ?> :</td>
                <td><input type="text" name="searchStr" id="searchStr" /></td>
                <td><input type="submit" name="searchBtn" value="<?php echo JText::_('COM_ISBNREGISTRY_SEARCH_PUBLISHER_SEARCH_BUTTON_LABEL'); ?>"</td>
                <?php JHTML::_('form.token'); ?>
            </tr>
        </table>
    </div>
    <?php if (JFactory::getApplication()->input->get('searchBtn', null, 'string') != null || JFactory::getApplication()->input->get('limit', 0, 'int') != 0) : ?>
        <h3><?php echo JText::_('COM_ISBNREGISTRY_SEARCH_PUBLISHER_SUBTITLE_RESULTS'); ?></h3>
        <div id="j-main-container" class="span10">
            <?php if (empty($this->items)) : ?>
                <div class="alert alert-no-items">
                    <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
            <?php else : ?>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%"><?php echo JText::_('COM_ISBNREGISTRY_SEARCH_PUBLISHERS_NUM'); ?></th>
                            <th width="65%">
                                <?php echo JText::_('COM_ISBNREGISTRY_SEARCH_PUBLISHERS_OFFICIAL_NAME'); ?>
                            </th>
                            <th width="30%">
                                <?php echo JText::_('COM_ISBNREGISTRY_SEARCH_PUBLISHERS_ACTIVE_IDENTIFIERS'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <?php echo $this->pagination->getListFooter(); ?>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($this->items as $i => $row) : ?>
                            <?php
                            $link = '<a href="' . JRoute::_('index.php?option=com_isbnregistry&view=searchpublisher&layout=info&tmpl=component&publisherId=' . $row->id);
                            $link .= '" class="modal" rel="{size: {x: 800, y: 600}, handler:\'iframe\'}">' . $row->official_name . '</a>';
                            ?>
                            <tr>
                                <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                                <td>
                                    <?php echo $link; ?>
                                    <span class="ismn_label">
                                        <?php echo (isset($this->ismn_publisher_ids[$row->id]) ? JText::_('COM_ISBNREGISTRY_SEARCH_PUBLISHERS_ISMN_LABEL') : ''); ?>
                                    </span>
                                </td>   
                                <td>
                                    <?php
                                    $identifier = empty($row->active_identifier_isbn) ? '' : $row->active_identifier_isbn;
                                    $identifier .=!empty($row->active_identifier_isbn) && !empty($row->active_identifier_ismn) ? ', ' : '';
                                    $identifier .= empty($row->active_identifier_ismn) ? '' : $row->active_identifier_ismn;
                                    echo $identifier;
                                    ?>
                                </td> 
                            </tr>
                        <?php endforeach; ?>              
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php echo JHtml::_('form.token'); ?>
</form>