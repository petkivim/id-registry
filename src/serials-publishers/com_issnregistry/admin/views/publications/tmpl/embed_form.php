<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<form action="index.php?option=com_issnregistry&view=publications&formId=<?php echo JFactory::getApplication()->input->getInt('formId', 0); ?>&tmpl=component&layout=embed_form" method="post" id="adminForm" name="adminForm">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="1%"><?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_NUM'); ?></th>
                <th width="10%">
                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_ISSN'); ?>
                </th>	          
                <th width="60%">
                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_TITLE'); ?>
                </th>	
                <th width="15%">
                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_MEDIUM'); ?>
                </th>
                <th width="20%">
                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_STATUS'); ?>
                </th>	
                <th width="2%">
                    <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_ID'); ?>
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
                    $linkPublication = JRoute::_('index.php?option=com_issnregistry&task=publication.edit&id=' . $row->id);
                    $linkForm = JRoute::_('index.php?option=com_issnregistry&task=form.edit&id=' . $row->form_id);
                    ?>
                    <tr>  
                        <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                        <td>
                            <?php echo $row->issn; ?>
                        </td> 
                        <td>
                            <a href="<?php echo $linkPublication; ?>" title="<?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_EDIT'); ?>" target="new">
                                <?php echo $row->title; ?>
                            </a>
                        </td>   
                        <td>
                            <?php echo (empty($row->medium) ? '' : JText::_('COM_ISSNREGISTRY_PUBLICATION_MEDIUM_' . $row->medium)); ?>
                        </td>
                        <td>
                            <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATION_STATUS_' . $row->status); ?>
                        </td>                                
                        <td align="center">
                            <?php echo $row->id; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td></td>
                    <td colspan="2">
                        <?php echo JText::_('COM_ISSNREGISTRY_PUBLICATIONS_NONE'); ?>
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
