<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<form action="index.php?option=com_isbnregistry&view=publicationsembed&publisherId=<?php echo JFactory::getApplication()->input->getInt('publisherId', 0); ?>&tmpl=component" method="post" id="adminForm" name="adminForm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_NUM'); ?></th>
                <th width="20%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_TITLE'); ?>
                </th>	
                <th width="20%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_IDENTIFIER'); ?>
                </th>
                <th width="25%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_COMMENTS'); ?>
                </th>		
                <th width="15%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_CREATED'); ?>
                </th>	
                <th width="15%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_MODIFIED'); ?>
                </th>
                <th width="2%">
                    <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_ID'); ?>
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
            <?php if (!empty($this->items)) : ?>
                <?php
                foreach ($this->items as $i => $row) :
                    $link = JRoute::_('index.php?option=com_isbnregistry&task=publication.edit&id=' . $row->id);
                    ?>
                    <tr>
                        <td><?php echo $this->pagination->getRowOffset($i); ?></td>   
                        <td>
                            <a target="new" href="<?php echo $link; ?>" title="<?php echo JText::_('COM_ISBNREGISTRY_EDIT_PUBLICATION'); ?>">
                                <?php echo $row->title; ?>
                            </a>
                        </td> 
                        <td>
                            <?php
                            // Load publication model
                            $publicationModel = JModelLegacy::getInstance('publication', 'IsbnregistryModel');
                            // Get identifiers string
                            echo $publicationModel->getIdentifiersString($row->publication_identifier_print, $row->publication_identifier_electronical);
                            ?>
                        </td> 
                        <td>
                            <?php echo (strlen($row->comments) > 50 ? substr($row->comments, 0, 47) . '...' : $row->comments); ?>
                        </td>   
                        <td>
                            <?php echo JHtml::date($row->created, 'd.m.Y'); ?>
                        </td>
                        <td>
                            <?php echo strcmp($row->modified, '0000-00-00 00:00:00') == 0 ? '-' : JHtml::date($row->modified, 'd.m.Y'); ?>
                        </td> 
                        <td align="center">
                            <?php echo $row->id; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td></td>
                    <td>
                        <?php echo JText::_('COM_ISBNREGISTRY_PUBLICATIONS_NONE'); ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php echo JHtml::_('form.token'); ?>
</form>
