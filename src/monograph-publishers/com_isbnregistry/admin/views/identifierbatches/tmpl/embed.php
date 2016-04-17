<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHTML::_('behavior.modal');
?>
<form action="index.php?option=com_isbnregistry&view=identifierbatches&publisherId=<?php echo JFactory::getApplication()->input->getInt('publisherId', 0); ?>&tmpl=component&layout=embed&type=<?php echo JFactory::getApplication()->input->getString('type', ''); ?>" method="post" id="adminForm" name="adminForm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_NUM'); ?></th>
                <th width="30%">
                    <?php echo JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_PUBLICATION'); ?>
                </th>	
                <th width="10%">
                    <?php echo JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_MESSAGE'); ?>
                </th>	
                <th width="10%">
                    <?php echo JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_IDENTIFIER_TYPE'); ?>
                </th>
                <th width="10%">
                    <?php echo JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_COUNT'); ?>
                </th>
                <th width="10%">
                    <?php echo JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_SHOW_IDENTIFIERS_LINK'); ?>
                </th>	
                <th width="20%">
                    <?php echo JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_CREATED'); ?>
                </th>
                <th width="2%">
                    <?php echo JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_ID'); ?>
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
                    $link = '<a href="' . JRoute::_('index.php?option=com_isbnregistry&view=identifiers&tmpl=component&layout=embed&batchId=' . $row->id . '');
                    $link .= '" class="modal" rel="{size: {x: 400, y: 400}, handler:\'iframe\'}">' . JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_SHOW_IDENTIFIERS') . '</a>';
                    ?>
                    <tr>
                        <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                        <td>
                            <?php
                            if ($row->publication_id != 0) {
                                $publicationUrl = JRoute::_('index.php?option=com_isbnregistry&task=publication.edit&id=' . $row->publication_id);
                                echo '<a href="' . $publicationUrl . '" title="' . JText::_('COM_ISBNREGISTRY_EDIT_PUBLICATION') . '" target="blank">' . $this->publications[$row->publication_id] . '</a>';
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>  
                        <td>
                            <?php
                            if (empty($this->messages[$row->id])) {
                                $publicationId = '';
                                if ($row->publication_id != 0) {
                                    $code = 'identifier_created_' . strtolower($row->identifier_type);
                                    $publicationId = '&publicationId=' . $row->publication_id;
                                } else {
                                    $code = 'big_publisher_' . strtolower($row->identifier_type);
                                }
                                $url = '<a href="' . JRoute::_('index.php?option=com_isbnregistry&view=message&layout=send&tmpl=component&code=' . $code . '&publisherId=' . $this->publisher_id . '&batchId=' . $row->id . '&' . JSession::getFormToken() . '=1' . $publicationId);
                                $url .= '" class="modal" rel="{size: {x: 800, y: 500}, handler:\'iframe\'}">' . JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_SEND_MESSAGE') . '</a>';
                                echo $url;
                            } else {
                                $url = '<a target="blank" href="' . JRoute::_('index.php?option=com_isbnregistry&view=message&id=' . $this->messages[$row->id]) . '">';
                                $url .= JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_SHOW_MESSAGE') . '</a>';
                                echo $url;
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $row->identifier_type; ?>
                        </td>  
                        <td>
                            <?php echo ($row->identifier_count + $row->identifier_canceled_used_count - $row->identifier_canceled_count); ?>
                        </td> 
                        <td>
                            <?php echo $link; ?>
                        </td>                         
                        <td>
                            <?php echo JHtml::date($row->created, 'd.m.Y H:i:s') . ' (' . $row->created_by . ')'; ?>
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
                        <?php echo JText::_('COM_ISBNREGISTRY_IDENTIFIER_BATCHES_NONE'); ?>
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
